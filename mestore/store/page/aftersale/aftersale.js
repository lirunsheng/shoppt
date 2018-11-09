// page/aftersale/aftersale.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    goods: ['未收到货', '已收到货', '请选择'],
    i: 2,
    reason: ['不喜欢/不想要', '空包裹', '未按约定时间发货', '物流未到', '货物破损', '请选择'],
    j: 5,
    goodstatus: '',
    reas: '',
    notes: ''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      order_id: options.id,
      store_id: options.store_id
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  },
  open1: function () {
    var that = this;
    wx.showActionSheet({
      itemList: ['未收到货', '已收到货'],
      success: function (res) {
        if (!res.cancel) {
          that.setData({
            i: res.tapIndex,
            goodstatus: that.data.goods[res.tapIndex]
          })
        }
      }
    });
  },
  open2: function () {
    var that = this;
    wx.showActionSheet({
      itemList: ['不喜欢/不想要', '空包裹', '未按约定时间发货', '物流未到', '货物破损'],
      success: function (res) {
        if (!res.cancel) {
          that.setData({
            j: res.tapIndex,
            reas: that.data.reason[res.tapIndex]
          })
        }
      }
    });
  },
  addnotes: function (e) {
    this.setData({
      notes: e.detail.value
    })
  },
  submitinfo: function () {
    var that = this;
    if (that.data.notes == "" || that.data.reas == "" || that.data.goodstatus == "") {
      wx.showModal({
        title: "提示",
        content: "请填写完整信息"
      })
    }
    else {
      //反馈入库
      wx.request({
        url: "http://mestore.vicp.io/store/aftersale/add",
        data: {
          store_id: that.data.store_id,
          goodstatus: that.data.goodstatus,
          reason: that.data.reas,
          note: that.data.notes,
          order_id: that.data.order_id
        },
        success: function (res) {
          if (res.data) {
            wx.showToast({
              title: '提交成功！',
              duration: 3000,
              complete: function () {
                setTimeout(function () {
                  wx.switchTab({
                    url: "/page/index/index"
                  })
                }, 2000)
              }
            })
          }
          else {
            wx.showToast({
              title: '提交失败！',
              duration: 3000,
              complete: function () {
                setTimeout(function () {
                  wx.switchTab({
                    url: "/page/index/index"
                  })
                }, 2000)
              }
            })
          }
        }
      })
    }
  }
})