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
                    <div class="col-md-6">
                        <div class="table-report" style="overflow: auto;"></div>
                    </div>
                    <div class="col-md-6 row-akumulasi" style="display: none;">

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

                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h5 class="text-center" style="background-color: blueviolet;">Scale Pcs</h5>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header text-center py-0 bg-success"
                                        style="border-radius: 0px !important;">
                                        <strong style="color: black;"><i class="fa fa-thumbs-up"></i> Good</strong>
                                    </div>
                                    <div class="card-body p-0">
                                        <h4 class="card-title text-center good-persen-pcs">0%</h4>
                                        <div class="row">
                                            <div class="col-md-8">Total Pcs</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 good-total-pcs">0</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">Total Weight</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 good-totalweight-pcs">0</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">Total Average</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 good-totalaverage-pcs">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header text-center py-0 bg-warning"
                                        style="border-radius: 0px !important;">
                                        <strong style="color: black;"><i class="fa fa-table-list"></i> Total</strong>
                                    </div>
                                    <div class="card-body p-0">
                                        <h4 class="card-title text-center pcs-total">0 Pcs</h4>
                                        <div class="row">
                                            <div class="col-md-8">Total Weight</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 pcs-totalweight">0</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">Total Average</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 pcs-totalaverage">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header text-center py-0 bg-danger"
                                        style="border-radius: 0px !important;">
                                        <strong style="color: black;"><i class="fa fa-thumbs-down"></i> NG</strong>
                                    </div>
                                    <div class="card-body p-0">
                                        <h4 class="card-title text-center ng-persen">0 %</h4>
                                        <div class="row">
                                            <div class="col-md-8">Total Pcs</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 ng-totalpcs">0</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">Total Weight</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 ng-totalweight">0</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">Total Average</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 ng-totalaverage">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h5 class="text-center" style="background-color: blueviolet;">Scale Box</h5>
                            </div>
                        </div>

                        <div class="row my-2">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header text-center py-0 bg-success"
                                        style="border-radius: 0px !important;">
                                        <strong style="color: black;"><i class="fa fa-thumbs-up"></i> Good</strong>
                                    </div>
                                    <div class="card-body p-0">
                                        <h4 class="card-title text-center good-persen-box">0 %</h4>
                                        <div class="row">
                                            <div class="col-md-8">Total Pcs</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 good-total-box">0</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">Total Weight</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 good-totalweight-box">0</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">Total Average</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 good-totalaverage-box">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header text-center py-0 bg-warning"
                                        style="border-radius: 0px !important;">
                                        <strong style="color: black;"><i class="fa fa-table-list"></i> Total</strong>
                                    </div>
                                    <div class="card-body p-0">
                                        <h4 class="card-title text-center box-total">0 Box</h4>
                                        <div class="row">
                                            <div class="col-md-8">Total Weight</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 box-totalweight">0</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">Total Average</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2 box-totalaverage">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header text-center py-0 bg-danger"
                                        style="border-radius: 0px !important;">
                                        <strong style="color: black;"><i class="fa fa-thumbs-down"></i> NG</strong>
                                    </div>
                                    <div class="card-body p-0">
                                        <h4 class="card-title text-center">0 %</h4>
                                        <div class="row">
                                            <div class="col-md-8">Total Pcs</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2">0</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">Total Weight</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2">0</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">Total Average</div>
                                            <div class="col-md-1">:</div>
                                            <div class="col-md-2">0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                            pcsPersenGood = 0,
                            totalPcsGood = 0,
                            totalWeightGoodPcs = 0,
                            totalAverageGoodPcs = 0,
                            totalWeightPcs = 0,
                            totalAveragePcs = 0,
                            boxPersenGood = 0,
                            totalBoxGood = 0,
                            totalWeightGoodBox = 0,
                            totalAverageGoodBox = 0,
                            totalWeightBox = 0,
                            totalAverageBox = 0;
                        const uniquePcs = new Set();
                        const uniqueBox = new Set();

                        const resultTable = $(
                            `<table class="table my-table my-tablelog my-table-striped w-100"></table>`
                        );

                        resultTable.append(`
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Operator</th>
                                    <th>Date Time</th>
                                    <th>Production Code</th>
                                    <th>Duration</th>
                                    <th>Pcs Good (%)</th>
                                    <th>Box Good (%)</th>
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

                            const pcs1Good = parseInt(val['pcs1_percent_good']);
                            const pcs2Good = parseInt(val['pcs2_percent_good']);
                            const boxGood = parseInt(val['box_percecnt_good']);
                            const totalGood = pcs1Good + pcs2Good;

                            totalSeconds += durationToSeconds(val['duration']);
                            uniquePcs.add(val['codeProductpcs']);
                            uniqueBox.add(val['codeProductBox']);

                            pcsPersenGood += totalGood;
                            totalPcsGood += parseInt(val['pcs1_number_good']) + parseInt(val[
                                'pcs2_number_good']);
                            totalWeightGoodPcs += parseInt(val['pcs1_weright_good']) + parseInt(val[
                                'pcs2_weright_good']);
                            totalAverageGoodPcs += parseInt(val['pcs1_average_good']) + parseInt(val[
                                'pcs2_average_good']);

                            totalWeightPcs += parseInt(val['pcs1_weight_total']) + parseInt(val[
                                'pcs2_weight_total']);
                            totalAveragePcs += parseInt(val['pcs1_average_total']) + parseInt(val[
                                'pcs2_average_total']);

                            boxPersenGood += parseInt(val['box_percecnt_good']);
                            totalBoxGood += parseInt(val['box_number_good']);
                            totalWeightGoodBox += parseInt(val['box_weight_good']);
                            totalAverageGoodBox += parseInt(val['box_average_good']);

                            totalWeightBox += parseInt(val['box_weight_total']);
                            totalAverageBox += parseInt(val['box_average_total']);

                            tableBody.append(`
                                    <tr>
                                        <td>${key + 1}</td>
                                        <td>${val['operator']}</td>
                                        <td>${createdAt}</td>
                                        <td>${val['CodeProduction']}</td>
                                        <td>${val['duration']}</td>
                                        <td>${totalGood}%</td>
                                        <td>${boxGood}%</td>
                                    </tr>
                            `);
                        });

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
                        $('.row-akumulasi').show();
                        // SET AKUMULASI
                        $('.akm-date-from').html(start);
                        $('.akm-date-to').html(end);
                        $('.akm-tot-batch').html(res.data.length);
                        $('.akm-tot-dur').html(secondsToDuration(totalSeconds));
                        $('.akm-tot-pcs').html(uniquePcs.size);
                        $('.akm-tot-box').html(uniqueBox.size);

                        $('.good-persen-pcs').html(`${pcsPersenGood}%`);
                        $('.good-total-pcs').html(totalPcsGood);
                        $('.good-totalweight-pcs').html(totalWeightGoodPcs);
                        $('.good-totalaverage-pcs').html(totalAverageGoodPcs);

                        $('.pcs-total').html(uniquePcs.size);
                        $('.pcs-totalweight').html(totalWeightPcs);
                        $('.pcs-totalaverage').html(totalAveragePcs);

                        $('.good-persen-box').html(`${boxPersenGood}%`);
                        $('.good-total-box').html(totalBoxGood);
                        $('.good-totalweight-box').html(totalWeightGoodBox);
                        $('.good-totalaverage-box').html(totalAverageGoodBox);

                        $('.box-total').html(uniqueBox.size);
                        $('.box-totalweight').html(totalWeightBox);
                        $('.box-totalaverage').html(totalAverageBox);
                        // END SET AKUMULASI
                    } else {
                        tableReport.empty();
                        $('.btn-excell').hide();
                        $('.btn-pdf').hide();
                        $('.row-akumulasi').hide();
                        // SET AKUMULASI
                        $('.akm-date-from').html("");
                        $('.akm-date-to').html("");
                        $('.akm-tot-batch').html("");
                        $('.akm-tot-dur').html("");
                        $('.akm-tot-pcs').html("");
                        $('.akm-tot-box').html("");

                        $('.good-persen-pcs').html("0%");
                        $('.good-total-pcs').html(0);
                        $('.good-totalweight-pcs').html(0);
                        $('.good-totalaverage-pcs').html(0);

                        $('.pcs-total').html("0 Pcs");
                        $('.pcs-totalweight').html(0);
                        $('.pcs-totalaverage').html(0);

                        $('.good-persen-box').html("0%");
                        $('.good-total-box').html(0);
                        $('.good-totalweight-box').html(0);
                        $('.good-totalaverage-box').html(0);

                        $('.box-total').html("0 Box");
                        $('.box-totalweight').html(0);
                        $('.box-totalaverage').html(0);
                        // END SET AKUMULASI
                    }
                },
                complete: function() {
                    $('.ajax-loader').css("visibility", "hidden");
                }
            });
        }


        function numberFormat(number) {
            return Number(number).toLocaleString('en-US');
        }
    </script>
@endsection
