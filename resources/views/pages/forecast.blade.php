@extends('html.html')

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    const chartData = @json($chartData);
    const categories = chartData.map(item => item.date);

    const actualData = chartData.map(item => item.sales_actual);

    const forecastData = chartData.map(item => item.forecast);

    var options = {
        chart: {
            type: 'line',
            height: 400,
            toolbar: {
                show: true
            }
        },
        series: [
            {
                name: 'Actual Sales',
                data: actualData
            },
            {
                name: 'Forecast',
                data: forecastData
            }
        ],

        xaxis: {
            categories: categories
        },

        stroke: {
            curve: 'smooth',
            width: 3
        },

        markers: {
            size: 5
        },

        dataLabels: {
            enabled: false
        },

        tooltip: {
            shared: true,
            intersect: false
        },

        legend: {
            position: 'top'
        },

        yaxis: {
            title: {
                text: 'Quantity'
            }
        }
    };

    var tets = new ApexCharts(document.querySelector("#forecastChart"),options).render();
</script>

<script>
    $(document).ready(function () {
        $('.select2' ).select2( {
            theme: 'bootstrap-5',
        });

        $('#forecastTable').DataTable({
            order:[],
        })
    });

    $('#start_date').on('change', function () {
        $('#end_date').attr('min', $(this).val());

        let startDate = $(this).val();
        let endDate = $('#end_date').val();

        if (endDate && endDate < startDate) {
            $('#end_date').val('');
        }
    });

</script>
@endpush

@section('content')
@include('components.navbar')
@include('components.sidebar')

<main id="main" class="main">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-4">Forecast Parameter</h5>
            <form action="{{ route('forecast.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Items</label>
                        <select class="form-select select2" name="item_id" required>
                            <option value="">---</option>
                            @foreach ($dataBarang as $barang )
                            <option value="{{ $barang->id }}" {{ request('item_id')==$barang->id ? 'selected' : '' }}>
                                {{ $barang->kode_barang.' - '.$barang->nama_barang }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-md-2">
                        <label class="form-label">Start Date</label>
                        <input id="start_date" type="date" name="start_date" class="form-control"
                            value="{{ request('start_date') }}" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">End Date</label>
                        <input id="end_date" type="date" name="end_date" class="form-control"
                            value="{{ request('end_date') }}" required>
                    </div> --}}

                    <div class="col-md-2">
                        <label class="form-label">Year</label>
                        <select name="years" class="form-select" required>
                            @for ($year = now()->year - 5; $year <= now()->year + 5; $year++)
                                <option value="{{ $year }}" {{ request('years', now()->year) == $year ? 'selected' :
                                    ''}}>
                                    {{ $year }}
                                </option>
                                @endfor
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100">
                            Show Forecast
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Forecast result</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="forecastTable" class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Period</th>
                            <th>Item Name</th>
                            <th>Month</th>
                            <th>Total Sales</th>
                            {{-- <th>Rata-rata (MA)</th> --}}
                            <th>Forecast</th>
                            <th>MAPE (%)</th>
                            <th>MAE</th>
                            <th>MSE</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataForecast as $key => $val)
                        <tr>
                            <td>{{ $val['periode'] }}</td>
                            <td>{{ $val['nama_barang'] }}</td>
                            <td>{{ $val['bulan'] }}</td>
                            <td>{{ $val['total_penjualan_aktual'] }}</td>
                            {{-- <td>16.731</td> --}}
                            <td>{{ $val['forecast'] == null ? '-' : $val['forecast'] }}</td>
                            <td>{{ $val['forecast'] == null ? '-' : $val['mape'].'%' }}</td>
                            <td>{{ $val['forecast'] == null ? '-' : $val['mae'] }}</td>
                            <td>{{ $val['forecast'] == null ? '-' : $val['mse'] }}</td>
                            <td>{{ $val['stok_saat_ini'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">
                Forecast Trend
            </h5>
        </div>
        <div class="card-body">
            <div id="forecastChart"></div>
        </div>
    </div>
</main>
@include('components.footer')
@endsection
