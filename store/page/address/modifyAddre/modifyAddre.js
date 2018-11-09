var flag = false;
var app = getApp();
var QQMapWX = require('../../qqmap-wx-jssdk.js');
var qqmap = new QQMapWX({
  //在腾讯地图开放平台申请密钥 http://lbs.qq.com/mykey.html
  key: 'ZBSBZ-72C2X-VXD4G-ZDI35-THPGO-JFFL2'
});

Page({
  data: {
    name: "请填写您的姓名",
    tell: "请填写您的联系方式",
    area: '',
    door: "街道门牌信息",
    index: "0",
    id:null
  },
  onLoad: function(options) {
    this.setData({
      name: options.name,
      tell: options.tell,
      area: options.area,
      door: options.door,
      index: options.index,
      id:app.globalData.userInfo2.id
    })
    console.log("传过来的index" + options.index);
    console.log("接收到的index" + this.data.index);
  },


  areaPickerBindchange: function(e) {
    this.setData({
      areaValue: e.detail.value
    })
  },
  addrePickerBindchange: function(e) {
    this.setData({
      addreValue: e.detail.value
    })
  },

  //点击删除
  delete: function() {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认删除该地址信息吗？',
      success: function(res) {
        if (res.confirm) {
          wx.request({
            url: 'https://huazai233.picp.vip/store/order/deleteAddress?index=' + that.data.index + "&id=" + that.data.id,
            success: function() {
              wx.showToast({
                title: '删除成功！',
              })
              wx.navigateBack({
                url: '/page/address/chooseAddre/chooseAddre',
              })
            }
            //？后面跟的是需要传递到下一个页面的参数
          });
        } else if (res.cancel) {
          console.log('用户点击取消')
        }
      }
    })
  },

  //点击取消，返回上个页面
  cancel: function() {
    wx.navigateBack({
      url: '/page/address/chooseAddre/chooseAddre',
    })
  },

  //点击保存
  formSubmit: function(e) {
    var warn = "";
    var that = this;
    if (e.detail.value.name == "") {
      warn = "请填写您的姓名！";
    } else if (e.detail.value.tell == "") {
      warn = "请填写您的手机号！";
    } else if (!(/^1(3|4|5|7|8)\d{9}$/.test(e.detail.value.tell))) {
      warn = "手机号格式不正确";
    } else if (e.detail.value.area == '0') {
      warn = "请选择您的所在区域";
    } else if (e.detail.value.door == "") {
      warn = "请输入您的详细地址";
    } else {
      flag = true;
      console.log('form发生了submit事件，携带数据为：', e.detail.value)
      wx.request({
        url: 'https://huazai233.picp.vip/store/order/modifyAddress?tell=' + e.detail.value.tell + "&door=" + e.detail.value.door + "&name=" + e.detail.value.name + "&area=" + e.detail.value.area + "&index=" + that.data.index + "&id=" + that.data.id,
        success: function() {
          wx.showToast({
            title: '保存成功！',
          })
          wx.navigateBack({
            url: '/page/address/chooseAddre/chooseAddre',
          })
        }
        //？后面跟的是需要传递到下一个页面的参数
      });
    }


    if (flag == false) {
      wx.showModal({
        title: '提示',
        content: warn
      })
    }

  },

})