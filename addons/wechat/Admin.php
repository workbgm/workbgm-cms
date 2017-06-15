<?php
namespace addons\wechat;
use app\common\controller\AddonsBase;
use EasyWeChat\Foundation\Application;
use app\Zui;
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/4/1
 * Time: 16:48
 * For:
 */

/**
 * 后台访问控制类
 * Class Admin
 * @package Addons\wechat
 */
class Admin extends  AddonsBase
{

    public function handler(){

        $id=input('id');
        $wechat = model('wechat')->find($id);
        $wechat['wechat']=unserialize($wechat['wechat']);
        $options = [
            'debug'  => true,
            'app_id' => $wechat['wechat']['appid'],
            'secret' => $wechat['wechat']['appsecret'],
            'token'  => $wechat['wechat']['token'],
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file'  => RUNTIME_PATH.'/wechat/wechat.log',
            ],
        ];
        $app = new Application($options);
        $server=$app->server;
        $server->setMessageHandler(function ($message) {
            return "您好！欢迎关注我!!!!";
        });
        $response = $app->server->serve();
        // 将响应输出
        return $response->send();

    }

    public function wechat($page = 1){
        $m=model('wechat');
        return parent::m_index($page,$m,'wechat');
    }

    public function wechatAdd(){
        $ul=array();
        $li1['href']=site_url('wechat.wechat');
        $li1['name']='微信管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新微信';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('zui',(new Zui())->addTab($ul)
            ->addFormBegin(site_url('wechat.wechatSave'))
            ->addFormGroup('appid','input',"wechat[appid]",'','required')
            ->addFormGroup('appsecret','input',"wechat[appsecret]",'','required')
            ->addFormGroup('token','input',"wechat[token]",'','required')
            ->addFormGroup('备注','textarea',"remark")
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('wechatAdd');
    }

    public function wechatSave(){
        return parent::m_save('wechat',model('wechat'),'wechat');
    }

    public function wechatEdit(){
        $id=input('id');
        $wechat = model('wechat')->find($id);
        $wechat['wechat']=unserialize($wechat['wechat']);
        $ul=array();
        $li1['href']=site_url('wechat.wechat');
        $li1['name']='微信管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='编辑微信';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('zui',(new Zui())->addTab($ul)
            ->addFormBegin(site_url('wechat.wechatUpdate'))
            ->addFormHiddenInfo('id',$wechat['id'])
            ->addFormGroup('appid','input',"wechat[appid]",$wechat['wechat']['appid'],'required')
            ->addFormGroup('appsecret','input',"wechat[appsecret]",$wechat['wechat']['appsecret'],'required')
            ->addFormGroup('token','input',"wechat[token]",$wechat['wechat']['token'],'required')
            ->addFormGroup('备注','textarea',"remark",$wechat['remark'])
            ->addFormButtons('更新')
            ->addFormEnd()
            ->html());
        return parent::m_add('wechatEdit');
    }

    public function wechatUpdate(){
        $id=input('id');
        return parent::m_update($id,'wechat',model('wechat'));
    }

    public function wechatDel(){
        return parent::m_delete(input('id'),model('wechat'));
    }
}