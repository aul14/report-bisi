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
                            <a href="{{ route('log_weight.index') }}" class="btn btn-md btn-outline-warning">Refresh</a>
                            <a href="javascript:void(0)" class="btn btn-md btn-outline-primary btn-search">Search</a>
                            <a href="javascript:void(0)" class="btn btn-md btn-outline-success btn-excell"
                                style="display: none">Excell</a>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-weight">
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

            $(".btn-search").click(function(e) {
                e.preventDefault();
                let date_start = $(".form-search input[name=date_start]").val();
                let date_end = $(".form-search input[name=date_end]").val();
                let scale_name = $(".form-search input[name=scale_name]").val();
                let line_number = $(".form-search input[name=line_number]").val();

                searchReport(date_start, date_end, scale_name, line_number);
            });


        });

        function searchReport(dateStart = null, dateEnd = null, scaleName = null, lineNumber = null) {
            $.ajax({
                type: "post",
                url: '{{ route('log_weight.data') }}',
                data: {
                    date_start: dateStart,
                    date_end: dateEnd,
                    scale_name: scaleName,
                    line_number: lineNumber
                },
                dataType: "json",
                success: function(res) {
                    const tableWeight = $('.table-weight');
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
                                    <th>Index Number</th>
                                    <th>Total Number</th>
                                    <th>Weight</th>
                                    <th>Created At</th>
                                    <th>Status</th>
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
                            const createdAt = new Date(val['createdAt'])
                                .toLocaleString('en-US', options);

                            tableBody.append(`
                                <tr>
                                    <td>${key+1}</td>  
                                    <td>${val['linenumber']}</td>  
                                    <td>${val['timbanganName']}</td>  
                                    <td>${val['indexnumber']}</td>  
                                    <td>${val['totalNumber']}</td>  
                                    <td>${val['weight']}</td>  
                                    <td>${createdAt}</td>  
                                    <td>${val['Status']}</td>  
                                </tr>
                            `);
                        });

                        tableWeight.empty().append(resultTable);
                        $('.btn-excell').show();

                        // Event listener untuk ekspor Excel
                        $('.btn-excell').on('click', function() {
                            exportReportToExcel(res);
                        });

                        loadDataTable();
                    } else {
                        tableWeight.empty();
                        $('.btn-excell').hide();
                    }
                }
            });
        }

        async function exportReportToExcel(res) {
            // Buat workbook dan worksheet
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Log Weight');


            // Tambahkan tabel kolom di bawah gambar
            worksheet.mergeCells('A1:H1');
            worksheet.getCell('A1').value = 'Log Weight';
            worksheet.getCell('A1').font = {
                size: 14,
                bold: true
            };
            worksheet.getCell('A1').alignment = {
                vertical: 'middle',
                horizontal: 'center'
            };

            // Definisikan kolom tabel dimulai dari baris setelah gambar
            worksheet.columns = [{
                    key: 'no',
                    width: 5
                },
                {
                    key: 'linenumber',
                    width: 25
                },
                {
                    key: 'timbanganName',
                    width: 25
                },
                {
                    key: 'indexnumber',
                    width: 25
                },
                {
                    key: 'totalNumber',
                    width: 25
                },
                {
                    key: 'weight',
                    width: 25
                },
                {
                    key: 'createdAt',
                    width: 25
                },
                {
                    key: 'Status',
                    width: 25
                },
            ];
            // Data diambil dari res[] (sesuaikan dengan data Anda)
            const data = res.data; // Sesuaikan ini dengan data yang dihasilkan dari jQuery
            const tableData = data.map((row, index) => ({
                no: index + 1,
                linenumber: row.linenumber,
                timbanganName: row.timbanganName,
                indexnumber: row.indexnumber,
                totalNumber: row.totalNumber,
                weight: row.weight,
                createdAt: row.createdAt,
                Status: row.Status,
            }));

            // Definisikan header untuk tabel
            const tableHeader = ['No', 'Line Number', 'Scale Name', 'Index Number', 'Total Number', 'Weight',
                'Created At', 'Status'
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

            // Simpan workbook sebagai file Excel
            workbook.xlsx.writeBuffer().then(function(data) {
                const blob = new Blob([data], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'log_weight.xlsx';
                a.click();
                window.URL.revokeObjectURL(url);
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
