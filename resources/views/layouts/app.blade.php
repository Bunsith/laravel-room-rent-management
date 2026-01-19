<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Room Rental')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg rr-navbar navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand rr-brand" href="{{ route('dashboard') }}">ROOM RENTAL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pills">
                    @can('rooms.view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}" href="{{ route('rooms.index') }}">Room</a>
                        </li>
                    @endcan
                    @can('customers.view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">Customer</a>
                        </li>
                    @endcan
                    @can('rentals.view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('rentals.*') && in_array(request('tab', 'available'), ['available', 'rented'], true) ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'available']) }}">Rental</a>
                        </li>
                    @endcan
                    @can('collections.view')
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') === 'collection' || request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'collection']) }}">Collection</a>
                        </li>
                    @endcan
                    @can('journal.view')
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') === 'journal' || request()->routeIs('journal-entries.*') ? 'active' : '' }}" href="{{ route('rentals.index', ['tab' => 'journal']) }}">Account</a>
                        </li>
                    @endcan
                    @can('reports.view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">Reports</a>
                        </li>
                    @endcan
                    @can('users.view')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Users</a>
                        </li>
                    @endcan
                </ul>
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item d-flex align-items-center me-3 rr-welcome">
                        <i class="bi bi-person-circle me-2"></i>
                        <span>Welcome, {{ auth()->user()->name ?? 'User' }}</span>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-1"></i>
                            Settings
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @can('settings.manage')
                                <li>
                                    <a class="dropdown-item" href="{{ route('settings.index') }}">App Settings</a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endcan
                            <li>
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid rr-page">
        @include('partials.alerts')
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
