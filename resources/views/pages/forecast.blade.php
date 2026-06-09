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

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3"></div>
<div class="container-fluid">

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <h5 class="mb-4">Parameter Forecast</h5>

        <form method="GET">

            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Nama Barang</label>
                    <select class="form-select">
                        <option>-- Pilih Barang --</option>
                        <option>Switch</option>
                        <option>Safety Rotating Hub</option>
                        <option>Key Holder Assy Grey</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Periode Data</label>
                    <select class="form-select">
                        <option>3 Bulan</option>
                        <option>6 Bulan</option>
                        <option>12 Bulan</option>
                        <option>7 Hari</option>
                        <option>14 Hari</option>
                        <option>30 Hari</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        Hitung Forecast
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

<div class="card shadow-sm">

    <div class="card-header">
        <h5 class="mb-0">Hasil Forecasting</h5>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="forecastTable"
                   class="table table-bordered table-hover">

                <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Periode</th>
                    <th>Bulan</th>
                    <th>Total Penjualan</th>
                    <th>Rata-rata (MA)</th>
                    <th>Forecast</th>
                    <th>MAPE (%)</th>
                    <th>Akurasi (%)</th>
                    <th>Stok Saat Ini</th>
                    <th>Status</th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <td>1</td>
                    <td>Switch</td>
                    <td>3 Bulan</td>
                    <td>Desember 2025</td>
                    <td>16.731</td>
                    <td>16.995</td>
                    <td>17.025</td>
                    <td>1.76</td>
                    <td>98.24</td>
                    <td>10</td>
                    <td>
                        <span class="badge bg-warning">
                            Pesan 7
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Safety Rotating Hub</td>
                    <td>3 Bulan</td>
                    <td>Desember 2025</td>
                    <td>60.000</td>
                    <td>80.000</td>
                    <td>73.333</td>
                    <td>22.22</td>
                    <td>77.78</td>
                    <td>120</td>
                    <td>
                        <span class="badge bg-success">
                            Cukup
                        </span>
                    </td>
                </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>
<div class="card shadow-sm mt-4">

    <div class="card-header">
        <h5 class="mb-0">
            Tren Permintaan Barang
        </h5>
    </div>

    <div class="card-body">

        <canvas id="forecastChart"
                height="100"></canvas>

    </div>

</div>
@include('components.footer')
@endsection
