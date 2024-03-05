# STRIPE API TOWER

1. laravel sanctum を利用して認証
1. Domain ホワイトリストで特定のドメインからしかアクセス出来ないようにしてます
1. 完成後 PrivateRepository に変わります

## function

1. カード決済(guest)
1. 決済履歴
1. 顧客登録
1. 顧客一覧
1. 顧客詳細
1. 顧客削除
1. カード登録＆顧客新規登録
1. カード登録＆既存顧客関連付け
1. 決済取消し(返金)
1.

## カード登録

api.domain.jp/api/payment/point/{amount}/{userId}
[header]
// shopCode = 4 桁の SHOP コード
HEADER['X-SHOP-CODE' => shopcode,]

amount = 金額
userId = 購入者のユーザー ID

{userId}
新規登録の場合,newuser
既存ユーザー決済,userPayment
ゲスト決済,guest
