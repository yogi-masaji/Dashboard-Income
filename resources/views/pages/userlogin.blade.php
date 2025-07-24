@extends('layout.nav')

@section('content')
    @php
        $navbarTitle = session('selected_location_name', 'User Login History');
    @endphp

    {{-- Menambahkan beberapa style kustom dan Bootstrap Icons --}}
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
        <style>
            body {
                background-color: #0A1525;
                /* New Body Color */
                color: #ffffff;
                /* Default text color */
            }

            #card_header {
                background-color: #082142;
                /* Darker header */
                color: white;
                border-bottom: 1px solid #10356A;
            }

            .card-body.text-light {
                background-color: #092953;
                /* Main card body color */
            }


            .user-detail {
                border-radius: 0.25rem;
                min-height: 250px;
            }

            .list-cu-login text-white {
                border: 1px solid #1a4a8a;
                /* Adjusted border */
            }

            table.dataTable {
                color: white;
            }

            table.dataTable thead th {
                color: #ffc107;
                /* Gold header text */
            }

            /* Darker rows for striping effect */
            table.dataTable.table-striped tbody tr:nth-of-type(odd) {
                background-color: #0f3161;
            }

            table.dataTable.table-striped tbody tr:nth-of-type(even) {
                background-color: #10356A;
            }

            table.dataTable tbody tr:hover {
                background-color: #154282;
                /* Row hover background */
            }

            /* Allow table cell content to wrap */
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
                color: white !important;
            }

            .dataTables_wrapper .dataTables_length select {
                background-color: #10356A;
                color: white;
                border: 1px solid #1a4a8a;
            }

            .dataTables_wrapper .dataTables_filter input {
                background-color: #10356A;
                color: white;
                border: 1px solid #1a4a8a;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
                color: #ffffff !important;
            }

            .modal-content {
                background-color: #092953;
                border: 1px solid #ffc107;
            }

            .bi-circle-fill.text-success {
                color: #28a745 !important;
            }

            .bi-circle-fill.text-danger {
                color: #dc3545 !important;
            }
        </style>
    @endpush

    <div class="container-fluid px-0">
        <div class="card no-body h-100" style="background-color: #092953;">
            <!-- Card Header -->
            <div class="d-flex justify-content-between px-3 py-2 align-items-center" id="card_header">
                <div class="d-flex align-items-center">
                    <i class="bi bi-people-fill fa-lg me-2 text-white"></i>
                    <h4 class="header text-white m-0">User Login</h4>
                </div>
                <p class="card-text font-weight-light font-italic text-white m-0">
                    {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            </div>

            <div class="card-body text-light">
                <!-- Summary Cards -->
                <div class="row g-4">
                    <!-- Kartu 1: Total Pengguna -->
                    <div class="col-xl-3 col-lg-6">
                        <div class="card" style="height: 100%;">
                            <div class="card-body d-flex align-items-center">
                                <div
                                    style="background-color: #0d6efd; border-radius: 50%; padding: 10px; display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; flex-shrink: 0;">
                                    <i class="bi bi-people-fill" style="font-size: 1.75rem; color: #cfe2ff;"></i>
                                </div>
                                <div style="margin-left: 12px;">
                                    <h3 style="font-weight: bold; margin-bottom: 0; color: #212529;">{{ $totalUser }}
                                    </h3>
                                    <p style="margin-bottom: 0; color: #212529;">Total Pengguna</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu 2: Pengguna Online -->
                    <div class="col-xl-3 col-lg-6">
                        <div class="card" style="height: 100%;">
                            <div class="card-body d-flex align-items-center">
                                <div
                                    style="background-color: #198754; border-radius: 50%; padding: 10px; display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; flex-shrink: 0;">
                                    <i class="bi bi-wifi" style="font-size: 1.75rem; color: #d1e7dd;"></i>
                                </div>
                                <div style="margin-left: 12px;">
                                    <h3 style="font-weight: bold; margin-bottom: 0; color: #212529;">{{ $onlineUser }}
                                    </h3>
                                    <p style="margin-bottom: 0; color: #212529;">Pengguna Online</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu 3: Pengguna Offline -->
                    <div class="col-xl-3 col-lg-6">
                        <div class="card" style="height: 100%;">
                            <div class="card-body d-flex align-items-center">
                                <div
                                    style="background-color: #ffc107; border-radius: 50%; padding: 10px; display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; flex-shrink: 0;">
                                    <i class="bi bi-wifi-off" style="font-size: 1.75rem; color: #fff3cd;"></i>
                                </div>
                                <div style="margin-left: 12px;">
                                    <h3 style="font-weight: bold; margin-bottom: 0; color: #212529;">{{ $offlineUser }}
                                    </h3>
                                    <p style="margin-bottom: 0; color: #212529;">Pengguna Offline</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu 4: Pengguna Terdaftar -->
                    <div class="col-xl-3 col-lg-6">
                        <div class="card" style="height: 100%;">
                            <div class="card-body d-flex align-items-center">
                                <div
                                    style="background-color: #dc3545; border-radius: 50%; padding: 10px; display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; flex-shrink: 0;">
                                    <i class="bi bi-person-fill-add" style="font-size: 1.75rem; color: #f8d7da;"></i>
                                </div>
                                <div style="margin-left: 12px;">
                                    <h3 style="font-weight: bold; margin-bottom: 0; color: #212529;">{{ $registeredUser }}
                                    </h3>
                                    <p style="margin-bottom: 0; color: #212529;">Pengguna Terdaftar</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Main Content -->
                <div class="row mt-4">
                    <!-- User List Table -->
                    <div class="col-lg-9">
                        <div class="text-center border-bottom border-top mb-2 p-3"
                            style="color: #ffffff; border-color: #ffffff !important;">USER LIST</div>
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
                                                <img src="{{ asset('img/photos/' . $user->foto_profile) }}" alt="Profile"
                                                    style="border-radius: 50%" width="40" height="40">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama_Staff) }}&background=5b00bd&color=fff"
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
                                                <button type="button" class="btn btn-sm btn-success text-dark change-group"
                                                    data-toggle="modal" data-target="#changeGroupModal"
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
                                            <a href="#" class="text-info item-detail"
                                                data-id="{{ $user->id_Staff }}" data-name="{{ $user->nama_Staff }}"
                                                data-photo="{{ $user->foto_profile }}"
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
                        <div class="row" style="background-color: #082142; padding: 10px;border-radius: 0.25rem;">
                            <div class="col-12 mb-3">
                                <div class="user-detail p-3">
                                    @php $firstUser = $userlog_list->first(); @endphp
                                    <div class="text-center" id="detailPhoto">
                                        @if ($firstUser?->foto_profile)
                                            <img src="{{ asset('img/photos/' . $firstUser->foto_profile) }}"
                                                alt="Profile" style="border-radius: 50%" width="80">
                                        @elseif($firstUser)
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($firstUser->nama_Staff) }}&background=5b00bd&color=fff"
                                                style="border-radius: 50%;" width="80">
                                        @else
                                            <div
                                                style="width: 80px; height: 80px; background-color: #ffffff; border-radius: 50%; margin: auto;">
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
                                                <span class="text-secondary">{{ $firstUser->email_Staff }}</span> |
                                                {{ $firstUser->user_type_name }}
                                            @endif
                                        </div>
                                        <div class="mt-2 text-light" id="detailDefaultLocation">
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
                                                <i
                                                    class="bi bi-clock-history h3 text-white
                                                "></i><br>
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
                                    <li class="list-group-item d-flex justify-content-between align-items-center list-cu-login text-white text-center"
                                        style="background-color: #082142; color: #FFC107;">
                                        <span class="h6 text-white"><b>TOTAL LOGIN THIS YEAR</b></span>
                                    </li>
                                    @forelse($totalLoginMonth as $loginData)
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center list-cu-login text-white text-white">
                                            <p class="text-white">{{ $loginData->period }}</p>
                                            <span
                                                class="badge badge-primary badge-pill">{{ $loginData->total_login }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item list-cu-login text-white">No login data this year.</li>
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
            <div class="modal-content text-light">
                <form action="{{ route('user.login.updateGroup') }}" method="POST">
                    @csrf
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title" id="changeGroupModalTitle"><b>Change Group</b></h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="idStaff" id="modalIdStaff">
                        <select class="form-control" name="groupUser" id="idSelected" required
                            style="background-color: #10356A; color: white; border-color: #ffc107;">
                            <option value="">-- Select User Group --</option>
                            @foreach ($listGroupUser as $group)
                                <option value="{{ $group->id_Group }}">{{ $group->nama_Group }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
                "responsive": false, // Disable responsive to prevent table modifications that can interfere
                "scrollX": true, // Allow horizontal scroll if needed
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
                e.preventDefault(); // Prevent default anchor behavior

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
                $('#detailEmailandType').html('<span class="text-secondary">' + email + '</span> | ' +
                    userType);
                $('#detailDefaultLocation').html('<i class="bi bi-geo-alt-fill text-warning"></i> ' +
                    default_location);
                $('#detailLastLogin').text(last_login);

                var registrationHtml = (status_register == 1) ?
                    '<i class="bi bi-person-check-fill h3 text-success"></i><br><small>Registered</small>' :
                    '<i class="bi bi-person-dash-fill h3 text-secondary"></i><br><small>Unregistered</small>';
                $('#detailActivated').html(registrationHtml);

                var photoUrl = photo ?
                    '{{ asset('img/photos/') }}/' + photo :
                    'https://ui-avatars.com/api/?name=' + encodeURIComponent(name) +
                    '&background=5b00bd&color=fff';
                $('#detailPhoto').html('<img src="' + photoUrl +
                    '" alt="Profile" style="border-radius: 50%" width="80">');

                // Update Total Login History list
                var loginList = $('#totalLoginList');
                var url = "{{ route('user.login.getHistory', ['id' => ':id']) }}".replace(':id', id);

                // Show loading state
                loginList.find("li:not(:first)").remove();
                loginList.append(
                    '<li class="list-group-item list-cu-login text-white text-center"><div class="spinner-border spinner-border-sm" role="status"></div></li>'
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
                                    '<li class="list-group-item d-flex justify-content-between align-items-center list-cu-login text-white">' +
                                    item.period +
                                    '<span class="badge badge-primary badge-pill">' +
                                    item.total_login + '</span>' +
                                    '</li>';
                                loginList.append(listItem);
                            });
                        } else {
                            loginList.append(
                                '<li class="list-group-item list-cu-login text-white">No login data this year.</li>'
                            );
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        loginList.find("li:not(:first)").remove();
                        loginList.append(
                            '<li class="list-group-item list-cu-login text-white text-danger">Failed to load data.</li>'
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
