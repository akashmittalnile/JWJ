@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community-approval.css') }}">
@endpush

@section('title','Journey with Journals - Community Details')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Approval Community Details</h4>
    <div class="search-filter wd4">
        <div class="row g-1">
            <div class="col-md-5">
                <div class="form-group">
                    <a href="{{ route('admin.community-management.approval') }}" class="btn-bl">Back</a>
                </div>
            </div>

            <div class="col-md-7">
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
                <div class="col-md-12">
                    <div class="jwj-posts-posts-card">
                        <div class="jwj-posts-head">
                            <div class="post-member-item">
                                <div class="post-member-image">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="post-member-text">
                                    <h3>Jane Doe</h3>
                                    <div class="post-member-plan"><img src="{{ assets('assets/images/freeplan.svg') }}"> Plan A member</div>
                                </div>
                            </div>
                            <div class="jwjcard-group-action">
                            </div>
                        </div>
                        <div class="jwj-posts-body">
                            <div class="row g-1">
                                <div class="col-md-5">
                                    <div id="communitycarousel1" class="communitycarousel1 owl-carousel owl-theme">
                                        <div class="item">
                                            <div class="community-posts-media">
                                                <img src="{{ assets('assets/images/1.png') }}">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="community-posts-media">
                                                <img src="{{ assets('assets/images/2.png') }}">
                                            </div>
                                        </div>
                                        <div class="item">
                                            <div class="community-posts-media">
                                                <img src="{{ assets('assets/images/3.png') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="jwjcard-body">
                                        <div class="community-post-description">
                                            <h3>Church Group</h3>
                                            <div class="admincommunity-text">User Community</div>
                                            <p>Today wins a good din I washed this morning and everyone showed for their shift. The boss bought lunch for us all. It was Gus, but it was gocd,after wocktenet warry snd kitin et.TO’elants for dinks. The muse was sold nil we tound some cecere to play darts with. 1 ave lonn @ride home. We gre aDking forwart to meetinz to ts wedkend to go to six Floes, Italred with from this ottomoon, she is planning on hosting Thankhirg this year, Shals going to ma up a menu for all of us to pick from.think will batre greco bean cosserole this your, Was wall</p>
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
                                            <div class="managecommunity-group-action">
                                                <a class="approvecommunity-btn" href="{{ route('admin.community-management.approval') }}">Approve Community Request</a>
                                                <a class="rejectcommunity-btn" data-bs-toggle="modal" data-bs-target="#rejectcommunityrequest">Reject Community Request</a>
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
    </div>
</div>

<!-- REJECT COMMUNITY REQUEST -->
<div class="modal jwj-modal fade" id="rejectcommunityrequest" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form">
                    <h2>Enter Reject Reason</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea type="text" class="form-control" placeholder="Type Your Reply Message Here.."></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button class="save-btn">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection