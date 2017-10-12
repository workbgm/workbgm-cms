<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/21
 * Time: 14:58
 * For:
 */


namespace app\common\enum;


class OrderStatusEnum
{
    // 待支付
    const UNPAID = 1;

    // 已支付
    const PAID = 2;

    // 已发货
    const DELIVERED = 3;

    // 已支付，但库存不足
    const PAID_BUT_OUT_OF = 4;

    // 已处理PAID_BUT_OUT_OF
    const HANDLED_OUT_OF = 5;
}