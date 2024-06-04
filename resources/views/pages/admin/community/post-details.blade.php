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
                                    <img src="{{ (isset($post->user->profile) && file_exists(public_path('uploads/profile/'.$post->user->profile)) ) ? assets('uploads/profile/'.$post->user->profile) : assets('assets/images/no-image.jpg') }}">
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
                                <a class="delete-btn1" data-bs-toggle="modal" data-bs-target="#deletePostModal" href="javascript:void(0)"> <img src="{{ assets('assets/images/trash.svg') }}"> Delete Post</a>
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
                                                <a class="Like-btn"><img id="likeme" data-isliked="{{ $isLiked }}" data-likecount="{{ $likesCount ?? 0 }}" data-postid="{{ encrypt_decrypt('encrypt', $post->id) }}" src="{{ $isLiked ? assets('assets/images/like1.svg') : assets('assets/images/like.svg') }}"> <span id="likesCounting">{{ $likesCount ?? 0 }}</span> likes</a>
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
                                <h1>Comments({{ $commentCount ?? 0 }})</h1>
                                <div class="jwj-comment-head-action">
                                    <a href="javascript:void(0)" class="addcomment-btn" data-bs-toggle="modal" data-bs-target="#addcomment"><i class="las la-plus"></i> Add comment</a>
                                </div>
                            </div>

                            @forelse($commentArr as $item)
                            <div class="jwj-comment-item">
                                <div class="jwj-comment-profile">
                                    <img src="{{ (isset($item['posted_by_profile_image']) && file_exists(public_path('uploads/profile/'.$item['posted_by_profile_image'])) ) ? assets('uploads/profile/'.$item['posted_by_profile_image']) : assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="jwj-comment-content">
                                    <div class="jwj-comment-head">
                                        <h2 style="font-weight: 500; font-size: 15px">{{ $item['posted_by'] ?? 'NA' }}</h2>
                                        <div class="jwj-date"><i class="las la-calendar"></i>{{ $item['posted_date'] }}</div>
                                    </div>
                                    <div class="jwj-comment-descr mb-2">{{ $item['comment'] ?? 'NA' }}</div>
                                    <div class="jwj-comment-action">
                                        <a class="Reply-btn"  href="javascript:void(0)" data-commentid="{{ encrypt_decrypt('encrypt', $item['comment_id']) }}" ><i class="las la-reply"></i> Reply</a>
                                        @if($item['my_comment'])
                                        <a class="edit-btn1" data-comment="{{ $item['comment'] ?? '' }}" data-commentid="{{ encrypt_decrypt('encrypt', $item['comment_id']) }}" href="javacript:void(0)"><img src="{{ assets('assets/images/editwh.svg') }}"> Edit</a>
                                        @endif
                                        <a class="delete-btn1" id="delete-button" data-commentid="{{ encrypt_decrypt('encrypt', $item['comment_id']) }}" href="javascript:void(0)"><img src="{{ assets('assets/images/trash.svg') }}"> Delete </a>
                                    </div>
                                </div> 
                            </div>
                                @foreach($item['reply'] as $val)
                                <div class="jwj-comment-item sub-comment"> 
                                    <div class="jwj-comment-profile">
                                        <img src="{{ (isset($val['reply_posted_by_profile_image']) && file_exists(public_path('uploads/profile/'.$val['reply_posted_by_profile_image'])) ) ? assets('uploads/profile/'.$val['reply_posted_by_profile_image']) : assets('assets/images/no-image.jpg') }}">
                                    </div>
                                    <div class="jwj-comment-content">
                                        <div class="jwj-comment-head">
                                            <h2 style="font-weight: 500; font-size: 15px">{{ $val['reply_posted_by'] ?? 'NA' }}</h2>
                                            <div class="jwj-date"><i class="las la-calendar"></i>{{ $val['reply_posted_date'] ?? 'NA' }}</div>
                                        </div>
                                        <div class="jwj-comment-descr mb-2">{{ $val['reply_comment'] ?? 'NA' }}</div>
                                        <div class="jwj-comment-action">
                                            <a class="Reply-btn" data-commentid="{{ encrypt_decrypt('encrypt', $item['comment_id']) }}"  href="javascript:void(0)"><i class="las la-reply"></i> Reply</a>
                                            @if($val['reply_my_comment'])
                                            <a class="edit-btn1" data-comment="{{ $val['reply_comment'] ?? '' }}" data-commentid="{{ encrypt_decrypt('encrypt', $val['reply_id']) }}" href="javacript:void(0)"><img src="{{ assets('assets/images/editwh.svg') }}"> Edit</a>
                                            @endif
                                            <a class="delete-btn1" data-commentid="{{ encrypt_decrypt('encrypt', $val['reply_id']) }}" id="delete-button" data-commentid="" href="javascript:void(0)"><img src="{{ assets('assets/images/trash.svg') }}"> Delete </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @empty
                            <div class="d-flex justify-content-center align-items-center flex-column">
                                <div>
                                    <img width="350" src="{{ assets('assets/images/no-comment.svg') }}" alt="no-data">
                                </div>
                            </div>
                            @endforelse


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

<!-- edit comment -->
<div class="modal lm-modal fade" id="editComment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form">
                    <h2>Edit Comment</h2>
                    <form action="{{ route('admin.community-management.post.edit.comment') }}" method="post" id="editCommentForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" class="form-control" name="comment" id="old_comment" placeholder="Enter comment"></textarea>
                                    <input type="hidden" value="{{ encrypt_decrypt('encrypt', $post->id) }}" name="post_id">
                                    <input type="hidden" value="" id="edit_comment_id" name="comment_id">
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
                    <form action="{{ route('admin.community-management.post.create.comment') }}" method="post" id="replyForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" class="form-control" name="comment" placeholder="Enter reply"></textarea>
                                    <input type="hidden" value="" name="comment_id" id="reply_comment_id">
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
    $(document).on('click', ".Reply-btn", function() {
        $('#reply_comment_id').val($(this).data('commentid'));
        $('#addReply').modal('show');
    });

    $(document).on("click", "#likeme", function() {
        let isLiked = $(this).data('isliked');
        if(!isLiked){
            $("#likesCounting").text(parseInt($(this).data('likecount')) + 1);
            $(this).attr("src", "{{ assets('assets/images/like1.svg') }}");
        } else {
            $("#likesCounting").text(parseInt($(this).data('likecount')) - 1);
            $(this).attr("src", "{{ assets('assets/images/like.svg') }}");
        }
        let formData = new FormData();
        formData.append('id', $(this).data('postid'))
        formData.append('_token', "{{ csrf_token() }}")
        $.ajax({
            type: 'post',
            url: "{{ route('admin.community-management.post.like.unlike') }}",
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
    });

    $(document).on('click', ".edit-btn1", function() {
        $('#edit_comment_id').val($(this).data('commentid'));
        $('#old_comment').val($(this).data('comment'));
        $('#editComment').modal('show');
    });

    $(document).on('click', '#delete-button', function(e) {
        e.preventDefault();
        $('#communityCommentId').val($(this).data('commentid'));
        $('#deleteCommentModal').modal('show');
    });

    $('#addcomment, #addReply').on('hidden.bs.modal', function(e) {
        $(this).find('form').trigger('reset');
        $(".form-control").removeClass("is-invalid");
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
                        

        $('#replyForm').validate({
            rules: {
                comment: {
                    required: true,
                },
            },
            messages: {
                comment: {
                    required: 'Please enter reply',
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

        $('#editCommentForm').validate({
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