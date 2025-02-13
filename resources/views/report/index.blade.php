@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Report'])
    <style>
        /* Membekukan kolom pertama hingga ketujuh di bagian footer */
        table.dataTable tfoot th {
            position: sticky;
            left: 0;
            z-index: 2;
            /* Menjaga agar tetap di atas elemen lain */
            background-color: white;
            color: #000;
            /* Warna latar belakang untuk menghindari transparansi */
            border-top: 1px solid #ddd;
        }

        /* Membuat kolom dari 2-7 tetap beku */
        table.dataTable tfoot th:nth-child(-n+7) {
            position: sticky;
            background-color: white;
            color: #000;
            left: 0;
            z-index: 2;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-datatable/css/dataTables.fixedColumn.css') }}">

    <div class="row mt-1 px-1">
        <div class="card">
            <div class="card-body px-1">
                <form class="form-search">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mt-2 overflow-auto">
                            <div class="input-group mb-3">
                                <input type="text" name="date_start" placeholder="Start Date" autocomplete="off"
                                    class="daterangepicker-field form-control text-center">
                                <span class="input-group-text"><i class="fa fa-calendar-days"></i></span>
                                <input type="text" name="date_end" placeholder="End Date" autocomplete="off"
                                    class="daterangepicker-field form-control text-center">
                            </div>
                        </div>

                        <div class="col-md-12 my-2">
                            <a href="{{ route('report.index') }}" class="btn btn-md btn-outline-warning">Refresh</a>
                            <a href="javascript:void(0)" class="btn btn-md btn-outline-primary btn-search">Search</a>
                            {{-- <a href="javascript:void(0)" class="btn btn-md btn-outline-danger btn-pdf"
                            style="display: none">PDF</a> --}}
                            <a href="javascript:void(0)" class="btn btn-md btn-outline-success btn-excell"
                                style="display: none">Excell</a>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-12 row-head-detail" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">Date From</div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-6 akm-date-from"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">Date To</div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-6 akm-date-to"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">Total Batch</div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-6 akm-tot-batch"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">Total Duration</div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-5 akm-tot-dur"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Total Pcs</div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-5 akm-tot-pcs"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Total Box</div>
                                    <div class="col-md-1">:</div>
                                    <div class="col-md-5 akm-tot-box"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div id="pc2-good" style="height: 450px; width: 100%;"></div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div id="pc3-pcs" style="height: 450px; width: 100%;"></div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div id="pc4-box" style="height: 450px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-report">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/plugins/bootstrap-datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datatable/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('assets/export-excell/html2canvas.min.js') }}"></script>
    <script src="{{ asset('assets/export-excell/exceljs.min.js') }}"></script>

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".btn-search").click(function(e) {
                e.preventDefault();
                let date_start = $(".form-search input[name=date_start]").val();
                let date_end = $(".form-search input[name=date_end]").val();

                if (date_start.trim() === '') {
                    alert('Date start cannot be empty!');
                    return
                }

                if (date_end.trim() === '') {
                    alert('Date end cannot be empty!');
                    return
                }

                searchReport(date_start, date_end);
            });

        });

        function loadDataTable() {
            $('.my-table').DataTable({
                processing: true,
                serverSide: false,
                pagingType: 'full_numbers',
                scrollY: "50vh",
                scrollCollapse: true,
                scrollX: true,
                oLanguage: {
                    oPaginate: {
                        sNext: '<span class="fas fa-angle-right pgn-1" style="color: #5e72e4"></span>',
                        sPrevious: '<span class="fas fa-angle-left pgn-2" style="color: #5e72e4"></span>',
                        sFirst: '<span class="fas fa-angle-double-left pgn-3" style="color: #5e72e4"></span>',
                        sLast: '<span class="fas fa-angle-double-right pgn-4" style="color: #5e72e4"></span>',
                    }
                },
                columnDefs: [{
                    defaultContent: "-",
                    targets: "_all"
                }],
                fixedColumns: {
                    leftColumns: 6 // Membekukan kolom pertama hingga ketiga
                },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    $(api.table().footer()).find('th').css({
                        position: 'sticky',
                        bottom: 0,
                        zIndex: 1030, // agar tetap di atas konten
                        backgroundColor: 'white'
                    });
                }
            });
        }

        function searchReport(start, end) {
            $.ajax({
                type: "post",
                url: '{{ route('ajax_get_report') }}',
                data: {
                    date_start: start,
                    date_end: end
                },
                dataType: "json",
                beforeSend: function() {
                    $('.ajax-loader').css("visibility", "visible");
                },
                success: function(res) {
                    const tableReport = $('.table-report');
                    if (res.data.length > 0) {
                        // Helper function to convert duration to seconds
                        const durationToSeconds = (duration) => {
                            const [hours, minutes, seconds] = duration.split(":").map(Number);
                            return hours * 3600 + minutes * 60 + seconds;
                        };

                        // Menghitung total durasi, unik codeProductpcs, dan unik codeProductBox
                        let totalSeconds = 0,
                            pcs1NumTotal = 0,
                            pcs1WeightTotal = 0,
                            pcs1AvgTotal = 0,
                            pcs1NumOverWeight = 0,
                            pcs1WeightOverWeight = 0,
                            pcs1AvgOverWeight = 0,
                            pcs1PercentOverWeight = 0,
                            pcs1NumGood = 0,
                            pcs1WeightGood = 0,
                            pcs1AvgGood = 0,
                            pcs1PercentGood = 0,
                            pcs1NumUnderWeight = 0,
                            pcs1WeightUnderWeight = 0,
                            pcs1AvgUnderWeight = 0,
                            pcs1PercentUnderWeight = 0,
                            pcs1NumberError = 0,
                            pcs1PercentError = 0,
                            pcs2NumTotal = 0,
                            pcs2WeightTotal = 0,
                            pcs2AvgTotal = 0,
                            pcs2NumOverWeight = 0,
                            pcs2WeightOverWeight = 0,
                            pcs2AvgOverWeight = 0,
                            pcs2PercentOverWeight = 0,
                            pcs2NumGood = 0,
                            pcs2WeightGood = 0,
                            pcs2AvgGood = 0,
                            pcs2PercentGood = 0,
                            pcs2NumUnderWeight = 0,
                            pcs2WeightUnderWeight = 0,
                            pcs2AvgUnderWeight = 0,
                            pcs2PercentUnderWeight = 0,
                            pcs2NumberError = 0,
                            pcs2PercentError = 0,
                            boxNumTotal = 0,
                            boxWeightTotal = 0,
                            boxAvgTotal = 0,
                            boxNumOverWeight = 0,
                            boxWeightOverWeight = 0,
                            boxAvgOverWeight = 0,
                            boxPercentOverWeight = 0,
                            boxNumGood = 0,
                            boxWeightGood = 0,
                            boxAvgGood = 0,
                            boxPercentGood = 0,
                            boxNumUnderWeight = 0,
                            boxWeightUnderWeight = 0,
                            boxAvgUnderWeight = 0,
                            boxPercentUnderWeight = 0,
                            boxNumberError = 0,
                            boxPercentError = 0;
                        const uniquePcs = new Set();
                        const uniqueBox = new Set();
                        const pie3 = [],
                            pie4 = [],
                            pie2 = [];

                        const resultTable = $(
                            `<table class="table my-table my-tablelog my-table-striped w-100"></table>`
                        );

                        resultTable.append(`
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Code Production</th>
                                    <th rowspan="2">Operator</th>
                                    <th rowspan="2">Product</th>
                                    <th rowspan="2">Duration</th>
                                    <th rowspan="2">Line Number</th>
                                    <th colspan="17" style="background-color: orange;">Scale 1</th>
                                    <th colspan="17" style="background-color: red;">Scale 2</th>
                                    <th colspan="17" style="background-color: green;">Scale Box</th>
                                </tr>
                                <tr>
                                    <th style="background-color: orange;">Number Total</th>
                                    <th style="background-color: orange;">Weight Total</th>
                                    <th style="background-color: orange;">AVG Total</th>
                                    <th style="background-color: orange;">Number Over Weight</th>
                                    <th style="background-color: orange;">Weight Over Weight</th>
                                    <th style="background-color: orange;">AVG Over Weight</th>
                                    <th style="background-color: orange;">Percent Over Weight</th>
                                    <th style="background-color: orange;">Number Good</th>
                                    <th style="background-color: orange;">Weight Good</th>
                                    <th style="background-color: orange;">AVG Good</th>
                                    <th style="background-color: orange;">Percent Good</th>
                                    <th style="background-color: orange;">Number Under Weight</th>
                                    <th style="background-color: orange;">Weight Under Weight</th>
                                    <th style="background-color: orange;">AVG Under Weight</th>
                                    <th style="background-color: orange;">Percent Under Weight</th>
                                    <th style="background-color: orange;">Number Error</th>
                                    <th style="background-color: orange;">Percent Error</th>
                                    <th style="background-color: red;">Number Total</th>
                                    <th style="background-color: red;">Weight Total</th>
                                    <th style="background-color: red;">AVG Total</th>
                                    <th style="background-color: red;">Number Over Weight</th>
                                    <th style="background-color: red;">Weight Over Weight</th>
                                    <th style="background-color: red;">AVG Over Weight</th>
                                    <th style="background-color: red;">Percent Over Weight</th>
                                    <th style="background-color: red;">Number Good</th>
                                    <th style="background-color: red;">Weight Good</th>
                                    <th style="background-color: red;">AVG Good</th>
                                    <th style="background-color: red;">Percent Good</th>
                                    <th style="background-color: red;">Number Under Weight</th>
                                    <th style="background-color: red;">Weight Under Weight</th>
                                    <th style="background-color: red;">AVG Under Weight</th>
                                    <th style="background-color: red;">Percent Under Weight</th>
                                    <th style="background-color: red;">Number Error</th>
                                    <th style="background-color: red;">Percent Error</th>
                                    <th style="background-color: green;">Number Total</th>
                                    <th style="background-color: green;">Weight Total</th>
                                    <th style="background-color: green;">AVG Total</th>
                                    <th style="background-color: green;">Number Over Weight</th>
                                    <th style="background-color: green;">Weight Over Weight</th>
                                    <th style="background-color: green;">AVG Over Weight</th>
                                    <th style="background-color: green;">Percent Over Weight</th>
                                    <th style="background-color: green;">Number Good</th>
                                    <th style="background-color: green;">Weight Good</th>
                                    <th style="background-color: green;">AVG Good</th>
                                    <th style="background-color: green;">Percent Good</th>
                                    <th style="background-color: green;">Number Under Weight</th>
                                    <th style="background-color: green;">Weight Under Weight</th>
                                    <th style="background-color: green;">AVG Under Weight</th>
                                    <th style="background-color: green;">Percent Under Weight</th>
                                    <th style="background-color: green;">Number Error</th>
                                    <th style="background-color: green;">Percent Error</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        `);

                        const tableBody = resultTable.find('tbody');

                        $.each(res.data, function(key, val) {
                            const options = {
                                timeZone: 'Asia/Jakarta',
                                hour12: false,
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            };
                            const createdAt = new Date(val['starproduction'])
                                .toLocaleString('en-US', options);

                            const pcs1Good = parseFloat(val['pcs1_percent_good']);
                            const pcs2Good = parseFloat(val['pcs2_percent_good']);
                            const boxGood = parseFloat(val['box_percecnt_good']);
                            const totalGood = pcs1Good + pcs2Good;

                            // SUMMARY
                            pcs1NumTotal += parseFloat(val['pcs1_number_total']);
                            pcs1WeightTotal += parseFloat(val['pcs1_weight_total']);
                            pcs1AvgTotal += parseFloat(val['pcs1_average_total']);
                            pcs1NumOverWeight += parseFloat(val['pcs1_number_overweight']);
                            pcs1WeightOverWeight += parseFloat(val['pcs1_weight_overweight']);
                            pcs1AvgOverWeight += parseFloat(val['pcs1_average_overweight']);
                            pcs1PercentOverWeight += parseFloat(val['pcs1_percent_overweight']);
                            pcs1NumGood += parseFloat(val['pcs1_number_good']);
                            pcs1WeightGood += parseFloat(val['pcs1_weright_good']);
                            pcs1AvgGood += parseFloat(val['pcs1_average_good']);
                            pcs1PercentGood += parseFloat(val['pcs1_percent_good']);
                            pcs1NumUnderWeight += parseFloat(val['pcs1_number_underweight']);
                            pcs1WeightUnderWeight += parseFloat(val['pcs1_weight_underweight']);
                            pcs1AvgUnderWeight += parseFloat(val['pcs1_average_underweight']);
                            pcs1PercentUnderWeight += parseFloat(val['pcs1_percent_underweight']);
                            pcs1NumberError += parseFloat(val['pcs1_number_error']);
                            pcs1PercentError += parseFloat(val['pcs1_percent_error']);

                            pcs2NumTotal += parseFloat(val['pcs2_number_total']);
                            pcs2WeightTotal += parseFloat(val['pcs2_weight_total']);
                            pcs2AvgTotal += parseFloat(val['pcs2_average_total']);
                            pcs2NumOverWeight += parseFloat(val['pcs2_number_overweight']);
                            pcs2WeightOverWeight += parseFloat(val['pcs2_weight_overweight']);
                            pcs2AvgOverWeight += parseFloat(val['pcs2_average_overweight']);
                            pcs2PercentOverWeight += parseFloat(val['pcs2_percent_overweight']);
                            pcs2NumGood += parseFloat(val['pcs2_number_good']);
                            pcs2WeightGood += parseFloat(val['pcs2_weright_good']);
                            pcs2AvgGood += parseFloat(val['pcs2_average_good']);
                            pcs2PercentGood += parseFloat(val['pcs2_percent_good']);
                            pcs2NumUnderWeight += parseFloat(val['pcs2_number_underweight']);
                            pcs2WeightUnderWeight += parseFloat(val['pcs2_weight_underweight']);
                            pcs2AvgUnderWeight += parseFloat(val['pcs2_average_underweight']);
                            pcs2PercentUnderWeight += parseFloat(val['pcs2_percent_underweight']);
                            pcs2NumberError += parseFloat(val['pcs2_number_error']);
                            pcs2PercentError += parseFloat(val['pcs2_percent_error']);

                            boxNumTotal += parseFloat(val['box_number_total']);
                            boxWeightTotal += parseFloat(val['box_weight_total']);
                            boxAvgTotal += parseFloat(val['box_average_total']);
                            boxNumOverWeight += parseFloat(val['box_number_overweight']);
                            boxWeightOverWeight += parseFloat(val['box_weight_overweight']);
                            boxAvgOverWeight += parseFloat(val['box_average_overweight']);
                            boxPercentOverWeight += parseFloat(val['box_percent_overweight']);
                            boxNumGood += parseFloat(val['box_number_good']);
                            boxWeightGood += parseFloat(val['box_weight_good']);
                            boxAvgGood += parseFloat(val['box_average_good']);
                            boxPercentGood += parseFloat(val['box_percecnt_good']);
                            boxNumUnderWeight += parseFloat(val['box_number_underweight']);
                            boxWeightUnderWeight += parseFloat(val['box_weight_underweight']);
                            boxAvgUnderWeight += parseFloat(val['box_average_underweight']);
                            boxPercentUnderWeight += parseFloat(val['box_percent_underweight']);
                            boxNumberError += parseFloat(val['box_number_error']);
                            boxPercentError += parseFloat(val['box_percent_error']);
                            // END SUMMARY

                            totalSeconds += durationToSeconds(val['duration']);
                            uniquePcs.add(val['codeProductpcs']);
                            uniqueBox.add(val['codeProductBox']);

                            tableBody.append(`
                                    <tr>
                                        <td>${key + 1}</td>
                                        <td>${val['CodeProduction']}</td>
                                        <td>${val['operator']}</td>
                                        <td>${val['codeProductBox']}</td>                                      
                                        <td>${val['duration']}</td>                                      
                                        <td>${val['line_number']}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_number_total']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_weight_total']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_average_total']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_number_overweight']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_weight_overweight']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_average_overweight']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_percent_overweight']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_number_good']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_weright_good']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_average_good']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_percent_good']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_number_underweight']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_weight_underweight']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_average_underweight']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_percent_underweight']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_number_error']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs1_percent_error']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_number_total']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_weight_total']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_average_total']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_number_overweight']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_weight_overweight']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_average_overweight']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_percent_overweight']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_number_good']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_weright_good']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_average_good']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_percent_good']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_number_underweight']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_weight_underweight']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_average_underweight']))} G</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_percent_underweight']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_number_error']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['pcs2_percent_error']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_number_total']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_weight_total']))} KG</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_average_total']))} KG</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_number_overweight']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_weight_overweight']))} KG</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_average_overweight']))} KG</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_percent_overweight']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_number_good']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_weight_good']))} KG</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_average_good']))} KG</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_percecnt_good']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_number_underweight']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_weight_underweight']))} KG</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_average_underweight']))} KG</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_percent_underweight']))} %</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_number_error']))}</td>                                      
                                        <td style="text-align: right;">${formatRibuan(parseFloat(val['box_percent_error']))} %</td>                                                                    
                                    </tr>
                            `);
                        });

                        resultTable.append(`
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1NumTotal))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1WeightTotal))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1AvgTotal))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1NumOverWeight))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1WeightOverWeight))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1AvgOverWeight))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1PercentOverWeight))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1NumGood))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1WeightGood))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1AvgGood))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1PercentGood))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1NumUnderWeight))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1WeightUnderWeight))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1AvgUnderWeight))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1PercentUnderWeight))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1NumberError))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs1PercentError))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2NumTotal))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2WeightTotal))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2AvgTotal))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2NumOverWeight))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2WeightOverWeight))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2AvgOverWeight))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2PercentOverWeight))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2NumGood))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2WeightGood))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2AvgGood))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2PercentGood))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2NumUnderWeight))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2WeightUnderWeight))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2AvgUnderWeight))} G</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2PercentUnderWeight))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2NumberError))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(pcs2PercentError))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxNumTotal))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxWeightTotal))} KG</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxAvgTotal))} KG</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxNumOverWeight))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxWeightOverWeight))} KG</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxAvgOverWeight))} KG</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxPercentOverWeight))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxNumGood))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxWeightGood))} KG</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxAvgGood))} KG</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxPercentGood))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxNumUnderWeight))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxWeightUnderWeight))} KG</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxAvgUnderWeight))} KG</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxPercentUnderWeight))} %</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxNumberError))}</th>
                                    <th style="text-align: right;">${formatRibuan(parseFloat(boxPercentError))} %</th>
                                </tr>
                            </tfoot>
                        `);

                        pie2.push({
                            value: pcs1NumGood + pcs2NumGood + boxNumGood,
                            name: 'Total Good'
                        }, {
                            value: pcs1NumTotal + pcs2NumTotal + boxNumTotal - (pcs1NumGood +
                                pcs2NumGood + boxNumGood),
                            name: 'Total Not Good'
                        });

                        pie3.push({
                            value: pcs1NumGood + pcs2NumGood,
                            name: 'Total Good Pcs'
                        }, {
                            value: pcs1NumTotal + pcs2NumTotal - (pcs1NumGood + pcs2NumGood),
                            name: 'Total Not Good Pcs'
                        });

                        pie4.push({
                            value: boxNumGood,
                            name: 'Total Good Box'
                        }, {
                            value: boxNumTotal - boxNumGood,
                            name: 'Total Not Good Box'
                        });

                        pieCharts('pc2-good', 'Total All Good & Not Good', pie2);
                        pieCharts('pc3-pcs', 'Total Good & Not Good Pcs', pie3);
                        pieCharts('pc4-box', 'Total Good & Not Good Box', pie4);


                        // Konversi total detik kembali ke format HH:MM:SS
                        const secondsToDuration = (seconds) => {
                            const hours = Math.floor(seconds / 3600).toString().padStart(2, "0");
                            const minutes = Math.floor((seconds % 3600) / 60).toString().padStart(2, "0");
                            const secs = (seconds % 60).toString().padStart(2, "0");
                            return `${hours}:${minutes}:${secs}`;
                        };

                        tableReport.empty().append(resultTable);
                        $('.btn-excell').show();
                        $('.btn-pdf').show();
                        $('.row-head-detail').show();
                        // SET AKUMULASI
                        $('.akm-date-from').html(start);
                        $('.akm-date-to').html(end);
                        $('.akm-tot-batch').html(res.data.length);
                        $('.akm-tot-dur').html(secondsToDuration(totalSeconds));
                        $('.akm-tot-pcs').html(pcs1NumTotal + pcs2NumTotal);
                        $('.akm-tot-box').html(boxNumTotal);
                        // END SET AKUMULASI

                        // Event listener untuk ekspor Excel
                        $('.btn-excell').on('click', function() {
                            exportReportToExcel(res);
                        });

                        loadDataTable();
                    } else {
                        tableReport.empty();
                        $('.btn-excell').hide();
                        $('.btn-pdf').hide();
                        $('.row-head-detail').hide();
                        // SET AKUMULASI
                        $('.akm-date-from').html("");
                        $('.akm-date-to').html("");
                        $('.akm-tot-batch').html("");
                        $('.akm-tot-dur').html("");
                        $('.akm-tot-pcs').html("");
                        $('.akm-tot-box').html("");
                        // END SET AKUMULASI
                    }
                },
                complete: function() {
                    $('.ajax-loader').css("visibility", "hidden");
                }
            });
        }

        function formatRibuan(angka) {
            return angka
                .toFixed(2) // Membulatkan angka ke 2 desimal
                .replace('.', ',') // Mengubah titik menjadi koma untuk desimal
                .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Menambahkan titik sebagai pemisah ribuan
        }

        async function exportReportToExcel(res) {
            // Buat workbook dan worksheet
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Report');

            // Ambil data dari HTML
            const dateFrom = document.querySelector('.akm-date-from').textContent.trim();
            const dateTo = document.querySelector('.akm-date-to').textContent.trim();
            const totalBatch = document.querySelector('.akm-tot-batch').textContent.trim();
            const totalDuration = document.querySelector('.akm-tot-dur').textContent.trim();
            const totalPcs = document.querySelector('.akm-tot-pcs').textContent.trim();
            const totalBox = document.querySelector('.akm-tot-box').textContent.trim();

            // Menangkap chart menggunakan html2canvas
            const chart1 = document.getElementById('pc3-pcs'); // chart untuk "Jenis Barang"
            const chart2 = document.getElementById('pc4-box'); // chart untuk "Jenis Kendaraan"

            const chart1Canvas = await html2canvas(chart1);
            const chart2Canvas = await html2canvas(chart2);

            const chart1Img = chart1Canvas.toDataURL('image/png');
            const chart2Img = chart2Canvas.toDataURL('image/png');

            // Tambahkan data teks ke worksheet sebelum gambar
            worksheet.getCell('A1').value = 'Date From';
            worksheet.getCell('B1').value = ':';
            worksheet.getCell('C1').value = dateFrom;
            worksheet.getCell('A2').value = 'Date To';
            worksheet.getCell('B2').value = ':';
            worksheet.getCell('C2').value = dateTo;
            worksheet.getCell('A3').value = 'Total Batch';
            worksheet.getCell('B3').value = ':';
            worksheet.getCell('C3').value = totalBatch;
            worksheet.getCell('D1').value = 'Total Duration';
            worksheet.getCell('E1').value = ':';
            worksheet.getCell('F1').value = totalDuration;
            worksheet.getCell('D2').value = 'Total Pcs';
            worksheet.getCell('E2').value = ':';
            worksheet.getCell('F2').value = totalPcs;
            worksheet.getCell('D3').value = 'Total Box';
            worksheet.getCell('E3').value = ':';
            worksheet.getCell('F3').value = totalBox;

            // Atur format teks data
            worksheet.getCell('A1').font = {
                bold: true
            };
            worksheet.getCell('A2').font = {
                bold: true
            };
            worksheet.getCell('A3').font = {
                bold: true
            };
            worksheet.getCell('D1').font = {
                bold: true
            };
            worksheet.getCell('D2').font = {
                bold: true
            };
            worksheet.getCell('D3').font = {
                bold: true
            };


            // Menambahkan gambar chart ke worksheet setelah teks
            const chart1ImageId = workbook.addImage({
                base64: chart1Img,
                extension: 'png',
            });

            const chart2ImageId = workbook.addImage({
                base64: chart2Img,
                extension: 'png',
            });

            worksheet.addImage(chart1ImageId, 'A7:D18'); // Gambar pertama (chart jenis barang)
            worksheet.addImage(chart2ImageId, 'E7:H18'); // Gambar kedua (chart jenis kendaraan)

            // Tambahkan tabel kolom di bawah gambar
            worksheet.mergeCells('A19:W19');
            worksheet.getCell('A19').value = 'Report';
            worksheet.getCell('A19').font = {
                size: 14,
                bold: true
            };
            worksheet.getCell('A19').alignment = {
                vertical: 'middle',
                horizontal: 'center'
            };

            // Definisikan kolom tabel dimulai dari baris setelah gambar
            worksheet.columns = [{
                    key: 'no',
                    width: 5
                },
                {
                    key: 'CodeProduction',
                    width: 20
                },
                {
                    key: 'operator',
                    width: 20
                },
                {
                    key: 'codeProductpcs',
                    width: 20
                },
                {
                    key: 'duration',
                    width: 20
                },
                {
                    key: 'line_number',
                    width: 20
                },
                {
                    key: 'pcs1_number_total',
                    width: 25
                },
                {
                    key: 'pcs1_weight_total',
                    width: 25
                },
                {
                    key: 'pcs1_average_total',
                    width: 25
                },
                {
                    key: 'pcs1_number_overweight',
                    width: 25
                },
                {
                    key: 'pcs1_weight_overweight',
                    width: 25
                },
                {
                    key: 'pcs1_average_overweight',
                    width: 25
                },
                {
                    key: 'pcs1_percent_overweight',
                    width: 25
                },
                {
                    key: 'pcs1_number_good',
                    width: 25
                },
                {
                    key: 'pcs1_weright_good',
                    width: 25
                },
                {
                    key: 'pcs1_average_good',
                    width: 25
                },
                {
                    key: 'pcs1_percent_good',
                    width: 25
                },
                {
                    key: 'pcs1_number_underweight',
                    width: 25
                },
                {
                    key: 'pcs1_weight_underweight',
                    width: 25
                },
                {
                    key: 'pcs1_average_underweight',
                    width: 25
                },
                {
                    key: 'pcs1_percent_underweight',
                    width: 25
                },
                {
                    key: 'pcs1_number_error',
                    width: 25
                },
                {
                    key: 'pcs1_percent_error',
                    width: 25
                },
                {
                    key: 'pcs2_number_total',
                    width: 25
                },
                {
                    key: 'pcs2_weight_total',
                    width: 25
                },
                {
                    key: 'pcs2_average_total',
                    width: 25
                },
                {
                    key: 'pcs2_number_overweight',
                    width: 25
                },
                {
                    key: 'pcs2_weight_overweight',
                    width: 25
                },
                {
                    key: 'pcs2_average_overweight',
                    width: 25
                },
                {
                    key: 'pcs2_percent_overweight',
                    width: 25
                },
                {
                    key: 'pcs2_number_good',
                    width: 25
                },
                {
                    key: 'pcs2_weright_good',
                    width: 25
                },
                {
                    key: 'pcs2_average_good',
                    width: 25
                },
                {
                    key: 'pcs2_percent_good',
                    width: 25
                },
                {
                    key: 'pcs2_number_underweight',
                    width: 25
                },
                {
                    key: 'pcs2_weight_underweight',
                    width: 25
                },
                {
                    key: 'pcs2_average_underweight',
                    width: 25
                },
                {
                    key: 'pcs2_percent_underweight',
                    width: 25
                },
                {
                    key: 'pcs2_number_error',
                    width: 25
                },
                {
                    key: 'pcs2_percent_error',
                    width: 25
                },
                {
                    key: 'box_number_total',
                    width: 25
                },
                {
                    key: 'box_weight_total',
                    width: 25
                },
                {
                    key: 'box_average_total',
                    width: 25
                },
                {
                    key: 'box_number_overweight',
                    width: 25
                },
                {
                    key: 'box_weight_overweight',
                    width: 25
                },
                {
                    key: 'box_average_overweight',
                    width: 25
                },
                {
                    key: 'box_percent_overweight',
                    width: 25
                },
                {
                    key: 'box_number_good',
                    width: 25
                },
                {
                    key: 'box_weight_good',
                    width: 25
                },
                {
                    key: 'box_average_good',
                    width: 25
                },
                {
                    key: 'box_percecnt_good',
                    width: 25
                },
                {
                    key: 'box_number_underweight',
                    width: 25
                },
                {
                    key: 'box_weight_underweight',
                    width: 25
                },
                {
                    key: 'box_average_underweight',
                    width: 25
                },
                {
                    key: 'box_percent_underweight',
                    width: 25
                },
                {
                    key: 'box_number_error',
                    width: 25
                },
                {
                    key: 'box_percent_error',
                    width: 25
                },
            ];

            // Data diambil dari res[] (sesuaikan dengan data Anda)
            const data = res.data; // Sesuaikan ini dengan data yang dihasilkan dari jQuery
            const tableData = data.map((row, index) => ({
                no: index + 1,
                CodeProduction: row.CodeProduction,
                operator: row.operator,
                codeProductpcs: row.codeProductpcs,
                duration: row.duration,
                line_number: row.line_number,
                pcs1_number_total: row.pcs1_number_total,
                pcs1_weight_total: row.pcs1_weight_total,
                pcs1_average_total: row.pcs1_average_total,
                pcs1_number_overweight: row.pcs1_number_overweight,
                pcs1_weight_overweight: row.pcs1_weight_overweight,
                pcs1_average_overweight: row.pcs1_average_overweight,
                pcs1_percent_overweight: row.pcs1_percent_overweight,
                pcs1_number_good: row.pcs1_number_good,
                pcs1_weright_good: row.pcs1_weright_good,
                pcs1_average_good: row.pcs1_average_good,
                pcs1_percent_good: row.pcs1_percent_good,
                pcs1_number_underweight: row.pcs1_number_underweight,
                pcs1_weight_underweight: row.pcs1_weight_underweight,
                pcs1_average_underweight: row.pcs1_average_underweight,
                pcs1_percent_underweight: row.pcs1_percent_underweight,
                pcs1_number_error: row.pcs1_number_error,
                pcs1_percent_error: row.pcs1_percent_error,
                pcs2_number_total: row.pcs2_number_total,
                pcs2_weight_total: row.pcs2_weight_total,
                pcs2_average_total: row.pcs2_average_total,
                pcs2_number_overweight: row.pcs2_number_overweight,
                pcs2_weight_overweight: row.pcs2_weight_overweight,
                pcs2_average_overweight: row.pcs2_average_overweight,
                pcs2_percent_overweight: row.pcs2_percent_overweight,
                pcs2_number_good: row.pcs2_number_good,
                pcs2_weright_good: row.pcs2_weright_good,
                pcs2_average_good: row.pcs2_average_good,
                pcs2_percent_good: row.pcs2_percent_good,
                pcs2_number_underweight: row.pcs2_number_underweight,
                pcs2_weight_underweight: row.pcs2_weight_underweight,
                pcs2_average_underweight: row.pcs2_average_underweight,
                pcs2_percent_underweight: row.pcs2_percent_underweight,
                pcs2_number_error: row.pcs2_number_error,
                pcs2_percent_error: row.pcs2_percent_error,
                box_number_total: row.box_number_total,
                box_weight_total: row.box_weight_total,
                box_average_total: row.box_average_total,
                box_number_overweight: row.box_number_overweight,
                box_weight_overweight: row.box_weight_overweight,
                box_average_overweight: row.box_average_overweight,
                box_percent_overweight: row.box_percent_overweight,
                box_number_good: row.box_number_good,
                box_weight_good: row.box_weight_good,
                box_average_good: row.box_average_good,
                box_percecnt_good: row.box_percecnt_good,
                box_number_underweight: row.box_number_underweight,
                box_weight_underweight: row.box_weight_underweight,
                box_average_underweight: row.box_average_underweight,
                box_percent_underweight: row.box_percent_underweight,
                box_number_error: row.box_number_error,
                box_percent_error: row.box_percent_error,
            }));

            // Definisikan header untuk tabel
            const tableHeader = ['No', 'Code Production', 'Operator', 'Product', 'Duration', 'Line Number',
                'Scale 1 - Number Total', 'Scale 1 - Weight Total', 'Scale 1 - AVG Total',
                'Scale 1 - Number Over Weight', 'Scale 1 - Weight Over Weight', 'Scale 1 - AVG Over Weight',
                'Scale 1 - Percent Over Weight', 'Scale 1 - Number Good', 'Scale 1 - Weight Good',
                'Scale 1 - AVG Good',
                'Scale 1 - Percent Good',
                'Scale 1 - Number Under Weight', 'Scale 1 - Weight Under Weight', 'Scale 1 - AVG Under Weight',
                'Scale 1 - Percent Under Weight', 'Scale 1 - Number Error',
                'Scale 1 - Percent Error', 'Scale 2 - Number Total', 'Scale 2 - Weight Total',
                'Scale 2 - AVG Total',
                'Scale 2 - Number Over Weight', 'Scale 2 - Weight Over Weight', 'Scale 2 - AVG Over Weight',
                'Scale 2 - Percent Over Weight', 'Scale 2 - Number Good', 'Scale 2 - Weight Good',
                'Scale 2 - AVG Good',
                'Scale 2 - Percent Good',
                'Scale 2 - Number Under Weight', 'Scale 2 - Weight Under Weight', 'Scale 2 - AVG Under Weight',
                'Scale 2 - Percent Under Weight', 'Scale 2 - Number Error',
                'Scale 2 - Percent Error', 'Scale Box - Number Total', 'Scale Box - Weight Total',
                'Scale Box - AVG Total',
                'Scale Box - Number Over Weight', 'Scale Box - Weight Over Weight', 'Scale Box - AVG Over Weight',
                'Scale Box - Percent Over Weight', 'Scale Box - Number Good', 'Scale Box - Weight Good',
                'Scale Box - AVG Good',
                'Scale Box - Percent Good',
                'Scale Box - Number Under Weight', 'Scale Box - Weight Under Weight',
                'Scale Box - AVG Under Weight',
                'Scale Box - Percent Under Weight', 'Scale Box - Number Error',
                'Scale Box - Percent Error'
            ];

            // Tambahkan header ke baris 20
            worksheet.addRow(tableHeader);

            worksheet.getRow(20).eachCell((cell) => {
                cell.font = {
                    bold: true
                }; // Set font bold untuk header
                cell.alignment = {
                    vertical: 'middle',
                    horizontal: 'center'
                };
            });

            // Tambahkan data ke worksheet dimulai dari baris 21
            tableData.forEach((row) => {
                worksheet.addRow(row);
            });

            // Hitung total untuk setiap kolom dan tambahkan di baris footer
            const footer = {
                no: 'Total',
                pcs1_number_total: tableData.reduce((sum, row) => sum + parseFloat(row.pcs1_number_total || 0), 0),
                pcs1_weight_total: tableData.reduce((sum, row) => sum + parseFloat(row.pcs1_weight_total || 0), 0),
                pcs1_average_total: tableData.reduce((sum, row) => sum + parseFloat(row.pcs1_average_total || 0),
                    0),
                pcs1_number_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs1_number_overweight || 0), 0),
                pcs1_weight_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs1_weight_overweight || 0), 0),
                pcs1_average_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs1_average_overweight || 0), 0),
                pcs1_percent_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs1_percent_overweight || 0), 0),
                pcs1_number_good: tableData.reduce((sum, row) => sum + parseFloat(row.pcs1_number_good || 0), 0),
                pcs1_weright_good: tableData.reduce((sum, row) => sum + parseFloat(row.pcs1_weright_good || 0), 0),
                pcs1_average_good: tableData.reduce((sum, row) => sum + parseFloat(row.pcs1_average_good || 0), 0),
                pcs1_percent_good: tableData.reduce((sum, row) => sum + parseFloat(row.pcs1_percent_good || 0), 0),
                pcs1_number_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs1_number_underweight || 0), 0),
                pcs1_weight_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs1_weight_underweight || 0), 0),
                pcs1_average_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs1_average_underweight || 0), 0),
                pcs1_percent_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs1_percent_underweight || 0), 0),
                pcs1_number_error: tableData.reduce((sum, row) => sum + parseFloat(row.pcs1_number_error || 0), 0),
                pcs1_percent_error: tableData.reduce((sum, row) => sum + parseFloat(row.pcs1_percent_error || 0),
                    0),
                pcs2_number_total: tableData.reduce((sum, row) => sum + parseFloat(row.pcs2_number_total || 0), 0),
                pcs2_weight_total: tableData.reduce((sum, row) => sum + parseFloat(row.pcs2_weight_total || 0), 0),
                pcs2_average_total: tableData.reduce((sum, row) => sum + parseFloat(row.pcs2_average_total || 0),
                    0),
                pcs2_number_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs2_number_overweight || 0), 0),
                pcs2_weight_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs2_weight_overweight || 0), 0),
                pcs2_average_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs2_average_overweight || 0), 0),
                pcs2_percent_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs2_percent_overweight || 0), 0),
                pcs2_number_good: tableData.reduce((sum, row) => sum + parseFloat(row.pcs2_number_good || 0), 0),
                pcs2_weright_good: tableData.reduce((sum, row) => sum + parseFloat(row.pcs2_weright_good || 0), 0),
                pcs2_average_good: tableData.reduce((sum, row) => sum + parseFloat(row.pcs2_average_good || 0), 0),
                pcs2_percent_good: tableData.reduce((sum, row) => sum + parseFloat(row.pcs2_percent_good || 0), 0),
                pcs2_number_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs2_number_underweight || 0), 0),
                pcs2_weight_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs2_weight_underweight || 0), 0),
                pcs2_average_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs2_average_underweight || 0), 0),
                pcs2_percent_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .pcs2_percent_underweight || 0), 0),
                pcs2_number_error: tableData.reduce((sum, row) => sum + parseFloat(row.pcs2_number_error || 0), 0),
                pcs2_percent_error: tableData.reduce((sum, row) => sum + parseFloat(row.pcs2_percent_error || 0),
                    0),
                box_number_total: tableData.reduce((sum, row) => sum + parseFloat(row.box_number_total || 0), 0),
                box_weight_total: tableData.reduce((sum, row) => sum + parseFloat(row.box_weight_total || 0), 0),
                box_average_total: tableData.reduce((sum, row) => sum + parseFloat(row.box_average_total || 0),
                    0),
                box_number_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .box_number_overweight || 0), 0),
                box_weight_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .box_weight_overweight || 0), 0),
                box_average_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .box_average_overweight || 0), 0),
                box_percent_overweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .box_percent_overweight || 0), 0),
                box_number_good: tableData.reduce((sum, row) => sum + parseFloat(row.box_number_good || 0), 0),
                box_weight_good: tableData.reduce((sum, row) => sum + parseFloat(row.box_weight_good || 0), 0),
                box_average_good: tableData.reduce((sum, row) => sum + parseFloat(row.box_average_good || 0), 0),
                box_percecnt_good: tableData.reduce((sum, row) => sum + parseFloat(row.box_percecnt_good || 0), 0),
                box_number_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .box_number_underweight || 0), 0),
                box_weight_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .box_weight_underweight || 0), 0),
                box_average_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .box_average_underweight || 0), 0),
                box_percent_underweight: tableData.reduce((sum, row) => sum + parseFloat(row
                    .box_percent_underweight || 0), 0),
                box_number_error: tableData.reduce((sum, row) => sum + parseFloat(row.box_number_error || 0), 0),
                box_percent_error: tableData.reduce((sum, row) => sum + parseFloat(row.box_percent_error || 0),
                    0),
            };

            // Menambahkan footer ke worksheet
            worksheet.addRow(footer);

            // Menggabungkan sel untuk "Total" dari kolom No hingga Line Number
            worksheet.mergeCells('A' + worksheet.lastRow.number + ':F' + worksheet.lastRow.number);

            // Mengatur alignment teks "Total" di kolom "No" untuk rata kiri
            worksheet.getCell('A' + worksheet.lastRow.number).alignment = {
                vertical: 'middle',
                horizontal: 'center',
            };

            // Mengatur alignment angka di sel lainnya untuk rata kanan
            worksheet.getRow(worksheet.lastRow.number).eachCell((cell, colNumber) => {
                if (colNumber > 1) { // Kolom lainnya selain "No"
                    cell.alignment = {
                        vertical: 'middle',
                        horizontal: 'left',
                    };
                }
            });

            // Simpan workbook sebagai file Excel
            workbook.xlsx.writeBuffer().then(function(data) {
                const blob = new Blob([data], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'report.xlsx';
                a.click();
                window.URL.revokeObjectURL(url);
            });
        }


        function pieCharts(chartID, chartText, pieData) {
            let chartPie = document.getElementById(chartID);
            let myChart = echarts.init(chartPie);
            let option;

            option = {
                title: {
                    text: chartText,
                    left: 'center',
                },
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },
                legend: {
                    top: 'bottom'
                },
                series: [{
                    name: chartText,
                    type: 'pie',
                    radius: ['45%', '60%'],
                    labelLine: {
                        length: 15
                    },
                    label: {
                        formatter: '{a|{a}}{abg|}\n{hr|}\n  {b|{b}：}{c}  {per|{d}%}  ',
                        backgroundColor: '#F6F8FC',
                        borderColor: '#8C8D8E',
                        borderWidth: 1,
                        borderRadius: 4,
                        rich: {
                            a: {
                                color: '#6E7079',
                                lineHeight: 22,
                                align: 'center'
                            },
                            hr: {
                                borderColor: '#8C8D8E',
                                width: '100%',
                                borderWidth: 1,
                                height: 0
                            },
                            b: {
                                color: '#4C5058',
                                fontSize: 14,
                                fontWeight: 'bold',
                                lineHeight: 33
                            },
                            per: {
                                color: '#fff',
                                backgroundColor: '#4C5058',
                                padding: [3, 4],
                                borderRadius: 4
                            }
                        }
                    },
                    data: pieData
                }]
            };

            option && myChart.setOption(option);
            $(window).on('resize', function() {
                if (myChart != null && myChart != undefined) {
                    myChart.resize();
                }
            });
        }


        function numberFormat(number) {
            return Number(number).toLocaleString('en-US');
        }
    </script>
@endsection
