var currentPage=1;
var direction='';
var page1,page2,page3,page4,page5,page6,page7,page8,page9,left;
var page1_balloon,page1_rob,page1_car,page2_title,page2_day1,page3_p1,page3_p2,page3_p3,page4_gameinfo,page4_big;
window.onload = function () {
    shareAdd();
     page1 = document.getElementById("page1");
     page1_balloon= document.getElementById("page1_balloon");
    page1_car= document.getElementById("page1_car");
    page1_rob= document.getElementById("page1_rob");
     page2 = document.getElementById("page2");
    page2_title= document.getElementById("page2_title");
    page2_day1= document.getElementById("page2_day1");
     page3 = document.getElementById("page3");
    page3_p1= document.getElementById("page3_p1");
    page3_p2= document.getElementById("page3_p2");
    page3_p3= document.getElementById("page3_p3");
     page4 = document.getElementById("page4");
    page4_gameinfo=document.getElementById("page4_gameinfo");
    page4_big=document.getElementById("page4_big");
    page4_my=document.getElementById("page4_my");
    page4_share=document.getElementById("page4_share");
     page5 = document.getElementById("page5");
     page6 = document.getElementById("page6");
     page7 = document.getElementById("page7");
     page8 = document.getElementById("page8");
     page9 = document.getElementById("page9");
    left=document.getElementById("left");

    var startX, startY, moveEndX, moveEndY, X, Y;
    var dBody=document.getElementsByTagName('body')[0];
    dBody.addEventListener('touchstart', function (e) {
        //e.preventDefault();
        startX = e.touches[0].pageX;
        startY = e.touches[0].pageY;
    });

    dBody.addEventListener('touchmove', function (e) {
       // e.preventDefault();
        moveEndX = e.changedTouches[0].pageX;
        moveEndY = e.changedTouches[0].pageY;
        X = moveEndX - startX;
        Y = moveEndY - startY;
        if (Math.abs(X) > Math.abs(Y) && X > 0) {
            direction='right';
        } else if (Math.abs(X) > Math.abs(Y) && X < 0) {
            direction='left';
        } else if (Math.abs(Y) > Math.abs(X) && Y > 0) {
            direction='bottom';
        } else if (Math.abs(Y) > Math.abs(X) && Y < 0) {
            direction='up';
        } else {
            direction='';
        }
    });

    dBody.addEventListener("touchend",function(e) {
       //e.preventDefault();
        if(direction=='left'){
            direction='';
            toNextPage();
        }else if(direction=='right'){
            direction='';
            if(currentPage>4){
                toPage(4,0);
            }else{
                toPrePage();
            }
        }
    });
    page4_gameinfo.addEventListener("click",function(e){
        m_alert('',$('#page4_gameinfo_txt').html());
    });
    page4_big.addEventListener("click",function(e){
        m_alert('','活动还未开始，敬请期待！');
    });
    page4_share.addEventListener("click",function(e){
        m_alert('','活动还未开始，敬请期待！');
    });
    page4_my.addEventListener("click",function(e){
        m_alert('','活动还未开始，敬请期待！');
    });
    pageEffects(1);
}


var toNextPage=function(){
    if(currentPage>=4) return;
    if(currentPage==3) {
        left.style.display = "none";
    }
    var nextPage=currentPage+1;
    var script='page'+currentPage+'.style.display = "none"; page'+nextPage+'.style.display = "block";';
    eval(script);
    currentPage=nextPage;
    pageEffects(currentPage);
}
var toPrePage=function(){
    if(currentPage==1) return;
    var prePage=currentPage-1;
    var script='page'+currentPage+'.style.display = "none"; page'+prePage+'.style.display = "block";';
    eval(script);
    currentPage=prePage;
    pageEffects(currentPage);
}

var toPage=function(to,val){
    var nextPage=to;
    var script='page'+currentPage+'.style.display = "none"; page'+nextPage+'.style.display = "block";';
    eval(script);
    currentPage=nextPage;
    pageEffects(currentPage);
}

var pageEffects=function(page){
    switch(page){
        case 1:
            setTimeout(function() {
                page1_balloon.setAttribute("class","animated fadeInUp");
            },500);
            setTimeout(function() {
                page1_car.setAttribute("class","animated slideInLeft");
            },300);
            setTimeout(function() {
                page1_rob.setAttribute("class","animated swing");
            },800);
            break;
        case 2:
            page2_title.setAttribute("class","animated bounceInRight");
            setTimeout(function(){
                page2_day1.setAttribute("class","animated flash");
            },1000);
            break;
        case 3:
            page3_p2.setAttribute("class","animated fadeInLeftBig");
            setTimeout(function(){
                page3_p1.setAttribute("class","animated fadeInDownBig");
            },500);
            setTimeout(function(){
                page3_p3.setAttribute("class","animated fadeInUpBig");

            },800);
            break;
        case 4:
            break;
        case 5:
            break;
        case 6:
            break;
    }
}

var m_alert=function(title,content){
    if(title==''){
        layer.open({
            style: 'border:none; background-color:#0E3D54; color:#fff;',
            content:content,
            btn: '关闭'
        })
    }else{
        layer.open({
            title: [
                title,
                'background-color:#F6902F; color:#fff;'
            ],
            content: content
            ,style: 'background-color:#0E3D54; color:#FDF001;' //自定风格
            ,btn: '关闭'
        });
    }

}

var shareAdd=function () {
    $.post("http://10.194.0.121:8088/chanzhieps/www/admin.php?m=activity&f=shareToIncreaseEnergies", { uid: "1" },
        function(data){
            alert("Data Loaded: " + data);
        });
}
