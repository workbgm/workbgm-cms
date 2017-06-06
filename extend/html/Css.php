<?php
namespace  app;
/**
 *  M-PHP开发框架
 * User: 吴渭明
 * Date: 2017/5/31
 * Time: 上午8:27
 * For:
 */
class Css
{
    /**
     * 引入css文件。
     * Import a css file.
     *
     * @param  string $url
     * @access public
     * @return void
     */
    public static function import($url, $attrib = '')
    {
        global $config;
        if(!empty($attrib)) $attrib = ' ' . $attrib;
        echo "<link rel='stylesheet' href='$url?v={$config->version}' type='text/css' media='screen'{$attrib} />\n";
    }

    /**
     * 打印css代码。
     * Print a css code.
     *
     * @param  string    $css
     * @static
     * @access public
     * @return void
     */
    public static function internal($css)
    {
        echo "<style>$css</style>";
    }
}