/**
 * Created by wuweiming on 2017/5/11.
 */
$(function(){
    banner();
});

var g_current_block=0;
var g_time=3000;
var g_block_Interval;
function banner(){
    $('.control-item').click(function(){
        window.clearInterval(g_block_Interval);
        var current_block=$('.control-item').index(this);
        if(g_current_block==current_block) return false;
        else{
            $('.control-item:eq('+g_current_block+')').removeClass('active');
            $('.index-banner-block:eq('+g_current_block+')').css('display','none');
            $('.index-banner-block:eq('+g_current_block+')').removeClass('animated slideInRight');
            var imgsrc=$('.index-banner-block:eq('+g_current_block+') img').attr('src');
            $('.index-banner').css('background','url("'+imgsrc+'")no-repeat');
            $(this).addClass('active');
            $('.index-banner-block:eq('+current_block+')').css('display','block');
            $('.index-banner-block:eq('+g_current_block+')').addClass('animated slideInRight');
            g_current_block=current_block;
            interval();
        }
    });
    interval();
}

function interval(){
    g_block_Interval=window.setInterval(function () {
        var current_block=g_current_block;
        $('.control-item:eq('+g_current_block+')').removeClass('active');
        $('.index-banner-block:eq('+g_current_block+')').css('display','none');
        $('.index-banner-block:eq('+g_current_block+')').removeClass('animated slideInRight');
        var imgsrc=$('.index-banner-block:eq('+g_current_block+') img').attr('src');
        $('.index-banner').css('background','url("'+imgsrc+'")no-repeat fixed 0px '+ $('.index-banner').css('padding-top'));
        g_current_block++;
        if(g_current_block==3)g_current_block=0;
        $('.control-item:eq('+g_current_block+')').addClass('active');
        $('.index-banner-block:eq('+g_current_block+')').css('display','block');
        $('.index-banner-block:eq('+g_current_block+')').addClass('animated slideInRight');
    },g_time);
}

