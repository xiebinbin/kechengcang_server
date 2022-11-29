# 接入文档

## 签名算法
```php
md5(md5($salt) . md5($secureKey))
```
## 创建订单
POST:/api/web/pay-orders/store
#### 请求参数
```json
{
    "title": "标题",
    "fee": 2000,
    "remark":"备注",
    "app_id": "",
    "salt": "随机字符",
    "sign":"签名",
    "currency":1
}

```
#### 响应参数
```javascript
{
    "code": 200,
    "message":"ok",
    "result": {
        "id":"YOw3MgLZ7RWGylz4",
        "title":"\u6d4b\u8bd5",
        "remark":"xx",
            //金额 精确到分
        "fee":2000,
            // 金额单位 1 usd
        "currency":1,
            // 付费金额 精确到分
        "pay_fee":11111,
            // 支付币种 5 FTM
        "pay_currency":5,
            // 付款账号
        "pay_account":"",
            // 收款账号
        "receipt_account":"0xE4758EF12D49893581f71e6abdfB1ddA16a043ab",
        // 回调地址 回调时 会post方法传回所有订单数据
            "callback_url":"",
            // 支付完成后跳转页
        "redirect_url":"",
            // 支付状态 1 待支付 2已提交 3支付完成 4超时
        "pay_status":1,
            // 状态 1未完成 2已完成
        "status": 1,
            // 回调状态 1 未完成 2已完成 3超时
        "callback_status":1,
            // 回调时间
        "callback_at":null,
            // 回调次数    
        "callback_times":0,
        // 支付完成时间
        "pay_finish_at":null, 
        "pay_refresh_at":null,
        "pay_submit_at":null,
        "created_at":"",
        "updated_at":"",
        "deleted_at":null,
         // 应用id   
        "app_id":"",
        // 电视展示地址    
        "tv_url": ""
    }
}
```
## 查询订单
POST:/api/web/pay-orders/show?id=YOw3MgLZ7RWGylz4


#### 响应参数
```json
{
    "code":200,
    "message":"ok",
    "result": {
        "id":"YOw3MgLZ7RWGylz4",
        "title":"\u6d4b\u8bd5",
        "remark":"xx",
        "fee":2000,
        "currency":1,
        "pay_fee":11111,
        "pay_currency":5,
        "pay_account":"",
        "receipt_account":"0xE4758EF12D49893581f71e6abdfB1ddA16a043ab",
        "callback_url":"",
        "redirect_url":"",
        "pay_status":1,
        "status":1,
        "callback_status":1,
        "callback_at":null,
        "callback_times":0,
        "pay_finish_at":null,
        "pay_refresh_at":null,
        "pay_submit_at":null,
        "created_at":"",
        "updated_at":"",
        "deleted_at":null,
        "app_id":"",
        "tv_url":""
    }
}
```
