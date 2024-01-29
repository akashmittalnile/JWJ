@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/support.css') }}">
@endpush

@section('title','Journey with Journals - Support & Communication')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Support & Communication</h4>
    <div class="search-filter wd2">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ route('admin.notification') }}" class="btn-bl">Manage App Notifcation</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="support-page-section">
        <div class="support-page-header">
            <div class="search-filter ">
                <div class="row g-1">
                    <div class="col-md-2">
                        <div class="form-group">
                            <a href="javascript:void(0)" class="btn-bl"><img src="{{ assets('assets/images/download.svg') }}"> Download report</a>
                        </div>
                    </div>

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
                                <option>Show All Inquiry Type</option>
                                <option>Plans Related</option>
                                <option>Billing Related</option>
                                <option>General Inquiry</option>
                                <option>Community Related</option>
                                <option>Community Guidelines</option>
                                <option>Community Creation</option>
                                <option>Task Management</option>
                                <option>Journals Related</option>
                                <option>Journaling Functionality</option>
                                <option>General Inquiry</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="search-form-group">
                                <input type="text" name="" class="form-control" placeholder="Search by user name, email & Phone no.">
                                <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="support-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="jwj-support-card">
                        <div class="jwjcard-support-head">
                            <div class="jwjcard-user-card">
                                <div class="jwjcard-user-avtar">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwjcard-user-text">
                                    <h4>Jane doe</h4>
                                </div>
                            </div>
                            <div class="jwjcard-user-action">
                                <a class="phone-btn" href=""><img src="{{ assets('assets/images/call.svg') }}"></a>
                                <a class="email-btn" href=""><img src="{{ assets('assets/images/sms.svg') }}"></a>
                            </div>
                        </div>
                        <div class="jwjcard-support-body">
                            <div class="support-desc">
                                <p>I Received A Subscription Renewal Confirmation, But I Wanted To Check If Everything Is In Order. Can You Confirm The Details And Ensure That My Subscription Is Active?</p>
                            </div>
                            <div class="support-option-info">
                                <p>Inquiry Type</p>
                                <h2>Plan Related </h2>
                            </div>
                            <div class="support-action-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a class="Sendreply-btn" href="javascript:void(0)">Send reply</a>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control">
                                            <option>Select Status</option>
                                            <option>Closed</option>
                                            <option>In-Progress</option>
                                            <option>Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jwjcard-support-foot">
                            <div class="support-date-info">
                                <img src="{{ assets('assets/images/calendar.svg') }}"> Submitted On 26 April,2023- 09:23PM
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="jwj-support-card">
                        <div class="jwjcard-support-head">
                            <div class="jwjcard-user-card">
                                <div class="jwjcard-user-avtar">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwjcard-user-text">
                                    <h4>Jane doe</h4>
                                </div>
                            </div>
                            <div class="jwjcard-user-action">
                                <a class="phone-btn" href=""><img src="{{ assets('assets/images/call.svg') }}"></a>
                                <a class="email-btn" href=""><img src="{{ assets('assets/images/sms.svg') }}"></a>
                            </div>
                        </div>
                        <div class="jwjcard-support-body">
                            <div class="support-desc">
                                <p>I Received A Subscription Renewal Confirmation, But I Wanted To Check If Everything Is In Order. Can You Confirm The Details And Ensure That My Subscription Is Active?</p>
                            </div>
                            <div class="support-option-info">
                                <p>Inquiry Type</p>
                                <h2>Plan Related </h2>
                            </div>
                            <div class="support-action-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a class="Sendreply-btn" href="javascript:void(0)">Send reply</a>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control">
                                            <option>Select Status</option>
                                            <option>Closed</option>
                                            <option>In-Progress</option>
                                            <option>Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jwjcard-support-foot">
                            <div class="support-date-info">
                                <img src="{{ assets('assets/images/calendar.svg') }}"> Submitted On 26 April,2023- 09:23PM
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="jwj-support-card">
                        <div class="jwjcard-support-head">
                            <div class="jwjcard-user-card">
                                <div class="jwjcard-user-avtar">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwjcard-user-text">
                                    <h4>Jane doe</h4>
                                </div>
                            </div>
                            <div class="jwjcard-user-action">
                                <a class="phone-btn" href=""><img src="{{ assets('assets/images/call.svg') }}"></a>
                                <a class="email-btn" href=""><img src="{{ assets('assets/images/sms.svg') }}"></a>
                            </div>
                        </div>
                        <div class="jwjcard-support-body">
                            <div class="support-desc">
                                <p>I Received A Subscription Renewal Confirmation, But I Wanted To Check If Everything Is In Order. Can You Confirm The Details And Ensure That My Subscription Is Active?</p>
                            </div>
                            <div class="support-option-info">
                                <p>Inquiry Type</p>
                                <h2>Plan Related </h2>
                            </div>
                            <div class="support-action-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a class="Sendreply-btn" href="javascript:void(0)">Send reply</a>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control">
                                            <option>Select Status</option>
                                            <option>Closed</option>
                                            <option>In-Progress</option>
                                            <option>Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jwjcard-support-foot">
                            <div class="support-date-info">
                                <img src="{{ assets('assets/images/calendar.svg') }}"> Submitted On 26 April,2023- 09:23PM
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="jwj-support-card">
                        <div class="jwjcard-support-head">
                            <div class="jwjcard-user-card">
                                <div class="jwjcard-user-avtar">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwjcard-user-text">
                                    <h4>Jane doe</h4>
                                </div>
                            </div>
                            <div class="jwjcard-user-action">
                                <a class="phone-btn" href=""><img src="{{ assets('assets/images/call.svg') }}"></a>
                                <a class="email-btn" href=""><img src="{{ assets('assets/images/sms.svg') }}"></a>
                            </div>
                        </div>
                        <div class="jwjcard-support-body">
                            <div class="support-desc">
                                <p>I Received A Subscription Renewal Confirmation, But I Wanted To Check If Everything Is In Order. Can You Confirm The Details And Ensure That My Subscription Is Active?</p>
                            </div>
                            <div class="support-option-info">
                                <p>Inquiry Type</p>
                                <h2>Plan Related </h2>
                            </div>
                            <div class="support-action-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a class="Sendreply-btn" href="javascript:void(0)">Send reply</a>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control">
                                            <option>Select Status</option>
                                            <option>Closed</option>
                                            <option>In-Progress</option>
                                            <option>Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jwjcard-support-foot">
                            <div class="support-date-info">
                                <img src="{{ assets('assets/images/calendar.svg') }}"> Submitted On 26 April,2023- 09:23PM
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reply -->
<div class="modal lm-modal fade" id="Sendreply" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Sendreply-modal-form">
                    <h2>Reply</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <select class="form-control">
                                    <option>Select Status</option>
                                    <option>Closed</option>
                                    <option>In-Progress</option>
                                    <option>Pending</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea type="text" class="form-control" placeholder="Type Your Reply Message Here.."></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button class="save-btn">SEND REPLY</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection