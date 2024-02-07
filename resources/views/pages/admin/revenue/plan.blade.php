@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/revenue.css') }}">
@endpush

@section('title','Journey with Journals - Subscription Plan')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Manage Subscription Plan</h4>
    <div class="search-filter wd2">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ route('admin.revenue-management.plans') }}" class="btn-bl">Sync Plan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="revenue-page-section">
        <div class="revenue-content">
            <div class="row">
                
                <?php $count = 0; ?>
                
                @forelse($plan as $val)
                <?php $count++; ?>
                <div class="col-md-4">
                    <div class="membership-list-item">
                        <div class="membership-header">
                            <div class="membership-plan-image">
                                @if($count == 1)
                                <img src="{{ assets('assets/images/freeplan.svg') }}">
                                @elseif($count == 2)
                                <img src="{{ assets('assets/images/goldplan.svg') }}">
                                @elseif($count == 3)
                                <img src="{{ assets('assets/images/platinumplan.svg') }}">
                                @endif
                            </div>
                            <div class="membership-text">
                                <div class="membership-title">{{ $val->name ?? 'NA' }}</div>
                                <div class="membership-day-price">@if($val->monthly_price == 0) <span></span> @else @if($val->currency == 'usd')$@endif{{number_format((float)$val->monthly_price, 2, '.', '')}}/month @endif</div>
                            </div>
                        </div>
                        <div class="membership-month-price">
                            <p>@if($val->anually_price == 0) Free <span></span> @else @if($val->currency == 'usd')$@endif{{number_format((float)$val->anually_price, 2, '.', '')}} <span>Per Year</span> @endif</p>
                        </div>
                        <div class="membership-body">
                            <div class="membership-list">
                                <ul>
                                    <li><i class="las la-check-circle"></i>1 Entry Per Day/250 Words</li>
                                    <li><i class="las la-check-circle"></i>3 Routine Tasks</li>
                                    <li><i class="las la-check-circle"></i>View Community</li>
                                    <li><i class="las la-check-circle"></i>Participate In Preset Communities</li>
                                </ul>
                            </div>
                            <a class="Buy-btn" data-bs-toggle="modal" data-bs-target="#EditPlan">Subscribe Now</a>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse

                <!-- <div class="col-md-4">
                    <div class="membership-list-item">
                        <div class="membership-header">
                            <div class="membership-plan-image">
                                <img src="{{ assets('assets/images/goldplan.svg') }}">
                            </div>
                            <div class="membership-text">
                                <div class="membership-title">Plan B</div>
                                <div class="membership-day-price">$ 04.20/day</div>
                            </div>
                        </div>
                        <div class="membership-month-price">
                            <p>$5.99 <span>Per Month</span></p>
                        </div>
                        <div class="membership-body">
                            <div class="membership-list">
                                <ul>
                                    <li><i class="las la-check-circle"></i>3 Entries Per Day/ 250 Words Each</li>
                                    <li><i class="las la-check-circle"></i>Add 1 Picture Per Day</li>
                                    <li><i class="las la-check-circle"></i>10 Routines With Ability To Share</li>
                                    <li><i class="las la-check-circle"></i>Participate In Communities</li>
                                </ul>
                            </div>
                            <a class="Buy-btn" data-bs-toggle="modal" data-bs-target="#SubscribeNow">Subscribe Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="membership-list-item">
                        <div class="membership-header">
                            <div class="membership-plan-image">
                                <img src="{{ assets('assets/images/platinumplan.svg') }}">
                            </div>
                            <div class="membership-text">
                                <div class="membership-title">Plan C</div>
                                <div class="membership-day-price">$ 04.20/day</div>
                            </div>
                        </div>
                        <div class="membership-month-price">
                            <p>$129.00 <span> Per Month</span></p>
                        </div>
                        <div class="membership-body">
                            <div class="membership-list">
                                <ul>
                                    <li><i class="las la-check-circle"></i>Up To 5 Entries Per Day/500 Words Each</li>
                                    <li><i class="las la-check-circle"></i>Add 3 Picture Per Day</li>
                                    <li><i class="las la-check-circle"></i>Unlimited Routines With Ability To Share</li>
                                    <li><i class="las la-check-circle"></i>Submit Your Own Communities/ App Approval Required</li>
                                </ul>
                            </div>
                            <a class="Buy-btn" data-bs-toggle="modal" data-bs-target="#SubscribeNow">Subscribe Now</a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>

<!-- Subscribe Now -->
<div class="modal lm-modal fade" id="EditPlan" tabindex="-1" aria-labelledby="EditPlanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="plan-modal-form">
                    <h2 id="planName">Manage Plan B</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Enter plan name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="number" min="0.1" step="0.01" class="form-control" placeholder="Enter price per month">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="number" min="1" step="0.01" class="form-control" placeholder="Enter price per year">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <ul class="plan-list">
                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="3 Entries Per Day/ 250 Words Each" name="feature">
                                            <label for="3 Entries Per Day/ 250 Words Each">
                                                3 Entries Per Day/ 250 Words Each
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="Add 1 Picture Per Day" name="feature">
                                            <label for="Add 1 Picture Per Day">
                                                Add 1 Picture Per Day
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="10 Routines With Ability To Share" name="feature">
                                            <label for="10 Routines With Ability To Share">
                                                10 Routines With Ability To Share
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="Participate In Communities" name="feature">
                                            <label for="Participate In Communities">
                                                Participate In Communities
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="1 Entry Per Day/250 Words" name="feature">
                                            <label for="1 Entry Per Day/250 Words">
                                                1 Entry Per Day/250 Words
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="3 Routine Tasks" name="feature">
                                            <label for="3 Routine Tasks">
                                                3 Routine Tasks
                                            </label>
                                        </div>
                                    </li>


                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="View Community" name="feature">
                                            <label for="View Community">
                                                View Community
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="Participate In Preset Communities" name="feature">
                                            <label for="Participate In Preset Communities">
                                                Participate In Preset Communities
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="Up To 5 Entries Per Day/500  Words Each" name="feature">
                                            <label for="Up To 5 Entries Per Day/500  Words Each">
                                                Up To 5 Entries Per Day/500 Words Each
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="Add 3 Pictures Per Day" name="feature">
                                            <label for="Add 3 Pictures Per Day">
                                                Add 3 Pictures Per Day
                                            </label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="Unlimited Routines With Ability To Share" name="feature">
                                            <label for="Unlimited Routines With Ability To Share">
                                                Unlimited Routines With Ability To Share
                                            </label>
                                        </div>
                                    </li>


                                    <li>
                                        <div class="plancheckbox">
                                            <input type="checkbox" id="Submit Your Own Communities/ App Approval Required." name="feature">
                                            <label for="Submit Your Own Communities/ App Approval Required.">
                                                Submit Your Own Communities/ App Approval Required.
                                            </label>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>



                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Discard</button>
                                <button class="save-btn">Inactive Plan</button>
                                <button class="save-btn">Save Change</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection