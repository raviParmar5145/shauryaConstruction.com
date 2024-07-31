<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Email </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">
    @if ( $mailData['userType'] == 'customer')
        <h1>Thanks for your order!!</h1>
        <h2>Your Order Id Is: # {{ $mailData['order']->id }}</h2>
    @else
        <h1>You have receiver an order!!</h1>
        <h2>Order Id Is: # {{ $mailData['order']->id }}</h2>
    @endif
        
    <h2>Shipping Address</h2>
    <address>
        <strong>{{ $mailData['order']->first_name.' '.$mailData['order']->last_name }} </strong><br>
        {{ $mailData['order']->address }}<br>
        {{ $mailData['order']->city }}, {{  $mailData['order']->zip }} {{ getCountryInfo($mailData['order']->country_id)->name }}<br>
        Phone: {{ $mailData['order']->mobile }}<br>
        Email: {{ $mailData['order']->email }}
    </address>
    <h2>Products</h2>

    <table class="table table-striped">
        <thead>
            <tr style="background: #ccc;">
                <th>Product</th>
                <th width="100">Price</th>
                <th width="100">Qty</th>                                        
                <th width="100">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td widtd="100">{{ number_format($item->price) }}</td>
                    <td widtd="100">{{ $item->qty }}</td>                                        
                    <td widtd="100">{{ $item->total }}</td>
                </tr>
            @endforeach

            <tr>
                <th colspan="3" class="text-right">Subtotal:</th>
                <td>${{ number_format($mailData['order']->subtotal,2) }}</td>
            </tr>

            <tr>
                <th colspan="3" class="text-right">Discount: {{ (!empty($mailData['order']->coupon_code)) ? '('.$mailData['order']->coupon_code. ')' : '' }}</th>
                <td>${{ number_format($mailData['order']->discount,2) }}</td>
            </tr>
            
            <tr>
                <th colspan="3" class="text-right">Shipping:</th>
                <td>${{ number_format($mailData['order']->shipping,2) }}</td>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Grand Total:</th>
                <td>${{ number_format($mailData['order']->grand_total,2) }}</td>
            </tr>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>