/**
 * Created by wuweiming on 2017/5/11.
 */
$(function(){

    $(".public-header-nav .item a").hover(function(){
        var content=$(this).attr('data-content');
        if(content){
            $('#'+content).css('display','block');
        }
    },function(){
        var content=$(this).attr('data-content');
        if(content){
            $('#'+content).css('display','none');
        }
    });

    $('.public-header-nav-item-content').hover(function(){
        $(this).css('display','block');
    },function(){
        $(this).css('display','none');
    });
});