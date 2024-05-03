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
                    <a class="nav-link  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                        <div class="noti-icon" id="trigger-unseen">
                            <img src="{{ assets('assets/images/notification.svg') }}" alt="user">
                            <span class="noti-badge">{{ getNotification('unseen') }}</span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" data-bs-popper="none">
                        <div class="notification-head">
                            <h2>Notifications</h2>
                        </div>
                        <div class="notification-body">
                            @forelse(getNotification() as $val)
                            @if($val->type == 'COMMUNITY')
                            <a href="{{ route('admin.community-management.approval') }}">
                            @else
                            <a href="javascript:void(0)">
                            @endif
                                <div class="notification-item">
                                    <div class="notification-item-icon">
                                        <i class="la la-bell"></i>
                                    </div>
                                    <div class="notification-item-text">
                                        <h2>{{ $val->title ?? "NA" }}</h2>
                                        <p style="color: #1079c0;;">{{ $val->message ?? "NA" }}</p>
                                        <p><span><i class="fas fa-clock"></i>{{ date('d M, Y H:i A', strtotime($val->created_at)) }}</span></p>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div>
                                    <img width="200" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
                                </div>
                            </div>
                            @endforelse
                        </div>

                        <a href="" data-bs-toggle="modal" data-bs-target="#clearModal">
                            <div class="notification-foot">
                                Clear All Notifications
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item profile-dropdown dropdown">
                    <a class="nav-link dropdown-toggle" id="profile" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
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

<!-- LOGOUT COMMUNITY -->
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
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
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

<!-- CLEAR NOTIFICATION -->
<div class="modal lm-modal fade" id="clearModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form text-center">
                    <h2>Are You Sure?</h2>
                    <p>You want to clear all the notifications!</p>
                    <form action="{{ route('admin.clear.notification') }}" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                    <button type="submit" class="save-btn">Yes! Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    $(document).on('click', '#trigger-unseen', function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: 'GET',
            url: "{{ route('admin.notify.seen') }}",
            success: function(data) {
                if (data.status) {
                    $('.noti-badge').text(0);
                }
            },
            error: function(e) {
                console.log(e);
            }
        })
    })
</script>
@endpush