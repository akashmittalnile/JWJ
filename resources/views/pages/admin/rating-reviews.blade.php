@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/reviews.css') }}">
@endpush

@section('title','Journey with Journals - Rating & Reviews')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Rating & Reviews</h4>
    <div class="search-filter wd5">
        <div class="row g-1">
            <div class="col-md-4">
                <div class="form-group">
                    <a href="javascript:void(0)" class="btn-bl"><img src="{{ assets('assets/images/download.svg') }}"> Download report</a>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group">
                    <input type="month" name="" class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="search-form-group">
                        <input type="text" name="" class="form-control" placeholder="Search by name">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="revenue-page-section">
        <div class="revenue-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="jwjcard">
                        <div class="card-body">
                            <div class="jwj-table">
                                <table class="table xp-table  " id="customer-table">
                                    <thead>
                                        <tr class="table-hd">
                                            <th>Sr No.</th>
                                            <th>Name</th>
                                            <th>Rating</th>
                                            <th>Review</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="sno">1</div>
                                            </td>
                                            <td>
                                                Jane Doe
                                            </td>
                                            <td>
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
                                            </td>
                                            <td>
                                                I recently had the pleasure of visiting this furniture store…
                                            </td>
                                            <td>
                                                <a class="trash-btn" href=""><img src="{{ assets('assets/images/trash.svg') }}"></a>
                                                <a class="reply-btn" href=""><img src="{{ assets('assets/images/reply.svg') }}"></a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="sno">2</div>
                                            </td>
                                            <td>
                                                Jane Doe
                                            </td>
                                            <td>
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
                                            </td>
                                            <td>
                                                As a User Experience Designer, I know the importance of seamless interactions, and…
                                            </td>
                                            <td>
                                                <a class="trash-btn" href=""><img src="{{ assets('assets/images/trash.svg') }}"></a>
                                                <a class="reply-btn" data-bs-toggle="modal" data-bs-target="#Sendreply"><img src="{{ assets('assets/images/reply.svg') }}"></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="jwj-table-pagination">
                                <ul class="jwj-pagination">
                                    <li class="disabled" id="example_previous">
                                        <a href="javascript:void(0)" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                    </li>
                                    <li class="active">
                                        <a href="javascript:void(0)" class="page-link">1</a>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0)" aria-controls="example" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0)" aria-controls="example" data-dt-idx="3" tabindex="0" class="page-link">3</a>
                                    </li>
                                    <li class="next" id="example_next">
                                        <a href="javascript:void(0)" aria-controls="example" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                                    </li>
                                </ul>
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