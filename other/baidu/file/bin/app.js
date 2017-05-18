var page = require('webpage').create(),
  system = require('system'),
  fs = require('fs');
page.settings.resourceTimeout = 10000;
var url = system.args[1];
var url=' https://wenku.baidu.com/view/544da9e7524de518964b7d98.html?from=search';
g_page_arr = new Array('');
g_page_arr_reday = new Array(false);
$g_doc = '';
g_count = 0;
g_title = '';
//睡眠函数
function sleep(milliSeconds) {
  console.log('sleep '+milliSeconds+'ms</br>');
  var startTime = new Date().getTime();
  while (new Date().getTime() < startTime + milliSeconds);
};
//等待函数
function waitFor(testFx, onReady, timeOutMillis) {
  var maxtimeOutMillis = timeOutMillis ? timeOutMillis : 100000,
    start = new Date().getTime(),
    condition = false,
    interval = setInterval(function () {
      if ((new Date().getTime() - start < maxtimeOutMillis) && !condition) {
        // If not time-out yet and condition not yet fulfilled
        condition = (typeof (testFx) === "string" ? eval(testFx) : testFx()); //< defensive code
      } else {
        if (!condition) {
          console.log("waitFor page timeout" );
          phantom.exit(1);
        } else {
          // Condition fulfilled (timeout and/or condition is 'true')
          console.log("waitFor page finished in " + (new Date().getTime() - start) + "ms." );
          typeof (onReady) === "string" ? eval(onReady) : onReady(); //< Do what it's supposed to do once the condition is fulfilled
          clearInterval(interval); //< Stop this interval
        }
      }
    }, 250); //< repeat check every 250ms
};

function trim(s){
    return s.replace(/(^\s*)|(\s*$)/g, "");
}

//获取某一页的内容
function getPageHtml(index, page) {
  var pObj = '1343.66*' + index;
  var html = '';
  waitFor(function () {
    return page.evaluate(function (pObj) {
      function eclipse_page(pObj) {
        var pObj_arr = pObj.split('*');
        var pageIndex = pObj_arr[1];
        $('.page-input').val(pageIndex).trigger('change');
        var e = jQuery.Event("keydown");
        e.keyCode = 13;
        $('.page-input').trigger(e);
        return $('#pageNo-' + pageIndex).html();
      }
      return eclipse_page(pObj);
    }, pObj);
  }, function () {
    html = page.evaluate(function (index) {
      return $('#pageNo-' + index).html();
    }, index);
    page.render('['+index+']'+g_title + '.pdf');
    g_page_arr[index - 1] = html;
    g_page_arr_reday[index - 1] = true;
    console.log('get page html ' + index + ' success' );
    if (index == g_count) {
      var pHtml = page.evaluate(function (g_page_arr) {
        document.body.style.visibility = "hidden";
        for (var i = 0; i < g_page_arr.length; i++) {
          $('#pageNo-' + (i + 1)).html(g_page_arr[i]);
        }
        return $('html').html();
      }, g_page_arr);
      sleep(5000);
      page.render(trim(g_title) + '.pdf');
      console.log(trim(g_title) + '.pdf');
      phantom.exit();
    } else {
      getPageHtml(++index, page);
    }
  });
}

page.open(url, function (status) {
  console.log('url status:' + status );
  if (status === "success") {
      // page.evaluate(function () {
      //     var f=document.createElement('script')
      //     f.setAttribute("type","text/javascript")
      //     f.setAttribute("src", 'http://libs.baidu.com/jquery/1.8.3/jquery.min.js')
      //     document.getElementsByTagName("head")[0].appendChild(f)
      // });
    //获取标题
    var title = page.evaluate(function () {
      return document.title;
    });
    g_title = title;
    console.log('title:' + title );
    //获取总页数
    var count = page.evaluate(function () {
      return parseInt($(".page-count")[0].innerHTML.replace("/", ""));
    });
    g_count = count;
    console.log('count:' + count );
    //展开所有页面(不包含大于50页的)
    page.evaluate(function () {
      $('.moreBtn').click();
    });
    console.log('open all pages' );
    //获取页面高度
     var width=page.evaluate(function(){
       return $('body').width();
     });
	 console.log('width:'+width);
	 var height=page.evaluate(function(){
       return $('body').height();
     });
	 page.evaluate(function(){
		//$('#hd').remove();
		//$('#doc-header-test').remove();
         $('a[title="全屏显示"]')[0].click();
	 });
	page.viewportSize = {width: width, height: height};
	page.settings.javascriptEnabled = false;
	console.log('page width:'+width+' height:'+height);
    getPageHtml(1, page);
  } else {
    phantom.exit();
  }
});
