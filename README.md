# STRIPE API TOWER

1. Domain ホワイトリストで特定のドメインからしかアクセス出来ないようにしてます
1. 完成後 PrivateRepository に変わります

## function

1. カード決済
1. 決済履歴
1. 顧客登録
1. 顧客一覧
1. 顧客詳細
1. 顧客削除
1. カード登録
1. カード登録＆顧客新規登録
1. カード登録＆既存顧客関連付け
1. 決済取消し(返金)
1.

## カード決済

payment/{shopCode}/{amount}/{userType}/{userId}
payment/6JUR/3500/newuser/
register/BZHW/{userId}

[newuser]http://localhost:8000/payment/BZHW/2500/newuser
[userPayment]http://localhost:8000/payment/BZHW/2500/userPayment/cus_YI9NuxOm
[guest]http://localhost:8000/payment/BZHW/2500/guest
[registCard for Customer]http://localhost:8000/register/BZHW/{userId}

{shopCode} 4 桁のショップコード

{amount} 金額

{userType}
登録と新規決済,newuser
カード登録済ユーザー決済,userPayment
決済だけ,guest

{userId}
8 桁のカスタマー ID
購入者のユーザー 顧客登録した際に返却される 8 桁 ID
