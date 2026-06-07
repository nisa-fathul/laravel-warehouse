@extends('html.html')

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    new ApexCharts(document.querySelector("#forecastChart1"),
    {
        chart:{
            type:'line',
            height:300
        },
        series:[
            {
                name:'Forecast',
                data:[320,350,370,390,410,430]
            }
        ],
        xaxis:{
            categories:['Jul','Aug','Sep','Oct','Nov','Dec']
        }
        }
    ).render();

    new ApexCharts(document.querySelector("#forecastChart2"),
        {
            chart:{
                type:'line',
                height:300
            },
            series:[
                {
                    name:'Forecast',
                    data:[150,165,180,195,210,225]
                }
            ],
            xaxis:{
                categories:['Jul','Aug','Sep','Oct','Nov','Dec']
            }
        }
    ).render();
</script>
@endpush

@section('content')
@include('components.navbar')
@include('components.sidebar')

<main id="main" class="main">
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h4 class="card-title mb-2">
                Demand Forecasting — Next 6 Months
            </h4>

            <p class="text-muted mb-0">
                Predictions use weighted moving average with seasonal trend analysis.
                Confidence interval ±12%.
            </p>

        </div>

    </div>

    <div class="row g-4">

        <!-- Product 1 -->
        <div class="col-xl-6">

            <div class="card shadow-sm h-100">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-0">
                            Steel Plate A36
                        </h5>

                        <small class="text-muted">
                            SKU001 • Raw Material • PT Baja Indonesia
                        </small>
                    </div>

                    <div>
                        <span class="badge bg-success">
                            LOW RISK
                        </span>

                        <span class="badge bg-primary">
                            6.5 Months Coverage
                        </span>
                    </div>

                </div>

                <div class="card-body">

                    <div id="forecastChart1" style="height:300px;"></div>

                    <div class="table-responsive mt-4">

                        <table class="table table-bordered">

                            <tbody>

                                <tr>
                                    <th>Average Monthly Demand</th>
                                    <td>375 KG</td>
                                </tr>

                                <tr>
                                    <th>Current Stock</th>
                                    <td>2,450 KG</td>
                                </tr>

                                <tr>
                                    <th>Reorder Point</th>
                                    <td>500 KG</td>
                                </tr>

                                <tr>
                                    <th>Lead Time</th>
                                    <td>14 Days</td>
                                </tr>

                                <tr>
                                    <th>Safety Stock</th>
                                    <td>210 KG</td>
                                </tr>

                                <tr>
                                    <th>Suggested Reorder</th>
                                    <td>750 KG</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        <!-- Product 2 -->
        <div class="col-xl-6">

            <div class="card shadow-sm h-100">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-0">
                            Aluminium Sheet
                        </h5>

                        <small class="text-muted">
                            SKU002 • Material • PT Aluminium Jaya
                        </small>
                    </div>

                    <div>
                        <span class="badge bg-warning text-dark">
                            MEDIUM RISK
                        </span>

                        <span class="badge bg-primary">
                            2.8 Months Coverage
                        </span>
                    </div>

                </div>

                <div class="card-body">

                    <div id="forecastChart2" style="height:300px;"></div>

                    <div class="table-responsive mt-4">

                        <table class="table table-bordered">

                            <tbody>

                                <tr>
                                    <th>Average Monthly Demand</th>
                                    <td>185 PCS</td>
                                </tr>

                                <tr>
                                    <th>Current Stock</th>
                                    <td>520 PCS</td>
                                </tr>

                                <tr>
                                    <th>Reorder Point</th>
                                    <td>400 PCS</td>
                                </tr>

                                <tr>
                                    <th>Lead Time</th>
                                    <td>10 Days</td>
                                </tr>

                                <tr>
                                    <th>Safety Stock</th>
                                    <td>120 PCS</td>
                                </tr>

                                <tr>
                                    <th>Suggested Reorder</th>
                                    <td>370 PCS</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>
</main>

@include('components.footer')
@endsection
