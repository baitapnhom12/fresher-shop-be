<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gift Coupon</title>
</head>
<body>
   
    <p>Hey Guy!</p>
    <p>We gift you coupons: <strong></strong></p>
    <p>Please visit <a href="{{ config('app.url') }}">my Website</a> to use it.</p>
    
   @foreach ($coupons as $key => $value)
       <b><p>Code Coupon {{$key+1}}</p></b>
       <li>Code: {{$value->sku}}</li>
       @if ($value->type==$price)
       <li>Discount: 20000</li>  
       @else
       <li>Discount: {{$value->discount * 100}}%</li>  
       @endif
       
       <li>Expired At: {{date('d-m-Y H:i:s', strtotime($value->expired_at))}}</li>
   @endforeach
   <p>Thank you for using our service.</p>
</body>
</html>