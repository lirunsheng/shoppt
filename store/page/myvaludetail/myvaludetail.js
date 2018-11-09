// page/myvaludetail/myvaludetail.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    showstar: "/imgs/valuation/lightstar.png",
    hidestar: "/imgs/valuation/star.png",
    objectArray: [{
      name: "描述相符",
      unique: '0',
      flag: "5"
    },
    {
      name: "发货速度",
      unique: '1',
      flag: "5"
    },
    {
      name: "服务态度",
      unique: '2',
      flag: "5"
    }
    ]
    
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var that = this;
    var order = JSON.parse(options.order)
    var a = JSON.parse(order.feedbacktype)
    var tmp1 = "objectArray[0].flag";
    var tmp2 = "objectArray[1].flag";
    var tmp3 = "objectArray[2].flag";
    that.setData({
      [tmp1]:a[0],
      [tmp2]:a[1],
      [tmp3]:a[2],
      feed: order.feedback,
      order: order,
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function() {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  },
  backTO:function(){
    wx.navigateBack({
      delta:1
    })
  },
  redtodetail: function () {
    var that = this;
    var id = JSON.stringify(that.data.order);
    wx.navigateTo({
      url: "/page/valdetail/valdetail?orderlist=" + id
    })
  },
})