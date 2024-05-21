@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community-management.css') }}">
@endpush

@section('title','Journey with Journals - Community Management Details')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Community Management Details</h4>
    <div class="search-filter wd1">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ route('admin.community-management.list') }}" class="btn-bl">Back</a>
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
                                    <img src="{{ isset($data->user_image) ? assets('uploads/profile/'.$data->user_image) : assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="post-member-text">
                                    <h3>{{ $data->user_name ?? 'NA' }}</h3>
                                </div>
                            </div>
                            <div class="jwjcard-group-action">
                                <div class="jwjcard-member-item">
                                    <div class="jwjcard-member-info">
                                        @php $count=1; @endphp
                                        @forelse($follow as $item)
                                        @if($count > 3) @break @endif
                                        @php $user = $item->user; @endphp
                                        <span class="jwjcard-member-image image{{$count}}">
                                            <img src="{{ isset($user->profile) ? assets('uploads/profile/'.$user->profile) : assets('assets/images/no-image.jpg') }}">
                                        </span>
                                        @php $count++; @endphp
                                        @empty
                                        @endforelse
                                    </div>
                                    <p>{{ count($follow) }} Followers</p>
                                </div>
                            </div>
                        </div>
                        <div class="jwj-posts-body">
                            <div class="row g-1">
                                <div class="col-md-4">
                                    <div id="communitycarousel1" class=" communitycarousel1 owl-carousel owl-theme">
                                        @forelse($imgs as $item)
                                        <div class="item">
                                            <div class="community-posts-media">
                                                <img src="{{ assets('uploads/community/'.$item->name) }}">
                                            </div>
                                        </div>
                                        @empty
                                        <div class='item'>
                                            <div class='community-media'>
                                                <img src="{{ assets('assets/images/no-image.jpg') }}">
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="jwjcard-body">
                                        <div class="community-post-description mb-1">
                                            <h3>{{ $data->name ?? 'NA' }}</h3>
                                            <div class="admincommunity-text">@if($data->role==2) Admin @else User @endif Community</div>
                                            <p>{{ $data->description ?? 'NA' }}</p>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="User-contact-info">
                                                    <div class="User-contact-info-content">
                                                        <h2>Status</h2>
                                                        <div class="switch-toggle">
                                                            <p style='color: #8C9AA1;'>Inactive</p>
                                                            <div class="">
                                                                <label class="toggle" for="myToggle">
                                                                    <input data-id="{{encrypt_decrypt('encrypt', $data->id)}}" class="toggle__input" name="status" @if($data->status==1) checked @endif type="checkbox" id="myToggle">
                                                                    <div class="toggle__fill"></div>
                                                                </label>
                                                            </div>
                                                            <p style='color: #8C9AA1;'>Active</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="post-date-info">
                                                    <img src="{{ assets('assets/images/calendar.svg') }}"> Active since: {{ date('d M, Y h:i a', strtotime($data->created_at)) }}
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


    <div class="community-page-section">
        <div class="row">
            <div class="col-md-4">
                <div class="sidebar-member-info">
                    <div class="sidebar-member-head">
                        <h2>Member List</h2>
                    </div>
                    <div class="sidebar-member-body">
                        <div class="sidebar-member-list-card" style="min-height: 37px; max-height: 520px; overflow-y: auto; overflow-x: hidden;">

                            @forelse($follow as $item)
                            @php $user = $item->user; @endphp
                            <div class="sidebar-member-item">
                                <div class="sidebar-member-item-image"><img src="{{ isset($user->profile) ? assets('uploads/profile/'.$user->profile) : assets('assets/images/no-image.jpg') }}"></div>
                                <div class="sidebar-member-item-text">
                                    <h2>{{ $user->name ?? "NA" }}</h2>
                                    <div class="sidebar-member-plan"><img src="{{ isset($user->plan->image) ? assets('assets/images/'.$user->plan->image) : assets('assets/images/freeplan.svg') }}">{{ $user->plan->name ?? 'Plan A' }} Member</div>
                                </div>
                            </div>
                            @empty
                            <div class="sidebar-member-item" style="border: none;">
                                <div class="mx-auto">
                                    No followers
                                </div>
                            </div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="page-posts-title-section">
                    <h4>Total Posts</h4>
                    <div class="search-filter wd7">
                        <div class="row g-1">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="search-form-group">
                                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search by post title">
                                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <a data-bs-toggle="modal" data-bs-target="#PostOnCommunity" href="javascript:void(0)" class="btn-bl">Post On Community</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div id="post-card-lists">

                        </div>

                        <div class="jwj-table-pagination">
                            <ul class="jwj-pagination" id="appendPagination">

                            </ul>
                        </div>

                    </div>
                </div>
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
                                <input type="hidden" name="postId" value="" id="communityPostId">
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

<!-- Post On Community -->
<div class="modal lm-modal fade" id="PostOnCommunity" data-community-id="{{ encrypt_decrypt('encrypt', $data->id) }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form">
                    <h2>Post On Community</h2>
                    <form id="postForm" method="post" action="{{ route('admin.community-management.create-post') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" name="title" class="form-control" placeholder="Title">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="file" name="images[]" accept="image/png, image/jpg, image/jpeg" class="form-control" multiple>
                                    <input type="hidden" name="community_id" id="community_id" value="{{ encrypt_decrypt('encrypt', $data->id) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea name="post_description" class="form-control" placeholder="Type your message here"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="submit" class="save-btn">Post</button>
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
        $(document).on('change', '.toggle__input', function(e) {
            let status = ($(this).is(":checked")) ? 1 : 2;
            let id = $(this).data('id');
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: "{{ route('admin.community-management.change.status') }}",
                data: {
                    id,
                    status,
                    '_token': "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        toastr.success(result.message);
                        setInterval(() => {window.location.reload()}, 2000);
                    } else {
                        toastr.error(result.message);
                        return false;
                    }
                },
                error: function(data, textStatus, errorThrown) {
                    jsonValue = jQuery.parseJSON(data.responseText);
                    console.error(jsonValue.message);
                },
            });
        });


        $(document).ready(function() {
            $('#postForm').validate({
                rules: {
                    title: {
                        required: true,
                    },
                    'images[]': {
                        required: true,
                    },
                    post_description: {
                        required: true,
                    },
                },
                messages: {
                    title: {
                        required: 'Please enter title',
                    },
                    'images[]': {
                        required: 'Please upload at least one image',
                    },
                    post_description: {
                        required: 'Please enter description',
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
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(data, textStatus, errorThrown) {
                            jsonValue = jQuery.parseJSON(data.responseText);
                            console.error(jsonValue.message);
                        },
                    });
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

            const getList = (page, search = null, role = null, ustatus = null) => {
                $.ajax({
                    type: 'get',
                    url: "{{ route('admin.community-management.details', $id) }}",
                    data: {
                        page, search
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result.status) {
                            let userData = result.data.html.data;
                            let html = result.data.html;
                            $("#post-card-lists").html(result.data.html);
                            $('.communitycarouselpost1').owlCarousel({
                                loop: false,
                                margin: 10,
                                nav: false,
                                dots: false,
                                responsive: {
                                    1000: {
                                        items: 1
                                    }
                                }
                            });
                            $("#appendPagination").html('');
                            if (result.data.lastPage != 1) {
                                let paginate = `<li class="${result.data.currentPage==1 ? 'disabled' : ''}" id="example_previous">
                                        <a href="javascript:void(0)" data-page="${result.data.currentPage-1}" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                    </li>`;
                                for (let i = 1; i <= result.data.lastPage; i++) {
                                    paginate += `<li class="${result.data.currentPage==i ? 'active' : ''}">
                                            <a href="javascript:void(0)" data-page="${i}" class="page-link">${i}</a>
                                        </li>`;
                                }
                                paginate += `<li class="${result.data.currentPage==result.data.lastPage ? 'disabled next' : 'next'}" id="example_next">
                                            <a href="javascript:void(0)" data-page="${result.data.currentPage+1}" aria-controls="example" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                                        </li>`;
                                $("#appendPagination").append(paginate);
                            }
                        } else {
                            let html = `<div class="d-flex justify-content-center align-items-center flex-column">
                                        <div>
                                            <img width="350" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
                                        </div>
                                    </div>`;
                            $("#post-card-lists").html(html);
                            $("#appendPagination").html('');
                        }
                    },
                    error: function(data, textStatus, errorThrown) {
                        jsonValue = jQuery.parseJSON(data.responseText);
                        console.error(jsonValue.message);
                    },
                });
            };
            getList(1);
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                getList($(this).data('page'));
            })
            $(document).on('keyup', "#searchInput", function() {
                let search = $("#searchInput").val();
                getList($(this).data('page'), search);
            });
        });





        $(document).on('click', '#confirmDeletePostBtn', function(e) {
            e.preventDefault();
            let postId = $('#deletePostModal').data('postId');
            let $postCard = $('.jwj-posts-posts-card[data-post-id="' + postId + '"]');
            $.ajax({
                type: 'post',
                url: "",
                data: {
                    postId,
                    '_token': "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        // If successful, remove the post card from the DOM
                        $postCard.remove();
                        toastr.success(response.message);
                        $('#deletePostModal').modal('hide');
                        location.reload();
                    } else {
                        toastr.error(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    toastr.error('Error deleting post. Please try again later.');
                }
            });
        });

        $(document).on('click', '#delete-button', function(e) {
            e.preventDefault();
            let postId = $(this).data('postid');
            $('#communityPostId').val(postId); // Set postId to modal
            $('#deletePostModal').modal('show');
        });
    </script>
@endpush