<!doctype html>
<html lang="zxx">
<head>
    <!-- Required meta tags -->
    {{-- <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
          type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet"> --}}
    <title>{!! __(config('settings.company_name')) !!}</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .sm-w-full {
            width: 100% !important;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body style="margin: 0;">
<div style="width: 640px; margin: 0 auto; height: 100vh; position: relative;">
    <!-- top header background -->
    <table style="background-color: #000; padding: 15px; width: 100%;">
    </table>
    <div class="wrap" style="padding: 30px 30px;">
        <div>

            <table style=" width: 100%; padding-bottom:50px;">
                <tr>
                    <td>
                        <a href="#">
                            <img src="{{asset('assets/front/img/Logo.jpg')}}" alt="logo" style="height: 7.2rem">
                        </a>
                    </td>
                    <td>
                        <table style="width:100%; margin-right: left; text-align: right;">
                            <tr>
                                <td class="lats-tab--" style="font-weight: bold; font-size: 35px; color: #000;">
                                    Invoice
                                </td>
                            </tr>
                            <tr>
                                <td class="lats-tab--" style="font-weight: bold; font-size: 16px; color: #000;">
                                    Invoice No: <span style="color: #444444;">
                                    @if($loggedInUser->user_type=="user")
                                            {!! $order->order_number !!}
                                        @else
                                            {!! $order->order_no!!}
                                        @endif
                                </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>


            <table style="width: 100%; padding-bottom:50px; color: #000;">
                <tr>
                    <td style="font-size: 18px; font-weight: bold;">
                        {{$order->address->name}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 15px;font-weight: 400; color: #707070;">
                        Phone Number:
                        <a style="font-size: 15px;font-weight: 400; color: #707070;" href="tel:971 41239842">
                            {{$order->address->user_phone ? $order->address->user_phone  : 'N/A'}}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 15px;font-weight: 400; color: #707070;">
                        Email:
                        <a style="font-size: 15px;font-weight: 400; color: #707070;">
                            {{$order->address->address ? $order->address->address : 'N/A'}}
                        </a>
                    </td>
                </tr>
            </table>

        </div>
        <!-- items table -->
        <div class="overflow-x">
            <table style="width: 100%; border-collapse: collapse; padding-bottom: 40px;">
                <thead style="background-color: #000 ; padding: 10px 0; height: 40px; margin-bottom: 20px;">
                <tr style="text-align: center;">
                    <th style="font-size: 14px;font-weight: 400;color: #fff; text-align:center;">
                        Product Name
                    </th>
                    <th style="font-size: 14px;font-weight: 400;color: #fff; text-align:center;">
                        Quantity
                    </th>

                    <th style="font-size: 14px;font-weight: 400;color: #fff; text-align:center;">
                        Price
                    </th>
                    <th style="font-size: 14px;font-weight: 400;color: #fff; text-align:center;">
                        Total
                    </th>
                    <th style="font-size: 14px;font-weight: 400;color: #fff; text-align:center;">
                        Payment Method
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style="height: 20px; width: 100%;"></tr>
                @php $sr =1; @endphp
                @forelse($order->orderItems as $key=> $orderItem)
                    <tr style="height: 35px; background-color: #f1f1f1;">
                        <td style="font-size: 14px;font-weight: 400;color: #444; text-align:center;">
                            {{translate((array)$orderItem->name)}}
                        </td>
                        <td style="font-size: 14px;font-weight: 400;color: #444; text-align:center;">
                            {{$orderItem->quantity }}
                        </td>


                        <td style="font-size: 14px;font-weight: 400;color: #444; text-align:center;">
                            {!! getPrice($orderItem->price,session()->get('currency') ? session()->get('currency') : 'AED')!!}
                        </td>
                        <td style="font-size: 14px;font-weight: 400;color: #444; text-align:center;">
                            {!! getPrice($orderItem->subtotal ,session()->get('currency') ? session()->get('currency') : 'AED') !!}
                        </td>
                        <td style="font-size: 14px;font-weight: 400;color: #444; text-align:center;">
                            @if($order->payment_method=="cash_on_delivery")
                                Cash On Delivery
                            @elseif($order->payment_method=="samsung_pay")
                                Samsung Pay
                            @elseif($order->payment_method=="credit_card")
                                Credit Card
                            @else
                                Apple Pay
                            @endif
                        </td>
                    </tr>
                @empty
                @endforelse
                </tbody>
            </table>
        </div>
        <!-- Amount summery table -->
        <div class="overflow-x">
            <table
                style="background-color: #fff;border-radius: 15px; box-shadow: 0 3px 30px #00000029; padding: 16px 20px 16px; width: 50%;margin-left: auto; margin-top: 40px;">
                <thead>
                <tr>
                    <td>
                        <h5
                            style="font-size: 18px; font-weight: bold; color: #000; margin: 0; text-align: left; margin-bottom: 15px; line-height: 22px;">
                            Amount Summary
                        </h5>
                    </td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="font-size: 16px; font-weight: 300; color: #999999; text-align: left;">Subtotal</td>
                    <td style="font-size: 16px; font-weight: 400; color: #444444; text-align: right;"> {!! getPrice($order->subtotal , session()->get('currency') ? session()->get('currency') : 'AED')!!}</td>
                </tr>

                <tr>
                    <td style="font-size: 16px; font-weight: 300; color: #999999; text-align: left;">Shipping
                    </td>
                    <td style="font-size: 16px; font-weight: 400; color: #444444; text-align: right;"> {!! getPrice($order->shipping , session()->get('currency') ? session()->get('currency') : 'AED')!!}</td>
                </tr>

                <tr>
                    <td style="font-size: 16px; font-weight: 300; color: #999999; text-align: left;">VAT
                    </td>
                    <td style="font-size: 16px; font-weight: 400; color: #444444; text-align: right;"> {!! getPrice($order->vat , session()->get('currency') ? session()->get('currency') : 'AED')!!}</td>
                </tr>
                <tr>
                    <td style="font-size: 16px; font-weight: 300; color: #999999; text-align: left;">Total</td>
                    <td style="font-size: 16px; font-weight: 400; color: #444444; text-align: right;">{!! getPrice($order->total , session()->get('currency') ? session()->get('currency') : 'AED')!!}</td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
    <!-- footer table -->

    <table style="width: 100%;background-color: #000; height: 70px; position: absolute; bottom: 0;">
        <tr style=" justify-content: center; align-items: center; height: 60px;">
            <td style="text-align: center; font-size: 15px; color: #fff; font-weight: 400;">Copyright © <a href="#"
                                                                                                           style="font-size: 15px; color: #fff; font-weight: 400;">Build
                    It</a>
                - All Rights Reserved
            </td>
        </tr>
    </table>
</div>
</body>

</html>
