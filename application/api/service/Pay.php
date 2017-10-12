<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/21
 * Time: 10:29
 * For:
 */


namespace app\api\service;
use app\api\model\Order as OrderModel;


use app\common\enum\OrderStatusEnum;
use app\common\exception\OrderException;
use app\common\exception\TokenException;
use think\Exception;
use think\Loader;
use think\Log;


Loader::import('wxpay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;

    public function __construct($orderID)
    {

        if(!$orderID){
            throw new Exception('订单号不能为空');
        }
        $this->orderID = $orderID;
    }

    public function pay(){
        $this->checkOrderValid();
        $orderService = new Order();
        $status = $orderService->checkOrderStock($this->orderID);
        if(!$status['pass']){
            return $status;
        }

        $orderModel = new OrderModel();
        $order =$orderModel->find($this->orderID);

        return $this->makeWxPreOrder($status['orderPrice'],$order);
    }

    //发送订单请求
    private function makeWxPreOrder($totalPrice,$order){
        //openid
        $openid = Token::getCurrentTokenVar('openid');
        if(!$openid){
            throw new TokenException();
        }

        //微信支付
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice*100);
        $wxOrderData->SetBody($order->snap_name);
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData){
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        // 失败时不会返回result_code
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
        }
        $this->recordPreOrder($wxOrder);
        //$this->updateOrderStatus();
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function recordPreOrder($wxOrder){
        // 必须是update，每次用户取消支付后再次对同一订单支付，prepay_id是不同的
        OrderModel::where('id', '=', $this->orderID)
            ->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    //更新支付状态
    private function updateOrderStatus(){
        OrderModel::where('id', '=', $this->orderID)
            ->update(['status' => OrderStatusEnum::PAID]);
    }

    // 签名
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }

    private function checkOrderValid(){
        $order = OrderModel::where('id','=',$this->orderID)->find();
        if(!$order){
            throw new OrderException();
        }

        if(!Token::isValidOperate($order->user_id)){
            throw  new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }

        if($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
               'msg' => '订单已支付过',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }

        $this->orderNO = $order->order_no;

        return true;
    }



}