@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Batch Production'])
    <div class="row mt-1 px-1">
        <div class="card">
            <div class="card-header px-1">
                <a href="javascript:void(0)" class="btn btn-primary btn-md mb-2 float-left batch_prod-add">Tambah
                    Batch Production</a>
            </div>
            <div class="card-body px-1">
                <div class="table-responsive">
                    <table class="my-table table my-tableview my-table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Code Production</th>
                                <th>Product Pcs</th>
                                <th>Product Box</th>
                                <th>Status</th>
                                <th>Operator</th>
                                <th>Updated At</th>
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
                    <h5 class="modal-title" id="titleModal">Form Batch Production</h5>
                </div>
                <form class="save-form">
                    <input type="hidden" name="url_link" value="{{ route('batch_production.store') }}">
                    <input type="hidden" name="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Code Production <span class="text-red">*</span></label>
                                    <input type="text" class="form-control" name="CodeProduction" autocomplete="off"
                                        required placeholder="...">
                                </div>
                                <div class="form-group">
                                    <label>Product Pcs <span class="text-red">*</span></label>
                                    <select name="codeProductpcs" required class="product-pcs-select">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Product Box <span class="text-red">*</span></label>
                                    <select name="codeProductBox" required class="product-box-select">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status </label>
                                    <input type="text" class="form-control" name="Status" disabled value="created"
                                        autocomplete="off" placeholder="...">
                                </div>
                                <div class="form-group">
                                    <label>Operator </label>
                                    <input type="text" class="form-control" name="operator" disabled
                                        value="{{ session('fullname') }}" autocomplete="off" placeholder="...">
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

            productPcsSelect();

            productBoxSelect();

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
                ajax: '{{ route('batch_production.get') }}',
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
                        data: 'CodeProduction',
                        name: 'CodeProduction'
                    },
                    {
                        data: 'codeProductpcs',
                        name: 'codeProductpcs'
                    },
                    {
                        data: 'codeProductBox',
                        name: 'codeProductBox'
                    },
                    {
                        data: 'Status',
                        name: 'Status'
                    },
                    {
                        data: 'operator',
                        name: 'operator'
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

        function productPcsSelect() {
            $(`.product-pcs-select`).select2({
                placeholder: 'Search...',
                width: "100%",
                allowClear: true,
                ajax: {
                    url: '{{ route('batch_production.get.product_pcs') }}',
                    dataType: 'json',
                    type: 'POST',
                    delay: 0,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.namaproduct,
                                    id: item.codeproduct,
                                }
                            })
                        };
                    },
                    cache: false
                }
            });
        }

        function productBoxSelect() {
            $(`.product-box-select`).select2({
                placeholder: 'Search...',
                width: "100%",
                allowClear: true,
                ajax: {
                    url: '{{ route('batch_production.get.product_box') }}',
                    dataType: 'json',
                    type: 'POST',
                    delay: 0,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.namaproduct,
                                    id: item.codeproduct,
                                }
                            })
                        };
                    },
                    cache: false
                }
            });
        }

        function addForm() {
            $(".batch_prod-add").click(function(e) {
                e.preventDefault();
                $('.my-modal').modal('show');
                $('.save-form')[0].reset();
                $("input[name=edit_id]").val("");
                $(".product-pcs-select").empty();
                $(".product-box-select").empty();
                $("#titleModal").html(`Form Tambah Batch Production`);
            });
        }

        function editForm() {
            $(document).on("click", ".batch_prod-edit", function(e) {
                e.preventDefault();
                let editId = $(this).data('id');
                $('.my-modal').modal('show');
                $('.save-form')[0].reset();;
                $("#titleModal").html(`Form Edit Batch Production`);
                $("input[name=edit_id]").val(editId);

                $.ajax({
                    type: "get",
                    url: '{{ route('batch_production.show') }}',
                    data: {
                        id: editId
                    },
                    dataType: "json",
                    success: function(res) {
                        $("input[name=CodeProduction]").val(res.data.CodeProduction);
                        $("input[name=Status]").val(res.data.Status);
                        $("input[name=operator]").val(res.data.operator);

                        var newProductPcs = new Option(res.data.namaproduct_pcs, res.data
                            .codeProductpcs, true, true);
                        $("select[name=codeProductpcs]").append(newProductPcs).change();

                        var newProductBox = new Option(res.data.namaproduct_box, res.data
                            .codeProductBox, true, true);
                        $("select[name=codeProductBox]").append(newProductBox).change();
                    }
                });
            });
        }

        function deleteForm() {
            $(document).on("click", ".batch_prod-delete", function(e) {
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
                                    url: '{{ route('batch_production.destroy') }}',
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
    </script>
@endsection
