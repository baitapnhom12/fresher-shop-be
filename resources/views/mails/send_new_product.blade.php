<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Notify New Product</title>
</head>

<body>
    <p>Hey Guy!</p>
    <p>We have a new product: <strong>{{ $product }}</strong> </p>
    <p>Please visit <a href="{{ config('app.url') }}">my Website</a> to show more.</p>
    <p>Thank you for using our service.</p>
</body>

</html>
