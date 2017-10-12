{__NOLAYOUT__}
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>{$adminsite_title}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="__STATIC__/admin/images/logo.ico">
    <link rel="stylesheet" href="__STATIC__/admin/zui/dist/css/zui.min.css">
    <script src="__STATIC__/admin/zui/dist/lib/jquery/jquery.js"></script>
    <script src="__STATIC__/admin/zui/dist/js/zui.min.js"></script>
    <style type="text/css">
        .bg{
            width: 100%;
            height:100%;
            position: fixed;
            top:0;
            left:0;
            z-index:100;
            background-color: #fff;
        }
        .bg .bg-msg{
            position: absolute;
            top:0;
            bottom:0;
            left: 0;
            right: 0;
            margin: auto;
            margin-top: 100px;
            width: 600px;
            height:500px;
        }
    </style>
</head>
<body>
<?php $wait=1;?>
<div class="bg">
    <div class="bg-msg">
        <!--msg s-->
            <?php switch ($code) {?>
            <?php case 1:?>
                <div class="alert with-icon alert-success">
                <i class="icon-ok-sign"></i>
                <div class="content">
                    <?php echo(strip_tags($msg));?></br>
            页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
                </div>
                </div>
            <?php break;?>
            <?php case 0:?>
                <div class="alert with-icon alert-danger">
                <i class="icon-remove-sign"></i>
                <div class="content">
                    <?php echo(strip_tags($msg));?></br>
           页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
                </div>
                </div>
            <?php break;?>
            <?php } ?>
        <!--msg e-->
    </div>
</div>
    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</body>
</html>
