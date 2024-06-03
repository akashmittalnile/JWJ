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
                    <a href="{{ route('admin.community-management.details', encrypt_decrypt('encrypt', $post->community_id)) }}" class="btn-bl">Back</a>
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
                                    <img src="{{ isset($post->user->profile) ? assets('uploads/profile/'.$post->user->profile) : assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="post-member-text">
                                    <h3>{{ $post->user->name ?? 'NA' }}</h3>
                                    <div class="post-member-plan">
                                        @if($post->user->role == 2) Administrator
                                        @else
                                        <img src="{{ isset($post->user->plan->image) ? assets('assets/images/'.$post->user->plan->image) : assets('assets/images/freeplan.svg') }}">
                                        {{ $post->user->plan->name ?? 'Plan A' }} Member
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="jwjcard-group-action">
                                <a class="managecommunity-btn" data-bs-toggle="modal" data-bs-target="#deletePostModal" href="javascript:void(0)">Delete Post</a>
                            </div>
                        </div>
                        <div class="jwj-posts-body">
                            <div class="row g-1">
                                <div class="col-md-4">
                                    <div id="communitycarousel1" class=" communitycarousel1 owl-carousel owl-theme">
                                        @forelse($post->images as $item)
                                        <div class="item">
                                            <div class="community-posts-media">
                                                <a data-fancybox="" href="{{ assets('uploads/community/post/'.$item->name) }}">
                                                    <img src="{{ assets('uploads/community/post/'.$item->name) }}">
                                                </a>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="item">
                                            <div class="community-posts-media">
                                                <img src="{{ assets('assets/images/no-image.jpg') }}">
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="jwjcard-body">
                                        <div class="community-post-description">
                                            <h3>{{ $post->title ?? 'NA' }}</h3>
                                            <div class="post-date-info">
                                                <img src="{{ assets('assets/images/calendar.svg') }}"> Submitted On : {{ date('d M, Y h:iA', strtotime($post->created_at)) }}
                                            </div>
                                            <p>{{ $post->post_description ?? 'NA' }}</p>
                                            <div class="community-post-action">
                                                <a class="Like-btn"><img src="{{ assets('assets/images/like.svg') }}"> {{ $post->likeCount() ?? 0 }} likes</a>
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
                                <h1>Comments({{ $post->commentCount() ?? 0 }})</h1>
                                <div class="jwj-comment-head-action">
                                    <a href="javascript:void(0)" class="addcomment-btn" data-bs-toggle="modal" data-bs-target="#addcomment"><i class="las la-plus"></i> Add comment</a>
                                </div>
                            </div>

                            @forelse($post->comments() as $item)
                            <div class="jwj-comment-item block">
                                <div class="jwj-comment-profile">
                                    <img src="{{ isset($item->user->profile) ? assets('uploads/profile/'.$item->user->profile) : assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwj-comment-content">
                                    <div class="jwj-comment-head">
                                        <h2 style="font-weight: 500; font-size: 15px">{{ $item->user->name ?? 'NA' }}</h2>
                                        <div class="jwj-date"><i class="las la-calendar"></i>{{ date('d M, Y h:iA', strtotime($item->created_at)) }}</div>
                                    </div>
                                    <div class="jwj-comment-descr mb-2">{{ $item->comment ?? 'NA' }}</div>
                                    <div class="jwj-comment-action">
                                        <a class="Reply-btn"  href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addReply"><i class="las la-reply"></i> Reply</a>
                                        <a class="edit-btn1" href="#"><img src="{{ assets('assets/images/editwh.svg') }}"> Edit</a>
                                        <a class="delete-btn1" id="delete-button" data-commentid="{{ encrypt_decrypt('encrypt', $item->id) }}" href="javascript:void(0)"><img src="{{ assets('assets/images/trash.svg') }}">Delete</a>
                                    </div>
                                </div> 
                            </div>

                            <div class="jwj-comment-item block sub-comment"> 
                                <div class="jwj-comment-profile">
                                    <img src="{{ isset($item->user->profile) ? assets('uploads/profile/'.$item->user->profile) : assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwj-comment-content">
                                    <div class="jwj-comment-head">
                                        <h2 style="font-weight: 500; font-size: 15px">{{ $item->user->name ?? 'NA' }}</h2>
                                        <div class="jwj-date"><i class="las la-calendar"></i>{{ date('d M, Y h:iA', strtotime($item->created_at)) }}</div>
                                    </div>
                                    <div class="jwj-comment-descr mb-2">{{ $item->comment ?? 'NA' }}</div>
                                    <div class="jwj-comment-action">
                                        <a class="Reply-btn"  href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addReply"><i class="las la-reply"></i> Reply</a>
                                        <a class="delete-btn1" id="delete-button" data-commentid="{{ encrypt_decrypt('encrypt', $item->id) }}" href="javascript:void(0)"><img src="{{ assets('assets/images/trash.svg') }}">Delete</a>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div>
                                    <img width="350" src="{{ assets('assets/images/no-comment.svg') }}" alt="no-data">
                                </div>
                            </div>
                            @endforelse

                            @if($post->commentCount() > 4 )
                            <div class="text-center mt-4">
                                <a style="width: 12%;" href="javascript:void(0)" id="loadMore" class="addcomment-btn">Load More</a>
                                <a style="width: 12%;" href="javascript:void(0)" class="d-none addcomment-btn" id="showLess">Show Less</a>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal lm-modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form text-center">
                    <h2>Are You Sure?</h2>
                    <p>You want to delete this comment!</p>
                    <form action="{{ route('admin.community-management.post.comment.delete') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="id" value="{{ $post->id }}" id="communityCommentId">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                    <button type="submit" class="save-btn">Yes! Delete</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal lm-modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form text-center">
                    <h2>Are You Sure?</h2>
                    <p>You want to delete this post!</p>
                    <form action="{{ route('admin.community-management.post.delete') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="postId" value="{{ $post->id }}" id="communityPostId">
                                <input type="hidden" name="postDetail" value="true">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                    <button type="submit" class="save-btn">Yes! Delete</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
                    <form action="{{ route('admin.community-management.post.create.comment') }}" method="post" id="commentForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" class="form-control" name="comment" placeholder="Enter comment"></textarea>
                                    <input type="hidden" value="{{ encrypt_decrypt('encrypt', $post->id) }}" name="post_id">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="submit" class="save-btn">Send</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- add Reply -->
<div class="modal lm-modal fade" id="addReply" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form">
                    <h2>Add Reply</h2>
                    <form action="" method="post" id="commentForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" class="form-control" name="comment" placeholder="Enter comment"></textarea>
                                    <input type="hidden" value="{{ encrypt_decrypt('encrypt', $post->id) }}" name="post_id">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="submit" class="save-btn">Send</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('js')
<script>
    $(document).on('click', '#delete-button', function(e) {
        e.preventDefault();
        $('#communityCommentId').val($(this).data('commentid'));
        $('#deleteCommentModal').modal('show');
    });

    $('#addcomment').on('hidden.bs.modal', function(e) {
        $(this).find('form').trigger('reset');
        $("#addcomment .form-control").removeClass("is-invalid");
    });

    $(document).ready(function() {
        $('.communitycarousel1').owlCarousel({
            loop: true,
            margin: 10,
            nav: false,
            dots: false,
            responsive: {
                1000: {
                    items: 1
                }
            }
        });
                            
        let len = $(".block").length;
        $(".block").slice(4, len).hide();
        if ($(".block:hidden").length != 0) {
            $("#loadMore").show();
        }
        $("#loadMore").on("click", function(e) {
            e.preventDefault();
            let hidelen = $(".block:hidden").length
            $(".block:hidden").slice(0, hidelen).slideDown();
            $("#showLess").removeClass('d-none');
            $(this).addClass('d-none');
        });
        $("#showLess").on("click", function(e) {
            e.preventDefault();
            $(".block").slice(4, len).slideUp();
            $("#loadMore").removeClass('d-none');
            $(this).addClass('d-none');
        });

        $('#commentForm').validate({
            rules: {
                comment: {
                    required: true,
                },
            },
            messages: {
                comment: {
                    required: 'Please enter comment',
                },
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                let formData = new FormData(form);
                $.ajax({
                    type: 'post',
                    url: form.action,
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            setInterval(() => {window.location.reload()}, 2000);
                            return false;
                        } else {
                            toastr.error(response.message);
                            return false;
                        }
                    },
                    error: function(data, textStatus, errorThrown) {
                        jsonValue = jQuery.parseJSON(data.responseText);
                        console.error(jsonValue.message);
                    },
                })
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
        });
    });
</script>
@endpush