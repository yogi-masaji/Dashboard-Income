@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
        $chiselVersion = session('selected_location_chisel_Version', 'Chisel Version Tidak Diketahui');
        $systemCode = session('selected_location_system', 'System Code Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    {{-- Custom Styles for Light Theme --}}
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }

        .card {
            background-color: #ffffff !important;
            border: #d9d9d9 1px solid !important;
            height: auto;
            border-radius: 10px !important;
            color: #000 !important;
        }

        .card-header {
            background-color: #f7f7f7 !important;
            color: #000;
            border-bottom: 1px solid #d9d9d9;
        }

        label {
            color: #212529;
        }

        .form-control {
            background-color: #ffffff;
            color: #212529;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            background-color: #ffffff;
            color: #212529;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        .form-control option {
            background: #ffffff;
            color: #212529;
        }

        .form-control::placeholder {
            color: #6c757d;
        }

        /* DataTables light theme adjustments */
        #location-table {
            color: #212529;
        }

        #location-table thead th {
            color: #212529;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: #212529 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #212529 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #6c757d !important;
        }

        .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .page-link {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            color: #007bff;
        }

        /* Modal light theme */
        .modal-content {
            background-color: #ffffff;
            color: #212529;
            border: 1px solid rgba(0, 0, 0, .2);
            border-radius: 10px;
        }

        .modal-header {
            background-color: #f7f7f7;
            color: #212529;
            border-bottom: 1px solid #dee2e6;
        }

        .close {
            color: #000000;
            text-shadow: 0 1px 0 #ffffff;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
        }
    </style>

    <div class="container-fluid mt-4">
        {{-- Form to Add New Location --}}
        <div class="card shadow-lg">
            <div class="card-header">
                <h3 class="mb-0">Form Input Location</h3>
            </div>
            <div class="card-body">
                <form id="formTambah">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="kode_Lokasi">Location Code</label>
                            <input id="kode_Lokasi" type="text" class="form-control" name="kode_Lokasi"
                                placeholder="e.g., JKT01" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="nama_Lokasi">Location Name</label>
                            <input id="nama_Lokasi" type="text" class="form-control" name="nama_Lokasi"
                                placeholder="e.g., Jakarta Office" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="ip_Lokasi">IP Location</label>
                            <input id="ip_Lokasi" type="text" class="form-control" name="ip_Lokasi"
                                placeholder="e.g., 192.168.1.1" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="system_code">System Location</label>
                            <select name="system_code" id="system_code" class="form-control" required>
                                <option value="" disabled selected>--Select System--</option>
                                <option value="EZITAMA">EZITAMA</option>
                                <option value="PARKEE">PARKEE</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" id="btn_submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save
                    </button>
                </form>
            </div>
        </div>

        {{-- Table to Show Locations --}}
        <div class="card mt-4 shadow-lg">
            <div class="card-header">
                <h3 class="mb-0">Location List</h3>
            </div>
            <div class="card-body mt-3">
                <table id="location-table" class="table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Location Code</th>
                            <th class="text-center">Location Name</th>
                            <th class="text-center">Location IP</th>
                            <th class="text-center">System</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data will be loaded via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Edit Location Modal --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Location</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEdit">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id_Lokasi" id="e_id_Lokasi">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Location Code</label>
                            <div class="col-sm-9">
                                <input type="text" name="kode_Lokasi" id="e_kode_Lokasi" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Location Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama_Lokasi" id="e_nama_Lokasi" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Location IP</label>
                            <div class="col-sm-9">
                                <input type="text" name="ip_Lokasi" id="e_ip_Lokasi" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Location System</label>
                            <div class="col-sm-9">
                                <select name="system_code" id="e_system_code" class="form-control" required>
                                    <option value="EZITAMA">EZITAMA</option>
                                    <option value="PARKEE">PARKEE</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer gap-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_update" class="btn btn-primary"><i class="fas fa-save"></i>
                        Update</button>
                </div>
            </div>
        </div>
    </div>



    {{-- Add jQuery, DataTables, and SweetAlert2 --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Customizing SweetAlert2 for light mode
        const swalWithLightButton = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });


        $(document).ready(function() {
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize DataTable for client-side processing
            var table = $('#location-table').DataTable({
                processing: true,
                ajax: {
                    url: "{{ route('config.locations.data') }}",
                    dataSrc: 'data'
                },
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'kode_Lokasi'
                    },
                    {
                        data: 'nama_Lokasi'
                    },
                    {
                        data: 'ip_Lokasi'
                    },
                    {
                        data: 'system_code'
                    },
                    {
                        data: 'id_Lokasi',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            let editButton =
                                `<button type="button" class="btn btn-info btn-sm item_edit" data-id="${data}">Edit</button>`;
                            let deleteButton =
                                `<button type="button" class="btn btn-danger btn-sm item_hapus" data-id="${data}">Delete</button>`;
                            return `<div class="text-center">${editButton} &nbsp; ${deleteButton}</div>`;
                        }
                    }
                ]
            });

            // Add row numbers to the first column
            table.on('draw.dt', function() {
                var PageInfo = $('#location-table').DataTable().page.info();
                table.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });


            // Handle SUBMIT for new location
            $('#formTambah').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('config.locations.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        swalWithLightButton.fire('Success!', response.message, 'success');
                        $('#formTambah')[0].reset();
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        swalWithLightButton.fire('Error!', xhr.responseJSON.message, 'error');
                    }
                });
            });

            // Handle click on EDIT button
            $('#location-table').on('click', '.item_edit', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('config.locations.show', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(data) {
                        $('#e_id_Lokasi').val(data.id_Lokasi);
                        $('#e_kode_Lokasi').val(data.kode_Lokasi);
                        $('#e_nama_Lokasi').val(data.nama_Lokasi);
                        $('#e_ip_Lokasi').val(data.ip_Lokasi);
                        $('#e_system_code').val(data.system_code);
                        $('#modalEdit').modal('show');
                    }
                });
            });

            // Handle click on UPDATE button in modal
            $('#btn_update').on('click', function() {
                var id = $('#e_id_Lokasi').val();
                $.ajax({
                    url: "{{ route('config.locations.update', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: $('#formEdit').serialize(),
                    success: function(response) {
                        $('#modalEdit').modal('hide');
                        swalWithLightButton.fire('Success!', response.message, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        swalWithLightButton.fire('Error!', xhr.responseJSON.message, 'error');
                    }
                });
            });


            // Handle DELETE button click
            $('#location-table').on('click', '.item_hapus', function() {
                var id = $(this).data('id');
                swalWithLightButton.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('config.locations.destroy', ':id') }}".replace(
                                ':id', id),
                            type: 'DELETE',
                            success: function(response) {
                                swalWithLightButton.fire('Deleted!', response.message,
                                    'success');
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                swalWithLightButton.fire('Error!',
                                    'Failed to delete location.', 'error');
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
