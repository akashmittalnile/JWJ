<div class="header">
    <nav class="navbar">
        <div class="navbar-menu-wrapper">
            <ul class="navbar-nav f-navbar-nav">
                <li class="nav-item">
                    <a class="nav-link nav-toggler" data-toggle="minimize">
                        <img src="{{ assets('assets/images/menu-icon.svg') }}">
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item noti-dropdown dropdown">
                    <a class="nav-link  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="noti-icon">
                            <img src="{{ assets('assets/images/notification.svg') }}" alt="user">
                            <span class="noti-badge">0</span>
                        </div>
                    </a>
                    <div class="dropdown-menu">

                    </div>
                </li>
                <li class="nav-item profile-dropdown dropdown">
                    <a class="nav-link dropdown-toggle" id="profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-pic">
                            <div class="profile-pic-image">
                                <img src="{{ isset(auth()->user()->profile) ? assets('uploads/profile/'.auth()->user()->profile) : assets('assets/images/no-image.jpg') }}" alt="user">
                            </div>
                            <div class="profile-pic-text">
                                <h3>{{ auth()->user()->name ?? 'NA' }}</h3>
                                <p>Administrator</p>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('admin.profile') }}" class="dropdown-item">
                            <i class="las la-user"></i> Profile
                        </a>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#openLogoutModal" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
                <li class="nav-item profile-dropdown dropdown">
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                        <span class="icon-menu"><img src="{{ assets('assets/images/menu-icon.svg') }}"></span>
                    </button>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- ADD NEW COMMUNITY -->
<div class="modal lm-modal fade" id="openLogoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form text-center">
                    <h2>Are You Sure?</h2>
                    <p>You want to logout!</p>
                    <form action="{{ route('admin.logout') }}" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Discard</button>
                                    <button type="submit" class="save-btn">Yes! Logout</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>