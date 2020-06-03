<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Transferred Payment</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <table class="table table-bordered table-hover ">
        <thead>
            <tr>
                <th>Transferred Amount</th>
                {{-- <th>Remaining Balance</th> --}}
                <th>Month</th>
                <th>Transferred Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transferredPayments as $transferredPayment )
            <tr>
                <td>{{ number_format($transferredPayment->transferred_amount,env('NUMBER_FORMAT')) }}</td>
                {{-- <td>{{ number_format($transferredPayment->remaining_balance,env('NUMBER_FORMAT')) }}</td> --}}
                <td>{{ date('Y-m' , strtotime($transferredPayment->date)) }}</td>
                <td>{{ $transferredPayment->created_at }}</td>
            </tr>
            @endforeach
            <tr>
                <td><b>Total Transferred</b></td>
                <td colspan="3"><b>{{ number_format($totalPayment->transferred_amount,env('NUMBER_FORMAT')) }}</b></td>
            </tr>
        </tbody>
    </table>
</body>

</html>