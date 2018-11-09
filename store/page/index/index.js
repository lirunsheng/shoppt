var app = getApp();
var server = require('../../utils/server');
var QQMapWX = require('../qqmap-wx-jssdk.js');
var qqmap = new QQMapWX({
  //在腾讯地图开放平台申请密钥 http://lbs.qq.com/mykey.html
  key: 'ZBSBZ-72C2X-VXD4G-ZDI35-THPGO-JFFL2'
});

Page({
  data: {
    myLatitude: null,
    myLongitude: null,
    myAddress: '定位中…',
    scrollDown: false,
    filterId: 1,
    banners: [{
        id: 3,
        img: 'https://huazai233.picp.vip/wximgs/topImage/1.jpg',
        url: '',
        name: ''
      },
      {
        id: 1,
        img: 'https://huazai233.picp.vip/wximgs/topImage/2.jpg',
        url: '',
        name: ''
      },
      {
        id: 2,
        img: 'https://huazai233.picp.vip/wximgs/topImage/3.jpg',
        url: '',
        name: ''
      }
    ],
    icons: [
      [{
          id: 1,
          img: '/imgs/index/icon_1.png',
          name: '查询商品',
          url: '/page/index/search'
        },
        {
          id: 2,
          img: '/imgs/index/icon_2.png',
          name: '绑定手机',
          url: '/page/bindphone/bindphone'
        },
        {
          id: 3,
          img: '/imgs/index/icon_3.png',
          name: '收货地址',
          url: '/page/address/chooseAddreSY/chooseAddreSY'
        },
        {
          id: 4,
          img: '/imgs/index/icon_4.png',
          name: '商家入驻',
          url: '/page/storeenter/storeenter'
        },
      ]
    ],
    store: null //商店信息
  },

  onLoad: function() {
    var that = this;
    wx.getLocation({
      type: 'wgs84',
      success: function(res) {
        console.log(res);
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
              myAddress: a.street_number
            })
            //控制台输出结果
            console.log(that.data.myAddress)
          }
        });
        console.log(that.data.myLatitude);
        console.log(that.data.myLongitude);
        wx.request({
          url: 'https://huazai233.picp.vip/store/searchshop/indexStore',
          header: {
            'content-type': 'application/json'
          },
          success: function(res) {
            that.setData({
              store: res.data
            })
            console.log(that.data.myLatitude);
            console.log(that.data.myLongitude);
            for (var i in res.data) {
              var lat1 = res.data[i].store_latitude;
              var lng1 = res.data[i].store_longitude;
              var st = 'store[' + i + '].distance';
              var distance = that.getDistance(lat1, lng1);
              that.setData({
                [st]: distance
              });
            }
            // for(var i in that.data.store){
            //   console.log(that.data.store[i].distance);
            // }  循环输出距离

            //    按照距离排序输出
            that.data.store.sort(function(a, b) {
              return a.distance > b.distance;
            });
            that.setData({
              store: that.data.store
            });
            console.log(that.data.store);

          }
        })
      }
    })
  },

  onShow: function() { //用于刷新
    var that = this
    // app.globalData.userInfo2 = res.data;
    // console.log(app.globalData.userInfo2.id);
    // wx.request({
    //   url: 'https://huazai233.picp.vip/store/order/show',
    //   data: {
    //     id: app.globalData.userInfo2.id
    //   },
    //   header: {
    //     'content-type': 'application/json'
    //   },
    //   success: function(res) {
    //     var a = res.data.length;
    //     var arr = [];
    //     for (var i = 0; i < a; i++) {
    //       if (res.data[i].status === "已完成")
    //         arr.push(res.data[i])
    //     }
    //     app.globalData.finishorder = arr
    //   },
    //   fail: function() {
    //     // fail
    //   },
    //   complete: function() {
    //     // complete
    //   }
    // })
    // console.log('onShow111');
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

    distance = Math.round(distance / 1000);
    return distance + "m";
  },

  connectstore: function(e) { //联系商家
    wx.makePhoneCall({
      phoneNumber: e.currentTarget.dataset.tell
    })
  },

  onScroll: function(e) { //图片滚动效果
    if (e.detail.scrollTop > 100 && !this.data.scrollDown) {
      this.setData({
        scrollDown: true
      });
    } else if (e.detail.scrollTop < 100 && this.data.scrollDown) {
      this.setData({
        scrollDown: false
      });
    }
  },

  tapSearch: function() {
    wx.navigateTo({
      url: '/page/index/search'
    });
  },

  toNearby: function() {
    wx.navigateTo({
      url: '/page/map/map?myLatitude=' + this.data.myLatitude + '&myLonitude=' + this.data.myLongitude
    })
  },

  iconby: function(e) {

    var url = e.currentTarget.dataset.url;
    console.log(url);
    wx.navigateTo({
      url: url
    })
  },


  // tapFilter: function(e) {
  //   switch (e.target.dataset.id) {
  //     case '1':
  //       this.data.shops.sort(function(a, b) {
  //         return a.id > b.id;
  //       });
  //       break;
  //     case '2':
  //       this.data.shops.sort(function(a, b) {
  //         return a.sales < b.sales;
  //       });
  //       break;
  //     case '3':
  //       this.data.shops.sort(function(a, b) {
  //         return a.distance > b.distance;
  //       });
  //       break;
  //   }
  //   this.setData({
  //     filterId: e.target.dataset.id,
  //     shops: this.data.shops
  //   });
  // },
  tapBanner: function(e) {
    var name = this.data.banners[e.target.dataset.id].name;
    wx.showModal({
      title: '提示',
      content: '您点击了“' + name + '”活动链接，活动页面暂未完成！',
      showCancel: false
    });
  },

  downloading: function() {
    wx.startPullDownRefresh()
    wx.showNavigationBarLoading() //在标题栏中显示加载
    setTimeout(function() {
      // complete
      wx.hideNavigationBarLoading() //完成停止加载
      wx.stopPullDownRefresh() //停止下拉刷新
    }, 1500);
  }

});