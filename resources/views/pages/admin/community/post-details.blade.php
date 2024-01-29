@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community-management.css') }}">
@endpush

@section('title','Journey with Journals - Community Post Details')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Community Post Details</h4>
    <div class="search-filter wd1">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ route('admin.community-management.details', encrypt_decrypt('encrypt', 1)) }}" class="btn-bl">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="community-details-page-section">
        <div class="community-content-details">
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
                                <a class="managecommunity-btn" href="#">Delete Post</a>
                            </div>
                        </div>
                        <div class="jwj-posts-body">
                            <div class="row g-1">
                                <div class="col-md-5">
                                    <div id="communitycarousel1" class=" communitycarousel1 owl-carousel owl-theme">
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
                                            <h3>Jesus I Trust In You</h3>
                                            <div class="post-date-info">
                                                <img src="{{ assets('assets/images/calendar.svg') }}"> Submitted On 26 April,2023- 09:23PM
                                            </div>
                                            <p>Today wins a good din I washed this morning and everyone showed for their shift. The boss bought lunch for us all. It was Gus, but it was gocd,after wocktenet warry snd kitin et.TO’elants for dinks. The muse was sold nil we tound some cecere to play darts with. 1 ave lonn @ride home. We gre aDking forwart to meetinz to ts wedkend to go to six Floes, Italred with from this ottomoon, she is planning on hosting Thankhirg this year, Shals going to ma up a menu for all of us to pick from.think will batre greco bean cosserole this your, Was wall</p>
                                            <div class="community-post-action">
                                                <a class="Like-btn" href="#"><img src="{{ assets('assets/images/like.svg') }}"> 2.5K likes</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="jwj-comment-section">
                        <div class="jwj-comment-list">
                            <div class="jwj-comment-box-head">
                                <h1>Comments(3)</h1>
                                <div class="jwj-comment-head-action">
                                    <a class="addcomment-btn" data-bs-toggle="modal" data-bs-target="#addcomment"><i class="las la-plus"></i> Add comment</a>
                                </div>
                            </div>

                            <div class="jwj-comment-item">
                                <div class="jwj-comment-profile">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwj-comment-content">
                                    <div class="jwj-comment-head">
                                        <h2>Maude Hall</h2>
                                        <div class="jwj-date"><i class="las la-calendar"></i>08 Jan, 2023, 09:30PM</div>
                                    </div>
                                    <div class="jwj-comment-descr">That's a fantastic new app feature. You and your team did an excellent job of incorporating user testing feedback. That's a fantastic new app feature. You and your team did an excellent job of incorporating user testing feedback.</div>
                                    <div class="jwj-comment-action">
                                        <a class="delete-btn1" href="#"><img src="{{ assets('assets/images/trash.svg') }}"> Delete</a>
                                    </div>
                                </div>
                            </div>

                            <div class="jwj-comment-item">
                                <div class="jwj-comment-profile">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwj-comment-content">
                                    <div class="jwj-comment-head">
                                        <h2>Maude Hall</h2>
                                        <div class="jwj-date"><i class="las la-calendar"></i>08 Jan, 2023, 09:30PM</div>
                                    </div>
                                    <div class="jwj-comment-descr">That's a fantastic new app feature. You and your team did an excellent job of incorporating user testing feedback. That's a fantastic new app feature. You and your team did an excellent job of incorporating user testing feedback.</div>
                                    <div class="jwj-comment-action">
                                        <a class="delete-btn1" href="#"><img src="{{ assets('assets/images/trash.svg') }}"> Delete</a>
                                    </div>
                                </div>
                            </div>

                            <div class="jwj-comment-item">
                                <div class="jwj-comment-profile">
                                    <img src="{{ assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwj-comment-content">
                                    <div class="jwj-comment-head">
                                        <h2>Maude Hall</h2>
                                        <div class="jwj-date"><i class="las la-calendar"></i>08 Jan, 2023, 09:30PM</div>
                                    </div>
                                    <div class="jwj-comment-descr">That's a fantastic new app feature. You and your team did an excellent job of incorporating user testing feedback. That's a fantastic new app feature. You and your team did an excellent job of incorporating user testing feedback.</div>
                                    <div class="jwj-comment-action">
                                        <a class="delete-btn1" href="#"><img src="{{ assets('assets/images/trash.svg') }}"> Delete</a>
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

<!-- add comment -->
<div class="modal lm-modal fade" id="addcomment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form">
                    <h2>Add Comment</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea type="text" class="form-control" placeholder="Type Your Reply Message Here.."></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button class="save-btn">SEND </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection