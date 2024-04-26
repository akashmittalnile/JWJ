<div class="sidebar-wrapper sidebar-offcanvas" id="sidebar">
    <div class="sidebar-logo">
        <a class="brand-logo" href="{{ route('admin.dashboard') }}">
            <img class="" src="{{ assets('assets/images/logo.svg') }}" alt="">
        </a>
    </div>
    <div class="sidebar-nav">
        <nav class="sidebar">
            <ul class="nav">
                <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M10.5 19.9V4.1C10.5 2.6 9.86 2 8.27 2H4.23C2.64 2 2 2.6 2 4.1V19.9C2 21.4 2.64 22 4.23 22H8.27C9.86 22 10.5 21.4 10.5 19.9Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22 10.9V4.1C22 2.6 21.36 2 19.77 2H15.73C14.14 2 13.5 2.6 13.5 4.1V10.9C13.5 12.4 14.14 13 15.73 13H19.77C21.36 13 22 12.4 22 10.9Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M22 19.9V18.1C22 16.6 21.36 16 19.77 16H15.73C14.14 16 13.5 16.6 13.5 18.1V19.9C13.5 21.4 14.14 22 15.73 22H19.77C21.36 22 22 21.4 22 19.9Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('admin.users*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.users.list') }}">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M9.16 10.87C9.06 10.86 8.94 10.86 8.83 10.87C6.45 10.79 4.56 8.84 4.56 6.44C4.56 3.99 6.54 2 9 2C11.45 2 13.44 3.99 13.44 6.44C13.43 8.84 11.54 10.79 9.16 10.87Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.41 4C18.35 4 19.91 5.57 19.91 7.5C19.91 9.39 18.41 10.93 16.54 11C16.46 10.99 16.37 10.99 16.28 11" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M4.16 14.56C1.74 16.18 1.74 18.82 4.16 20.43C6.91 22.27 11.42 22.27 14.17 20.43C16.59 18.81 16.59 16.17 14.17 14.56C11.43 12.73 6.92 12.73 4.16 14.56Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M18.34 20C19.06 19.85 19.74 19.56 20.3 19.13C21.86 17.96 21.86 16.03 20.3 14.86C19.75 14.44 19.08 14.16 18.37 14" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="menu-title">User Management</span>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('admin.revenue-management*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.revenue-management.list') }}">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M8.67188 14.3298C8.67188 15.6198 9.66188 16.6598 10.8919 16.6598H13.4019C14.4719 16.6598 15.3419 15.7498 15.3419 14.6298C15.3419 13.4098 14.8119 12.9798 14.0219 12.6998L9.99187 11.2998C9.20187 11.0198 8.67188 10.5898 8.67188 9.36984C8.67188 8.24984 9.54187 7.33984 10.6119 7.33984H13.1219C14.3519 7.33984 15.3419 8.37984 15.3419 9.66984" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 6V18" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="menu-title">Revenue Management</span>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('admin.community-management*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.community-management.list') }}">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                <path d="M22.8 12C22.8 6.48 18.32 2 12.8 2C7.28005 2 2.80005 6.48 2.80005 12C2.80005 17.52 7.28005 22 12.8 22" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8.80002 3H9.80002C7.85002 8.84 7.85002 15.16 9.80002 21H8.80002" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M15.8 3C16.77 5.92 17.2601 8.96 17.2601 12" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.80005 16V15C6.72005 15.97 9.76005 16.46 12.8 16.46" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.80005 9.0001C9.64005 7.0501 15.96 7.0501 21.8 9.0001" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M18.8366 18C18.8153 17.9969 18.7879 17.9969 18.7635 18C18.2269 17.9817 17.8 17.542 17.8 17.0015C17.8 16.4489 18.2452 16 18.8 16C19.3519 16 19.8 16.4489 19.8 17.0015C19.797 17.542 19.3732 17.9817 18.8366 18Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M21.8 20.8027C21.0078 21.5476 19.9573 22 18.8 22C17.6428 22 16.5923 21.5476 15.8 20.8027C15.8446 20.3732 16.1116 19.9528 16.5879 19.6238C17.8075 18.7921 19.8015 18.7921 21.0122 19.6238C21.4885 19.9528 21.7555 20.3732 21.8 20.8027Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M18.8 22C21.0092 22 22.8 20.2091 22.8 18C22.8 15.7909 21.0092 14 18.8 14C16.5909 14 14.8 15.7909 14.8 18C14.8 20.2091 16.5909 22 18.8 22Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="menu-title">Community Management</span>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('admin.journal*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.journal.list') }}">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                <path d="M22.8 12C22.8 6.48 18.32 2 12.8 2C7.28005 2 2.80005 6.48 2.80005 12C2.80005 17.52 7.28005 22 12.8 22" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8.80002 3H9.80002C7.85002 8.84 7.85002 15.16 9.80002 21H8.80002" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M15.8 3C16.77 5.92 17.2601 8.96 17.2601 12" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.80005 16V15C6.72005 15.97 9.76005 16.46 12.8 16.46" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3.80005 9.0001C9.64005 7.0501 15.96 7.0501 21.8 9.0001" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M18.8366 18C18.8153 17.9969 18.7879 17.9969 18.7635 18C18.2269 17.9817 17.8 17.542 17.8 17.0015C17.8 16.4489 18.2452 16 18.8 16C19.3519 16 19.8 16.4489 19.8 17.0015C19.797 17.542 19.3732 17.9817 18.8366 18Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M21.8 20.8027C21.0078 21.5476 19.9573 22 18.8 22C17.6428 22 16.5923 21.5476 15.8 20.8027C15.8446 20.3732 16.1116 19.9528 16.5879 19.6238C17.8075 18.7921 19.8015 18.7921 21.0122 19.6238C21.4885 19.9528 21.7555 20.3732 21.8 20.8027Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M18.8 22C21.0092 22 22.8 20.2091 22.8 18C22.8 15.7909 21.0092 14 18.8 14C16.5909 14 14.8 15.7909 14.8 18C14.8 20.2091 16.5909 22 18.8 22Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="menu-title">Journal Management</span>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('admin.rating-review*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.rating-review.list') }}">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M13.73 3.51L15.49 7.03C15.73 7.52 16.37 7.99 16.91 8.08L20.1 8.61C22.14 8.95 22.62 10.43 21.15 11.89L18.67 14.37C18.25 14.79 18.02 15.6 18.15 16.18L18.86 19.25C19.42 21.68 18.13 22.62 15.98 21.35L12.99 19.58C12.45 19.26 11.56 19.26 11.01 19.58L8.02 21.35C5.88 22.62 4.58 21.67 5.14 19.25L5.85 16.18C5.98 15.6 5.75 14.79 5.33 14.37L2.85 11.89C1.39 10.43 1.86 8.95 3.9 8.61L7.09 8.08C7.62 7.99 8.26 7.52 8.5 7.03L10.26 3.51C11.22 1.6 12.78 1.6 13.73 3.51Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="menu-title">Rating & Reviews</span>
                    </a>
                </li>

                <li class="nav-item {{ (Route::is('admin.support*') || Route::is('admin.notification*')) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.support') }}">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M5.46 18.49V15.57C5.46 14.6 6.22 13.73 7.3 13.73C8.27 13.73 9.14 14.49 9.14 15.57V18.38C9.14 20.33 7.52 21.95 5.57 21.95C3.62 21.95 2 20.32 2 18.38V12.22C1.89 6.6 6.33 2.05 11.95 2.05C17.57 2.05 22 6.6 22 12.11V18.27C22 20.22 20.38 21.84 18.43 21.84C16.48 21.84 14.86 20.22 14.86 18.27V15.46C14.86 14.49 15.62 13.62 16.7 13.62C17.67 13.62 18.54 14.38 18.54 15.46V18.49" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="menu-title">Support & Communication</span>
                    </a>
                </li>

                <!-- <li class="nav-item {{ (Route::is('admin.chats*') || Route::is('admin.chats*')) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.chats') }}">
                        <span class="menu-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z" stroke="#455A64" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15.9965 11H16.0054" stroke="#455A64" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M11.9955 11H12.0045" stroke="#455A64" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7.99451 11H8.00349" stroke="#455A64" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="menu-title">Chats</span>
                    </a>
                </li> -->

                <li class="nav-item {{ Route::is('admin.routine*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.routine.category') }}">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                <path d="M21.68 8.58003V15.42C21.68 16.54 21.08 17.58 20.11 18.15L14.17 21.58C13.2 22.14 12 22.14 11.02 21.58L5.08001 18.15C4.11001 17.59 3.51001 16.55 3.51001 15.42V8.58003C3.51001 7.46003 4.11001 6.41999 5.08001 5.84999L11.02 2.42C11.99 1.86 13.19 1.86 14.17 2.42L20.11 5.84999C21.08 6.41999 21.68 7.45003 21.68 8.58003Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12.6 11.0001C13.8868 11.0001 14.93 9.95687 14.93 8.67004C14.93 7.38322 13.8868 6.34009 12.6 6.34009C11.3132 6.34009 10.27 7.38322 10.27 8.67004C10.27 9.95687 11.3132 11.0001 12.6 11.0001Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.6 16.6601C16.6 14.8601 14.81 13.4001 12.6 13.4001C10.39 13.4001 8.60004 14.8601 8.60004 16.6601" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="menu-title">Manage Routine Category</span>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('admin.profile*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.profile') }}">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                <path d="M21.68 8.58003V15.42C21.68 16.54 21.08 17.58 20.11 18.15L14.17 21.58C13.2 22.14 12 22.14 11.02 21.58L5.08001 18.15C4.11001 17.59 3.51001 16.55 3.51001 15.42V8.58003C3.51001 7.46003 4.11001 6.41999 5.08001 5.84999L11.02 2.42C11.99 1.86 13.19 1.86 14.17 2.42L20.11 5.84999C21.08 6.41999 21.68 7.45003 21.68 8.58003Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12.6 11.0001C13.8868 11.0001 14.93 9.95687 14.93 8.67004C14.93 7.38322 13.8868 6.34009 12.6 6.34009C11.3132 6.34009 10.27 7.38322 10.27 8.67004C10.27 9.95687 11.3132 11.0001 12.6 11.0001Z" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.6 16.6601C16.6 14.8601 14.81 13.4001 12.6 13.4001C10.39 13.4001 8.60004 14.8601 8.60004 16.6601" stroke="#455A64" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="menu-title">Manage Profile</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#openLogoutModal">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M17.44 14.62L20 12.06L17.44 9.5" stroke="#455A64" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9.76 12.06H19.93" stroke="#455A64" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M11.76 20C7.34 20 3.76 17 3.76 12C3.76 7 7.34 4 11.76 4" stroke="#455A64" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="menu-title">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>