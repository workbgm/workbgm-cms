<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/15
 * Time: 上午10:00
 * For:
 */


namespace app\api\controller\v1;

use app\api\model\ShopUser as ShopUserModel;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\common\exception\ShopUserMissException;
use app\common\exception\SuccessMessage;


class Address
{
    public function createOrUpdateAddress(){
        $validate = new AddressNew();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        $user = ShopUserModel::get($uid);
        if(!user){
            throw new ShopUserMissException();
        }

        $dataArray = $validate->getDataByRule(input('post.'));

        $userAddress = $user->address;

        if(!$userAddress){
            $user->address()->save($dataArray);
        }else{
            $user->address->save($dataArray);
        }

        return json(new SuccessMessage());
    }

}