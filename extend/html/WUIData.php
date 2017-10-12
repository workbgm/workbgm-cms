<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/7/27
 * Time: 9:40
 * For:
 */


namespace app;


class WUIData
{
    /**
     * 专门给select用的数据源整理
     * @param $list  数据库查出来的表记录
     * @param string $name  对应的name
     * @param string $value  对应的value
     * @return array
     */
    public function select($list,$name="name",$value="value",$sp="->"){
        $arr = [];
        $names = explode($sp,$name);
        foreach ($list as $vo){
            $item['name']='';
            foreach ($names as $n){
                $ns = explode('.',$n);
                $iName = $vo;
                foreach ($ns as $v){
                    $iName = $iName[$v];
                }
                $item['name'] .= $iName.$sp;
            }
            $item['name']=rtrim($item['name'],$sp);
            $item['value'] = $vo[$value];
            array_push($arr,$item);
        }
        return $arr;
    }

}