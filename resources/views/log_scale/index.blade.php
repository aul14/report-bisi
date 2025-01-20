@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Log Weight'])

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
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="scale_name" name="scale_name"
                                    placeholder="Scale Name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="nimber" class="form-control" id="line_number" name="line_number"
                                    placeholder="Line Number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 my-2">
                            <a href="{{ route('log_scale.index') }}" class="btn btn-md btn-outline-warning">Refresh</a>
                            <a href="javascript:void(0)" class="btn btn-md btn-outline-primary btn-search">Search</a>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-scale">
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
    <script src="{{ asset('assets/export-excell/html2canvas.min.js') }}"></script>
    <script src="{{ asset('assets/export-excell/exceljs.min.js') }}"></script>
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

        function searchReport(dateStart = null, dateEnd = null, scaleName = null, lineNumber = null) {
            $.ajax({
                type: "post",
                url: '{{ route('log_scale.data') }}',
                data: {
                    date_start: dateStart,
                    date_end: dateEnd,
                    scale_name: scaleName,
                    line_number: lineNumber
                },
                dataType: "json",
                success: function(res) {
                    const tableScale = $('.table-scale');
                    if (res.data.length > 0) {
                        const resultTable = $(
                            `<table class="table my-table my-tablelog my-table-striped w-100"></table>`
                        );

                        resultTable.append(`
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Line Number</th>
                                    <th>Scale Name</th>
                                    <th>Total Number</th>
                                    <th>Total Weight</th>
                                    <th>Good Number</th>
                                    <th>Good Weight</th>
                                    <th>Over Number</th>
                                    <th>Over Weight</th>
                                    <th>Under Weight</th>
                                    <th>Error Number</th>
                                    <th>Index</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        `);

                        const tableBody = resultTable.find('tbody');

                        $.each(res.data, function(key, val) {
                            tableBody.append(`
                                <tr>
                                    <td>${key+1}</td>  
                                    <td>${val['linenumber']}</td>  
                                    <td>${val['scaleName']}</td>  
                                    <td>${val['totalNumber']}</td>  
                                    <td>${val['totalweight']}</td>  
                                    <td>${val['goodNumber']}</td>  
                                    <td>${val['goodWeight']}</td>  
                                    <td>${val['overNumber']}</td>  
                                    <td>${val['overWeight']}</td>  
                                    <td>${val['undeWeight']}</td>  
                                    <td>${val['errorNumber']}</td>  
                                    <td>${val['Indexs']}</td>  
                                    <td>${val['createdAt']}</td>  
                                    <td>${val['Status']}</td>  
                                </tr>
                            `);
                        });

                        tableScale.empty().append(resultTable);

                        loadDataTable();
                    } else {
                        tableScale.empty();
                    }
                }
            });
        }

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
            });
        }
    </script>
@endsection
