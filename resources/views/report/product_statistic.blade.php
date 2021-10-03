@extends('layouts.admin')

@section('title')
    <title>Laporan Statistik Produk</title>
@endsection

<style>
h3 {text-align: center;}
</style>

@section('content')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Laporan Statistik Produk</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                Statistik Produk
                            </h4>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('report.product_statistic') }}" method="get">
                                <div class="input-group mb-3 col-md-4 float-right">
                                    <input type="text" id="created_at" name="date" class="form-control">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="submit">Filter</button>
                                    </div>
                                    {{-- <a target="_blank" class="btn btn-primary ml-2" id="exportpdf">Export PDF</a> --}}
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <th>Varian</th>
                                            <th>Stok</th>
                                            <th>Terjual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product as $row)
                                            @foreach ($row->variant as $subrow)
                                                    <tr>
                                                        <td>{{ $row->name }}</td>
                                                        <td>{{ $subrow->name }}</td>
                                                        <td>{{ $subrow->stock }}</td>
                                                        <td>{{ $subrow->sold }}</td>
                                                    </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        $(document).ready(function() {
            let start = moment().startOf('month')
            let end = moment().endOf('month')

            $('#exportpdf').attr('href', '/administrator/reports/order/pdf/' + start.format('YYYY-MM-DD') + '+' + end.format('YYYY-MM-DD'))

            $('#created_at').daterangepicker({
                startDate: start,
                endDate: end
            }, function(first, last) {
                $('#exportpdf').attr('href', '/administrator/reports/order/pdf/' + first.format('YYYY-MM-DD') + '+' + last.format('YYYY-MM-DD'))
            })
        })

        const table = document.querySelector('table');
        let headerCell = null;
        for (let row of table.rows) {
            const firstCell = row.cells[0];
            if (headerCell === null || firstCell.innerText !== headerCell.innerText) {
                headerCell = firstCell;
            } else {
                headerCell.rowSpan++;
                firstCell.remove();
            }
        }
    </script>
@endsection()