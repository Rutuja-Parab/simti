<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'SIMTI')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.materialdesignicons.com/7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Bundle JS (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

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
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <div class="col-md-2 bg-primary text-white p-4 d-flex flex-column shadow-sm">
                <h4 class="text-center fw-bold mb-4">
                    <img src="/logo1.png" alt="Logo" class="sidebar-logo img-fluid">
                </h4>


            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4 position-relative" style="overflow:hidden;">
                <img src="/logo.png" alt="Logo Background" class="content-bg-logo" />
                <div style="position:relative;z-index:1;">
                    <div class="d-flex justify-content-end mb-3 align-items-center">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    @yield('scripts')
</body>

</html>
