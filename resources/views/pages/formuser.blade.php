@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

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
        #user-table {
            color: #212529;
        }

        #user-table thead th {
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

    <style>
        .mode-gelap body {
            background-color: #121212;
            color: #e0e0e0;
        }

        .mode-gelap .card {
            background-color: #192e50 !important;
            border: #333 1px solid !important;
            color: #e0e0e0 !important;
        }

        .mode-gelap .card-header {
            background-color: #070d17 !important;
            color: #ffffff;
            border-bottom: 1px solid #333;
        }

        .mode-gelap label {
            color: #e0e0e0;
        }

        .mode-gelap .form-control {
            background-color: #1e1e1e;
            color: #ffffff;
            border: 1px solid #444;
        }

        .mode-gelap .form-control:focus {
            background-color: #1e1e1e;
            color: #ffffff;
            border-color: #66afe9;
            box-shadow: 0 0 0 0.2rem rgba(102, 175, 233, .25);
        }

        .mode-gelap .form-control option {
            background: #1e1e1e;
            color: #ffffff;
        }

        .mode-gelap .form-control::placeholder {
            color: #aaaaaa;
        }

        /* DataTables dark theme adjustments */
        .mode-gelap #user-table {
            color: #e0e0e0;
        }

        .mode-gelap #user-table thead th {
            color: #ffffff;
        }

        .mode-gelap .dataTables_wrapper .dataTables_length,
        .mode-gelap .dataTables_wrapper .dataTables_filter,
        .mode-gelap .dataTables_wrapper .dataTables_info,
        .mode-gelap .dataTables_wrapper .dataTables_processing,
        .mode-gelap .dataTables_wrapper .dataTables_paginate {
            color: #e0e0e0 !important;
        }

        .mode-gelap .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #ffffff !important;
        }

        .mode-gelap .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #666666 !important;
        }

        .mode-gelap .page-item.active .page-link {
            background-color: #66afe9;
            border-color: #66afe9;
        }

        .mode-gelap .page-link {
            background-color: #1e1e1e;
            border: 1px solid #333;
            color: #66afe9;
        }

        /* Modal dark theme */
        .mode-gelap .modal-content {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border: 1px solid rgba(255, 255, 255, .1);
        }

        .mode-gelap .modal-header {
            background-color: #2a2a2a;
            color: #ffffff;
            border-bottom: 1px solid #333;
        }

        .mode-gelap .close {
            color: #ffffff;
            text-shadow: 0 1px 0 #000000;
        }

        .mode-gelap .modal-footer {
            border-top: 1px solid #333;
        }
    </style>
    <div class="container-fluid mt-4">
        <div class="card shadow-lg">
            <div class="card-header">
                <h4 class="mb-0">Form Input User</h4>
            </div>
            <div class="card-body">
                <form id="formTambah">
                    @csrf
                    <input type="hidden" id="id_staff" name="id_staff">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Email</label>
                            <input id="email" type="email" class="form-control" name="email" placeholder="Email"
                                required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Password</label>
                            <input id="password" type="password" class="form-control" name="password"
                                placeholder="Password" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Name</label>
                            <input id="nama" type="text" class="form-control" name="nama" placeholder="Name"
                                required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Role</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="">--User's Role--</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Location Default</label>
                            <select name="location" id="location" class="form-control">
                                <option value="">--Location Default--</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>IP Default</label>
                            <input id="ip" type="text" class="form-control" name="ip"
                                placeholder="IP Default" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>System Default</label>
                            <input id="system" type="text" class="form-control" name="system"
                                placeholder="System Default" required>
                        </div>
                    </div>
                    <button type="submit" id="btn_submit" class="btn btn-primary mt-2">Simpan</button>
                </form>
            </div>
        </div>

        <div class="card mt-4 shadow-lg">
            <div class="card-header">
                <h4 class="mb-0">Table Show</h4>
            </div>
            <div class="card-body">
                <table id="user-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Group/Location</th>
                            <th>Location Name</th>
                            <th>IP Location</th>
                            <th>System Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Change -->
    <div class="modal fade" id="modalChange" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change User Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formChange" method="POST">
                        @csrf
                        <input type="hidden" name="e_id" id="e_id">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="e_email" id="e_email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="e_password" id="e_password" class="form-control"
                                placeholder="Enter new password to change">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-2" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_change" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Default Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEdit" method="POST">
                        @csrf
                        <input type="hidden" name="d_id" id="d_id">
                        <div class="form-group">
                            <label>Location</label>
                            <select name="e_location" id="e_location" class="form-control">
                                <option value="">--Location Default--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>IP Default</label>
                            <input id="e_ip" type="text" class="form-control" name="e_ip"
                                placeholder="IP Default" required>
                        </div>
                        <div class="form-group">
                            <label>System Default</label>
                            <input id="e_system" type="text" class="form-control" name="e_system"
                                placeholder="System Default" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary me-2" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_edit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Customizing SweetAlert2 for light mode
            const swalWithLightButton = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            var table = $('#user-table').DataTable({
                processing: true,
                serverSide: false, // Set to false as we load all data at once
                ajax: {
                    url: '{{ route('form.user.data') }}',
                    dataSrc: '' // Menunjukkan bahwa data adalah array langsung
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'email_Staff',
                        name: 'email_Staff'
                    },
                    {
                        data: 'user_type_code',
                        name: 'user_type_code'
                    },
                    {
                        data: 'group.nama_Group',
                        name: 'group.nama_Group',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'site_name',
                        name: 'site_name'
                    },
                    {
                        data: 'ip',
                        name: 'ip'
                    },
                    {
                        data: 'default_system_loc',
                        name: 'default_system_loc'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let button = '';
                            if (row.user_type_code !== 'IT') {
                                button = `
                                <button class="btn btn-warning btn-sm item_change mb-2" data-id="${row.id_Staff}">
                                    <i class="fa fa-key"></i> change user
                                </button>
                                <button class="btn btn-primary btn-sm item_edit mb-2" data-id="${row.id_Staff}" data-nama="${row.lokasi}">
                                    <i class="fa fa-map-marker-alt"></i> edit default location
                                </button>
                                <button class="btn btn-danger btn-sm item_hapus" data-id="${row.id_Staff}">
                                    <i class="fa fa-trash"></i> delete user
                                </button>
                            `;
                            }
                            return button;
                        }
                    }
                ]
            });

            function reloadTable() {
                table.ajax.reload(null, false); // User-friendly reload
            }

            function show_id() {
                $.post('{{ route('form.user.id') }}', function(r) {
                    $('#id_staff').val(r[0].id_staff);
                });
            }

            function show_group() {
                $.post('{{ route('form.user.group') }}', function(r) {
                    var roleHtml = '<option value="">--User\'s Role--</option>';
                    $.each(r, function(index, key) {
                        roleHtml += `<option value="${key.id_Group}">${key.nama_Group}</option>`;
                    });
                    $('#role').html(roleHtml);
                });
            }

            show_id();
            show_group();


            $('#formTambah').on('submit', function(e) {
                e.preventDefault();
                var data = $(this).serialize();
                $.post('{{ route('form.user.insert') }}', data, function(r) {
                    if (r.code == 200) {
                        swalWithLightButton.fire('Success', r.message, 'success');
                        $('#formTambah')[0].reset();
                        show_id();
                        reloadTable();
                    } else {
                        swalWithLightButton.fire('Error', r.message, 'error');
                    }
                }).fail(function(xhr) {
                    swalWithLightButton.fire('Error', 'An error occurred.', 'error');
                });
            });

            $('#user-table tbody').on('click', '.item_change', function() {
                var id = $(this).data('id');
                $.post('{{ route('form.user.show') }}', {
                    id: id
                }, function(r) {
                    $('#e_id').val(r[0].id_Staff);
                    $('#e_email').val(r[0].email_Staff);
                    $('#e_password').val('');
                    $('#modalChange').modal('show');
                });
            });

            $('#btn_change').on('click', function() {
                var data = $('#formChange').serialize();
                $.post('{{ route('form.user.change') }}', data, function(r) {
                    $('#modalChange').modal('hide');
                    swalWithLightButton.fire('Success', r.message, 'success');
                    reloadTable();
                }).fail(function() {
                    swalWithLightButton.fire('Error', 'Update failed.', 'error');
                });
            });

            $('#user-table tbody').on('click', '.item_edit', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                $('#d_id').val(id);
                $.post('{{ route('form.user.default') }}', {
                    id: id,
                    nama: nama
                }, function(r) {
                    var locationHtml = '<option value="">--Location Default--</option>';
                    $.each(r, function(index, key) {
                        locationHtml +=
                            `<option value='${key.id_Lokasi},${key.nama_Lokasi}'>${key.nama_Lokasi}</option>`;
                    });
                    $('#e_location').html(locationHtml);
                    $('#modalEdit').modal('show');
                });
            });

            $('#btn_edit').on('click', function() {
                var data = $('#formEdit').serialize();
                $.post('{{ route('form.user.edit') }}', data, function(r) {
                    $('#modalEdit').modal('hide');
                    swalWithLightButton.fire('Success', r.message, 'success');
                    reloadTable();
                }).fail(function() {
                    swalWithLightButton.fire('Error', 'Update failed.', 'error');
                });
            });

            $('#user-table tbody').on('click', '.item_hapus', function() {
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
                        $.post('{{ route('form.user.delete') }}', {
                            id: id
                        }, function(r) {
                            if (r.code == 200) {
                                swalWithLightButton.fire('Deleted!',
                                    'User has been deleted.', 'success');
                                reloadTable();
                            } else {
                                swalWithLightButton.fire('Error', 'Failed to delete user.',
                                    'error');
                            }
                        });
                    }
                });
            });

            $("#role").change(function() {
                var role = $(this).val();
                if (!role) {
                    $('#location').html('<option value="">--Location Default--</option>');
                    $('#ip').val('');
                    $('#system').val('');
                    return;
                }
                $('#location').html('<option value="">--Loading...--</option>');
                $.post('{{ route('form.user.role') }}', {
                    role: role
                }, function(data) {
                    var locationHtml = '<option value="">--Location Default--</option>';
                    $.each(data, function(index, key) {
                        locationHtml +=
                            `<option value='${key.id_Lokasi},${key.nama_Lokasi}'>${key.nama_Lokasi}</option>`;
                    });
                    $('#location').html(locationHtml);
                });
            });

            function fetchIpAndSystem(locationId, ipField, systemField) {
                if (!locationId) {
                    $(ipField).val('');
                    $(systemField).val('');
                    return;
                }
                var id = locationId.split(',')[0];
                $.post('{{ route('form.user.ip') }}', {
                    id: id
                }, function(data) {
                    if (data && data[0]) {
                        $(ipField).val(data[0].ip_Lokasi);
                        $(systemField).val(data[0].system_code);
                    }
                });
            }

            $("#location").change(function() {
                fetchIpAndSystem($(this).val(), '#ip', '#system');
            });

            $("#e_location").change(function() {
                fetchIpAndSystem($(this).val(), '#e_ip', '#e_system');
            });
        });
    </script>
@endsection
