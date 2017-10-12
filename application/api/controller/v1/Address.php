<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/15
 * Time: 上午10:00
 * For:
 */


namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\ShopUser as ShopUserModel;
use app\api\model\ShopUserAddress as ShopUserAddressModel;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\common\exception\ShopUserMissException;
use app\common\exception\SuccessMessage;


class Address extends BaseController
{

    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress,getUserAddress']
    ];


    /**
     * 更新或者创建用户收获地址
     */
    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();

        $uid = TokenService::getCurrentUid();
        $user = ShopUserModel::get($uid);
        if(!$user){
            throw new ShopUserMissException([
                'code' => 404,
                'msg' => '用户收获地址不存在',
                'errorCode' => 60001
            ]);
        }
        $userAddress = $user->address;
        // 根据规则取字段是很有必要的，防止恶意更新非客户端字段
        $data = $validate->getDataByRule(input('post.'));
        if (!$userAddress )
        {
            // 关联属性不存在，则新建
            $user->address()
                ->save($data);
        }
        else
        {
            // 存在则更新
//            fromArrayToModel($user->address, $data);
            // 新增的save方法和更新的save方法并不一样
            // 新增的save来自于关联关系
            // 更新的save来自于模型
            $user->address->save($data);
        }
        return new SuccessMessage();
    }

    /**
     * 获取用户地址信息
     * @return UserAddress
     * @throws UserException
     */
    public function getUserAddress(){
        $uid = TokenService::getCurrentUid();
        $userAddress = ShopUserAddressModel::where('user_id', $uid)
            ->find();
        if(!$userAddress){
            throw new ShopUserMissException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        }
        return $userAddress;
    }


}