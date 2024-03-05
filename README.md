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

payment/{shopCode}/{amount}/{userType}/{userId}

{amount} 金額
{userId} 購入者のユーザー cus\_ から始まる ID

{userType}
登録と新規決済,newuser
カード登録済ユーザー決済,userPayment
決済だけ,guest
