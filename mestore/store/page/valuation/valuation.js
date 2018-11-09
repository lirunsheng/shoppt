// page/merenter/merenter.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    content: "购物愉快，很满意！",
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
    ],
    length: 0,
    files: []
  },
  length(e) {
    let length = e.detail.value.length;
    if (length > 50) {
      wx.showToast({
        title: "内容过长",
        image: "/imgs/valuation/warn.png"
      })
    }
    // console.log(length)
    this.setData({
      length: length,
      content: e.detail.value
    })
  },
  deleteImage: function(e) {
    var files = this.data.files;
    var index = e.currentTarget.dataset.index;
    files.splice(index, 1);
    this.setData({
      files: files
    });
  },
  chooseImage: function(e) {
    var that = this;
    wx.chooseImage({
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function(res) {
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        that.setData({
          files: that.data.files.concat(res.tempFilePaths)
        });
      }
    })
  },
  previewImage: function(e) {
    wx.previewImage({
      current: e.currentTarget.id, // 当前显示图片的http链接
      urls: this.data.files // 需要预览的图片http链接列表
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var that = this;
    let orderlist = JSON.parse(options.orderlist);
    this.setData({
      orderlist: orderlist,
    });
    console.log(that.data.orderlist)
  },
  changeColor11: function(e) {
    var tmp = "objectArray[" + e.target.dataset.flat + "].flag";
    var that = this;
    that.setData({
      [tmp]: 1
    });
  },
  changeColor12: function(e) {
    var tmp = "objectArray[" + e.target.dataset.flat + "].flag";
    var that = this;
    that.setData({
      [tmp]: 2
    });
  },
  changeColor13: function(e) {
    var tmp = "objectArray[" + e.target.dataset.flat + "].flag";
    var that = this;
    that.setData({
      [tmp]: 3
    });
  },
  changeColor14: function(e) {
    var tmp = "objectArray[" + e.target.dataset.flat + "].flag";
    var that = this;
    that.setData({
      [tmp]: 4
    });
  },
  changeColor15: function(e) {
    var tmp = "objectArray[" + e.target.dataset.flat + "].flag";
    var that = this;
    that.setData({
      [tmp]: 5
    });
  },
  redtodetail: function() {
    var that = this;
    var id = JSON.stringify(that.data.orderlist);
    wx.navigateTo({
      url: "/page/valdetail/valdetail?orderlist=" + id
    })
  },
  submitinfo: function() {
    var that=this;
    wx.showModal({
      title: '提示',
      content: '确认提交？',
      success(res) {
        if (res.confirm) {
          wx.request({
            url: 'http://mestore.vicp.io/store/addfeedback/add',
            data:{
              order_id:that.data.orderlist.id,
              flag1: that.data.objectArray[0].flag,
              flag2: that.data.objectArray[1].flag,
              flag3: that.data.objectArray[2].flag,
              content:that.data.content
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
        } else if (res.cancel) {
          console.log('用户点击取消')
        }
      }
    })
    
    // console.log(that.data.content)
    // console.log(that.data.objectArray[0].flag)
    // console.log(that.data.objectArray[1].flag)
    // console.log(that.data.objectArray[2].flag)
  }
})