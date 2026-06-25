@extends('html.html')

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    let dailyChartInstance;
    let monthlyChartInstance;

    const dailyChart = @json($dailyChart);
    const monthlyChart = @json($monthlyChart);

    dailyChartInstance = new ApexCharts(
        document.querySelector("#dailyChart"),
        {
            chart: {
                type: 'bar',
                height: 350
            },

            series: [
                {
                    name: 'Sales',
                    data: dailyChart.sales_actual
                },
                {
                    name: 'Delivery Qty',
                    data: dailyChart.delivery_qty
                },
            ],

            xaxis: {
                categories: dailyChart.period
            },

            dataLabels: {
                enabled: false
            }
        }
    )

    dailyChartInstance.render()

    monthlyChartInstance = new ApexCharts(
        document.querySelector("#monthlyChart"),
        {
            chart: {
                type: 'bar',
                height: 350
            },

            series: [
                {
                    name: 'Sales',
                    data: monthlyChart.sales_actual
                },
                {
                    name: 'Delivery Qty',
                    data: monthlyChart.delivery_qty
                },
            ],

            xaxis: {
                categories: monthlyChart.period
            },

            dataLabels: {
                enabled: false
            }
        }
    )

    monthlyChartInstance.render()

    $('#btn-preview-pdf').on('click', async function () {
        let dailyImg = '';
        let monthlyImg = '';

        if (dailyChartInstance) {
            const dailyUri = await dailyChartInstance.dataURI();
            dailyImg = dailyUri.imgURI;
        }

        if (monthlyChartInstance) {
            const monthlyUri = await monthlyChartInstance.dataURI();
            monthlyImg = monthlyUri.imgURI;
        }

        $('#daily_chart_image').val(dailyImg);
        $('#monthly_chart_image').val(monthlyImg);
        $('#download_pdf').val('pdf');

        $('#preview-report-form')
        .attr('target', '_blank')
        .submit();

        $('#preview-report-form')
        .removeAttr('target', '_blank')

        $('#download_pdf').val('');
    });
</script>

<script>
    $(document).ready(function () {
        $('.table').DataTable({
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

@php
$currentYear = now()->year;
@endphp

<main id="main" class="main">
    <form id="preview-report-form" action="{{ route('report.index.post') }}" method="POST">
        @csrf
        @method('post')
        <div class="card shadow-sm mt-4">
            <div class="row g-2 p-3 align-items-end">
                <div class="col-auto">
                    <label class="form-label">Start Date</label>
                    <input id="start_date" name="start_date" type="date" class="form-control"
                        value="{{ old('start_date', request('start_date', now()->format('Y-m-d'))) }}">
                </div>

                <div class="col-auto">
                    <label class="form-label">End Date</label>
                    <input id="end_date" name="end_date" type="date" class="form-control"
                        value="{{ old('end_date', request('end_date', now()->addDays(6)->format('Y-m-d'))) }}">
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center px-2">
                <input type="hidden" name="download_pdf" id="download_pdf" value="">
                <input type="hidden" name="daily_chart_image" id="daily_chart_image">
                <input type="hidden" name="monthly_chart_image" id="monthly_chart_image">

                <button type="button" id="btn-preview-pdf" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Preview PDF
                </button>
            </div>
            <div class="card-header">
                <h5 class="mb-0">
                    Daily Report
                </h5>
            </div>
            <div class="card-body">
                <div id="dailyChart"></div>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    Daily Table
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Sales Actual</th>
                                <th>Delivery Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailyData as $key => $value )
                            <tr>
                                <td>{{ $value['period'] }}</td>
                                <td>{{ $value['month'] }}</td>
                                <td>{{ $value['year'] }}</td>
                                <td>{{ $value['sales_actual'] }}</td>
                                <td>{{ $value['delivery_qty'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="row g-2 p-3 align-items-end">
                <div class="col-auto">
                    <label class="form-label">Year</label>
                    <select name="years" class="form-select">
                        @for ($year = $currentYear - 5; $year <= $currentYear + 5; $year++) <option value="{{ $year }}"
                            {{ request('years', $currentYear)==$year ? 'selected' : '' }}>
                            {{ $year }}
                            </option>
                            @endfor
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="card-header">
                <h5 class="mb-0">
                    Monthly Report
                </h5>
            </div>
            <div class="card-body">
                <div id="monthlyChart"></div>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    Monthly Table
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Year</th>
                                <th>Sales</th>
                                <th>Delivery Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monthlyData as $key => $value)
                            <tr>
                                <td>{{ $value['period'] }}</td>
                                <td>{{ $value['year'] }}</td>
                                <td>{{ $value['sales_actual'] }}</td>
                                <td>{{ $value['delivery_qty'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</main>
@endsection
</script>
