@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Product Box'])
    <div class="row mt-1 px-1">
        <div class="card">
            <div class="card-header px-1">
                <a href="javascript:void(0)" class="btn btn-primary btn-md mb-2 float-left box-add">Tambah
                    Product Box</a>
            </div>
            <div class="card-body px-1">
                <div class="table-responsive">
                    <table class="my-table table my-tableview my-table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th class="select-filter">Nama Product</th>
                                <th class="select-filter">Code Product</th>
                                <th class="select-filter">Index</th>
                                <th class="select-filter">Lower Limit</th>
                                <th class="select-filter">Upper Limit</th>
                                <th class="select-filter">Nominal Value</th>
                                <th class="select-filter">Updated At</th>
                                <th width="2px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade my-modal" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="titleModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModal">Form Product Box</h5>
                </div>
                <form class="save-form">
                    <input type="hidden" name="url_link" value="{{ route('box.store') }}">
                    <input type="hidden" name="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Product <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" name="namaproduct" autocomplete="off"
                                        required placeholder="...">
                                </div>
                                <div class="form-group">
                                    <label>Code Product <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" name="codeproduct" autocomplete="off"
                                        required placeholder="...">
                                </div>
                                <div class="form-group">
                                    <label>Index <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" name="indexs" autocomplete="off" required
                                        placeholder="...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lower Limit </label>
                                    <input type="number" class="form-control" name="lowerLimit" autocomplete="off"
                                        placeholder="...">
                                </div>
                                <div class="form-group">
                                    <label>Upper Limit </label>
                                    <input type="number" class="form-control" name="uperlimit" autocomplete="off"
                                        placeholder="...">
                                </div>
                                <div class="form-group">
                                    <label>Nominal Value </label>
                                    <input type="number" class="form-control" name="nominalvalue" autocomplete="off"
                                        placeholder="...">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END MODAL --}}
@endsection
@section('script')
    <script src="{{ asset('assets/plugins/bootstrap-datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function() {
            $.fn.DataTable.ext.pager.numbers_length = 5;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            showTableData();

            addForm();

            saveForm();

            editForm();

            deleteForm();
        });

        function showTableData() {
            $('.my-table').DataTable({
                processing: true,
                serverSide: true,
                pagingType: 'full_numbers',
                scrollY: "50vh",
                scrollCollapse: true,
                scrollX: true,
                ajax: '{{ route('box.get') }}',
                oLanguage: {
                    oPaginate: {
                        sNext: '<span class="fas fa-angle-right pgn-1" style="color: #5e72e4"></span>',
                        sPrevious: '<span class="fas fa-angle-left pgn-2" style="color: #5e72e4"></span>',
                        sFirst: '<span class="fas fa-angle-double-left pgn-3" style="color: #5e72e4"></span>',
                        sLast: '<span class="fas fa-angle-double-right pgn-4" style="color: #5e72e4"></span>',
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'namaproduct',
                        name: 'namaproduct'
                    },
                    {
                        data: 'codeproduct',
                        name: 'codeproduct'
                    },
                    {
                        data: 'indexs',
                        name: 'indexs'
                    },
                    {
                        data: 'lowerLimit',
                        name: 'lowerLimit'
                    },
                    {
                        data: 'uperlimit',
                        name: 'uperlimit'
                    },
                    {
                        data: 'nominalvalue',
                        name: 'nominalvalue'
                    },
                    {
                        data: 'updateAt',
                        name: 'updateAt'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [{
                    defaultContent: "-",
                    targets: "_all"
                }],


            });
        }

        function addForm() {
            $(".box-add").click(function(e) {
                e.preventDefault();
                $('.my-modal').modal('show');
                $('.save-form')[0].reset();
                $("input[name=edit_id]").val("");
                $("#titleModal").html(`Form Tambah Product Box`);
            });
        }

        function saveForm() {
            $('.save-form').safeform({
                timeout: 2000,
                submit: function(e) {
                    e.preventDefault();
                    // put here validation and ajax stuff..
                    let formdata = $(this).serializeArray();
                    let link = $(this).find("input[name=url_link]").val();
                    e.preventDefault();
                    $.ajax({
                        type: "post",
                        url: link,
                        data: formdata,
                        dataType: "json",
                        beforeSend: function() {
                            // Show image container
                            $('.ajax-loader').css("visibility", "visible");
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: res.msg,
                                }).then(function() {
                                    // Reset form fields to their default state
                                    $('.save-form')[0].reset();

                                    $('.my-table').DataTable().ajax.reload();

                                    $('.my-modal').modal('hide');
                                });
                                return;
                            } else {
                                if (res.message) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Oops...',
                                        text: res.message,
                                    });
                                    return;
                                } else {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Oops...',
                                        text: res.msg,
                                    });
                                    return;
                                }
                            }
                        },
                        complete: function(data) {
                            // Hide image container
                            $('.ajax-loader').css("visibility", "hidden");
                        }
                    });
                    // no need to wait for timeout, re-enable the form ASAP
                    $(this).safeform('complete');
                    return false;
                }
            });
        }

        function editForm() {
            $(document).on("click", ".box-edit", function(e) {
                e.preventDefault();
                let editId = $(this).data('id');
                $('.my-modal').modal('show');
                $("#titleModal").html(`Form Edit Product Box`);
                $('.save-form')[0].reset();
                $("input[name=edit_id]").val(editId);

                $.ajax({
                    type: "get",
                    url: '{{ route('box.show') }}',
                    data: {
                        id: editId
                    },
                    dataType: "json",
                    success: function(res) {
                        $("input[name=namaproduct]").val(res.data.namaproduct);
                        $("input[name=codeproduct]").val(res.data.codeproduct);
                        $("input[name=indexs]").val(res.data.indexs);
                        $("input[name=lowerLimit]").val(res.data.lowerLimit);
                        $("input[name=uperlimit]").val(res.data.uperlimit);
                        $("input[name=nominalvalue]").val(res.data.nominalvalue);
                    }
                });
            });
        }

        function deleteForm() {
            $(document).on("click", ".box-delete", function(e) {
                e.preventDefault();
                let deleteId = $(this).data('id');
                $.confirm({
                    title: "Confirmation",
                    content: "Apakah anda yakin, akan menghapus data ini?",
                    theme: 'bootstrap',
                    columnClass: 'medium',
                    typeAnimated: true,
                    buttons: {
                        hapus: {
                            text: 'Submit',
                            btnClass: 'btn-red',
                            action: function() {
                                $.ajax({
                                    type: "delete",
                                    url: '{{ route('box.destroy') }}',
                                    data: {
                                        id: deleteId
                                    },
                                    dataType: "json",
                                    beforeSend: function() {
                                        // Show image container
                                        $('.ajax-loader').css("visibility",
                                            "visible");
                                    },
                                    success: function(res) {
                                        if (res.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success!',
                                                text: res.msg,
                                            }).then(function() {
                                                $('.my-table').DataTable()
                                                    .ajax.reload();
                                            });
                                            return;
                                        } else {
                                            if (res.message) {
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'Oops...',
                                                    text: res.message,
                                                });
                                                return;
                                            } else {
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'Oops...',
                                                    text: res.msg,
                                                });
                                                return;
                                            }
                                        }
                                    },
                                    complete: function(data) {
                                        // Hide image container
                                        $('.ajax-loader').css("visibility",
                                            "hidden");
                                    }
                                });
                            }
                        },
                        close: function() {}
                    }
                });
            });
        }
    </script>
@endsection
