// page/bindphone/bingphone.js
var app = getApp();
var maxTime = 60;
var currentTime = maxTime;
Page({

  data: {
    show: "获取验证码",
    isdisable: false,
    newtell: '',
    inputoldtell: '',
    oldtell: '',
    id: '',
    code: '',
    smstell: '',
    smscode: '',
    judge:false
  },
  onLoad: function (options) {
    var that = this;
    if(app.globalData.userInfo2.tell=="")
    {
      that.setData({
        judge:true
      })
    }
    that.setData({
      oldtell: app.globalData.userInfo2.tell,
      id: app.globalData.userInfo2.id
    })
  },

  bindoldtellInput: function (e) {
    this.setData({
      inputoldtell: e.detail.value
    })
  },
  updateCode: function (e) {
    this.setData({
      code: e.detail.value
    })
  },
  bindnewtellInput: function (e) {
    this.setData({
      newtell: e.detail.value
    })
  },
  //获取短信验证码以及倒数
  reSendPhoneNum: function () {
    var that = this
    //判断原手机是否正确
    if (that.data.inputoldtell == that.data.oldtell) {
      that.setData({
        isdisable: true,
      })
      currentTime = maxTime
      if (currentTime > 0) {
        var interval = setInterval(function () {
          currentTime--;
          that.setData({
            show: "重新获取" + currentTime + "s"
          })
          if (currentTime <= 0) {
            currentTime = -1
            clearInterval(interval)
            that.setData({
              isdisable: false,
              show: "获取验证码"
            })
          }
        }, 1000)
      }
      //手机不为空发送验证码请求
      if (that.data.newtell) {
        wx.request({
          url: "http://mestore.vicp.io/user/sendmsm/sms",
          data: {
            tell: that.data.newtell
          },
          success: function (res) {
            //若出错显示错误显示
            if (!res.data.status) {
              wx.showModal({
                title: '错误提示：',
                content: res.data.msg,
                showCancel: false,
              })
            }
            that.setData({
              smstell: res.data.tell,
              smscode: res.data.code
            })
            console.log(res.data)
          }
        })
      }
      else {
        wx.showModal({
          title: '错误提示',
          content: "新手机号码不能为空",
          showCancel: false,
        })
      }
    }
    else {
      wx.showModal({
        title: '错误提示',
        content: "请输入正确原号码",
        showCancel: false,
      })
    }
  },
  changephone: function () {
    var that = this;
    if (that.data.newtell == that.data.smstell && that.data.code == that.data.smscode) {
      wx.request({
        url: "http://mestore.vicp.io/user/checkrandom/index",
        data: {
          id: that.data.id,
          tell: that.data.newtell
        },
        success: function (res) {
          if (!res.data.status) {
            wx.showModal({
              title: '错误：',
              content: "更改手机失败",
              showCancel: false
            })
          }
          else {
            wx.showToast({
              title: '修改成功！',
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
    }
    else {
      wx.showModal({
        title: '错误：',
        content: "请核实后手机或验证码",
        showCancel: false
      })
    }
  }
})