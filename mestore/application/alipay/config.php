<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016092100566268",

		//商户私钥
		'merchant_private_key' => "MIIEpAIBAAKCAQEAoNkp/uCQI5NV7ZgWge2+0/d/vIhKg/xEH9nXx58zrRtHKCauJzRORgUPtb4oq6DdMEH5RYH0AvaoV4JdzfqMC6MVDdXevtFarR/Cgi62HsZ3kbQnr5dPE/u7q2ZKlqFu2jxHmDBRV1DW/ZY3KrDM0/PuLE0yNaRg6oEhv/NzVu322F3D+nvmvY8jG5N2Cbkm/onAaU/13t5wRua3rDGti3gTQhj3J9CKXA7ZrWOl5TKVAsIS6QH3lNJhsBbw8kwoakKmIPVSgr8mGEMwRuEprKkqMOGy70jJWYrYWjZtgWCSzk8MhLPBWlEIEGRpj/9hkLwPc1ApDiD+p+cATWoPPwIDAQABAoIBAAwrwoi9t0pp2KOs0W8tbTYe0TST34eTLBh45GH/guqDWOQJ4T2oMJANy/2FkRBDkNkY9OK0jaE3xs8wVybB4ZzzzyEH4N3xe3oEIDS3kiJMBDorNqGLwnkRoEa868HwHzI2Q3iZl14TCOGRX0VEsYsMNyZ1BOqYMJkuAj7DDiQyqLdxnuzFz9+XOuVu4vm54+Nie8NA9UjMk+uzN5xGoDsAWIs3mZIGaW0/yYR0g0w/hwxlNOQPlxeB3R6sy355FEULzFxPW907JLcwA75AyUpyuqtFzuYjbVWElS1YKD91q0bC6hYIgAbKyIBNtde4R0n1bAgWuXkOdK/9WO/ypfECgYEAzhJOz1EDve2Hv4Z8W5e8nqlG5Uiu6lBHecQtgm0W/8PCb7hGa9NKV5D+tw0PVUHcGx7G/N0d8r6v/J4BB9HeXZNLkUezWDE1Lso1GJYOLfGnywqgtMD5ifZcw7OcUV9J/p4jH2RW/CmOMIOTyOi+GJuFX7cTfY6qD95JnpZxLRkCgYEAx9Hc3LA+4DWeQkBIy6LuqnEeWcXjxCyq2jTrhlI+h+hosSNKhx3N7nYhswER43eR+4VKXWBFHU0Gbyk3nKiiGZxEFRnnk47Dh15Uj9sgwGc4Pb/ByMDnJo84D0klg43u/1YAams8z/bz6629XVB4UKMy+FfKZgY0gbxy83vVUhcCgYEAhhWtiAIL8EIlKP8F3cceqRI2Giyv+7CewWFfx1TaOTOUSYNa9v/1iILE6jCqQGxpHpBpI9Fwkil3c2UQAJmTGcokynQgAwuJsldKMyQ4N7bc2iWhKdvCwv01oPU2ik/5Yp5c9ikg3FThuzlyKLEgN/zBzOTf3ru8djTKLFFxdMkCgYEAofeIMRJjRNXxW/5Dg1ykXF+onFf9UUxriMgQiSYtLXjDM6JJjNAp1jrHAhPB7kFDgT7/kkSwgRmoN36PZ1qBWoUB4qDGxyKXdyiNHrYeDBcezV6bsyA5vVWluS4vTD1GFo4ddCVMe73O5aBEuFi7zZEbhZWTp7Mr6GVaP+aGeS0CgYBViBZZEfjREC5lLxPM3xus13DIhM+MBziLCkS0HwaS0HXYWh/nsh0PZhbYgopu8HO5FYlb26sgq2L7nTX2ii27l2nRYwrixCCIlQ3j/uMaJVCJCyW0HiKt0eq6XKl8iOBo8clYgKivKWcQDXqMF9sPeryczqVH2tB7hQoF25fQ7w==",
		
		//异步通知地址
		'notify_url' => "http://mestore.vicp.io/alipay/notify_url.php",
		
		//同步跳转
		'return_url' => "http://mestore.vicp.io/alipay/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2WzB1lnCG1SDM0/e80gaAyCqnqqwj5KNYGpC3B2h0GTxpNAR2DUpGZLFRJBc4i6axoqtYhmNjmC/7ArjOU34ZLJrVT35JslRbuX9rHg4HzPDUUpK2tfhfEFejzsmUbuRgy8GrNIZt3jhnyXOlHnQKiHrQr1boarWQVaQ6rz/N5jvrLVjC/7xoJIQMIDPzIlAO1duJh26Y+ZC5bLR02pWpjRUAm1n0kPNeP8ZegZYKNkvjZaJO/b5Zyb61RGhPaPt2EE+rW8GXDYCESUnI29BkRJzYpVDcOzel52Atp3m8SZe3TJ8dfX5BkDufGt2/Cuuilmkw6obWak1/+Q3jTTEnwIDAQAB",
);