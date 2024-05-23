@php
    $containerNav = isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact' ? 'container-xxl' : 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';
@endphp

<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
                @include('_partials.macros', ['height' => 20])
            </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('variables.templateName') }}</span>
        </a>
    </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    @if (!isset($menuHorizontal))
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0 ">
                <a class="d-flex align-items-center px-0" href="https://acwad-it.com/">
                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                    <span class="d-none d-md-inline-block text-muted">ACWAD</span>
                </a>
            </div>
        </div>
        <!-- /Search -->
    @endif
    <ul class="navbar-nav flex-row align-items-center ms-auto">
        {{-- <!-- Language -->
        <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <i class='ti ti-language rounded-circle ti-md'></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ url('lang/en') }}" data-language="en">
                        <span class="align-middle">English</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ url('lang/fr') }}" data-language="fr">
                        <span class="align-middle">French</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ url('lang/de') }}" data-language="de">
                        <span class="align-middle">German</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ url('lang/pt') }}" data-language="pt">
                        <span class="align-middle">Portuguese</span>
                    </a>
                </li>
            </ul>
        </li>
        <!--/ Language --> --}}

        @if (isset($menuHorizontal))
            <!-- Search -->
            <li class="nav-item navbar-search-wrapper me-2 me-xl-0">
                <a class="nav-link search-toggler" href="javascript:void(0);">
                    <i class="ti ti-search ti-md"></i>
                </a>
            </li>
            <!-- /Search -->
        @endif
        @if ($configData['hasCustomizer'] == true)
            <!-- Style Switcher -->
            <li class="navbar-nav align-items-center">
                <div class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <i class='ti ti-md'></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start dropdown-styles">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                <span class="align-middle"><i class='ti ti-sun me-2'></i>Light</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                                <span class="align-middle"><i class="ti ti-device-desktop me-2"></i>System</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <!--/ Style Switcher -->
        @endif

        {{-- <!-- Quick links  -->
        <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                data-bs-auto-close="outside" aria-expanded="false">
                <i class='ti ti-layout-grid-add ti-md'></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-0">
                <div class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                        <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                        <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Add shortcuts"><i class="ti ti-sm ti-apps"></i></a>
                    </div>
                </div>
                <div class="dropdown-shortcuts-list scrollable-container">
                    <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                <i class="ti ti-calendar fs-4"></i>
                            </span>
                            <a href="{{ url('app/calendar') }}" class="stretched-link">Calendar</a>
                            <small class="text-muted mb-0">Appointments</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                <i class="ti ti-file-invoice fs-4"></i>
                            </span>
                            <a href="{{ url('app/invoice/list') }}" class="stretched-link">Invoice App</a>
                            <small class="text-muted mb-0">Manage Accounts</small>
                        </div>
                    </div>
                    <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                <i class="ti ti-users fs-4"></i>
                            </span>
                            <a href="{{ url('app/user/list') }}" class="stretched-link">User App</a>
                            <small class="text-muted mb-0">Manage Users</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                <i class="ti ti-lock fs-4"></i>
                            </span>
                            <a href="{{ url('app/access-roles') }}" class="stretched-link">Role Management</a>
                            <small class="text-muted mb-0">Permission</small>
                        </div>
                    </div>
                    <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                <i class="ti ti-chart-bar fs-4"></i>
                            </span>
                            <a href="{{ url('/') }}" class="stretched-link">Dashboard</a>
                            <small class="text-muted mb-0">User Profile</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                <i class="ti ti-settings fs-4"></i>
                            </span>
                            <a href="{{ url('pages/account-settings-account') }}" class="stretched-link">Setting</a>
                            <small class="text-muted mb-0">Account Settings</small>
                        </div>
                    </div>
                    <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                <i class="ti ti-help fs-4"></i>
                            </span>
                            <a href="{{ url('pages/faq') }}" class="stretched-link">FAQs</a>
                            <small class="text-muted mb-0">FAQs & Articles</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                                <i class="ti ti-square fs-4"></i>
                            </span>
                            <a href="{{ url('modal-examples') }}" class="stretched-link">Modals</a>
                            <small class="text-muted mb-0">Useful Popups</small>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <!-- Quick links --> --}}

        <!-- Notification -->
        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                data-bs-auto-close="outside" aria-expanded="false">
                <i class="ti ti-bell ti-md"></i>
                <span class="badge bg-danger rounded-pill badge-notifications" id="notifications_count">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end py-0">
                <li class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                        <h5 class="text-body mb-0 me-auto">Notification</h5>
                        <a href="javascript:void(0)" class="dropdown-notifications-all text-body"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"><i
                                class="ti ti-mail-opened fs-4"></i></a>
                    </div>

                    {{-- <span style="color: rgb(226, 226, 223)" id="notifications_count">
                      You have
                      {{ auth()->user()->unreadNotifications->count() }}
                      unread notifications
                    </span> --}}


                </li>


                <li class="dropdown-notifications-list scrollable-container">
                    <div id="unreadNotifications">
                        @foreach (auth()->user()->unreadNotifications as $notification)
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex">
                                        <a class="d-flex p-3 border-bottom" href="{{ url('/orders/index') }}">

                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar">
                                                    @if (auth()->user()->img != null)
                                                        <img src="{{ asset('assets/admin/uploads/' . auth()->user()->img) }}"
                                                            alt class="h-auto rounded-circle">
                                                    @else
                                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt
                                                            class="h-auto rounded-circle">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mr-3">
                                                <h5 class="notification-label mb-1">{{ $notification->data['title'] }}
                                                    {{ $notification->data['user'] }}
                                                </h5>
                                                <div class="notification-subtext">{{ $notification->created_at }}
                                                </div>
                                            </div>
                                        </a>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>

                                        </div>
                                    </div>
                                </li>



                            </ul>
                        @endforeach
                    </div>
                </li>



                <li class="dropdown-menu-footer border-top">
                    <a href="\MarkAsRead_all"
                        class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                        Mark All Read

                    </a>
                </li>


            </ul>
        </li>
        <!--/ Notification -->

        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    @if (Auth::user()->img != null)
                        <img src="{{ asset('assets/admin/uploads/' . Auth::user()->img) }}" alt
                            class="h-auto rounded-circle">
                    @else
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle">
                    @endif
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('adminPanelSetting.show', Auth::user()->id) }}">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    @if (Auth::user()->img != null)
                                        <img src="{{ asset('assets/admin/uploads/' . Auth::user()->img) }}" alt
                                            class="h-auto rounded-circle">
                                    @else
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt
                                            class="h-auto rounded-circle">
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-medium d-block">
                                    @if (Auth::check())
                                        {{ Auth::user()->name }}
                                    @else
                                        John Doe
                                    @endif
                                </span>
                                <small class="text-muted">Admin</small>
                            </div>
                        </div>
                    </a>
                </li>

                @if (Auth::check())
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class='ti ti-logout me-2'></i>
                            <span class="align-middle">Logout</span>
                        </a>
                    </li>
                    <form method="POST" id="logout-form" action="{{ route('logout') }}">
                        @csrf
                    </form>
                @else
                    <li>
                        <a class="dropdown-item"
                            href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                            <i class='ti ti-login me-2'></i>
                            <span class="align-middle">Login</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        <!--/ User -->
    </ul>
</div>

<!-- Search Small Screens -->
<div class="navbar-search-wrapper search-input-wrapper {{ isset($menuHorizontal) ? $containerNav : '' }} d-none">
    <input type="text"
        class="form-control search-input {{ isset($menuHorizontal) ? '' : $containerNav }} border-0"
        placeholder="Search..." aria-label="Search...">
    <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
</div>
@if (isset($navbarDetached) && $navbarDetached == '')
    </div>
@endif
</nav>
<!-- / Navbar -->
