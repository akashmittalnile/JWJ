@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/notification.css') }}">
@endpush

@section('title','Journey with Journals - Notifcation')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4> Manage App Notifcation</h4>
    <div class="search-filter wd2">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a class="btn-bl" data-bs-toggle="modal" data-bs-target="#CreateNotification">Push New App Notifcation</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="notification-page-section">
        <div class="notification-page-header">
            <div class="search-filter ">
                <div class="row g-1">
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="month" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Select Status</option>
                                <option>Closed</option>
                                <option>In-Progress</option>
                                <option>Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Show All</option>
                                <option>Plans A</option>
                                <option>Plans B</option>
                                <option>Plans C</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="search-form-group">
                                <input type="text" name="" class="form-control" placeholder="Search by title">
                                <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="notification-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="manage-notification-item">
                        <div class="manage-notification-icon">
                            <img src="{{ assets('assets/images/notification-bing.svg') }}">
                        </div>
                        <div class="manage-notification-content">
                            <div class="notification-date">Pushed on: 06 Dec, 2022 - 09:39Am</div>
                            <div class="notification-descr">Exciting News! For A Limited Time, Enjoy A Special Discount On Annual Subscription Plans. Upgrade Now And Save Big On Premium Features! 🚀</p>
                            </div>
                            <div class="notification-tag">
                                <div class="tags-list">
                                    <div class="Tags-text">Plan A Users</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="manage-notification-item">
                        <div class="manage-notification-icon">
                            <img src="{{ assets('assets/images/notification-bing.svg') }}">
                        </div>
                        <div class="manage-notification-content">
                            <div class="notification-date">Pushed on: 06 Dec, 2022 - 09:39Am</div>
                            <div class="notification-descr">
                                <p>Introducing Our Latest Subscription Tier! Unlock Even More Powerful Features To Enhance Your Experience. Check Out The New Plan Details And Elevate Your App Usage Today!</p>
                            </div>
                            <div class="notification-tag">
                                <div class="tags-list">
                                    <div class="Tags-text">Plan B Users</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create  Notification -->
<div class="modal lm-modal fade" id="CreateNotification" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="notification-modal-form">
                    <h2>Push New app notification</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Notification Title">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <select class="form-control">
                                    <option>Select Subscription Plan</option>
                                    <option>Plan A Users</option>
                                    <option>Plan B Users</option>
                                    <option>Plan C Users</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="file" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea type="text" class="form-control" placeholder="Type Your Reply Message Here.."></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Discard</button>
                                <button class="save-btn">Push App Notification</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection