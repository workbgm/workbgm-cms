<?php

/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/7/4
 * Time: 10:30
 * For:
 */
class WeiCode
{
    //订单状态map
    //1:未支付， 2：已支付，3：已发货 , 4: 已支付，但库存不足
    static public function getOrderStatus($status){
        switch ($status){
            case 1:
                return '<p class="text-warning">未支付</p>';
            case 2:
                return '<p class="text-muted">已支付</p>';
            case 3:
                return '<p class="text-primary">已发货</p>';
            case 4:
                return '<p class="text-danger">已支付，但库存不足</p>';
        }
    }

    static public function getOrderItems($items){
        $html = '<ol>';
        //$items_ =  json_decode($items);
        $items_ =  $items;
        foreach ($items_ as $item){
            $name = '商品名:'.$item->name;
            $counts = '总数:'.$item->counts;
            $price = '单价:'.$item->price;
            $totalPrice ='总价:'.$item->totalPrice;
            $ds = ' , ';
            $html .='<li>'.$name.$ds.$price.$ds.$counts.$ds.$totalPrice.'</li>';
        }
        $html .='</ol>';
        return $html;
    }

    static public function getOrderAddress($item){
        $html = '<p>';
        $name = '姓名:'.$item->name;
        $mobile = '电话:'.$item->mobile;
        $country = $item->country;
        $city = $item->city;
        $detail = $item->detail;
        $address = '地址:'.$city.$country.$detail;
        $ds = ' , ';
        $html .=$name.$ds.$mobile.$ds.$address;
        $html .='</p>';
        return $html;
    }

}