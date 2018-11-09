var app = getApp();
var server = require('../../utils/server');
Page({
  data: {
    a: [], //食品类下标
    b: [], //饮品类下标
    c: [], //日用品类下标
    d: [], //保健类下标
    store_id: null, //商店ID
    store_name: '', //商店名称
    goods: {}, //商店货物
    goodsList: [ //商店货物类型
      {
        id: 'f1',
        classifyName: '食品',
        goods: []
      },
      {
        id: 'f2',
        classifyName: '饮品',
        goods: []
      },
      {
        id: 'f3',
        classifyName: '日用品',
        goods: []
      },
      {
        id: 'f4',
        classifyName: '保健品',
        goods: []
      }
    ],
    cart: {
       //作为购物车信息传入TP5,数据库
      tell: getApp().globalData.tell,
      count: 0,
      order_total: 0,
      store_id: null,
      list: {},
      gc: {} //重构作为购物车列表
    },
    showCartDetail: false
  },
  onLoad: function(options) {
    var that = this;
    var st = options.store_id;
    var sn = options.store_name;
    this.setData({
      store_id: st,
      'cart.store_id': st
    });
    // console.log(this.data.cart.store_id);
    wx.request({
      url: 'http://mestore.vicp.io/store/searchshop/showStore?store_id=' + this.data.store_id,
      header: {
        'content-type': 'application/json'
      },
      success: function(res) {
        console.log('1111')
        console.log(res.data);
        var d = res.data;
        var k = d[0].store_name;
        that.setData({
          goods: d ,//将商品列表保存到goods里面
          store_name: k
        });
        app.globalData.store_name=that.data.store_name;
        app.globalData.store_id = that.data.store_id;
        // console.log(that.data.goods);
        // console.log('111111'+that.data.store_name);
        // var ret2 = [];
        // for( var i in ret){
        //   ret2[]=ret[i].go;
        // }


        //判断是哪种类型的商品
        for (var i = 0; i < res.data.length; i++) {
          if (d[i].goods_style == '食品') {
            that.data.a.push(i)
          }
          if (d[i].goods_style == '饮品') {
            that.data.b.push(i)
          }
          if (d[i].goods_style == '日用品') {
            that.data.c.push(i)
          }
          if (d[i].goods_style == '保健品') {
            that.data.d.push(i)
          }
          // console.log(d[i].goods_style)
        }
        // console.log(that.data.a)

        //设置每种商品类型有哪些商品
        that.setData({
          'goodsList[0].goods': that.data.a,
          'goodsList[1].goods': that.data.b,
          'goodsList[2].goods': that.data.c,
          'goodsList[3].goods': that.data.d
        })
        console.log(that.data.goodsList);
      }
    });
  },
  onShow: function() {
    this.setData({
      classifySeleted: this.data.goodsList[0].id,
    });
  },

  tapAddCart: function(e) { //列表页增加商品
    this.addCart(e.target.dataset.gid);
  },
  tapReduceCart: function(e) { //列表页删除商品
    this.reduceCart(e.target.dataset.gid);
  },
  addCart: function(goods_name) { //购物车增加商品函数
    var num = this.data.cart.list[goods_name] || 0;
    this.data.cart.list[goods_name] = num + 1;

    //--------------找出对应gc的下标---------------
    var i = 0
    while (this.data.goods[i].goods_name != goods_name) {
      i++;
    }
    this.data.cart.gc[i] = num + 1;
    //--------------------------------------------
    this.countCart();

  },
  reduceCart: function(goods_name) { //购物车删除商品函数
    var num = this.data.cart.list[goods_name] || 0;
    if (num <= 1) {
      delete this.data.cart.list[goods_name];

      //--------------找出对应gc的下标---------------
      var i = 0
      while (this.data.goods[i].goods_name != goods_name) {
        i++;
      }
      delete this.data.cart.gc[i];
      //--------------------------------------------

    } else {
      this.data.cart.list[goods_name] = num - 1;

      //--------------找出对应gc的下标---------------
      var i = 0
      while (this.data.goods[i].goods_name != goods_name) {
        i++;
      }
      this.data.cart.gc[i] = num - 1;
      //--------------------------------------------

    }
    // console.log(this.data.cart.list[goods_name]);
    this.countCart();
  },


  countCart: function() { //计算购物车的数量和总价
    var count = 0;
    var order_total = 0;
    var j = 0
    var that = this;
    for (var goods_name in that.data.cart.list) { //将对应商品下标找出
      var i = 0
      while (that.data.goods[i].goods_name != goods_name) {
        i++;
      }
      var goods = that.data.goods[i];
      count += that.data.cart.list[goods_name];
      // console.log(id);
      order_total += goods.goods_price * that.data.cart.list[goods_name];
      // that.data.gc.push(i);
      // var tmp ="gc["+i+"]";
      var tmv = that.data.cart.list[goods_name];
      that.data.cart.gc[i] = tmv;
      // that.setData({
      //   [tmp]: tmv
      // });    
      j++;
    }
    that.data.cart.count = count;
    that.data.cart.order_total = order_total;
    that.setData({
    cart: that.data.cart,
    });
    // console.log(this.data.cart.gc)
    console.log(this.data.cart)
  },


  follow: function() {
    this.setData({
      followed: !this.data.followed
    });
  },


  onGoodsScroll: function(e) { //食品下拉
    if (e.detail.scrollTop > 10 && !this.data.scrollDown) {
      this.setData({
        scrollDown: true
      });
    } else if (e.detail.scrollTop < 10 && this.data.scrollDown) {
      this.setData({
        scrollDown: false
      });
    }

    var scale = e.detail.scrollWidth / 570,
      scrollTop = e.detail.scrollTop / scale,
      h = 0,
      classifySeleted,
      len = this.data.goodsList.length;
    this.data.goodsList.forEach(function(classify, i) {
      var _h = 70 + classify.goods.length * (46 * 3 + 20 * 2);
      if (scrollTop >= h - 100 / scale) {
        classifySeleted = classify.id;
      }
      h += _h;
    });
    this.setData({
      classifySeleted: classifySeleted
    });
  },
  tapClassify: function(e) { //商品分类
    var id = e.target.dataset.id;
    this.setData({
      classifyViewed: id
    });
    var self = this;
    setTimeout(function() {
      self.setData({
        classifySeleted: id
      });
    }, 100);
  },
  showCartDetail: function() { //   展示购物车
    this.setData({
      showCartDetail: !this.data.showCartDetail
    });
  },
  hideCartDetail: function() { //  隐藏购物车
    this.setData({
      showCartDetail: false
    });
  },
  submit: function(e) {
    var that = this;
    var order_total = that.count
    // console.log();
    app.globalData.cart = that.data.cart;
    if(app.globalData.userInfo2.tell !='')
    {
    console.log(that.data.cart);
    wx.navigateTo({ //跳转页面 
      url: "/page/address/downOrder/downOrder"
    })
    }else{
      wx.showModal({
        title: '',
        content: '请先绑定手机',
        showCancel: true,
        cancelText: '取消',
        cancelColor: '',
        confirmText: '绑定手机',
        confirmColor: '',
        success: function(res) {
          if (res.confirm) {
          wx.navigateTo({ //跳转页面 
            url: "/page/bindphone/bindphone"
          })
          }
        },
      })
    }
  }
});