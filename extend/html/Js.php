<?php
namespace  app;
/**
 *  M-PHP开发框架
 * User: 吴渭明
 * Date: 2017/5/31
 * Time: 上午8:27
 * For:
 */
/**
 * JS类。
 * JS class.
 *
 * @package html
 */
class Js
{
    /**
     * 引入一个js文件。
     * Import a js file.
     *
     * @param  string $url
     * @param  string $ieParam    like 'lt IE 9'
     * @static
     * @access public
     * @return string
     */
    public static function import($url, $ieParam = '')
    {
        global $config;
        $pathInfo = parse_url($url);
        $mark  = !empty($pathInfo['query']) ? '&' : '?';

        $hasLimit = ($ieParam and stripos($ieParam, 'ie') !== false);
        if($hasLimit) echo "<!--[if $ieParam]>\n";
        echo "<script src='$url{$mark}v={$config->version}' type='text/javascript'></script>\n";
        if($hasLimit) echo "<![endif]-->\n";
    }

    /**
     * 开始输出js。
     * The start of javascript.
     *
     * @param  bool   $full
     * @static
     * @access public
     * @return string
     */
    static public function start($full = true)
    {
        if($full) return "<html><meta charset='utf-8'/><style>body{background:white}</style><script>";
        return "<script language='Javascript'>";
    }

    /**
     * 结束输出js。
     * The end of javascript.
     *
     * @param  bool    $newline
     * @static
     * @access public
     * @return void
     */
    static public function end($newline = true)
    {
        if($newline) return "\n</script>\n";
        return "</script>\n";
    }

    /**
     * 显示一个警告框。
     * Show a alert box.
     *
     * @param  string $message
     * @static
     * @access public
     * @return string
     */
    static public function alert($message = '')
    {
        return self::start() . "alert('" . $message . "')" . self::end() . self::resetForm();
    }

    /**
     * 关闭浏览器窗口。
     * Close window
     *
     * @static
     * @access public
     * @return void
     */
    static public function close()
    {
        return self::start() . "window.close()" . self::end();
    }

    /**
     * 显示错误信息。
     * Show error info.
     *
     * @param  string|array $message
     * @static
     * @access public
     * @return string
     */
    static public function error($message)
    {
        $alertMessage = '';
        if(is_array($message))
        {
            foreach($message as $item)
            {
                is_array($item) ? $alertMessage .= join('\n', $item) . '\n' : $alertMessage .= $item . '\n';
            }
        }
        else
        {
            $alertMessage = $message;
        }
        return self::alert($alertMessage);
    }

    /**
     * 重置禁用的提交按钮。
     * Reset the submit form.
     *
     * @static
     * @access public
     * @return string
     */
    static public function resetForm()
    {
        return self::start() . 'if(window.parent) window.parent.document.body.click();' . self::end();
    }

    /**
     * 显示一个确认框，点击确定跳转到$okURL，点击取消跳转到$cancelURL。
     * show a confirm box, press ok go to okURL, else go to cancleURL.
     *
     * @param  string $message      显示的内容。              the text to be showed.
     * @param  string $okURL        点击确定后跳转的地址。    the url to go to when press 'ok'.
     * @param  string $cancleURL    点击取消后跳转的地址。    the url to go to when press 'cancle'.
     * @param  string $okTarget     点击确定后跳转的target。  the target to go to when press 'ok'.
     * @param  string $cancleTarget 点击取消后跳转的target。  the target to go to when press 'cancle'.
     * @static
     * @access public
     * @return string
     */
    static public function confirm($message = '', $okURL = '', $cancleURL = '', $okTarget = "self", $cancleTarget = "self")
    {
        $js = self::start();

        $confirmAction = '';
        if(strtolower($okURL) == "back")
        {
            $confirmAction = "history.back(-1);";
        }
        elseif(!empty($okURL))
        {
            $confirmAction = "$okTarget.location = '$okURL';";
        }

        $cancleAction = '';
        if(strtolower($cancleURL) == "back")
        {
            $cancleAction = "history.back(-1);";
        }
        elseif(!empty($cancleURL))
        {
            $cancleAction = "$cancleTarget.location = '$cancleURL';";
        }

        $js .= <<<EOT
if(confirm("$message"))
{
    $confirmAction
}
else
{
    $cancleAction
}
EOT;
        $js .= self::end();
        return $js;
    }

    /**
     * $target会跳转到$url指定的地址。
     * change the location of the $target window to the $URL.
     *
     * @param   string $url    the url will go to.
     * @param   string $target the target of the url.
     * @static
     * @access  public
     * @return  string the javascript string.
     */
    static public function locate($url, $target = "self")
    {
        /* If the url if empty, goto the home page. */
        if(!$url)
        {
            global $config;
            $url = $config->webRoot;
        }

        $js  = self::start();
        if(strtolower($url) == "back")
        {
            $js .= "history.back(-1);\n";
        }
        else
        {
            $js .= "$target.location='$url';\n";
        }
        return $js . self::end();
    }

    /**
     * 关闭当前窗口。
     * Close current window.
     *
     * @static
     * @access public
     * @return string
     */
    static public function closeWindow()
    {
        return self::start(). "window.close();" . self::end();
    }

    /**
     * 经过一段时间后跳转到指定的页面。
     * Goto a page after a timer.
     *
     * @param   string $url    the url will go to.
     * @param   string $target the target of the url.
     * @param   int    $time   the timer, msec.
     * @static
     * @access  public
     * @return  string the javascript string.
     */
    static public function refresh($url, $target = "self", $time = 3000)
    {
        $js  = self::start();
        $js .= "setTimeout(\"$target.location='$url'\", $time);";
        $js .= self::end();
        return $js;
    }

    /**
     * 重新加载窗口。
     * Reload a window.
     *
     * @param   string $window the window to reload.
     * @static
     * @access  public
     * @return  string the javascript string.
     */
    static public function reload($window = 'self')
    {
        $js  = self::start();
        $js .= "$window.location.reload(true);\n";
        $js .= self::end();
        return $js;
    }

    /**
     * 用Javascript关闭colorbox弹出框。
     * Close colorbox in javascript.
     * This is a obsolete method, you can use 'closeModal' instead.
     *
     * @param  string $window
     * @static
     * @access public
     * @return string
     */
    static public function closeColorbox($window = 'self')
    {
        return self::closeModal($window);
    }

    /**
     * 用Javascript关闭模态框。
     * Close modal with javascript.
     *
     * @param  string $window
     * @param  string $location
     * @param  string $callback
     * @static
     * @access public
     * @return string
     */
    static public function closeModal($window = 'self', $location = 'this', $callback = 'null')
    {
        $js  = self::start();
        $js .= "if($window.location.href == self.location.href){ $window.window.close();}";
        $js .= "else{ $window.$.cookie('selfClose', 1);$window.$.closeModal($callback, '$location');}";
        $js .= self::end();
        return $js;
    }



    /**
     * 执行js代码。
     * Execute some js code.
     *
     * @param string $code
     * @static
     * @access public
     * @return string
     */
    static public function execute($code)
    {
        $js = self::start($full = false);
        $js .= $code;
        $js .= self::end();
        echo $js;
    }

    /**
     * 设置Javascript变量值。
     * Set js value.
     *
     * @param  string   $key
     * @param  mix      $value
     * @static
     * @access public
     * @return string
     */
    static public function set($key, $value)
    {
        global $config;
        $prefix = (isset($config->framework->jsWithPrefix) and $config->framework->jsWithPrefix == false) ? '' : 'v.';

        static $viewOBJOut;
        $js  = self::start(false);
        if(!$viewOBJOut and $prefix)
        {
            $js .= 'if(typeof(v) != "object") v = {};';
            $viewOBJOut = true;
        }

        if(is_numeric($value))
        {
            $js .= "{$prefix}{$key} = {$value};";
        }
        elseif(is_array($value) or is_object($value) or is_string($value))
        {
            /* Fix for auto-complete when user is number.*/
            if(is_array($value) or is_object($value))
            {
                $value = (array)$value;
                foreach($value as $k => $v)
                {
                    if(is_numeric($v)) $value[$k] = (string)$v;
                }
            }

            $value = json_encode($value);
            $js .= "{$prefix}{$key} = {$value};";
        }
        elseif(is_bool($value))
        {
            $value = $value ? 'true' : 'false';
            $js .= "{$prefix}{$key} = $value;";
        }
        else
        {
            $value = addslashes($value);
            $js .= "{$prefix}{$key} = '{$value};'";
        }
        $js .= self::end($newline = false);
        echo $js;
    }
}