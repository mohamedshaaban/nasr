<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>From</th>
            <th>To</th>
        </tr>
        <tr>
            <th>{{ $request->from_year == 'null' ? '-' : $request->from_month . '-' . $request->from_year}}</th>
            <th>{{ $request->to_year == 'null' ? '-' : $request->to_month . '-' . $request->to_year}}</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($analytics as $analytic )
        <tr>
            <td>{{ $analytic['area']}}</td>
            <td>{{ $analytic['percentage']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>