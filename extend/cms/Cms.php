<?php
/**
 * Created by PhpStorm.
 * User: wuweiming
 * Date: 2017/10/10
 * Time: 10:00
 */
use think\Db;
class Cms
{

    /**
     * 获取分类所有子分类
     * @param int $cid 分类ID
     * @return array|bool
     */
    public static function  get_category_children($cid)
    {
        if (empty($cid)) {
            return false;
        }

        $children = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->select();

        return array2tree($children);
    }

    /**
     * 获取导航一级菜单
     * @return [array] [id,name]
     */
    public static function get_category_children_1_level()
    {
        return Db::name('category')->where(['pid' => ['eq', "0"]])->select();
    }

    /**
     * 获取分类
     * @param $cid
     * @return array|bool|false|PDOStatement|string|\think\Model
     */
    public static  function get_category_bycid($cid)
    {
        if (empty($cid)) {
            return false;
        }

        return Db::name('category')->find($cid);
    }

    /**
     * 获取文章
     * @param $cid
     * @return array|bool|false|PDOStatement|string|\think\Model
     */
    public static  function get_article_bycid($cid)
    {
        if (empty($cid)) {
            return false;
        }

        return Db::name('article')->find($cid);
    }

    /**
     * 根据分类ID获取文章列表（包括子分类）
     * @param int $cid 分类ID
     * @param int $limit 显示条数
     * @param array $where 查询条件
     * @param array $order 排序
     * @param array $filed 查询字段
     * @return bool|false|PDOStatement|string|\think\Collection
     */
    public static function get_articles_by_cid($cid, $limit = 10, $where = [], $order = [], $filed = [])
    {
        if (empty($cid)) {
            return false;
        }

        $ids = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->column('id');
        $ids = (!empty($ids) && is_array($ids)) ? implode(',', $ids) . ',' . $cid : $cid;

        $fileds = array_merge(['id', 'cid', 'title', 'author', 'introduction', 'thumb', 'reading', 'publish_time'], (array)$filed);
        $map = array_merge(['cid' => ['IN', $ids], 'status' => 1, 'publish_time' => ['<= time', date('Y-m-d H:i:s')]], (array)$where);
        $sort = array_merge(['is_top' => 'DESC', 'sort' => 'DESC', 'publish_time' => 'DESC'], (array)$order);

        $article_list = Db::name('article')->where($map)->field($fileds)->order($sort)->limit($limit)->select();

        return $article_list;
    }

    /**
     * 根据分类ID获取文章列表，带分页（包括子分类）
     * @param int $cid 分类ID
     * @param int $page_size 每页显示条数
     * @param array $where 查询条件
     * @param array $order 排序
     * @param array $filed 查询字段
     * @return bool|\think\paginator\Collection
     */
    public static function get_articles_by_cid_paged($cid, $page_size = 15, $where = [], $order = [], $filed = [])
    {
        if (empty($cid)) {
            return false;
        }

        $ids = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->column('id');
        $ids = (!empty($ids) && is_array($ids)) ? implode(',', $ids) . ',' . $cid : $cid;

        $fileds = array_merge(['id', 'cid', 'title', 'introduction', 'thumb', 'reading', 'publish_time'], (array)$filed);
        $map = array_merge(['cid' => ['IN', $ids], 'status' => 1, 'publish_time' => ['<= time', date('Y-m-d H:i:s')]], (array)$where);
        $sort = array_merge(['is_top' => 'DESC', 'sort' => 'DESC', 'publish_time' => 'DESC'], (array)$order);
        //->order(['publish_time' => 'DESC'])
        $article_list = Db::name('article')->where($map)->field($fileds)->order(['publish_time' => 'DESC'])->paginate($page_size);
        //$article_list = Db::name('article')->where($map)->field($fileds)->order($sort)->paginate($page_size);
        return $article_list;
    }


}