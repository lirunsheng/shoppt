var server = require('./utils/server');
var app = getApp();
App({
  /**
   * 当小程序初始化完成时，会触发 onLaunch（全局只触发一次）
   */
  onLaunch: function() {
    
  },

  /**
   * 设置全局变量
   */
  globalData: {
    session_key:'',
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
    hasLogin: false,
  }
})