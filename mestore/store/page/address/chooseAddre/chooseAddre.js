var index = 0;
var app = getApp();
Page({
  data: {
    li: [],
    area: {},
  },
  addAddre: function(e) {
    wx.navigateTo({
      url: '/page/address/newAddre/newAddre'
    })
  },
  toModifyAddre: function(e) {
    // console.log(e);
    console.log("选中的电话" + e.currentTarget.dataset.tell);
    console.log("选中的index" + e.currentTarget.dataset.index)
    wx.navigateTo({
      url: '/page/address/modifyAddre/modifyAddre?name=' + e.currentTarget.dataset.name + "&tell=" + e.currentTarget.dataset.tell + "&door=" + e.currentTarget.dataset.door + "&index=" + e.currentTarget.dataset.index + "&area=" + e.currentTarget.dataset.area,
    })
  },
  todownOrder: function(e) {
    console.log(this.data.li);
    for (var i = 0; i < this.data.li.length; i++) {
      if (i == e.currentTarget.dataset.index) {
        var li2 = 'li[' + i + '].image';
        this.setData({
          [li2]: "/imgs/address/check.jpg"
        })
        console.log('1111' + this.data.li[0].image);
      } else {
        this.setData({
          'li2': "/imgs/address/uncheck.png"
        })
      }
    }
    let pages = getCurrentPages();  //上一页的数据
    let prevPage = pages[pages.length - 2];
    prevPage.setData({
      name: e.currentTarget.dataset.name,
      tell: e.currentTarget.dataset.tell,
      area: e.currentTarget.dataset.area,
      door: e.currentTarget.dataset.door,
      flag: true
    })
    wx.navigateBack({
      delta:1
    })
    // console.log(this.data.li);
  },

  onLoad: function() {
  },

  onShow:function(){
    var that = this;
    wx.request({
      url: 'http://mestore.vicp.io/store/order/showAddress?openid=' + app.globalData.userInfo2.openid,
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        that.setData({
          area: res.data,
          li: []
        })
        for (var i in that.data.area) {
          that.data.li.push({
            image: '/imgs/address/uncheck.png',
            'tell': that.data.area[i].tell,
            'name': that.data.area[i].name,
            'area': that.data.area[i].area,
            'door': that.data.area[i].door
          })
        }
        that.setData({
          li: that.data.li
        })
        console.log(that.data.li)
        console.log(that.data.area);
      }
    });

  },
  

})