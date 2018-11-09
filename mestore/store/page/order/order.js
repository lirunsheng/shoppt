//获取应用实例
// var uctooPay = require('../../utils/uctoo-pay.js')
var app = getApp()

Page({
  data: {
    currentTab: 0,
    orderlist: []
  },
  onLoad: function(options) {

  },
  onShow: function() {
    var that = this;
    wx.request({
      url: 'http://mestore.vicp.io/store/order/show',
      data: {
        id: app.globalData.userInfo2.id
      },
      header: {
        'content-type': 'application/json'
      },
      success: function(res) {

        that.setData({
          orderlist: res.data,
          // height: res.data.length*190+50
          height: wx.getSystemInfoSync().windowHeight
        })
        console.log("11111");
        var a =res.data.length;
        var arr = [];
        for(var i=0;i<a;i++){
          if (res.data[i].status === "已完成")
            arr.push(res.data[i])
        }
        app.globalData.finishorder = arr
        console.log(app.globalData.finishorder)
        console.log("11111");
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  // 付款
  payorder: function(e) {
    var that=this;
    wx.showModal({
      title: "请确认",
      content: "请确认订单？",
      confirmText: "确认",
      cancelText: '否',
      confirmColor: '#000000',
      cancelColor: '#3cc51f',
      success(res) {
        if (res.confirm) {
          wx.showToast({
            title: '正在生成订单，请稍候...',
            icon: 'loading',
            duration: 10000
          })

          console.log(e.target.dataset.id)
          wx.request({
            url: 'http://mestore.vicp.io/store/payorder/pay',
            data: {
              id: e.target.dataset.id
            },
            success: function (res) {
              if (res.data) {
                var orderlist = that.data.orderlist;
                orderlist[e.currentTarget.dataset.index].status = "待发货";
                that.setData({
                  orderlist: orderlist
                })
                wx.showToast({
                  title: '付款成功！',
                  duration: 3000,
                  complete: function () {
                    setTimeout(function () {
                      wx.switchTab({
                        url: "/page/order/order"
                      })
                    }, 2000)
                  }
                })
              } else {
                wx.showToast({
                  title: '支付失败！',
                  duration: 3000,
                })
              }
            },
          })
        } else if (res.cancel) {
          console.log('用户点击取消')
        }
      }
    })
  },
  switchSlider: function(e) {
    this.setData({
      current: e.target.dataset.index
    })
  },
  changeSlider: function(e) {
    this.setData({
      current: e.detail.current
    })
  },
  // 删除订单
  delorder: function(e) {

    var that = this
    console.log(e.currentTarget.dataset.id)
    wx.showModal({
      title: "请确认",
      content: "确认取消订单？",
      confirmText: "确认",
      cancelText: '否',
      confirmColor: '#000000',
      cancelColor: '#3cc51f',
      success(res) {
        if (res.confirm) {
          wx.showToast({
            title: '正在取消订单，请稍候...',
            icon: 'loading',
            duration: 10000
          })

          console.log(e.target.dataset.id)
          wx.request({
            url: 'http://mestore.vicp.io/store/delorder/del',
            data: {
              id: e.target.dataset.id
            },
            success: function(res) {
              if (res.data) {
                var orderlist = that.data.orderlist
                orderlist.splice(e.currentTarget.dataset.index, 1)
                that.setData({
                  orderlist: orderlist
                })
                wx.showToast({
                  title: '删除成功！',
                  duration: 3000,
                  complete: function() {
                    setTimeout(function() {
                      wx.switchTab({
                        url: "/page/order/order"
                      })
                    }, 2000)
                  }
                })
              } else {
                wx.showToast({
                  title: '删除失败！',
                  duration: 3000,
                })
              }
            },
          })
        } else if (res.cancel) {
          console.log('用户点击取消')
        }
      }
    })
  },
  conforder: function(e) {
    var that = this;
    wx.showModal({
      title: "请确认",
      content: "确认收货？",
      confirmText: "确认",
      cancelText: '否',
      confirmColor: '#000000',
      cancelColor: '#3cc51f',
      success(res) {
        if (res.confirm) {
          wx.showToast({
            title: '正在更新，请稍候...',
            icon: 'loading',
            duration: 10000
          })

          console.log(e.target.dataset.id)
          wx.request({
            url: 'http://mestore.vicp.io/store/conforder/confirm',
            data: {
              id: e.target.dataset.id
            },
            success: function(res) {
              if (res.data) {
                
                var orderlist = that.data.orderlist;
                orderlist[e.currentTarget.dataset.index].status = "待评价";
                that.setData({
                  orderlist: orderlist
                })
                wx.showToast({
                  title: '确认收货成功！',
                  duration: 3000,
                  complete: function() {
                    setTimeout(function() {
                      wx.switchTab({
                        url: "/page/order/order"
                      })
                    }, 2000)
                  }
                })
              } else {
                wx.showToast({
                  title: '收货失败！',
                  duration: 3000,
                })
              }
            },
          })
        } else if (res.cancel) {
          console.log('用户点击取消')
        }
      }
    })
  },
  /**
   * 导航标签选择1）
   */
  swichNav: function(e) {
    console.log(e);
    var that = this;
    if (this.data.currentTab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentTab: e.target.dataset.current,
        height: wx.getSystemInfoSync().windowWidth
      })
    }
  },
  /**
   * 导航页面显示2）
   */
  swiperChange: function(e) {
    this.setData({
      height: wx.getSystemInfoSync().windowHeight,
      currentTab: e.detail.current,
    })
  },
  redTodetail: function(e) {
    var that = this;
    var id = JSON.stringify(that.data.orderlist[e.currentTarget.dataset.id]);
    console.log(id);
    wx.navigateTo({
      url: "/page/valdetail/valdetail?orderlist=" + id
    })
  },
  connectstore:function(e){
    wx.makePhoneCall({
      phoneNumber: e.currentTarget.dataset.tell
    })
  },
  aftersale:function(e){
    wx.navigateTo({
      url:'/page/aftersale/aftersale?id='+e.currentTarget.dataset.id+"&store_id="+e.currentTarget.dataset.storeid
    })
  },
  redTovaluation:function(e){
    var that = this;
    var id = JSON.stringify(that.data.orderlist[e.currentTarget.dataset.index]);
    console.log(id);
    wx.navigateTo({
      url: "/page/valuation/valuation?orderlist=" + id
    })
  }
})