@component('mail::message')
# {{ $user->name }} 様

ご注文ありがとうございます。

以下の内容でご注文を承りました！

@component('mail::panel')
合計金額: ¥{{ number_format($order->total_price) }}
@endcomponent

詳細はマイページでご確認いただけます。

@endcomponent
