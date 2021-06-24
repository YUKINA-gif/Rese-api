<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <p>{{ $name }}様</p>

   <p>この度はReseご予約サービスをご利用いただき誠にありがとうございます。</p>
   <p>ご予約を確定いたしました。</p>

   <p>ご来店時に下記QRコードをお見せいただくとスムーズに入店していただけます。</p>

   <a>{{ $qr_code }}</a>

   <p>*予約内容の変更またはキャンセルがある場合はマイページよりお早めにご設定ください。当メールに返信いただきましても、ご対応はできかねますのでご了承くださいませ。</p>

   <p>Rese</p>
</body>
</html>