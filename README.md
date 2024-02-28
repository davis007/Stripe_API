# STRIPE API TOWER

1. laravel sanctum を利用して認証
1. Domain ホワイトリストで特定のドメインからしかアクセス出来ないようにしてます
1. 完成後 PrivateRepository に変わります

## function

1. カード登録
1. カード決済
1. カード与信
1. 顧客登録
1. カード登録＆顧客新規登録
1. 決済取消し(返金)
1.

api.domain.jp/payment/point/{shop_code}/{amount}/{userId}

shop_code = 4 桁の SHOP コード
amount = 金額
userId = 購入者のユーザー ID 新規登録の場合は newuser
