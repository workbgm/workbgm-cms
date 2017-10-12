var workbgm=w={
    name:'workbgm开发平台',
    fileInSee:null,
    init:function(){
        $.ajaxSetup({
            beforeSend: function () {
                //ajax请求之前
                $('.loading').fadeIn();
            },
            complete: function () {
                //ajax请求完成，不管成功失败
                $('.loading').fadeOut();
            },
            error: function () {
                //ajax请求失败
            }
        });
        // 选择时间和日期
        $(".form-datetime").datetimepicker(
            {
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1,
                format: "yyyy-mm-dd hh:ii"
            });

        // 仅选择日期
        $(".form-date").datetimepicker(
            {
                language:  "zh-CN",
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0,
                format: "yyyy-mm-dd"
            });

        // 选择时间
        $(".form-time").datetimepicker({
            language:  "zh-CN",
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 1,
            minView: 0,
            maxView: 1,
            forceParse: 0,
            format: 'hh:ii'
        });
        //预览图片
        $('a[name="image-see"]').hover(
            function () {
                var imagesrc=$("input[name='"+$(this).data('name')+"']").val();
                if(imagesrc=='') return false;
                if($(this).data('insee')) return false;
                imagesrc=window.IMAGE_PATH+imagesrc;
                $(this).data('insee',true);
                workbgm.fileInSee=$(this);
                var html = '';
                var ext = imagesrc.substr(imagesrc.lastIndexOf(".")).toLowerCase();//带.
                if(ext.toLowerCase()== '.pdf'){
                    html = '<a class="btn btn-primary" target="_blank" href="'+imagesrc+'">点击下载</a>';
                }else{
                    html = '<img src="'+imagesrc+'"><a class="btn btn-primary btn-block" target="_blank" href="'+imagesrc+'">点击下载</a>';
                }
                workbgm.alert(html,function(){
                    workbgm.fileInSee.data('insee',false);
                });
            }
        );
        //文件上传
        $('input[type="file"]').ajaxfileupload({
            action:window.FILE_UPLOAD_PATH,
            onComplete: function (d) {
                if (d.error == 0) {
                    $('input[name="' + $(this).attr('imge') + '"]').val(d.url);
                } else {

                }
            }
        });
        //ajax form
        $('.ajax-action').on('click', function () {
            var _action = $(this).data('action');
            bootbox.confirm({
                message: "您确认要执行<span style='color:red'>"+$(this).text()+"</span>吗?",
                buttons: {
                    confirm: {
                        label: '确认',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: '取消',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result){
                        $.ajax({
                            url: _action,
                            data: $('.ajax-form').serialize(),
                            success: function (info) {
                                if (info.code === 1) {
                                    location.href = info.url;
                                }
                                w.message(info.msg,null);
                            }
                        });
                    }
                }
            });
            return false;
        });

        //checkbox check all
        $('.check-all').click(function(){
            var $tbody=$(this).parents('.table').find('tbody');
            if($(this).prop("checked")){
                $("input[type='checkbox']",$tbody).prop("checked",true);
            }else{
                $("input[type='checkbox']",$tbody).prop("checked",false);
            }
        });
        //删除
        $('button[name="del"]').click(function(){
            var action = $(this).attr('action');
            bootbox.confirm({
                message: "您确认要删除吗?",
                buttons: {
                    confirm: {
                        label: '确认',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: '取消',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result){
                        self.location=action;
                    }
                }
            });
        });
    },
    alert:function(msg,callback){
        bootbox.alert({
            size: "small",
            title: this.name,
            message: msg,
            callback: callback
        })
    },
    message:function(msg,callback){
        bootbox.dialog({
            size: "small",
            message: msg,
            callback: callback
        })
    }
};

$(function(){
    workbgm.init();
});