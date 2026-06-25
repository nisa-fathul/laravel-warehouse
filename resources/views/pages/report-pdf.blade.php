<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h3 {
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px;
            text-align: left;
        }

        .chart {
            margin: 20px 0;
            text-align: center;
        }

        .chart img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

    <h2>Sales Report</h2>
    <p>
        Daily Period: {{ $startDate }} - {{ $endDate }} <br>
        Monthly Year: {{ $year }}
    </p>

    <h3>Daily Chart</h3>
    @if($dailyChartImage)
        <div class="chart">
            <img src="{{ $dailyChartImage }}" alt="Daily Chart">
        </div>
    @endif

    <h3>Daily Table</h3>
    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Month</th>
                <th>Year</th>
                <th>Sales Actual</th>
                <th>Delivery Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyData as $row)
                <tr>
                    <td>{{ $row['period'] }}</td>
                    <td>{{ $row['month'] }}</td>
                    <td>{{ $row['year'] }}</td>
                    <td>{{ $row['sales_actual'] }}</td>
                    <td>{{ $row['delivery_qty'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Monthly Chart</h3>
    @if($monthlyChartImage)
        <div class="chart">
            <img src="{{ $monthlyChartImage }}" alt="Monthly Chart">
        </div>
    @endif

    <h3>Monthly Table</h3>
    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Year</th>
                <th>Sales Actual</th>
                <th>Delivery Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyData as $row)
                <tr>
                    <td>{{ $row['period'] }}</td>
                    <td>{{ $row['year'] }}</td>
                    <td>{{ $row['sales_actual'] }}</td>
                    <td>{{ $row['delivery_qty'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
