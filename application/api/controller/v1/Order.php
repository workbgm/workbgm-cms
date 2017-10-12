<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/19
 * Time: 23:11
 * For:
 */


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\common\exception\OrderException;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\common\exception\SuccessMessage;

class Order extends  BaseController
{

    protected $beforeActionList=[
        'checkExclusiveScope' => ['only'=>'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser'],
         'checkSuperScope' => ['only' => 'delivery,getSummary']
    ];

    public function placeOrder(){
        (new OrderPlace())->goCheck();
        $products=input('post.products/a');
        $uid = TokenService::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($uid,$products);
        return $status;
    }

    /**
     * 获取订单详情
     * @param $id
     * @return static
     * @throws OrderException
     * @throws \app\lib\exception\ParameterException
     */
    public function getDetail($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail)
        {
            throw new OrderException();
        }
        return $orderDetail
            ->hidden(['prepay_id']);
    }

    public function getSummaryByUser($page=1,$size=15){
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders  = OrderModel::getSummaryByUser($uid,$page,$size);
        if(!$pagingOrders){
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ];
        }

       // $data = $pagingOrders->hidden(['snap_items', 'snap_address'])->toArray();
        $collection = collection($pagingOrders->items());
        if($collection->isEmpty()){
            $data =null;
        }else{
            $data = $collection->hidden(['snap_items', 'snap_address'])
                ->toArray();
        }
        return [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage()
        ];
    }

    /**
     * 获取全部订单简要信息（分页）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummary($page=1, $size = 20){
        (new PagingParameter())->goCheck();
//        $uid = Token::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByPage($page, $size);
        $collection = collection($pagingOrders->items());
        if ($collection->isEmpty())
        {
            return [
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
        }
        $data = $collection->hidden(['snap_items', 'snap_address'])
            ->toArray();
        return [
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];
    }

    public function delivery($id){
        (new IDMustBePositiveInt())->goCheck();
        $order = new OrderService();
        $success = $order->delivery($id);
        if($success){
            return new SuccessMessage();
        }
    }
}