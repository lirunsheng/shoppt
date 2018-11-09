var index = 0;
var app = getApp();
Page({
  data: {
    li: [],
    area: {},
  },
  addAddre: function(e) {
    wx.navigateTo({
      url: '/page/address/newAddreSY/newAddreSY'
    })
  },
  toModifyAddre: function(e) {
    // console.log(e);
    console.log("选中的电话" + e.currentTarget.dataset.tell);
    console.log("选中的index" + e.currentTarget.dataset.index)
    wx.navigateTo({
      url: '/page/address/modifyAddreSY/modifyAddreSY?name=' + e.currentTarget.dataset.name + "&tell=" + e.currentTarget.dataset.tell + "&door=" + e.currentTarget.dataset.door + "&index=" + e.currentTarget.dataset.index + "&area=" + e.currentTarget.dataset.area,
    })
  },
  todownOrder: function(e) {
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
          li:[]
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
    })

  }
})