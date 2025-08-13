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


    {{-- DataTables CSS --}}


    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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

        .form-control,
        .form-select {
            background-color: #ffffff;
            color: #212529;
            border: 1px solid #ced4da;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #ffffff;
            color: #212529;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

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

        .modal-footer {
            border-top: 1px solid #dee2e6;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: #212529 !important;
        }

        .dataTables_wrapper .form-control {
            background-color: #ffffff;
            color: #212529;
        }

        .page-item.disabled .page-link {
            background-color: #f8f9fa;
            border-color: #dee2e6;
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
    </style>

    <style>
        .mode-gelap body {
            background-color: #121212;
            color: #e0e0e0;
        }

        .mode-gelap .card {
            background-color: #192e50 !important;
            border: #333 1px solid !important;
            color: #ffffff !important;
        }

        .mode-gelap .card-header {
            background-color: #070d17 !important;
            color: #ffffff;
            border-bottom: 1px solid #333;
        }

        .mode-gelap label {
            color: #e0e0e0;
        }

        .mode-gelap .form-control,
        .mode-gelap .form-select {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #444;
        }

        .mode-gelap .form-control:focus,
        .mode-gelap .form-select:focus {
            background-color: #ffffff;
            color: #000000;
            border-color: #66afe9;
            box-shadow: 0 0 0 0.2rem rgba(102, 175, 233, .25);
        }

        /* Modal */
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

        .mode-gelap .modal-footer {
            border-top: 1px solid #333;
        }

        /* DataTables */
        .mode-gelap .dataTables_wrapper .dataTables_length,
        .mode-gelap .dataTables_wrapper .dataTables_filter,
        .mode-gelap .dataTables_wrapper .dataTables_info,
        .mode-gelap .dataTables_wrapper .dataTables_paginate {
            color: #e0e0e0 !important;
        }

        .mode-gelap .dataTables_wrapper .form-control {
            background-color: #1e1e1e;
            color: #ffffff;
        }

        /* Pagination */
        .mode-gelap .page-item.disabled .page-link {
            background-color: #1a1a1a;
            border-color: #333;
            color: #666;
        }

        .mode-gelap .page-item.active .page-link {
            background-color: #66afe9;
            border-color: #66afe9;
        }

        .mode-gelap .page-link {
            background-color: #1e1e1e;
            border: 1px solid #333;
            color: #ffffff;
        }
    </style>


    <div class="container-fluid mt-4">
        <div class="card shadow-lg">
            <div class="card-header">
                <h4 class="mb-0">Form Group Location</h4>
            </div>
            <div class="card-body">
                <div class="row align-items-end mb-4">
                    <div class="col-md-4">
                        <label for="group" class="form-label">Group User</label>
                        <select name="group" id="group" class="form-select">
                            <option value="0">--Select Group--</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button id="add-location-btn" type="button" class="btn btn-primary">Add Location</button>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#groupManagementModal">Manage Group</button>
                    </div>
                </div>

                <table id="group-location-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th>Location Name</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan dimuat oleh DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal: Add Location -->
    <div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addLocationForm">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title mb-0" id="addLocationModalLabel">Add Data Location</h5>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">Add Selected Locations</button>
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="group" id="selected-group-for-add">
                        <table id="available-locations-table" class="table table-striped table-bordered"
                            style="width:100%;">
                            <thead>
                                <tr>
                                    <th width="10%">Selected</th>
                                    <th>Location Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan dimuat via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal: Group Management -->
    <div class="modal fade" id="groupManagementModal" tabindex="-1" aria-labelledby="groupManagementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupManagementModalLabel">List Group</h5>
                    <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addGroupModal">Add Group</button>
                </div>
                <div class="modal-body">
                    <table id="group-table" class="table table-striped table-bordered" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Group Name</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data dimuat oleh DataTables -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Add Group -->
    <div class="modal fade" id="addGroupModal" tabindex="-1" aria-labelledby="addGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addGroupForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addGroupModalLabel">Add Data Group</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama-group" class="form-label">Group Name</label>
                            <input type="text" id="nama-group" name="nama_Group" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">List Menu</label>
                            <div id="add-menu-list-container" class="row">
                                <!-- Menu checkboxes will be loaded here -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Group -->
    <div class="modal fade" id="editGroupModal" tabindex="-1" aria-labelledby="editGroupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editGroupForm">
                    <input type="hidden" id="edit-id-group" name="id_Group">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editGroupModalLabel">Edit Data Group</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-nama-group" class="form-label">Group Name</label>
                            <input type="text" id="edit-nama-group" name="nama_Group" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">List Menu</label>
                            <div id="edit-menu-list-container" class="row">
                                <!-- Menu checkboxes will be loaded here -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Bootstrap 5.3 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            // Customizing SweetAlert2 for light mode
            const swalWithLightButton = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            // Setup CSRF token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi DataTables untuk tabel utama
            let groupLocationTable = $('#group-location-table').DataTable({
                processing: true,
                serverSide: false, // Kita akan handle data di client-side
                ajax: {
                    url: "{{ route('config.getGroupLocations') }}",
                    type: 'POST',
                    data: function(d) {
                        d.id_Group = $('#group').val();
                    },
                    dataSrc: '' // Response adalah array langsung
                },
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nama_Lokasi'
                    },
                    {
                        data: 'id_Glokasi',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            return `<button class="btn btn-danger btn-sm delete-location" data-id="${data}">Delete</button>`;
                        }
                    }
                ],
                drawCallback: function(settings) {
                    // Sembunyikan tabel jika tidak ada grup yang dipilih
                    var groupVal = $('#group').val();
                    if (!groupVal || groupVal === "0") {
                        $('#group-location-table_wrapper').hide();
                    } else {
                        $('#group-location-table_wrapper').show();
                    }
                }
            });

            // Inisialisasi DataTable untuk modal dan lainnya
            let availableLocationsTable = $('#available-locations-table').DataTable({
                paging: false,
                searching: true,
                info: false
            });
            let groupTable = $('#group-table').DataTable({
                paging: true,
                searching: true
            });

            // Fungsi untuk memuat dropdown group
            function loadGroupsDropdown() {
                $.ajax({
                    url: "{{ route('config.getGroups') }}",
                    type: 'GET',
                    success: function(data) {
                        let groupSelect = $('#group');
                        groupSelect.empty().append('<option value="0">--Select Group--</option>');
                        data.forEach(function(group) {
                            groupSelect.append(
                                `<option value="${group.id_Group}">${group.nama_Group}</option>`
                            );
                        });
                    }
                });
            }

            // Panggil fungsi saat halaman dimuat
            loadGroupsDropdown();

            // Event handler saat dropdown group berubah
            $('#group').on('change', function() {
                let groupId = $(this).val();
                if (groupId && groupId !== "0") {
                    $('#group-location-table_wrapper').show();
                    groupLocationTable.ajax.reload();
                } else {
                    groupLocationTable.clear().draw();
                    $('#group-location-table_wrapper').hide();
                }
            });

            // Event handler untuk tombol 'Add Location'
            $('#add-location-btn').on('click', function() {
                let selectedGroup = $('#group').val();
                if (!selectedGroup || selectedGroup === "0") {
                    swalWithLightButton.fire('Warning', 'Please select a group before adding locations.',
                        'warning');
                    return;
                }

                $('#selected-group-for-add').val(selectedGroup);

                // Muat lokasi yang tersedia
                $.ajax({
                    url: "{{ route('config.getAvailableLocations') }}",
                    type: 'POST',
                    data: {
                        selectedGroup: selectedGroup
                    },
                    success: function(data) {
                        availableLocationsTable.clear();
                        data.forEach(function(loc) {
                            availableLocationsTable.row.add([
                                `<input type="checkbox" name="location[]" value="${loc.id_Lokasi}">`,
                                loc.nama_Lokasi
                            ]).draw();
                        });
                        $('#addLocationModal').modal('show');
                    }
                });
            });

            // Event handler untuk submit form tambah lokasi
            $('#addLocationForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('config.storeMultipleLocations') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#addLocationModal').modal('hide');
                        swalWithLightButton.fire('Success', response.message, 'success');
                        groupLocationTable.ajax.reload();
                    },
                    error: function(xhr) {
                        swalWithLightButton.fire('Error', xhr.responseJSON.message, 'error');
                    }
                });
            });

            // Event handler untuk menghapus lokasi dari grup
            $('#group-location-table tbody').on('click', '.delete-location', function() {
                let id = $(this).data('id');
                let url = `{{ url('config/group-locations') }}/${id}`;

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
                            url: url,
                            type: 'DELETE',
                            success: function(response) {
                                swalWithLightButton.fire('Deleted!',
                                    'The location has been removed from the group.',
                                    'success');
                                groupLocationTable.ajax.reload();
                            },
                            error: function(xhr) {
                                swalWithLightButton.fire('Error!',
                                    'Failed to delete location.',
                                    'error');
                            }
                        });
                    }
                });
            });

            // Logika untuk Modal "Manage Group"
            $('#groupManagementModal').on('shown.bs.modal', function() {
                $.ajax({
                    url: '{{ route('config.getAllGroups') }}',
                    type: 'GET',
                    success: function(data) {
                        groupTable.clear();
                        data.forEach(function(group) {
                            groupTable.row.add([
                                group.nama_Group,
                                `<button class="btn btn-info btn-sm edit-group" data-id="${group.id_Group}">Edit</button>
                         <button class="btn btn-danger btn-sm delete-group" data-id="${group.id_Group}">Delete</button>`
                            ]).draw();
                        });
                    }
                });
            });

            // Fungsi untuk memuat menu checkbox
            function loadMenus(containerId, selectedMenus = []) {
                let menuContainer = $(`#${containerId}`);
                menuContainer.empty();
                $.ajax({
                    url: '{{ route('config.getAllMenus') }}',
                    type: 'GET',
                    success: function(menus) {
                        const defaultMenus = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
                        menus.forEach(function(menu) {
                            const isChecked = selectedMenus.includes(menu.id_Menu) ||
                                defaultMenus.includes(menu.id_Menu);
                            const isDisabled = defaultMenus.includes(menu.id_Menu);
                            menuContainer.append(`
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="nama_Menu[]" value="${menu.id_Menu}" id="menu_${containerId}_${menu.id_Menu}" ${isChecked ? 'checked' : ''} ${isDisabled ? 'disabled' : ''}>
                                <label class="form-check-label" for="menu_${containerId}_${menu.id_Menu}">
                                    ${menu.nama_Menu}
                                </label>
                            </div>
                        </div>
                    `);
                        });
                    }
                });
            }

            // Saat modal tambah grup ditampilkan, muat daftar menu
            $('#addGroupModal').on('show.bs.modal', function() {
                loadMenus('add-menu-list-container');
            });

            // Submit form tambah grup
            $('#addGroupForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: '{{ route('config.storeGroup') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#addGroupModal').modal('hide');
                        swalWithLightButton.fire('Success', response.message, 'success');
                        groupTable.ajax.reload(); // Refresh tabel grup
                        loadGroupsDropdown(); // Refresh dropdown
                    },
                    error: function(xhr) {
                        swalWithLightButton.fire('Error', xhr.responseJSON.message, 'error');
                    }
                });
            });

            // Klik tombol edit grup
            $('#group-table tbody').on('click', '.edit-group', function() {
                let id = $(this).data('id');
                let url = `{{ url('config/groups') }}/${id}/edit`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#edit-id-group').val(data.group.id_Group);
                        $('#edit-nama-group').val(data.group.nama_Group);
                        loadMenus('edit-menu-list-container', data.menus);
                        $('#editGroupModal').modal('show');
                    }
                });
            });

            // Submit form edit grup
            $('#editGroupForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#edit-id-group').val();
                let url = `{{ url('config/groups') }}/${id}`;
                let formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#editGroupModal').modal('hide');
                        swalWithLightButton.fire('Success', response.message, 'success');
                        // Refresh data
                        groupTable.ajax.reload(null, false);
                        loadGroupsDropdown();
                    },
                    error: function(xhr) {
                        swalWithLightButton.fire('Error', xhr.responseJSON.message, 'error');
                    }
                });
            });

            // Klik tombol hapus grup
            $('#group-table tbody').on('click', '.delete-group', function() {
                let id = $(this).data('id');
                let url = `{{ url('config/groups') }}/${id}`;

                swalWithLightButton.fire({
                    title: 'Are you sure to delete this group?',
                    text: "This will delete the group and all its location & menu associations!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(response) {
                                swalWithLightButton.fire('Deleted!', response.message,
                                    'success');
                                groupTable.ajax.reload(null, false);
                                loadGroupsDropdown();
                            },
                            error: function(xhr) {
                                swalWithLightButton.fire('Error!', xhr.responseJSON
                                    .message, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
