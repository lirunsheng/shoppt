var app = getApp();
var server = require('../../utils/server');
var QQMapWX = require('../qqmap-wx-jssdk.js');
var qqmap = new QQMapWX({
  //在腾讯地图开放平台申请密钥 http://lbs.qq.com/mykey.html
  key: 'ZBSBZ-72C2X-VXD4G-ZDI35-THPGO-JFFL2'
});

Page({
  data: {
    re: [],
    myLatitude: "",
    myLongitude: "",
    myAddress: "",
    filterId: 1,
    searchWords: '', //关键字
    placeholder: '搜索商品',
    shops: app.globalData.shops,
  },
  onLoad: function() {
    var that = this;
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
            // console.log(res)
            var a = res.result.address_component
            //获取市和区（区可能为空）
            that.setData({
              myAddress: a.city + a.district
            })
            //控制台输出结果
            // console.log(that.data.myAddress)
          }
        });
      }
    })
  },
  onShow: function() {
    this.setData({
      showResult: false
    });
  },
  inputSearch: function(e) {
    this.setData({
      searchWords: e.detail.value
    });
  },


  connectstore: function (e) { //联系商家
    wx.makePhoneCall({
      phoneNumber: e.currentTarget.dataset.tell
    })
  },

  doSearch: function() {
    var that = this;
    this.setData({
      showResult: true
    });
    //请求php文件搜索关键字
    console.log(this.data.searchWords);
    wx.request({
      url: 'https://huazai233.picp.vip/store/searchshop/search?searchWords=' + this.data.searchWords,
      header: {
        'content-type': 'application/json'
      },
      success: function(res) {
        // console.log(res)
        if (res.data.length == 0) {
          wx.showModal({
            title: '抱歉，没有找到该商品！',
            icon: 'success',
            duration: 2000
          })
        } else {

          that.setData({
            re: res.data
          });


          //按照距离排序
          for (var i in res.data) {
            var lat1 = res.data[i].store_latitude;
            var lng1 = res.data[i].store_longitude;
            var st = 're[' + i + '].distance';
            var distance = that.getDistance(lat1, lng1);
            that.setData({
              [st]: distance
            });
          }
          // for(var i in that.data.store){
          //   console.log(that.data.store[i].distance);
          // }  循环输出距离

          //    按照距离排序输出
          that.data.re.sort(function(a, b) {
            return a.distance > b.distance;
          });
          that.setData({
            re: that.data.re
          });
          console.log(that.data.re);
        }
      },
    })
  },

  //下拉刷新
  downloading: function () {
    wx.startPullDownRefresh()
  },

  //计算两点位置距离
  getDistance: function(lat1, lng1) {
    lat1 = lat1 || 0;
    lng1 = lng1 || 0;
    var lat2 = this.data.myLatitude;
    var lng2 = this.data.myLongitude;

    var rad1 = lat1 * Math.PI / 180.0;
    var rad2 = lat2 * Math.PI / 180.0;
    var a = rad1 - rad2;
    var b = lng1 * Math.PI / 180.0 - lng2 * Math.PI / 180.0;

    var r = 6378137;
    var distance = r * 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a / 2), 2) + Math.cos(rad1) * Math.cos(rad2) * Math.pow(Math.sin(b / 2), 2)));

    if (distance > 1000) {
      distance = distance * 100;
      distance = Math.round(distance / 1000);
      distance = distance / 100;
      return distance + "km";
    }

    return distance + "m";
  },
});