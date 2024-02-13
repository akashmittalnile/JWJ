@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/home.css') }}">
@endpush

@section('title','Journey with Journals - Dashboard')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Dashboard</h4>
</div>
<div class="body-main-content">
    <div class="overview-section">
        <div class="row row-cols-xl-5 row-cols-xl-3 row-cols-md-2 g-2">
            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>Total Users</p>
                                <h2>{{ $userCount ?? 0 }}</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/users.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>Total Community</p>
                                <h2>{{ $communityCount ?? 0 }}</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/community.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>Community Engagement</p>
                                <h2>{{ $communityFollowCount ?? 0 }} Followers</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/communityengagement.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="subscription-section">
        <div class="row">
            <div class="col-md-6">
                <div class="subscription-card1">
                    <div class="subscription-content">
                        <h2 class="subscription-title">Total Subscription Payment received</h2>
                        <p class="subscription-price">${{ number_format((float)$paymentReceived, 2, '.', '') }}</p>
                        <div class="subscription-button">
                            <a href="javascript:void(0)" class="Plan-btn">Monthly</a>
                            <a href="javascript:void(0)" class="Plan-btn-1">Annually</a>
                        </div>
                    </div>
                    <div class="subscription-chart">
                        <div id="chartBar1"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="subscription-card">
                    <div class="subscription-content">
                        <h2 class="subscription-title">Total Subscription Payment received</h2>
                        <p class="subscription-price">${{ number_format((float)$paymentReceived, 2, '.', '') }}</p>
                        <p class="subscription-text">{{ $subscribeUserCount ?? 0 }} Users Subscribed</p>
                        <div class="subscription-button">
                            <a href="javascript:void(0)" class="Plan-btn">Plan B</a>
                            <a href="javascript:void(0)" class="Plan-btn-1">Plan C</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="earnings-section">
        <div class="row">
            <div class="col-md-12">
                <div class="jwjcard">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="me-auto">
                                <h4 class="heading-title">Earnings</h4>
                            </div>
                            <div class="search-filter wd6">
                                <div class="search-filter">
                                    <div class="row g-1">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="date" name="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="form-control">
                                                    <option>Show All</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="form-control">
                                                    <option>Choose Plan</option>
                                                    <option>Plan A</option>
                                                    <option>Plan B</option>
                                                    <option>Plan C</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a href="javascript:void(0)" class="btn-bl">Download report</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="kik-chart">
                            <div id="chartBar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="earnings-section">
        <div class="row">
            <div class="col-md-12">
                <div class="jwjcard">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="me-auto">
                                <h4 class="heading-title">Rating & Reviews</h4>
                            </div>
                            <div class="search-filter wd5">
                                <div class="search-filter">
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="date" name="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="month" name="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <a href="{{ route('admin.rating-review.list') }}" class="btn-bl">View All</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="reviewrating" class="owl-carousel owl-theme">
                            <div class="item">
                                <div class="jwj-review-card">
                                    <div class="jwj-review-card-head">
                                        <div class="review-rating-user-avtar">
                                            <span>J</span>
                                        </div>
                                        <div class="review-rating-user-text">
                                            <h3>John</h3>
                                            <div class="review-rating">
                                                <div class="review-rating-icon">
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class=""><i class="las la-star"></i></span>
                                                </div>
                                                <div class="review-rating-text">5.0 Rating</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="jwj-review-card-body">
                                        <span class="review-quotes-shape"></span>
                                        <div class="review-desc">I Recently Had The Pleasure Of Visiting This Furniture Store, And I Must Say, I Was Thoroughly Impressed With …</div>
                                        <div class="review-date">01 Wed, 09:30</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Dates popup -->
<div class="modal kik-modal fade" id="ManageDates" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="iot-modal-form">
                    <h3>Manage Dates</h3>
                    <div class="form-group">
                        <h4>Selected Date</h4>
                        <input type="date" class="form-control">
                    </div>
                    <div class="form-group">
                        <ul class="kik-datesstatus-list">
                            <li>
                                <div class="kikradio">
                                    <input type="radio" name="datesstatustype" id="Not Available">
                                    <label for="Not Available">Not Available</label>
                                </div>
                            </li>
                            <li>
                                <div class="kikradio">
                                    <input type="radio" name="datesstatustype" id="Available">
                                    <label for="Available">Available</label>
                                </div>
                            </li>
                            <li>
                                <div class="kikradio">
                                    <input type="radio" name="datesstatustype" id="Tour Bookings">
                                    <label for="Tour Bookings">Tour Bookings</label>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="kik-modal-action">
                        <button class="yesbtn">Confirm & Save</button>
                        <button class="Cancelbtn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection