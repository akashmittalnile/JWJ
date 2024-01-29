@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community-approval.css') }}">
@endpush

@section('title','Journey with Journals - Community Approval')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>New Community Approval</h4>
    <div class="search-filter wd7">
        <div class="row g-1">
            <div class="col-md-4">
                <div class="form-group">
                    <a href="javascript:void(0)" class="btn-bl">VIEW ALL Rejected Community</a>
                </div>
            </div>

            <div class="col-md-8">
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
                <div class="col-md-4">
                    <div class="jwj-community-approval-card">
                        <div class="jwjcard-head">
                            <div class="jwjcard-member-item">
                                <div class="jwjcard-member-info">
                                    <span class="jwjcard-member-image image1">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                </div>
                                <p>Jane Doe</p>
                            </div>
                            <div class="jwjcard-group-action">
                                <a class="managecommunity-btn" href="{{ route('admin.community-management.approval-details', encrypt_decrypt('encrypt', 1)) }}">View Community</a>
                            </div>
                        </div>
                        <div id="communitycarousel1" class=" communitycarousel1 owl-carousel owl-theme">
                            <div class="item">
                                <div class="community-approval-media">
                                    <img src="{{ assets('assets/images/1.png') }}">
                                </div>
                            </div>

                            <div class="item">
                                <div class="community-approval-media">
                                    <img src="{{ assets('assets/images/2.png') }}">
                                </div>
                            </div>

                            <div class="item">
                                <div class="community-approval-media">
                                    <img src="{{ assets('assets/images/3.png') }}">
                                </div>
                            </div>
                        </div>
                        <div class="jwjcard-body">
                            <div class="admincommunity-text">User Community</div>
                            <div class="community-description">
                                <h3>In Christ We Unite: A Community Of Love And Faith</h3>
                                <p> Today wins a good din I washed this morning and everyone showed for their shift. The boss bought lunch for us all. It was Gus, but it was gocd,after wocktenet warry snd kitin et.TO’elants for dinks. The muse was sold nil we tound some cecere to play darts with. 1 ave lonn @ride home. We gre aDking forwart to meetinz to ts wedkend to go to six Floes, Italred with from this ottomoon, she is planning on hosting Thankhirg this year, Shals going to ma up a menu for all of us to pick from.think will batre greco bean cosserole this your, Was wall</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="jwj-community-approval-card">
                        <div class="jwjcard-head">
                            <div class="jwjcard-member-item">
                                <div class="jwjcard-member-info">
                                    <span class="jwjcard-member-image image1">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                </div>
                                <p>Jane Doe</p>
                            </div>
                            <div class="jwjcard-group-action">
                                <a class="managecommunity-btn" href="{{ route('admin.community-management.approval-details', encrypt_decrypt('encrypt', 1)) }}">View Community</a>
                            </div>
                        </div>
                        <div id="communitycarousel1" class=" communitycarousel1 owl-carousel owl-theme">
                            <div class="item">
                                <div class="community-approval-media">
                                    <img src="{{ assets('assets/images/1.png') }}">
                                </div>
                            </div>

                            <div class="item">
                                <div class="community-approval-media">
                                    <img src="{{ assets('assets/images/2.png') }}">
                                </div>
                            </div>

                            <div class="item">
                                <div class="community-approval-media">
                                    <img src="{{ assets('assets/images/3.png') }}">
                                </div>
                            </div>
                        </div>
                        <div class="jwjcard-body">
                            <div class="admincommunity-text">User Community</div>
                            <div class="community-description">
                                <h3>In Christ We Unite: A Community Of Love And Faith</h3>
                                <p> Today wins a good din I washed this morning and everyone showed for their shift. The boss bought lunch for us all. It was Gus, but it was gocd,after wocktenet warry snd kitin et.TO’elants for dinks. The muse was sold nil we tound some cecere to play darts with. 1 ave lonn @ride home. We gre aDking forwart to meetinz to ts wedkend to go to six Floes, Italred with from this ottomoon, she is planning on hosting Thankhirg this year, Shals going to ma up a menu for all of us to pick from.think will batre greco bean cosserole this your, Was wall</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="jwj-community-approval-card">
                        <div class="jwjcard-head">
                            <div class="jwjcard-member-item">
                                <div class="jwjcard-member-info">
                                    <span class="jwjcard-member-image image1">
                                        <img src="{{ assets('assets/images/no-image.jpg') }}">
                                    </span>
                                </div>
                                <p>Jane Doe</p>
                            </div>
                            <div class="jwjcard-group-action">
                                <a class="managecommunity-btn" href="{{ route('admin.community-management.approval-details', encrypt_decrypt('encrypt', 1)) }}">View Community</a>
                            </div>
                        </div>
                        <div id="communitycarousel1" class=" communitycarousel1 owl-carousel owl-theme">
                            <div class="item">
                                <div class="community-approval-media">
                                    <img src="{{ assets('assets/images/1.png') }}">
                                </div>
                            </div>

                            <div class="item">
                                <div class="community-approval-media">
                                    <img src="{{ assets('assets/images/2.png') }}">
                                </div>
                            </div>

                            <div class="item">
                                <div class="community-approval-media">
                                    <img src="{{ assets('assets/images/3.png') }}">
                                </div>
                            </div>
                        </div>
                        <div class="jwjcard-body">
                            <div class="admincommunity-text">User Community</div>
                            <div class="community-description">
                                <h3>In Christ We Unite: A Community Of Love And Faith</h3>
                                <p> Today wins a good din I washed this morning and everyone showed for their shift. The boss bought lunch for us all. It was Gus, but it was gocd,after wocktenet warry snd kitin et.TO’elants for dinks. The muse was sold nil we tound some cecere to play darts with. 1 ave lonn @ride home. We gre aDking forwart to meetinz to ts wedkend to go to six Floes, Italred with from this ottomoon, she is planning on hosting Thankhirg this year, Shals going to ma up a menu for all of us to pick from.think will batre greco bean cosserole this your, Was wall</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection