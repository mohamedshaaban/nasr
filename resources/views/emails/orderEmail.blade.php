<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title>Order Confirmation</title>
</head>

<body>
    <table width="100%" style="min-width:1000px;" border="0" cellspacing="0" cellpadding="20">
        <tr>
            <td height="130" align="center" valign="top" bgcolor="#cfd6de">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" height="68" style="padding:5px;" bgcolor="#FFFFFF"><img src="http://bastaat.com/images/logo.png" width="160" height="99" /></td>
                        <td width="50%" align="right" valign="middle" bgcolor="#FFFFFF" style="font-family:Verdana, Geneva, sans-serif; font-size:16px; line-height:normal; padding:5px">
                    </tr>
                    <tr>
                        <td height="40" colspan="2" align="center" valign="middle" style="min-height:300px;">
                    </tr>
                    <tr>
                        <td height="274" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF" ; style="min-height:300px;">
                            <div style=" padding:20px; font-size:28px; font-family:`dana, Geneva, sans-serif; font-weight:bold;  color:#fff; background-color:#79c13b">
                                <span style="color:#2175aa">  </span>Thank you :<span style="color:#2175aa"> {{$user['name']}} </span>                                For using <span style="color:#2175aa"> Bastaat </span></div>
                            <center>
                                <table width="100%" border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
                                    <tr>
                                        <div style="line-height: normal;    padding: 28px 10px 25px 10px;    font-size: 16px;    text-align: center;    font-family: Verdana, Geneva, sans-serif; margin: 10px 20px 10px 20px;">
                                            Thank you for your order from {{ config('app.name') }}. You can check the status of your order by
                                            <a href="{{ route('customer.my-orders')}}">logging into your account</a>
                                        </div>
                                        <div style="line-height: normal;    padding: 28px 10px 25px 10px;    font-size: 16px;    text-align: center;    font-family: Verdana, Geneva, sans-serif; margin: 10px 20px 10px 20px;">
                                            If you have questions about your order, you can email us at <a href="mailto:{{ $setting->email }}">{{$setting->email}}</a> or call us at {{ $setting->phone}}.</div>

                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <b>Your Order #{{$order->unique_id}}</b>
                                            <br> Placed on {{ $order->created_at}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="width: 25%;" valign="top">
                                            Items
                                        </td>
                                        <td align="center" style="width: 25%;" valign="top">
                                            Farm
                                        </td>
                                        <td align="center" style="width: 25%;" valign="top">
                                            Qty
                                        </td>
                                        <td align="center" style="width: 25%;" valign="top">
                                          Unit  Price
                                        </td>
                                        <td align="center" style="width: 25%;" valign="top">
                                            Price
                                        </td>
                                    </tr>
                                    @foreach($order->orderProducts as $product)
                                    <tr>
                                        <td align="center">
                                            {{ $product->product->name_en }}
                                        </td>
                                        <td align="center">
                                            {{ $product->product->vendor->code }}
                                        </td>
                                        <td align="center">
                                            {{ $product->quantity }}
                                        </td>
                                        <td align="center">
                                            {{number_format((float)($product->sub_total), 2, '.', '')}} KWD
                                        </td>
                                        <td align="center">
                                            {{number_format((float)($product->total), 2, '.', '')}} KWD
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </center>
                    </tr>
                    <tr>
                        <td height="274" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF" ; style="min-height:300px;">
                            <center>
                                <table width="100%" border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
                                    <tr>
                                        <b>Shipping Address</b>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                            name
                                        </td>
                                        <td align="center" valign="top">
                                            phone
                                        </td>
                                        <td align="center" valign="top">
                                            city
                                        </td>
                                        <td align="center" valign="top">
                                            block
                                        </td>
                                        <td align="center" valign="top">
                                            street
                                        </td>
                                        <td align="center" valign="top">
                                            avenue
                                        </td>
                                        <td align="center" valign="top">
                                            floor
                                        </td>
                                        <td align="center" valign="top">
                                            flat
                                        </td>
                                        <td align="center" valign="top">
                                            extra direction
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            {{ $address->first_name . ' ' . $address->second_name }}
                                        </td>
                                        <td align="center">
                                            {{ $address->phone_no }}
                                        </td>
                                        <td align="center">
                                            {{$address->city }}
                                        </td>
                                        <td align="center">
                                            {{ $address->block }}
                                        </td>
                                        <td align="center">
                                            {{ $address->street }}
                                        </td>
                                        <td align="center">
                                            {{$address->avenue}}
                                        </td>
                                        <td align="center">
                                            {{ $address->floor }}
                                        </td>
                                        <td align="center">
                                            {{ $address->flat }}
                                        </td>
                                        <td align="center">
                                            {{$address->extra_direction}}
                                        </td>
                                    </tr>
                                </table>
                            </center>
                    </tr>
                    <tr>
                        <td height="274" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF" ; style="min-height:300px;">
                            <center>
                                <table width="100%" border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
                                    <tr>
                                        <td>
                                            <h3 style="font-weight:300; line-height:1.1; font-size:18px; margin-bottom:10px; margin-top:0">Order Total </h3>
                                        </td>
                                        <td>{{ $order->total }}</td>
                                    </tr>
                                </table>
                            </center>
                    </tr>
                    <tr>
                        <td height="274" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF" ; style="min-height:300px;">
                            <center>
                                <table width="100%" border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
                                    <tr>
                                        <td>
                                            <h3 style="font-weight:300; line-height:1.1; font-size:18px; margin-bottom:10px; margin-top:0">Payment Method </h3>
                                        </td>
                                        <td>{{ $order->paymentmethods->title }}</td>
                                    </tr>
                                </table>
                            </center>
                    </tr>
                    <tr>
                        <td height="274" colspan="2" align="center" valign="middle" bgcolor="#FFFFFF" ; style="min-height:300px;">
                            <center>
                                <table width="100%" border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
                                    <tr>
                                        <td>
                                            <h3 style="font-weight:300; line-height:1.1; font-size:18px; margin-bottom:10px; margin-top:0"></h3>
                                        </td>
                                        <td>
                                            Your Order will be delivered with 24 hours
                                        </td>
                                    </tr>
                                </table>
                            </center>
                    </tr>
                </table>
                </td>
        </tr>
    </table>
</body>

</html>
