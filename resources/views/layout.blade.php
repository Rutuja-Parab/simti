<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/logo.png" type="image/x-icon">
    <title>@yield('title', 'SIMTI')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.materialdesignicons.com/7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- In your <head> section -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

    <!-- Before the closing </body> tag -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <style>
        .bg-primary {
            background-color: #15284B !important;
        }

        .text-primary {
            color: #15284B !important;
        }

        .btn-primary {
            background-color: #15284B !important;
            border-color: #15284B !important;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #1f3c72 !important;
            border-color: #1f3c72 !important;
        }

        .nav-link.active,
        .nav-link.show {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff !important;
        }

        .dropdown-toggle::after {
            margin-left: auto;
        }

        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1055;
            /* Above modals */
            max-width: 400px;
        }
        /* Responsive sidebar logo */
        .sidebar-logo {
            height: 60px;
            max-width: 100%;
        }
        @media (max-width: 576px) {
            .sidebar-logo {
                height: 36px;
            }
        }
        /* Responsive background logo */
        .content-bg-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: 0;
            max-width: 70vw;
            max-height: 70vh;
            pointer-events: none;
        }
        @media (max-width: 576px) {
            .content-bg-logo {
                max-width: 90vw;
                max-height: 30vh;
                opacity: 0.07;
            }
        }
    </style>
</head>

<body class="bg-light">



    @php
        $role = auth()->check() ? auth()->user()->role : null;
    @endphp

    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <div class="col-md-2 bg-primary text-white p-4 d-flex flex-column shadow-sm">
                 <a href="{{ route('dashboard') }}">
                 <h4 class="text-center fw-bold mb-2">
                    <img src="/logo1.png" alt="Logo" class="sidebar-logo img-fluid">
                </h4></a>
                @if(auth()->check())
                    <div class="text-center mb-4 small" style="font-weight:600;">
                        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                    </div>
                @endif

                <ul class="nav flex-column gap-2">
                    @if ($role === 'admin')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                                class="nav-link text-white {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="bi bi-people-fill me-2"></i> Manage Users
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/candidate') }}"
                                class="nav-link text-white {{ request()->is('candidate') ? 'active' : '' }}">
                                <i class="bi bi-person-lines-fill me-2"></i> Candidates
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('course.marks.index') }}"
                                class="nav-link text-white {{ request()->routeIs('course.marks.index') ? 'active' : '' }}">
                                <i class="bi bi-journal-text me-2"></i> Marks
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('marks.approvals') }}"
                                class="nav-link text-white {{ request()->routeIs('marks.approvals') ? 'active' : '' }}">
                                <i class="bi bi-check2-square me-2"></i> Approve Marks
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('marksheet.wizard') }}"
                                class="nav-link text-white {{ request()->routeIs('marksheet.wizard') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-pdf me-2"></i> Marksheet
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('certificates.wizard') }}"
                               class="nav-link text-white {{ request()->routeIs('certificates.wizard') ? 'active' : '' }}">
                                <i class="bi bi-award me-2"></i> Certificate Wizard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white d-flex justify-content-between align-items-center {{ request()->is('master*') ? '' : 'collapsed' }}"
                                data-bs-toggle="collapse" href="#subjectsCollapse" role="button"
                                aria-expanded="{{ request()->is('master*') ? 'true' : 'false' }}"
                                aria-controls="subjectsCollapse">
                                <span><i class="mdi mdi-book-open-page-variant-outline me-2"></i> Master</span>
                                <i class="bi bi-chevron-down small"></i>
                            </a>
                            <div class="collapse {{ request()->is('master*') ? 'show' : '' }}" id="subjectsCollapse">
                                <ul class="nav flex-column ms-4 mt-2">
                                    <li class="nav-item">
                                        <a href="{{ url('/master/courses') }}"
                                            class="nav-link text-white small {{ request()->is('master/courses') ? 'active' : '' }}">
                                            <i class="bi bi-dot"></i> Add Courses
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/master/course-details') }}"
                                            class="nav-link text-white small {{ request()->is('master/course-details') ? 'active' : '' }}">
                                            <i class="bi bi-dot"></i> Add Course Details
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/master/subjects') }}"
                                            class="nav-link text-white small {{ request()->is('master/subjects') ? 'active' : '' }}">
                                            <i class="bi bi-dot"></i> Add Subjects
                                        </a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a href="{{ url('/master/roles') }}"
                                            class="nav-link text-white small {{ request()->is('master/roles') ? 'active' : '' }}">
                                            <i class="bi bi-dot"></i> Roles
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/master/permissions') }}"
                                            class="nav-link text-white small {{ request()->is('master/permissions') ? 'active' : '' }}">
                                            <i class="bi bi-dot"></i> Permissions
                                        </a>
                                    </li> -->
                                </ul>
                            </div>
                        </li>
                    @elseif(in_array($role, ['faculty', 'examcell']))
                        <li class="nav-item">
                            <a href="{{ url('/candidate') }}"
                                class="nav-link text-white {{ request()->is('candidate') ? 'active' : '' }}">
                                <i class="bi bi-person-lines-fill me-2"></i> Candidates
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('course.marks.index') }}"
                                class="nav-link text-white {{ request()->routeIs('course.marks.index') ? 'active' : '' }}">
                                <i class="bi bi-journal-text me-2"></i> Marks
                            </a>
                        </li>
                        @if($role === 'examcell')
                        <li class="nav-item">
                            <a href="{{ route('marks.approvals') }}"
                                class="nav-link text-white {{ request()->routeIs('marks.approvals') ? 'active' : '' }}">
                                <i class="bi bi-check2-square me-2"></i> Approve Marks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('marksheet.wizard') }}"
                                class="nav-link text-white {{ request()->routeIs('marksheet.wizard') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-pdf me-2"></i> Marksheet
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('certificates.wizard') }}"
                               class="nav-link text-white {{ request()->routeIs('certificates.wizard') ? 'active' : '' }}">
                                <i class="bi bi-award me-2"></i> Certificate Wizard
                            </a>
                        </li>
                        @endif
                    @endif
                    
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4 position-relative" style="overflow:hidden;">
                <img src="/logo.png" alt="Logo Background" class="content-bg-logo" />
                <div style="position:relative;z-index:1;">
                    <div class="d-flex justify-content-end mb-3 align-items-center">
                        @if(in_array($role, ['admin', 'examcell','faculty']))
                            {{-- Notification Bell and Inline Panel --}}
                            @php
                                $notifications = auth()->user()->notifications()->take(5)->get();
                                $unreadCount = auth()->user()->unreadNotifications()->count();
                            @endphp
                            <div class="me-3" style="position: relative; display: inline-block;">
                                <button class="btn btn-outline-secondary position-relative" id="notificationBell" type="button">
                                    <i class="bi bi-bell"></i>
                                    @if($unreadCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unreadCount }}</span>
                                    @endif
                                </button>
                                <div id="notificationPanel" style="display:none; position:absolute; right:0; top:110%; z-index:9999; background:#fff; border:1px solid #ccc; border-radius:8px; min-width:350px; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                                    <div class="p-2 border-bottom fw-bold">Notifications</div>
                                    <div style="max-height:300px; overflow-y:auto;">
                                        @forelse($notifications as $notification)
                                            <div class="p-2 small {{ $notification->read_at ? '' : 'fw-bold bg-light' }}" style="border-bottom:1px solid #eee;">
                                                <div><strong>{{ $notification->data['faculty'] ?? '' }}</strong> edited marks for <strong>{{ $notification->data['candidate'] ?? '' }}</strong></div>
                                                <div>Course: {{ $notification->data['course'] ?? '' }}, Batch: {{ $notification->data['batch'] ?? '' }}</div>
                                                <div>Subject: {{ $notification->data['subject'] ?? '' }}</div>
                                                <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                                                <div>{{ $notification->data['message'] ?? '' }}</div>
                                            </div>
                                        @empty
                                            <div class="p-2 text-muted">No notifications</div>
                                        @endforelse
                                    </div>
                                    <div class="p-2 border-top">
                                        <button class="btn btn-link btn-sm text-success" id="markAllReadBtn" type="button">Mark all as read</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                    <div class="toast-container">
                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center gap-2">
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i></span>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger d-flex align-items-center gap-2">
                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle-fill"></i></span>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> Validation Errors</span>
                                <ul class="mt-2 mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-danger">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bell = document.getElementById('notificationBell');
            const panel = document.getElementById('notificationPanel');
            bell.addEventListener('click', function(e) {
                e.stopPropagation();
                panel.style.display = (panel.style.display === 'none' || panel.style.display === '') ? 'block' : 'none';
            });
            document.addEventListener('click', function() {
                panel.style.display = 'none';
            });
            panel.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            // Mark all as read only when button is clicked
            document.getElementById('markAllReadBtn').addEventListener('click', function() {
                fetch("{{ route('notifications.markAllRead') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => location.reload());
            });
        });

        setTimeout(() => {
            const toast = document.querySelector('.toast-container');
            if (toast) toast.style.display = 'none';
        }, 5000);

        // Mark notifications as read when dropdown is opened
        $(document).ready(function() {
            $('#notificationDropdown').on('show.bs.dropdown', function () {
                $.post("{{ route('notifications.markAllRead') }}", {_token: '{{ csrf_token() }}'});
            });
            $('#markAllReadBtn').on('click', function() {
                $.post("{{ route('notifications.markAllRead') }}", {_token: '{{ csrf_token() }}'}).done(function() {
                    location.reload();
                });
            });
        });
    </script>

    @yield('scripts')
</body>


</html>
