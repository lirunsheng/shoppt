var app = getApp();
var index = 0;
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
    door: '街道门牌信息',
    id:null
  },

  onLoad: function() {
    var that = this;
    that.setData({
      id:app.globalData.userInfo2.id
    });
    wx.getLocation({
      type: 'wgs84',
      success: function(res) {
        that.setData({
          myLatitude: res.latitude,
          myLongitude: res.longitude
        })
        //用腾讯地图的api，根据经纬度获取城市
        qqmap.reverseGeocoder({
          location: {
            latitude: that.data.myLatitude,
            longitude: that.data.myLongitude
          },
          success: function(res) {
            console.log(res)
            var a = res.result.address_component
            //获取市和区（区可能为空）
            that.setData({
              area: a.city + a.street_number
            })
            //控制台输出结果
            console.log(that.data.area)
          }
        });
      }
    })
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
  formSubmit: function(e) {
    var warn = "";
    var that = this;
    var flag = false;
    if (e.detail.value.name == "") {
      warn = "请填写您的姓名！";
    } else if (e.detail.value.tell == "") {
      warn = "请填写您的手机号！";
    } else if (!(/^1(3|4|5|7|8)\d{9}$/.test(e.detail.value.tell))) {
      warn = "手机号格式不正确";
    } else if (e.detail.value.area == '0') {
      warn = "请选择您的所在区域";
    } else if (e.detail.value.door == "") {
      warn = "请输入您的具体地址";
    } else {
      flag = true;
      wx.request({
        url: 'http://mestore.vicp.io/store/order/addAddress?tell=' + e.detail.value.tell + "&door=" + e.detail.value.door + "&name=" + e.detail.value.name + "&area=" + e.detail.value.area + "&door=" + e.detail.value.door + "&id=" + that.data.id,
        header: {
          'content-type': 'application/json'
        },
        success: function() {   
          wx.showToast({
            title: '保存成功',
            icon: 'success',
            duration: 2000,
          });    
          setTimeout(function () {
            wx.navigateBack({
            });
          }, 1000)
        }
      })
    }
    if (flag == false) {
      wx.showModal({
        title: '提示',
        content: warn
      })
    }

  },

})