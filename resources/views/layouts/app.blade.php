<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GemShop') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #d4af37;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #3498db;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin: 20px;
            min-height: calc(100vh - 40px);

            max-height: calc(100vh - 40px);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar .nav-link {
            color: var(--secondary-color);
            padding: 12px 20px;
            border-radius: 10px;
            margin: 5px 10px;
            transition: all 0.3s ease;

            
        }

        .sidebar .nav-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .main-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin: 20px 20px 20px 0;
            min-height: calc(100vh - 40px);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #b8941f;
            border-color: #b8941f;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
        }

        .badge {
            border-radius: 20px;
            padding: 8px 12px;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), #f4d03f);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .stats-card .stats-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            border-radius: 5px;
            margin: 2px 5px;
        }

        .dropdown-item:hover {
            background: var(--primary-color);
            color: white;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2">
                <div class="sidebar">
                    <div class="p-3">
                        <h4 class="text-center mb-4">
                            <i class="fas fa-gem text-warning"></i>
                            {{ config('app.name') }}
                        </h4>
                        <p class="text-muted text-center mb-4">
                            <i class="fas fa-user"></i> User - {{ Auth::user()->name ?? 'Guest' }}
                        </p>

                        <!-- Currency Display Switcher -->
                        <div class="text-center mb-4">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="currencyDisplayDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-coins me-1"></i>
                                    <span id="currentDisplayCurrency">{{ session('display_currency', 'LKR') }}</span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="currencyDisplayDropdown" id="currencyDisplayDropdownMenu">
                                    <!-- Currencies will be loaded here -->
                                </ul>
                            </div>
                            <small class="text-muted">Display Currency</small>
                        </div>
                    </div>

                    <nav class="nav flex-column">
                        <!-- System Menu -->
                        <div class="px-3 mb-3">
                            <h6 class="text-muted text-uppercase small fw-bold">System</h6>
                        </div>
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('about') }}">
                            <i class="fas fa-info-circle me-2"></i> About
                        </a>
                        <a class="nav-link" href="{{ route('calculator') }}">
                            <i class="fas fa-calculator me-2"></i> Calculator
                        </a>
                        <a class="nav-link" href="{{ route('backup') }}">
                            <i class="fas fa-database me-2"></i> Backup
                        </a>
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="fas fa-users me-2"></i> User Profile
                        </a>
                        <a class="nav-link" href="{{ route('permissions.index') }}">
                            <i class="fas fa-shield-alt me-2"></i> Permission
                        </a>

                        <!-- Maintain Menu -->
                        <div class="px-3 mb-3 mt-4">
                            <h6 class="text-muted text-uppercase small fw-bold">Maintain</h6>
                        </div>
                        <a class="nav-link" href="{{ route('suppliers.index') }}">
                            <i class="fas fa-truck me-2"></i> Supplier Profile
                        </a>
                        <a class="nav-link" href="{{ route('customers.index') }}">
                            <i class="fas fa-user-friends me-2"></i> Customer Profile
                        </a>
                        <a class="nav-link" href="{{ route('sales-assistants.index') }}">
                            <i class="fas fa-user-tie me-2"></i> Sales Assistant Profile
                        </a>
                        <a class="nav-link" href="{{ route('sales-executives.index') }}">
                            <i class="fas fa-user-graduate me-2"></i> Sales Executive Profile
                        </a>
                        <a class="nav-link" href="{{ route('craftsmen.index') }}">
                            <i class="fas fa-hammer me-2"></i> Craftsman Profile
                        </a>
                        <a class="nav-link" href="{{ route('tour-guides.index') }}">
                            <i class="fas fa-map-marked-alt me-2"></i> Tour Guide Profile
                        </a>
                        <a class="nav-link" href="{{ route('banks.index') }}">
                            <i class="fas fa-university me-2"></i> Bank Profile
                        </a>
                        <a class="nav-link" href="{{ route('items.index') }}">
                            <i class="fas fa-gem me-2"></i> Item Profile
                        </a>
                        <a class="nav-link" href="{{ route('currencies.index') }}">
                            <i class="fas fa-coins me-2"></i> Currency Profile
                        </a>

                        <!-- Tasks Menu -->
                        <div class="px-3 mb-3 mt-4">
                            <h6 class="text-muted text-uppercase small fw-bold">Tasks</h6>
                        </div>
                        <a class="nav-link" href="{{ route('purchase-orders.index') }}">
                            <i class="fas fa-shopping-cart me-2"></i> Purchase Order
                        </a>
                        <a class="nav-link" href="{{ route('grns.index') }}">
                            <i class="fas fa-receipt me-2"></i> G.R.N
                        </a>
                        <a class="nav-link" href="{{ route('invoices.index') }}">
                            <i class="fas fa-file-invoice me-2"></i> Invoice
                        </a>
                        <a class="nav-link" href="{{ route('sales-orders.index') }}">
                            <i class="fas fa-shopping-bag me-2"></i> Sales Order
                        </a>
                        <a class="nav-link" href="{{ route('stock-adjustments.index') }}">
                            <i class="fas fa-adjust me-2"></i> Stock Adjustment
                        </a>
                        <a class="nav-link" href="{{ route('customer-returns.index') }}">
                            <i class="fas fa-undo me-2"></i> Customer Return
                        </a>
                        <a class="nav-link" href="{{ route('supplier-returns.index') }}">
                            <i class="fas fa-arrow-left me-2"></i> Supplier Return
                        </a>
                        <a class="nav-link" href="{{ route('item-transfers.index') }}">
                            <i class="fas fa-exchange-alt me-2"></i> Item Transfer
                        </a>
                        <a class="nav-link" href="{{ route('alteration-commissions.index') }}">
                            <i class="fas fa-cogs me-2"></i> Alteration Commission
                        </a>

                        <!-- Workshop Menu -->
                        <div class="px-3 mb-3 mt-4">
                            <h6 class="text-muted text-uppercase small fw-bold">Workshop</h6>
                        </div>
                        <a class="nav-link" href="{{ route('job-issues.index') }}">
                            <i class="fas fa-tools me-2"></i> Job Issue
                        </a>
                        <a class="nav-link" href="{{ route('finished-good-transfers.index') }}">
                            <i class="fas fa-check-circle me-2"></i> Finished Good Transfer
                        </a>
                        <a class="nav-link" href="{{ route('workshop-adjustments.index') }}">
                            <i class="fas fa-balance-scale me-2"></i> Adjustment
                        </a>
                        <a class="nav-link" href="{{ route('craftsman-returns.index') }}">
                            <i class="fas fa-arrow-left me-2"></i> Craftsman Return
                        </a>
                        <a class="nav-link" href="{{ route('mtcs.index') }}">
                            <i class="fas fa-clipboard-list me-2"></i> M.T.C
                        </a>

                        <!-- Reports Menu -->
                        <div class="px-3 mb-3 mt-4">
                            <h6 class="text-muted text-uppercase small fw-bold">Reports</h6>
                        </div>
                        <a class="nav-link" href="{{ route('reports.stocks') }}">
                            <i class="fas fa-boxes me-2"></i> Stocks Report
                        </a>
                        <a class="nav-link" href="{{ route('reports.sales') }}">
                            <i class="fas fa-chart-line me-2"></i> Sales Report
                        </a>
                        <a class="nav-link" href="{{ route('reports.inventory') }}">
                            <i class="fas fa-warehouse me-2"></i> Inventory Report
                        </a>
                        <a class="nav-link" href="{{ route('reports.workshop') }}">
                            <i class="fas fa-hammer me-2"></i> Workshop Report
                        </a>
                        <a class="nav-link" href="{{ route('reports.guide-listing') }}">
                            <i class="fas fa-list me-2"></i> Guide Listing
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <div class="p-4">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Add active class to current page
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Load currencies for display dropdown
            loadDisplayCurrencies();
        });

        // Load available currencies for display
        function loadDisplayCurrencies() {
            fetch('/currency/active')
                .then(response => response.json())
                .then(data => {
                    const dropdownMenu = document.getElementById('currencyDisplayDropdownMenu');
                    dropdownMenu.innerHTML = '';

                    data.currencies.forEach(currency => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                            <a class="dropdown-item" href="#" onclick="setDisplayCurrency('${currency.code}')">
                                <i class="fas fa-coins me-2"></i>
                                ${currency.code} - ${currency.name}
                            </a>
                        `;
                        dropdownMenu.appendChild(li);
                    });
                })
                .catch(error => console.error('Error loading currencies:', error));
        }

        // Set display currency
        function setDisplayCurrency(currencyCode) {
            fetch('/currency/set-display', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    currency_code: currencyCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('currentDisplayCurrency').textContent = currencyCode;

                    // Show success message
                    showNotification('Display currency set to ' + currencyCode, 'success');

                    // Reload page to update all currency displays
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification('Failed to set display currency', 'error');
                }
            })
            .catch(error => {
                console.error('Error setting display currency:', error);
                showNotification('Error setting display currency', 'error');
            });
        }

        // Show notification
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }
    </script>
    
    @yield('scripts')
</body>
</html>
