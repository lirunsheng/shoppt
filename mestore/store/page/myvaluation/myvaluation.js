// page/myvaluation/myvaluation.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    that.setData({
      orderlist: app.globalData.finishorder
    })
    console.log(that.data.orderlist);
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
  navTo:function(e){
    var id = e.currentTarget.dataset.index;
    // var feedbacktype = e.currentTarget.dataset.feedbacktype;
    // var feedback = e.currentTarget.dataset.feedback
    var order = JSON.stringify(this.data.orderlist[id]);
    // console.log(feedbacktype);
    // console.log(feedback);
    wx.navigateTo({
      url:"/page/myvaludetail/myvaludetail?order="+order
    })
  }
})