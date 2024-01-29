@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community-management.css') }}">
@endpush

@section('title','Journey with Journals - Community Management')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Community Management</h4>
    <div class="search-filter wd8">
        <div class="row g-1">
            <div class="col-md-3">
                <div class="form-group">
                    <a data-bs-toggle="modal" data-bs-target="#addnewcommunity" class="btn-bl"> Add New Community</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <a href="{{ route('admin.community-management.approval') }}" class="btn-bl"> New Community Approval</a>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group">
                    <select class="form-control">
                        <option>Show All Community</option>
                        <option>User Created Community </option>
                        <option>Admin Created Community</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <div class="search-form-group">
                        <input type="text" name="" class="form-control" placeholder="Search by community name, user name, email or phone no.">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="community-page-section">
        <div class="community-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="jwj-community-card">
                        <div class="jwjcard-head">
                            <div class="jwjcard-group-card">
                                <div class="jwjcard-group-avtar">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwjcard-group-text">
                                    <h4>Church Group</h4>
                                </div>
                            </div>
                            <div class="jwjcard-group-action">
                                <a class="managecommunity-btn" href="{{ route('admin.community-management.details', encrypt_decrypt('encrypt', 1)) }}">Manage Community</a>
                            </div>
                        </div>
                        <div class="jwjcard-body">
                            <div class="admincommunity-text">User Community</div>
                            <div id="communitycarousel" class=" communitycarousel owl-carousel owl-theme">
                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/1.png') }}">
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/2.png') }}">
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/3.png') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="User-contact-info">
                                        <div class="User-contact-info-content">
                                            <h2>Mark AS</h2>
                                            <div class="switch-toggle">
                                                <p>Inactive</p>
                                                <div class="">
                                                    <label class="toggle" for="myToggle">
                                                        <input class="toggle__input" name="" type="checkbox" id="myToggle">
                                                        <div class="toggle__fill"></div>
                                                    </label>
                                                </div>
                                                <p>Active</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-shift-card">
                                        <div class="service-shift-card-image">
                                            <img src="{{ assets('assets/images/up-stock.svg') }}" height="14px">
                                        </div>
                                        <div class="service-shift-card-text">
                                            <h2>Total Posts</h2>
                                            <p>45</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jwjcard-foot">
                            <div class="jwjcard-member-item">
                                <div class="jwjcard-member-info">
                                    <span class="jwjcard-member-image image1">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                    <span class="jwjcard-member-image image2">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                    <span class="jwjcard-member-image image3">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                </div>
                                <p>13.7K Member Follows</p>
                            </div>
                            <div class="community-plan-info">
                                <img src="{{ assets('assets/images/platinumplan.svg') }}"> Plan C
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="jwj-community-card">
                        <div class="jwjcard-head">
                            <div class="jwjcard-group-card">
                                <div class="jwjcard-group-avtar">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwjcard-group-text">
                                    <h4>Christian Groups</h4>
                                </div>
                            </div>
                            <div class="jwjcard-group-action">
                                <a class="managecommunity-btn" href="{{ route('admin.community-management.details', encrypt_decrypt('encrypt', 1)) }}">Manage Community</a>
                            </div>
                        </div>
                        <div class="jwjcard-body">
                            <div class="admincommunity-text">Admin Community</div>
                            <div id="communitycarousel" class=" communitycarousel owl-carousel owl-theme">
                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/1.png') }}">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/2.png') }}">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/3.png') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="User-contact-info">
                                        <div class="User-contact-info-content">
                                            <h2>Mark AS</h2>
                                            <div class="switch-toggle">
                                                <p>Inactive</p>
                                                <div class="">
                                                    <label class="toggle" for="myToggle1">
                                                        <input class="toggle__input" name="" type="checkbox" id="myToggle1" checked>
                                                        <div class="toggle__fill"></div>
                                                    </label>
                                                </div>
                                                <p>Active</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-shift-card">
                                        <div class="service-shift-card-image">
                                            <img src="{{ assets('assets/images/up-stock.svg') }}" height="14px">
                                        </div>
                                        <div class="service-shift-card-text">
                                            <h2>Total Posts</h2>
                                            <p>45</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jwjcard-foot">
                            <div class="jwjcard-member-item">
                                <div class="jwjcard-member-info">
                                    <span class="jwjcard-member-image image1">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                    <span class="jwjcard-member-image image2">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                    <span class="jwjcard-member-image image3">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                </div>
                                <p>13.7K Member Follows</p>
                            </div>
                            <div class="community-plan-info">
                                <img src="{{ assets('assets/images/goldplan.svg') }}"> Plan A
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="jwj-community-card">
                        <div class="jwjcard-head">
                            <div class="jwjcard-group-card">
                                <div class="jwjcard-group-avtar">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwjcard-group-text">
                                    <h4>Christian Groups</h4>
                                </div>
                            </div>
                            <div class="jwjcard-group-action">
                                <a class="managecommunity-btn" href="{{ route('admin.community-management.details', encrypt_decrypt('encrypt', 1)) }}">Manage Community</a>
                            </div>
                        </div>
                        <div class="jwjcard-body">
                            <div class="admincommunity-text">Admin Community</div>
                            <div id="communitycarousel" class=" communitycarousel owl-carousel owl-theme">
                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/1.png') }}">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/2.png') }}">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/3.png') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="User-contact-info">
                                        <div class="User-contact-info-content">
                                            <h2>Mark AS</h2>
                                            <div class="switch-toggle">
                                                <p>Inactive</p>
                                                <div class="">
                                                    <label class="toggle" for="myToggle1">
                                                        <input class="toggle__input" name="" type="checkbox" id="myToggle1" checked>
                                                        <div class="toggle__fill"></div>
                                                    </label>
                                                </div>
                                                <p>Active</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-shift-card">
                                        <div class="service-shift-card-image">
                                            <img src="{{ assets('assets/images/up-stock.svg') }}" height="14px">
                                        </div>
                                        <div class="service-shift-card-text">
                                            <h2>Total Posts</h2>
                                            <p>45</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jwjcard-foot">
                            <div class="jwjcard-member-item">
                                <div class="jwjcard-member-info">
                                    <span class="jwjcard-member-image image1">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                    <span class="jwjcard-member-image image2">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                    <span class="jwjcard-member-image image3">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                </div>
                                <p>13.7K Member Follows</p>
                            </div>
                            <div class="community-plan-info">
                                <img src="{{ assets('assets/images/goldplan.svg') }}"> Plan A
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="jwj-community-card">
                        <div class="jwjcard-head">
                            <div class="jwjcard-group-card">
                                <div class="jwjcard-group-avtar">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwjcard-group-text">
                                    <h4>Christian Groups</h4>
                                </div>
                            </div>
                            <div class="jwjcard-group-action">
                                <a class="managecommunity-btn" href="{{ route('admin.community-management.details', encrypt_decrypt('encrypt', 1)) }}">Manage Community</a>
                            </div>
                        </div>
                        <div class="jwjcard-body">
                            <div class="admincommunity-text">Admin Community</div>
                            <div id="communitycarousel" class=" communitycarousel owl-carousel owl-theme">
                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/1.png') }}">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/2.png') }}">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="community-media">
                                        <img src="{{ assets('assets/images/3.png') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="User-contact-info">
                                        <div class="User-contact-info-content">
                                            <h2>Mark AS</h2>
                                            <div class="switch-toggle">
                                                <p>Inactive</p>
                                                <div class="">
                                                    <label class="toggle" for="myToggle1">
                                                        <input class="toggle__input" name="" type="checkbox" id="myToggle1" checked>
                                                        <div class="toggle__fill"></div>
                                                    </label>
                                                </div>
                                                <p>Active</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-shift-card">
                                        <div class="service-shift-card-image">
                                            <img src="{{ assets('assets/images/up-stock.svg') }}" height="14px">
                                        </div>
                                        <div class="service-shift-card-text">
                                            <h2>Total Posts</h2>
                                            <p>45</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jwjcard-foot">
                            <div class="jwjcard-member-item">
                                <div class="jwjcard-member-info">
                                    <span class="jwjcard-member-image image1">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                    <span class="jwjcard-member-image image2">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                    <span class="jwjcard-member-image image3">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                </div>
                                <p>13.7K Member Follows</p>
                            </div>
                            <div class="community-plan-info">
                                <img src="{{ assets('assets/images/goldplan.svg') }}"> Plan A
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ADD NEW COMMUNITY -->
<div class="modal lm-modal fade" id="addnewcommunity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form">
                    <h2>ADD NEW COMMUNITY</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Title">
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
                                <button class="save-btn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection