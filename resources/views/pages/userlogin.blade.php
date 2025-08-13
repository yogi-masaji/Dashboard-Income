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


    {{-- Menambahkan beberapa style kustom dan Bootstrap Icons --}}
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
        <style>
            /* ------------------------- */
            /* --- LIGHT MODE STYLES --- */
            /* ------------------------- */
            body {
                background-color: #f8f9fa;
                color: #212529;
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            .card,
            .list-group-item,
            .modal-content {
                transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            }

            .card {
                background-color: #ffffff !important;
                border: #d9d9d9 1px solid !important;
                border-radius: 10px !important;
                color: #000 !important;
            }

            .card-header,
            #card_header {
                background-color: #f7f7f7 !important;
                color: #000 !important;
                border-bottom: 1px solid #d9d9d9 !important;
            }

            .card-header h4,
            #card_header h4,
            #card_header p,
            #card_header i {
                color: #000 !important;
            }

            .card-body {
                background-color: #ffffff !important;
                color: #000 !important;
            }


            .user-detail-panel {
                background-color: #f7f7f7;
                padding: 15px;
                border-radius: 0.5rem;
                border: 1px solid #d9d9d9 !important;
            }

            .list-group-item {
                background-color: #ffffff;
                color: #212529;
                border: 1px solid #d9d9d9;
            }

            .list-group-item.header {
                background-color: #f7f7f7;
                color: #000;
                font-weight: bold;
            }

            table.dataTable {
                color: #212529;
            }

            table.dataTable thead th {
                color: #212529;
            }

            table.dataTable.table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, .05);
            }

            table.dataTable td {
                white-space: normal !important;
            }

            .email-cell {
                word-break: break-all;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                color: #212529 !important;
            }

            .dataTables_wrapper .dataTables_length select,
            .dataTables_wrapper .dataTables_filter input {
                background-color: #ffffff;
                color: #212529;
                border: 1px solid #ced4da;
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

            .close {
                color: #000;
                text-shadow: 0 1px 0 #fff;
            }

            .summary-icon-circle {
                border-radius: 50%;
                padding: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 50px;
                height: 50px;
                flex-shrink: 0;
            }

            .summary-icon-circle.bg-primary-light {
                background-color: #cfe2ff;
            }

            .summary-icon-circle.bg-success-light {
                background-color: #d1e7dd;
            }

            .summary-icon-circle.bg-warning-light {
                background-color: #fff3cd;
            }


            /* ------------------------ */
            /* --- DARK MODE STYLES --- */
            /* ------------------------ */

            body.mode-gelap .card {
                background-color: #192e50 !important;
                border-color: #424242 !important;
                color: #ffffff !important;
            }

            body.mode-gelap .card-header,
            body.mode-gelap #card_header {
                background-color: #121E32 !important;
                color: #ffffff !important;
                border-bottom: 1px solid #424242 !important;
            }

            body.mode-gelap .card-header h4,
            body.mode-gelap #card_header h4,
            body.mode-gelap #card_header p,
            body.mode-gelap #card_header i,
            body.mode-gelap p,
            body.mode-gelap h3,
            body.mode-gelap h5 {
                color: #ffffff !important;
            }

            body.mode-gelap .card-body {
                background-color: #192e50 !important;
                color: #ffffff !important;
            }

            body.mode-gelap .user-detail-panel {
                background-color: #121E32;
                border: 1px solid #424242 !important;
            }

            body.mode-gelap .user-detail-panel .small {
                color: #adb5bd !important;
            }

            body.mode-gelap .list-group-item {
                background-color: #192e50;
                color: #ffffff;
                border-color: #424242;
            }

            body.mode-gelap .list-group-item.header {
                background-color: #121E32;
            }

            body.mode-gelap .text-center.border-bottom.border-top {
                border-color: #424242 !important;
            }

            body.mode-gelap .dataTables_wrapper .dataTables_length,
            body.mode-gelap .dataTables_wrapper .dataTables_filter,
            body.mode-gelap .dataTables_wrapper .dataTables_info,
            body.mode-gelap .dataTables_wrapper .dataTables_paginate .paginate_button {
                color: #ffffff !important;
            }

            body.mode-gelap .dataTables_wrapper .dataTables_length select,
            body.mode-gelap .dataTables_wrapper .dataTables_filter input {
                background-color: #212121;
                color: #ffffff;
                border: 1px solid #424242;
            }

            body.mode-gelap .page-link {
                background-color: #212121;
                border: 1px solid #424242;
                color: #ffffff;
            }

            body.mode-gelap .page-item.disabled .page-link {
                background-color: #2a2a2a;
                border-color: #424242;
                color: #6c757d;
            }

            body.mode-gelap .page-item.active .page-link {
                background-color: #ffc107;
                border-color: #ffc107;
                color: #000;
            }

            body.mode-gelap .modal-content {
                background-color: #192e50;
                color: #ffffff;
            }

            body.mode-gelap .modal-header {
                background-color: #121E32;
                border-bottom: 1px solid #424242;
            }

            body.mode-gelap .modal-header .close {
                color: #fff;
                text-shadow: none;
            }

            body.mode-gelap .modal-footer {
                border-top: 1px solid #424242;
            }

            body.mode-gelap .summary-icon-circle.bg-primary-light {
                background-color: rgba(13, 110, 253, 0.25);
            }

            body.mode-gelap .summary-icon-circle.bg-success-light {
                background-color: rgba(25, 135, 84, 0.25);
            }

            body.mode-gelap .summary-icon-circle.bg-warning-light {
                background-color: rgba(255, 193, 7, 0.25);
            }

            body.mode-gelap .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_processing,
            .dataTables_wrapper .dataTables_paginate {
                color: #ffffff !important;
            }
        </style>
    @endpush

    <div class="mt-4">
        <div class="card shadow-lg content-custom">
            <!-- Card Header -->
            <div class="d-flex justify-content-between px-3 py-2 align-items-center" id="card_header">
                <div class="d-flex align-items-center">
                    <i class="bi bi-people-fill fa-lg me-2"></i>
                    <h4 class="header m-0">User Login</h4>
                </div>
            </div>

            <div class="card-body">
                <!-- Summary Cards -->
                <div class="row g-4">
                    <!-- Kartu 1: Total Pengguna -->
                    <div class="col-xl-4 col-lg-6">
                        <div class="card shadow-sm" style="height: 100%;">
                            <div class="card-body d-flex align-items-center">
                                <div class="summary-icon-circle bg-primary-light">
                                    <i class="bi bi-people-fill" style="font-size: 1.75rem; color: #0d6efd;"></i>
                                </div>
                                <div style="margin-left: 12px;">
                                    <h3 style="font-weight: bold; margin-bottom: 0; color:#000 !important;">
                                        {{ $totalUser }}
                                    </h3>
                                    <p class="mb-0" style="color:#000 !important;">Total Pengguna</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu 2: Pengguna Online -->
                    <div class="col-xl-4 col-lg-6">
                        <div class="card shadow-sm" style="height: 100%;">
                            <div class="card-body d-flex align-items-center">
                                <div class="summary-icon-circle bg-success-light">
                                    <i class="bi bi-wifi" style="font-size: 1.75rem; color: #198754;"></i>
                                </div>
                                <div style="margin-left: 12px;">
                                    <h3 style="font-weight: bold; margin-bottom: 0;color:#000 !important;">
                                        {{ $onlineUser }}
                                    </h3>
                                    <p class="mb-0" style="color:#000 !important;">Pengguna Online</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu 3: Pengguna Offline -->
                    <div class="col-xl-4 col-lg-6">
                        <div class="card shadow-sm" style="height: 100%;">
                            <div class="card-body d-flex align-items-center">
                                <div class="summary-icon-circle bg-warning-light">
                                    <i class="bi bi-wifi-off" style="font-size: 1.75rem; color: #ffc107;"></i>
                                </div>
                                <div style="margin-left: 12px;">
                                    <h3 style="font-weight: bold; margin-bottom: 0;color:#000 !important;">
                                        {{ $offlineUser }}
                                    </h3>
                                    <p class="mb-0" style="color:#000 !important;">Pengguna Offline</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Main Content -->
                <div class="row mt-4">
                    <!-- User List Table -->
                    <div class="col-lg-9">
                        <div class="text-center border-bottom border-top mb-2 p-3">USER LIST</div>
                        <table id="tbUserlist" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Name</th>
                                    <th class="text-center">Status Login</th>
                                    <th class="text-center">Login This Month</th>
                                    <th>Email</th>
                                    @if (session('user_type') == 'IT')
                                        <th class="text-center">User Group</th>
                                    @endif
                                    <th class="text-center">Status Active</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="show_data">
                                @foreach ($userlog_list as $user)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($user->foto_profile)
                                                <img src="{{ asset('storage/photos/' . $user->foto_profile) }}"
                                                    alt="Profile" style="border-radius: 50%" width="40" height="40">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama_Staff) }}&background=0d6efd&color=fff"
                                                    style="border-radius: 50%;" width="40">
                                            @endif
                                            &nbsp; {{ $user->nama_Staff }}
                                        </td>
                                        <td class="text-center align-middle">
                                            @if ($user->status_login == 1)
                                                <i class="bi bi-circle-fill text-success" title="Online"></i> <span
                                                    class="d-none d-md-inline">Online</span>
                                            @else
                                                <i class="bi bi-circle-fill text-danger" title="Offline"></i> <span
                                                    class="d-none d-md-inline">Offline</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">{{ $user->total_login }}</td>
                                        <td class="align-middle email-cell">{{ $user->email_Staff }}</td>
                                        @if (session('user_type') == 'IT')
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-info text-dark change-group"
                                                    data-bs-toggle="modal" data-bs-target="#changeGroupModal"
                                                    data-id="{{ $user->id_Staff }}" data-group="{{ $user->id_Group }}">
                                                    {{ $user->nama_Group }}
                                                </button>
                                            </td>
                                        @endif
                                        <td class="text-center align-middle">
                                            @if ($user->id_Registrant == 1)
                                                <i class="bi bi-person-check-fill text-success" title="Registered"></i>
                                                <span class="d-none d-md-inline">Registered</span>
                                            @else
                                                <i class="bi bi-person-dash-fill text-secondary" title="Unregistered"></i>
                                                <span class="d-none d-md-inline">Unregistered</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="#" class="text-info item-detail" data-id="{{ $user->id_Staff }}"
                                                data-name="{{ $user->nama_Staff }}" data-photo="{{ $user->foto_profile }}"
                                                data-status_login="{{ $user->status_login }}"
                                                data-email="{{ $user->email_Staff }}"
                                                data-usertype="{{ $user->user_type_name }}"
                                                data-last_login="{{ \Carbon\Carbon::parse($user->last_login)->diffForHumans() }}"
                                                data-default_location="{{ $user->default_location }}"
                                                data-status_register="{{ $user->id_Registrant }}">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- User Detail Panel -->
                    <div class="col-lg-3">
                        <div class="user-detail-panel">
                            <div class="col-12 mb-3">
                                <div class="user-detail p-3">
                                    @php $firstUser = $userlog_list->first(); @endphp
                                    <div class="text-center" id="detailPhoto">
                                        @if ($firstUser?->foto_profile)
                                            <img src="{{ asset('img/photos/' . $firstUser->foto_profile) }}"
                                                alt="Profile" style="border-radius: 50%" width="80">
                                        @elseif($firstUser)
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($firstUser->nama_Staff) }}&background=0d6efd&color=fff"
                                                style="border-radius: 50%;" width="80">
                                        @else
                                            <div
                                                style="width: 80px; height: 80px; background-color: #e9ecef; border-radius: 50%; margin: auto;">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-12 text-center mt-2 mb-4">
                                        <h5 class="mb-1" id="detailNama">
                                            @if ($firstUser)
                                                <b>
                                                    {{ $firstUser->nama_Staff }}
                                                    <i class="bi bi-circle-fill {{ $firstUser->status_login == 1 ? 'text-success' : 'text-danger' }} ml-1"
                                                        style="font-size: 0.7rem;"></i>
                                                </b>
                                            @endif
                                        </h5>
                                        <div id="detailEmailandType" class="small">
                                            @if ($firstUser)
                                                <span>{{ $firstUser->email_Staff }}</span> |
                                                {{ $firstUser->user_type_name }}
                                            @endif
                                        </div>
                                        <div class="mt-2" id="detailDefaultLocation">
                                            @if ($firstUser)
                                                <i class="bi bi-geo-alt-fill text-warning"></i>
                                                {{ $firstUser->default_location }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <div class="row">
                                            <div class="col-6" id="detailActivated">
                                                @if ($firstUser)
                                                    @if ($firstUser->id_Registrant == 1)
                                                        <i
                                                            class="bi bi-person-check-fill h3 text-success"></i><br><small>Registered</small>
                                                    @else
                                                        <i
                                                            class="bi bi-person-dash-fill h3 text-secondary"></i><br><small>Unregistered</small>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <i class="bi bi-clock-history h3 text-success"></i><br>
                                                <small id="detailLastLogin">
                                                    @if ($firstUser)
                                                        {{ \Carbon\Carbon::parse($firstUser->last_login)->diffForHumans() }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <ul class="list-group" id="totalLoginList">
                                    <li class="list-group-item header text-center">
                                        <p>TOTAL LOGIN THIS YEAR</p>
                                    </li>
                                    @forelse($totalLoginMonth as $loginData)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <p class="mb-0">{{ $loginData->period }}</p>
                                            <span
                                                class="badge bg-primary rounded-pill">{{ $loginData->total_login }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item">No login data this year.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="changeGroupModal" tabindex="-1" role="dialog" aria-labelledby="changeGroupModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('user.login.updateGroup') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeGroupModalTitle"><b>Change Group</b></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="idStaff" id="modalIdStaff">
                        <select class="form-control" name="groupUser" id="idSelected" required>
                            <option value="">-- Select User Group --</option>
                            @foreach ($listGroupUser as $group)
                                <option value="{{ $group->id_Group }}">{{ $group->nama_Group }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#tbUserlist').DataTable({
                "responsive": false,
                "scrollX": true,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "language": {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records",
                }
            });

            // Delegated event handler for detail links on table body
            $('#tbUserlist tbody').on('click', 'a.item-detail', function(e) {
                e.preventDefault();

                var clickedLink = $(this);
                var id = clickedLink.data('id');
                var name = clickedLink.data('name');
                var photo = clickedLink.data('photo');
                var status_login = clickedLink.data('status_login');
                var email = clickedLink.data('email');
                var userType = clickedLink.data('usertype');
                var last_login = clickedLink.data('last_login');
                var status_register = clickedLink.data('status_register');
                var default_location = clickedLink.data('default_location');

                // Update User Detail Card
                var statusIcon = (status_login == 1) ?
                    '<i class="bi bi-circle-fill text-success ml-1" style="font-size: 0.7rem;"></i>' :
                    '<i class="bi bi-circle-fill text-danger ml-1" style="font-size: 0.7rem;"></i>';

                $('#detailNama').html('<b>' + name + ' ' + statusIcon + '</b>');
                $('#detailEmailandType').html('<span>' + email + '</span> | ' +
                    userType);
                $('#detailDefaultLocation').html('<i class="bi bi-geo-alt-fill text-warning"></i> ' +
                    default_location);
                $('#detailLastLogin').text(last_login);

                var registrationHtml = (status_register == 1) ?
                    '<i class="bi bi-person-check-fill h3 text-success"></i><br><small>Registered</small>' :
                    '<i class="bi bi-person-dash-fill h3 text-secondary"></i><br><small>Unregistered</small>';
                $('#detailActivated').html(registrationHtml);

                var photoUrl = photo ?
                    '{{ asset('storage/photos/') }}/' + photo :
                    'https://ui-avatars.com/api/?name=' + encodeURIComponent(name) +
                    '&background=0d6efd&color=fff';
                $('#detailPhoto').html('<img src="' + photoUrl +
                    '" alt="Profile" style="border-radius: 50%" width="80">');

                // In dark mode, update the avatar API URL to use a darker background
                if (document.body.classList.contains('mode-gelap') && !photo) {
                    var darkAvatarUrl = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name) +
                        '&background=424242&color=fff';
                    $('#detailPhoto').html('<img src="' + darkAvatarUrl +
                        '" alt="Profile" style="border-radius: 50%" width="80">');
                }


                // Update Total Login History list
                var loginList = $('#totalLoginList');
                var url = "{{ route('user.login.getHistory', ['id' => ':id']) }}".replace(':id', id);

                // Show loading state
                loginList.find("li:not(:first)").remove();
                loginList.append(
                    '<li class="list-group-item text-center"><div class="spinner-border spinner-border-sm" role="status"></div></li>'
                );

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        loginList.find("li:not(:first)").remove(); // Clear loading/old items

                        if (data && data.length > 0) {
                            $.each(data, function(index, item) {
                                var listItem =
                                    '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                                    '<p class="mb-0">' + item.period + '</p>' +
                                    '<span class="badge bg-primary rounded-pill">' +
                                    item.total_login + '</span>' +
                                    '</li>';
                                loginList.append(listItem);
                            });
                        } else {
                            loginList.append(
                                '<li class="list-group-item">No login data this year.</li>'
                            );
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        loginList.find("li:not(:first)").remove();
                        loginList.append(
                            '<li class="list-group-item text-danger">Failed to load data.</li>'
                        );
                    }
                });
            });

            // Delegated event handler for modal on table body
            $('#tbUserlist tbody').on('click', '.change-group', function() {
                var id = $(this).data('id');
                var group = $(this).data('group');
                $('#modalIdStaff').val(id);
                $('#idSelected').val(group);
                $('#changeGroupModal').modal('show');
            });

            // Automatically click the first user detail link to load their info on page load.
            if ($('#tbUserlist tbody a.item-detail').length > 0) {
                // Use a small timeout to ensure DataTables has fully initialized
                setTimeout(function() {
                    $('#tbUserlist tbody a.item-detail').first().trigger('click');
                }, 100);
            }
        });
    </script>
@endpush
