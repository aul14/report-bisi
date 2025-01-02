@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Report'])
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
                            <a href="javascript:void(0)" class="btn btn-md btn-outline-danger btn-pdf"
                                style="display: none">PDF</a>
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
                            <div class="col-md-6 col-sm-12">
                                <div id="pc3-pcs" style="height: 450px; width: 100%;"></div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div id="pc4-box" style="height: 450px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-report" style="overflow: auto;">
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
    <script src="{{ asset('assets/plugins/bootstrap-datatable/js/dataTables.buttons.min.js') }}"></script>
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
                            pie4 = [];

                        const resultTable = $(
                            `<table class="table my-table my-tablelog my-table-striped w-100"></table>`
                        );

                        resultTable.append(`
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Code Production</th>
                                    <th rowspan="2">Code Pcs</th>
                                    <th rowspan="2">Code Box</th>
                                    <th rowspan="2">Duration</th>
                                    <th rowspan="2">Line Number</th>
                                    <th colspan="17" style="background-color: orange;">PCS 1</th>
                                    <th colspan="17" style="background-color: red;">PCS 2</th>
                                    <th colspan="17" style="background-color: green;">BOX</th>
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
                                        <td>${val['codeProductpcs']}</td>                                      
                                        <td>${val['codeProductBox']}</td>                                      
                                        <td>${val['duration']}</td>                                      
                                        <td>${val['line_number']}</td>                                      
                                        <td>${val['pcs1_number_total']}</td>                                      
                                        <td>${val['pcs1_weight_total']}</td>                                      
                                        <td>${val['pcs1_average_total']}</td>                                      
                                        <td>${val['pcs1_number_overweight']}</td>                                      
                                        <td>${val['pcs1_weight_overweight']}</td>                                      
                                        <td>${val['pcs1_average_overweight']}</td>                                      
                                        <td>${val['pcs1_percent_overweight']}</td>                                      
                                        <td>${val['pcs1_number_good']}</td>                                      
                                        <td>${val['pcs1_weright_good']}</td>                                      
                                        <td>${val['pcs1_average_good']}</td>                                      
                                        <td>${val['pcs1_percent_good']}</td>                                      
                                        <td>${val['pcs1_number_underweight']}</td>                                      
                                        <td>${val['pcs1_weight_underweight']}</td>                                      
                                        <td>${val['pcs1_average_underweight']}</td>                                      
                                        <td>${val['pcs1_percent_underweight']}</td>                                      
                                        <td>${val['pcs1_number_error']}</td>                                      
                                        <td>${val['pcs1_percent_error']}</td>                                      
                                        <td>${val['pcs2_number_total']}</td>                                      
                                        <td>${val['pcs2_weight_total']}</td>                                      
                                        <td>${val['pcs2_average_total']}</td>                                      
                                        <td>${val['pcs2_number_overweight']}</td>                                      
                                        <td>${val['pcs2_weight_overweight']}</td>                                      
                                        <td>${val['pcs2_average_overweight']}</td>                                      
                                        <td>${val['pcs2_percent_overweight']}</td>                                      
                                        <td>${val['pcs2_number_good']}</td>                                      
                                        <td>${val['pcs2_weright_good']}</td>                                      
                                        <td>${val['pcs2_average_good']}</td>                                      
                                        <td>${val['pcs2_percent_good']}</td>                                      
                                        <td>${val['pcs2_number_underweight']}</td>                                      
                                        <td>${val['pcs2_weight_underweight']}</td>                                      
                                        <td>${val['pcs2_average_underweight']}</td>                                      
                                        <td>${val['pcs2_percent_underweight']}</td>                                      
                                        <td>${val['pcs2_number_error']}</td>                                      
                                        <td>${val['pcs2_percent_error']}</td>                                      
                                        <td>${val['box_number_total']}</td>                                      
                                        <td>${val['box_weight_total']}</td>                                      
                                        <td>${val['box_average_total']}</td>                                      
                                        <td>${val['box_number_overweight']}</td>                                      
                                        <td>${val['box_weight_overweight']}</td>                                      
                                        <td>${val['box_average_overweight']}</td>                                      
                                        <td>${val['box_percent_overweight']}</td>                                      
                                        <td>${val['box_number_good']}</td>                                      
                                        <td>${val['box_weight_good']}</td>                                      
                                        <td>${val['box_average_good']}</td>                                      
                                        <td>${val['box_percecnt_good']}</td>                                      
                                        <td>${val['box_number_underweight']}</td>                                      
                                        <td>${val['box_weight_underweight']}</td>                                      
                                        <td>${val['box_average_underweight']}</td>                                      
                                        <td>${val['box_percent_underweight']}</td>                                      
                                        <td>${val['box_number_error']}</td>                                      
                                        <td>${val['box_percent_error']}</td>                                                                    
                                    </tr>
                            `);
                        });

                        resultTable.append(`
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-center">Total</th>
                                    <th>${pcs1NumTotal}</th>
                                    <th>${pcs1WeightTotal}</th>
                                    <th>${pcs1AvgTotal}</th>
                                    <th>${pcs1NumOverWeight}</th>
                                    <th>${pcs1WeightOverWeight}</th>
                                    <th>${pcs1AvgOverWeight}</th>
                                    <th>${pcs1PercentOverWeight}</th>
                                    <th>${pcs1NumGood}</th>
                                    <th>${pcs1WeightGood}</th>
                                    <th>${pcs1AvgGood}</th>
                                    <th>${pcs1PercentGood}</th>
                                    <th>${pcs1NumUnderWeight}</th>
                                    <th>${pcs1WeightUnderWeight}</th>
                                    <th>${pcs1AvgUnderWeight}</th>
                                    <th>${pcs1PercentUnderWeight}</th>
                                    <th>${pcs1NumberError}</th>
                                    <th>${pcs1PercentError}</th>
                                    <th>${pcs2NumTotal}</th>
                                    <th>${pcs2WeightTotal}</th>
                                    <th>${pcs2AvgTotal}</th>
                                    <th>${pcs2NumOverWeight}</th>
                                    <th>${pcs2WeightOverWeight}</th>
                                    <th>${pcs2AvgOverWeight}</th>
                                    <th>${pcs2PercentOverWeight}</th>
                                    <th>${pcs2NumGood}</th>
                                    <th>${pcs2WeightGood}</th>
                                    <th>${pcs2AvgGood}</th>
                                    <th>${pcs2PercentGood}</th>
                                    <th>${pcs2NumUnderWeight}</th>
                                    <th>${pcs2WeightUnderWeight}</th>
                                    <th>${pcs2AvgUnderWeight}</th>
                                    <th>${pcs2PercentUnderWeight}</th>
                                    <th>${pcs2NumberError}</th>
                                    <th>${pcs2PercentError}</th>
                                    <th>${boxNumTotal}</th>
                                    <th>${boxWeightTotal}</th>
                                    <th>${boxAvgTotal}</th>
                                    <th>${boxNumOverWeight}</th>
                                    <th>${boxWeightOverWeight}</th>
                                    <th>${boxAvgOverWeight}</th>
                                    <th>${boxPercentOverWeight}</th>
                                    <th>${boxNumGood}</th>
                                    <th>${boxWeightGood}</th>
                                    <th>${boxAvgGood}</th>
                                    <th>${boxPercentGood}</th>
                                    <th>${boxNumUnderWeight}</th>
                                    <th>${boxWeightUnderWeight}</th>
                                    <th>${boxAvgUnderWeight}</th>
                                    <th>${boxPercentUnderWeight}</th>
                                    <th>${boxNumberError}</th>
                                    <th>${boxPercentError}</th>
                                </tr>
                            </tfoot>
                        `);

                        pie3.push({
                            value: pcs1NumTotal + pcs2NumTotal,
                            name: 'Total Number Pcs'
                        }, {
                            value: pcs1NumGood + pcs2NumGood,
                            name: 'Good Number Pcs'
                        });

                        pie4.push({
                            value: boxNumTotal,
                            name: 'Total Number Box'
                        }, {
                            value: boxNumGood,
                            name: 'Good Number Box'
                        });

                        pieCharts('pc3-pcs', 'Total & Good Pcs', pie3);
                        pieCharts('pc4-box', 'Total & Good Box', pie4);

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
                        $('.akm-tot-pcs').html(uniquePcs.size);
                        $('.akm-tot-box').html(uniqueBox.size);
                        // END SET AKUMULASI
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
