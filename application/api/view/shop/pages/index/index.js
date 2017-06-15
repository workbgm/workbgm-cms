//index.js
//获取应用实例
var app = getApp()
var baseUrl ="http://work.eruipan.com/api.php/v1";
Page({
  data: {
    motto: 'Hello World',
    userInfo: {}
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
  },
  getToken:function(){
    //调用登录接口
    wx.login({
      success:function(res){
        var code = res.code;
        wx.request({
          url: baseUrl+'/token/user',
          data:{
            code:code
          },
          method:'POST',
          success:function(res){
            console.log(res.data);
            wx.setStorageSync('token', res.data.token);
          },
          fail:function(res){
            console.log(res.data);
          }
        })
      }
    })
  }
})
