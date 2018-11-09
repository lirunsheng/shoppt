var flag = false;
var app = getApp();
var utils = require('../../../utils/utils');
Page({
  data: {
    cart: [],
    store_name: "store1便利店",
    name: '',
    tell: null,
    area: null,
    door: null,
    timeValue: null,
    display1: "flex",
    display2: "none",
    remark: "请填写备注信息",
    payflag: 0 //判断是否支付
  },

  onLoad: function() {
    var timestamp = utils.formatTime(new Date());
    this.setData({
      timeValue: timestamp,
    });

    this.data.cart = app.globalData.cart;
    this.data.store_name = app.globalData.store_name; // 商 店 名 称
    console.log('1111');

    this.setData({
      cart: this.data.cart,
      store_name: this.data.store_name,
    });

    console.log(app.globalData.userInfo2.firaddres);
    var firaddres = app.globalData.userInfo2.firaddres; // 先确认是否有地址
    if (firaddres!=null && firaddres!='') {
      this.setData({
        name: app.globalData.userInfo2.firaddres.name,
        area: app.globalData.userInfo2.firaddres.area,
        door: app.globalData.userInfo2.firaddres.door,
        tell: app.globalData.userInfo2.firaddres.tell,
        display1: "none",
        display2: "flex"
      })
      flag = true;
    }

    // console.log('111'+firaddres)
    if (!flag) {
      this.setData({
        display1: "flex", 
        display2: "none",
      })
    }

  },

  onShow:function(){
    var timestamp = utils.formatTime(new Date());
    this.setData({
      timeValue: timestamp,
    });

    this.data.cart = app.globalData.cart;
    this.data.store_name = app.globalData.store_name; // 商 店 名 称
    console.log('1111');
    console.log(this.data.name);
    this.setData({
      cart: this.data.cart,
      store_name: this.data.store_name,
    });

    var firaddres = app.globalData.userInfo2.firaddres; // 先确认是否有地址
    if (this.data.name !=null && this.data.name!='') {   //判断是否选择地址
      flag = true
      console.log('222');
      this.setData({
        display1: "none",
        display2: "flex",
      })
    }

  },

  unpay: function(e) {
    this.setData({
      payflag: e.target.dataset.id
    })
  },

  pay: function(e) {
    this.setData({
      payflag: e.target.dataset.id
    })
  },

  timePickerBindchange: function(e) {
    this.setData({
      timeValue: e.detail.value
    })
  },
  datePickerBindchange: function(e) {
    this.setData({
      dateValue: e.detail.value
    })
  },

  //弹出层


  //点击立即下单
  formSubmit: function(e) {
    var that = this;
    var warn = ""; //弹框时提示的内容

    //如果信息填写不完整，弹出输入框
    if (flag == false) {
      wx.showModal({
        title: '提示',
        content: "请填写收货地址!"
      })
    } else if (that.data.timeValue == '不在配送时间内') {
      wx.showModal({
        title: '提示',
        content: "不在配送时间内!"
      })
    } else {
      wx.request({
        url: 'http://mestore.vicp.io/store/order/orderDowm', //传数组信息过PHP保存数据库
        data: {
          remark: e.detail.value.remark,
          list: that.data.cart,
          name: that.data.name,
          area: that.data.area,
          door: that.data.door,
          tell: that.data.tell,
          user_id: app.globalData.userInfo2.id,
          order_time: that.data.timeValue
        },
        header: {
          'Content-Type': 'application/json'
        },
        success: function(res) {
          // console.log(res.data.id);
          // console.log(res.data.order_total);
          // console.log(res.data.addtime);
          if (that.data.payflag == 1) {
            //接入微信支付
            wx.request({
              url: 'http://mestore.vicp.io/store/order/pay?id='+res.data.id,
              data: {
                remark: e.detail.value.remark,
                list: that.data.cart,
                name: that.data.name,
                area: that.data.area,
                door: that.data.door,
                tell: that.data.tell,
                user_id: app.globalData.userInfo2.id,
                order_time: that.data.timeValue
              },
              header: {
                'Content-Type': 'application/json'
              },
              success:function(){
                wx.showToast({
                  title: '支付成功！',
                  duration: 3000,
                  complete: function () {
                    setTimeout(function () {
                      wx.switchTab({
                        url: "/page/order/order"
                      })
                    }, 1500)

                  }
                })
              }
            })
          } else {
            console.log("unshow");
            wx.switchTab({
              url: '/page/order/order'
            })
          }
        }
      })

      

    }

  }
})