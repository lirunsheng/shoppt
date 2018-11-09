var app = getApp();

Page({
  data: {
    //判断小程序的API，回调，参数，组件等是否在当前版本可用。
    canIUse: wx.canIUse('button.open-type.getUserInfo')
  },
  onLoad: function() {
    var that = this;
    // 查看是否授权
    wx.getSetting({
      success: function(res) {
        if (res.authSetting['scope.userInfo', 'scope.userLocation']) {
          //从数据库获取用户信息
          wx.login({
            success(re) {
              if (re.code) {
                //发起网络请求
                wx.request({
                  url: 'https://huazai233.picp.vip/user/userinfo/keycode',
                  data: {
                    code: re.code
                  },
                  success(ret) {
                    app.globalData.openid = ret.data.openid;
                    app.globalData.session_key = ret.data.session_key;
                    //插入登录的用户的相关信息到数据库
                    wx.request({
                        url: 'https://huazai233.picp.vip/store/adduser/adduser?openid=' + ret.data.openid + '&nickName=' + app.globalData.nickName + '&avatarUrl=' + app.globalData.avatarUrl,
                        header: {
                          'content-type': 'application/json'
                        }
                      }),
                      wx.request({
                        url: "https://huazai233.picp.vip/user/userinfo/index?openid=" + app.globalData.openid,
                        success(r) {
                          app.globalData.userInfo2 = r.data;
                          console.log(app.globalData.userInfo2);
                        }
                      })
                  }
                })
              } else {
                console.log('登录失败！' + r.errMsg)
              }
            }
          })
          wx.switchTab({
            url: '../index/index'
          })

        }
      }
    })
  },

  bindGetUserInfo: function(e) {
    if (e.detail.userInfo) {
      //用户按了允许授权按钮
      var that = this;
      app.globalData.nickName = e.detail.userInfo.nickName;
      app.globalData.avatarUrl = e.detail.userInfo.avatarUrl;
      app.globalData.userInfo = e.detail.userInfo; //保存到全局变量

      //授权成功后，跳转进入小程序首页
      wx.switchTab({
        url: '../index/index'
      })
    } else {
      //用户按了拒绝按钮
      wx.showModal({
        title: '警告',
        content: '您点击了拒绝授权，将无法进入小程序，请授权之后再进入!!!',
        showCancel: false,
        confirmText: '返回授权',
        success: function(res) {
          if (res.confirm) {
            console.log('用户点击了“返回授权”')
          }
        }
      })
    }
  },
  //获取用户信息接口
})