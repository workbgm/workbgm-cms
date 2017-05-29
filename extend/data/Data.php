<?php
use think\Db;
use think\Config;
use think\Loader;
/**
 *  M-PHP开发框架
 * User: 吴渭明
 * Date: 2017/5/27
 * Time: 14:34
 * For:
 */
class Data
{

    /**
     * 根据表名获取表数据列表，带分页
     * @param int $table_name 表名
     * @param int $page_size 每页显示条数
     * @param array $where 查询条件
     * @param array $order 排序
     * @param array $filed 查询字段
     * @return bool|\think\paginator\Collection
     */
    public  static function get_table_data_paged($table_name, $page_size = 15, $where = [], $order = [], $filed = [])
    {
        if (empty($table_name)) {
            return false;
        }

        $fileds = array_merge([], (array)$filed);
        $map = array_merge([], (array)$where);
        $sort = array_merge([], (array)$order);

        $data_list = Db::name($table_name)->where($map)->field($fileds)->order($sort)->paginate($page_size);

        return $data_list;
    }

}