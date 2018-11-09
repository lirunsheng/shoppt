var server = require('./utils/server');
var app = getApp();
App({
  /**
   * 当小程序初始化完成时，会触发 onLaunch（全局只触发一次）
   */
  onLaunch: function() {
    var that = this;
    wx.login({
      success: res => {
        wx.request({
          url: that.globalData.wx_url_1 + res.code + that.globalData.wx_url_2,
          success: res => {
            that.globalData.openid = res.data.openid;
          },
          complete: function() {
            wx.request({
              url: "http://mestore.vicp.io/user/userinfo",
              data: {
                openid: that.globalData.openid
              },
              success(res) {
                that.globalData.userInfo2 = res.data;
                wx.request({
                  url: 'http://mestore.vicp.io/store/order/show',
                  data: {
                    id: that.globalData.userInfo2.id
                  },
                  header: {
                    'content-type': 'application/json'
                  },
                  success: function(res) {
                    var a = res.data.length;
                    var arr = [];
                    for (var i = 0; i < a; i++) {
                      if (res.data[i].status === "已完成")
                        arr.push(res.data[i])
                    }
                    that.globalData.finishorder = arr
                  },
                  fail: function() {
                    // fail
                  },
                  complete: function() {
                    // complete
                  }
                })
              }
            })
          }
        })
      }
    });

  },

  /**
   * 设置全局变量
   */
  globalData: {
    store_name: '',
    finishorder: [],
    store_id: '',
    cart: [],
    tell: 1,
    openid: 0,
    nickName: '',
    avatarUrl: '',
    myAddress: '11', //我的定位
    userInfo: [], //微信自带的个人信息
    userInfo2: [], //自定义的信息，包括电话号码、用户第一个自定义的收货地址
    wx_url_1: 'https://api.weixin.qq.com/sns/jscode2session?appid=wx1c8e7d1e70478262&secret=bc8f3f5c2cf0c2a2292b4c382717d986&js_code=',
    wx_url_2: '&grant_type=authorization_code',
    hasLogin: false,
    // shops: [
    // 	{
    // 		id: 1,
    // 		img: 'http://wxapp.im20.com.cn/impublic/waimai/imgs/shops/shop_1.jpg',
    // 		distance: 1.8,
    // 		sales: 1475,
    // 		logo: 'http://wxapp.im20.com.cn/impublic/waimai/imgs/shops/logo_1.jpg',
    // 		name: '杨国福麻辣烫(东四店)',
    // 		desc: '满25减8；满35减10；满60减15（在线支付专享）'
    // 	},
    // 	{
    // 		id: 2,
    // 		img: 'http://wxapp.im20.com.cn/impublic/waimai/imgs/shops/shop_2.jpg',
    // 		distance: 2.4,
    // 		sales: 1284,
    // 		logo: 'http://wxapp.im20.com.cn/impublic/waimai/imgs/shops/logo_2.jpg',
    // 		name: '忠友麻辣烫(东四店)',
    // 		desc: '满25减8；满35减10；满60减15（在线支付专享）'
    // 	},
    // 	{
    // 		id: 3,
    // 		img: 'http://wxapp.im20.com.cn/impublic/waimai/imgs/shops/shop_3.jpg',
    // 		distance: 2.3,
    // 		sales: 2039,
    // 		logo: 'http://wxapp.im20.com.cn/impublic/waimai/imgs/shops/logo_3.jpg',
    // 		name: '粥面故事(东大桥店)',
    // 		desc: '满25减8；满35减10；满60减15（在线支付专享）'
    // 	},
    // 	{
    // 		id: 4,
    // 		img: 'http://wxapp.im20.com.cn/impublic/waimai/imgs/shops/shop_4.jpg',
    // 		distance: 3.4,
    // 		sales: 400,
    // 		logo: 'http://wxapp.im20.com.cn/impublic/waimai/imgs/shops/logo_4.jpg',
    // 		name: '兄鸡',
    // 		desc: '满25减8；满35减10；满60减15（在线支付专享）'
    // 	}
    // ]
  },

  rd_session: null,

  login: function() {
    var self = this;
    wx.login({
      success: function(res) {
        console.log('wx.login', res)
        server.getJSON('/WxAppApi/setUserSessionKey', {
          code: res.code
        }, function(res) {
          console.log('setUserSessionKey', res)
          self.rd_session = res.data.data.rd_session;
          self.globalData.hasLogin = true;
          wx.setStorageSync('rd_session', self.rd_session);
          self.getUserInfo();
        });
      }
    });
  },

})