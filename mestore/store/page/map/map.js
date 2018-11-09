// page/map/map.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    myLongitude:null,
    myLatitude:null,
    i:0,
    markers: [
    {
      id:1,
      latitude: 23.020670,
      longitude: 113.75179,
        iconPath: '/imgs/map/location.png'
    },
    {
      id:2,
      latitude: 23.020670,
      longitude: 113.75389,
      iconPath: '/imgs/map/location.png'
    },
      {
        id: 3,
        latitude: 23.020670,
        longitude: 113.75209,
        iconPath: '/imgs/map/location.png'
      }
    ]

    // covers: [{
    //   latitude: 23.099794,
    //   longitude: 113.324520,
    // }, {
    //   latitude: 23.099298,
    //   longitude: 113.324129,
    // }]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that=this;
    that.setData({
      myLongitude: options.myLonitude,
      myLatitude: options.myLatitude
    })
    console.log(options.myLonitude);
    console.log(options.myLatitude);
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function (e) {
    this.mapCtx = wx.createMapContext('myMap')
  },
  
  moveToLocation: function () {
    this.mapCtx.moveToLocation()
  },
  translateMarker: function () {
    var i = this.data.i%3;
    console.log(this.data.markers[i].latitude)
     this.setData({
       i:this.data.i+1
     })
    this.mapCtx.translateMarker({
      markerId: i+1,
      autoRotate: true,
      duration: 1000,
      destination: {
        latitude: this.data.markers[i].latitude,
        longitude: this.data.markers[i].longitude,
      },
      animationEnd() {
        console.log('animation end')
      }
    })
  },
  includePoints: function () {
    this.mapCtx.includePoints({
      padding: [10],
      points: [{
        latitude: 23.020670,
        longitude: 113.75389,
      }, {
          latitude: 23.020670,
          longitude: 113.75209,
      }]
    })
  }
})