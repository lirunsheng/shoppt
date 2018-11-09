var app = getApp();
var server = require('../../utils/server');
Page({
	data: {
    userInfo:null
  },
	onLoad: function () {
	},
	onShow: function () {

		this.setData({
      userInfo: app.globalData.userInfo
		});
    console.log(this.data.userInfo);
	},
  redToAdd:function(){
    wx.navigateTo({
      url:"/page/address/chooseAddreSY/chooseAddreSY"
    })
  },
  redToBindPhone(){
    
    if (app.globalData.userInfo2.tell != "") {
      var tmp = app.globalData.userInfo2.tell;
      console.log(tmp);
      tmp = tmp.substr(0, 3) + "****" + tmp.substr(7, 4);
      wx.showModal({
        title: "你已绑定手机！",
        content: "你当前绑定的手机号码为" + tmp,
        confirmText: "修改手机",
        success: function (res) {
          if (res.cancel) {
            wx.switchTab({
              url: "/page/mine/mine"
            })
          }
          else
          {
            wx.navigateTo({
              url:"/page/bindphone/bindphone"
            })
          }
        }
      })
    }
    else{
      wx.showModal({
        title: "尚未绑定手机",
        confirmText: "绑定手机",
        success: function (res) {
          if (res.cancel) {
            wx.switchTab({
              url: "/page/mine/mine"
            })
          }
          else {
            wx.navigateTo({
              url: "/page/bindphone/bindphone"
            })
          }
        }
      })
    }
  },
  connetsale:function(){
    wx.makePhoneCall({
      phoneNumber: "13538304618"
    })
  }
});

