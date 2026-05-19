<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPEEDSHOP 2')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            overflow-x: hidden;
            min-height: 100vh;
            background: #f4f6fb;
            color: #0b1f3d;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #0d1b2a;
            color: #fff;
            padding-top: 1rem;
            overflow-y: auto;
        }

        .sidebar a {
            color: #fff;
        }

        .sidebar .brand {
            font-size: 1.25rem;
            font-weight: 700;
            padding-left: 1rem;
            padding-right: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
        }

        .content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
            background: #fff;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.08);
            border-radius: 1.25rem;
        }

        @media (max-width: 992px) {
            .sidebar {
                position: relative;
                height: auto;
                width: 100%;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="brand">
            <i class="bi bi-shop-window-fill fs-3"></i>
            <span>SPEEDSHOP 2</span>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                @if(auth()->check())
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                    <i class="bi bi-house-door-fill me-2"></i>Dashboard
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if(auth()->user()->role == 'karyawan')
                <a class="nav-link {{ request()->is('produk*') ? 'active' : '' }}" href="/produk">
                    <i class="bi bi-box-seam me-2"></i>Produk
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if(auth()->user()->role == 'karyawan')
                <a class="nav-link {{ request()->is('transaksi*') ? 'active' : '' }}" href="/transaksi">
                    <i class="bi bi-receipt me-2"></i>Transaksi
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if(in_array(auth()->user()->role, ['admin', 'owner']))
                <a class="nav-link {{ request()->is('stok*') ? 'active' : '' }}" href="/stok">
                    <i class="bi bi-bar-chart-line me-2"></i>Stok
                </a>
                @endif
            </li>

            <li class="nav-item">
                @if(in_array(auth()->user()->role, ['admin', 'owner']))
                <a class="nav-link {{ request()->is('faktur-pembelian*') ? 'active' : '' }}" href="/faktur-pembelian">
                    <i class="bi bi-file-earmark-text me-2"></i>Pembelian
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if(in_array(auth()->user()->role, ['admin', 'owner']))
                <a class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}" href="/laporan">
                    <i class="bi bi-graph-up me-2"></i>Laporan
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if(in_array(auth()->user()->role, ['admin', 'owner']))
                <a class="nav-link {{ request()->is('admin/pulsa-settings*') || request()->is('admin/token-settings*') ? 'active' : '' }}" href="/admin/pulsa-settings">
                    <i class="bi bi-gear-fill me-2"></i>Harga Pulsa & Token
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if(in_array(auth()->user()->role, ['admin', 'owner']))
                <a class="nav-link {{ request()->is('admin/karyawan*') ? 'active' : '' }}" href="/admin/karyawan">
                    <i class="bi bi-people-fill me-2"></i>Kelola User
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if(auth()->user()->role == 'karyawan')
                <a class="nav-link {{ request()->is('riwayat-transaksi*') ? 'active' : '' }}" href="/riwayat-transaksi">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Transaksi
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if(auth()->user()->role == 'karyawan')
                <a class="nav-link {{ request()->is('pulsa-token*') ? 'active' : '' }}" href="/pulsa-token">
                    <i class="bi bi-phone me-2"></i>Pulsa & Token
                </a>
                @endif
            </li>
            <li class="nav-item">
                @if(auth()->user()->role == 'karyawan')
                <a class="nav-link {{ request()->is('log-pulsa-token*') ? 'active' : '' }}" href="/log-pulsa-token">
                    <i class="bi bi-list-ul me-2"></i>Log Pulsa & Token
                </a>
                @endif
            </li>
        </ul>
    </div>

    <div class="content">
        @if(auth()->check())
        <div class="d-flex justify-content-end align-items-center mb-3">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userProfileDropdown">
                    <li><a class="dropdown-item" href="/profil">Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/logout">Logout</a></li>
                </ul>
            </div>
        </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>