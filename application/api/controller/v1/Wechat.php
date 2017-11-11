<?php
/**
 * Created by PhpStorm.
 * User: wuweiming
 * Date: 2017/11/9
 * Time: 17:40
 */

namespace app\api\controller\v1;

class Wechat
{
    /**
     * 开发模式设置
     * 链接:http://yn.zipscloud.com/api.php/v1/wechat
     * @return bool
     */
        public function  index(){
            $data = request()->get();
            $token = 'ybd';
            $sigArr = [$token,$data['timestamp'],$data['nonce']];
            sort($sigArr,SORT_STRING);
            $sigStr=implode($sigArr);
            $enctyStr=sha1($sigStr);
            if($data['signature']===$enctyStr){
                echo $data['echostr'];
            }else{
                return false;
            }
        }

    /**
     * 获取微信token
     * 链接:http://yn.zipscloud.com/api.php/v1/access
     */
        public function setAccess(){
            echo get_weixin_access_token();
        }

    /**
     * 获取微信用户标签
     * 链接:http://yn.zipscloud.com/api.php/v1/tags
     * //{"tags":[{"id":2,"name":"星标组","count":0},{"id":100,"name":"内部用户","count":2}]}
     */
        public function getTags(){
            $accessToken=get_weixin_access_token();
            return curl_get("https://api.weixin.qq.com/cgi-bin/tags/get?access_token={$accessToken}");
        }

    /**
     * 获取微信标签下所有用户的openid
     * 链接:http://yn.zipscloud.com/api.php/v1/getopenidsbytagid
     * //{"count":2,"data":{"openid":["offZnuFSCsgMZlBGsm97RrQiAC1g","offZnuEiImmBLDMpsnDPWbsFlEUI"]},"next_openid":"offZnuEiImmBLDMpsnDPWbsFlEUI"}
     */
        public function getOpenIdsByTagId($tagId){
            $result="";
            if(!empty($tagId)){
                $accessToken=get_weixin_access_token();
                $parms['tagid']=$tagId;
                $parms['next_openid']='';
                $url = "https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token={$accessToken}";
                $result=curl_post_raw($url,json_encode($parms));
            }
            return $result;
        }

    /**
     * 发送消息
     * 链接:http://yn.zipscloud.com/api.php/v1/sendmessage
     */
        public function sendMessage(){
            $accessToken=get_weixin_access_token();
            $url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token={$accessToken}";
            $data['touser']=json_decode($this->getOpenIdsByTagId(100),true)['data']['openid'];
            $data['msgtype']="text";
            $content='内部公告xy1:<a href="www.baidu.com">xx</a>';
            $data['text']['content']=$content;
            $result=curl_post_raw($url, json_encode($data,JSON_UNESCAPED_UNICODE));
            dump($result);
          // return json_decode($result,true)['errcode'];
        }

    /**
     * 发送模板消息
     * 链接:http://yn.zipscloud.com/api.php/v1/sendtemplatemessage
     */
        public function sendTemplateMessage(){
            $title="测试";
            $time="2017-11-10";
            $url="www.baidu.com";
            $data=array(
                'first'=>array('value'=>urlencode("您好，公司有新内部公告，请点击查看!"),'color'=>"#4a7d3b"),
                'keyword1'=>array('value'=>urlencode($title),'color'=>'#4a7d3b'),
                'keyword2'=>array('value'=>urlencode($time),'color'=>'#4a7d3b'),
                'remark'=>array('value'=>urlencode('感谢您的使用。'),'color'=>'#4a7d3b'),
            );
            $toUsers=json_decode($this->getOpenIdsByTagId(100),true)['data']['openid'];
            $accessToken=get_weixin_access_token();
            $urlAPI="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$accessToken}";
            $result='';
            foreach ($toUsers as $toUser){
                $template = array(
                    'touser' => $toUser,
                    'template_id' => "D6A3SHR2bOEA-akkI4zVMBYtAKhpIPVFa9imyKVSJ90",
                    'url' => $url,
                    "topcolor"=>"#FF0000",
                    'data' => $data
                );
                $arr=json_decode(curl_post_raw($urlAPI,urldecode(json_encode($template))),true);
                $errcode=$arr['errcode'];
                $result.=$toUser.':'.$errcode.'-';
            }
            echo $result;
        }
}