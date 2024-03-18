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
                                    @if($data->role!=2)
                                    <div class="post-member-plan"><img src="{{ assets('assets/images/freeplan.svg') }}"> Plan A member</div>
                                    @endif
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
                                                            <p>Inactive</p>
                                                            <div class="">
                                                                <label class="toggle" for="myToggle">
                                                                    <input data-id="{{encrypt_decrypt('encrypt', $data->id)}}" class="toggle__input" name="status" @if($data->status==1) checked @endif type="checkbox" id="myToggle">
                                                                    <div class="toggle__fill"></div>
                                                                </label>
                                                            </div>
                                                            <p>Active</p>
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
                                    <div class="sidebar-member-plan"><img src="{{ assets('assets/images/freeplan.svg') }}"> Plan A member</div>
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
                                        <input type="text" name="search" id="PostSearch" class="form-control" placeholder="Search by name, tags">
                                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <a data-bs-toggle="modal" data-bs-target="#PostOnCommunity" class="btn-bl"> Post
                                        On Community</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="post-card-lists">
                        </div>
                    </div>
                </div>
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
                    <form id="postForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" name="title" class="form-control" placeholder="Title">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{-- <input type="hidden" name="plan_id" value="{{ $plan_id }}">
                                    <input type="hidden" name="community_id" value="{{ $community_id }}"> --}}
                                    <select name="subscription_plan" class="form-control">
                                        <option>Select Subscription Plan</option>
                                        <option value="A">Plan A Users</option>
                                        <option value="B">Plan B Users</option>
                                        <option value="C">Plan C Users</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{-- <input type="file" name="file" class="form-control"> --}}
                                    <input type="file" name="images[]" class="form-control" multiple>
                                    <input type="hidden" name="community_id" id="community_id" value="{{ encrypt_decrypt('encrypt', $data->id) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea name="post_description" class="form-control" placeholder="Type Your Reply Message Here.."></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Discard</button>
                                    <button type="submit" class="save-btn">Save</button>
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
                    window.location.reload();
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
        $('#postForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'post',
                url: "{{ route('admin.community-management.create-post') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                    // Clear the form fields
                    $('#postForm')[0].reset(); // Reset the form
                    $('#PostOnCommunity').modal('hide');
                },
                error: function(xhr, textStatus, errorThrown) {
                    toastr.error(xhr.responseJSON.error);
                }
            });
        });
    });
    $(document).ready(function() {
        const communityId = "{{ encrypt_decrypt('encrypt', $data->id) }}";
        const getPosts = (searchTerm = null) => {
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.community-management.posts') }}",
                data: {
                    id: communityId,
                    search: searchTerm // Pass the search term to the backend
                },
                dataType: 'json',
                success: function(response) {
                    console.log("response: ", response);
                    if (response.status) {
                        // Clear existing posts
                        $('#post-card-lists').empty();
                        // Append new posts
                        response.data.posts.forEach(post => {
                            // Convert the created_at date to dd-mm-yyyy format
                            const createdAtDate = new Date(post.created_at);
                            const formattedDate =
                                `${createdAtDate.getDate()}-${createdAtDate.getMonth() + 1}-${createdAtDate.getFullYear()}`;
                            let subscription_plan;
                            switch (post.plan_id) {
                                case 6:
                                    subscription_plan = "Plan A member";
                                    break;
                                case 5:
                                    subscription_plan = "Plan B member";
                                    break;
                                case 4:
                                    subscription_plan = "Plan C member";
                                    break;
                                default:
                                    subscription_plan = "Unknown plan";
                            }
                            const imagesHtml = post.images.map(image => {
                                return `<div class="item"><img src="{{ assets('${image}') }} "></div>`;
                            }).join('');
                            $('#post-card-lists').append(`
                            <div class="jwj-posts-posts-card">
                                <div class="jwj-posts-head">
                                    <div class="post-member-item">
                                        <div class="post-member-image">
                                            <img src="{{ assets('assets/images/no-image.jpg') }}">
                                        </div>
                                        <div class="post-member-text">
                                            <h3>${post.user_name}</h3>
                                            <div class="post-member-plan"><img
                                                    src="{{ assets('assets/images/freeplan.svg') }}"> ${subscription_plan}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="jwjcard-group-action">
                                        <a class="managecommunity-btn" data-post-id="${post.id}"  href="javascript:void(0)">Delete Post</a>
                                    </div>
                                </div>
                                <div class="jwj-posts-body">
                                    <div class="row g-1">
                                        <div class="col-md-5">
                                            <div  class="owl-carousel owl-theme">
                                                ${imagesHtml}
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="jwjcard-body">
                                                <div class="community-post-description">
                                                    <h3>${post.title}</h3>
                                                    <div class="post-date-info">
                                                        <img src="">
                                                        Submitted
                                                        On ${formattedDate}
                                                    </div>
                                                    <p>${post.post_description}</p>
                                                    <div class="community-post-action">
                                                        <a class="Like-btn" href="javascript:void(0)"><img
                                                                src="{{ assets('assets/images/like.svg') }}"> 2.5K
                                                            likes</a>
                                                        <a class="Comment-btn" href="javascript:void(0)"><img
                                                                src="{{ assets('assets/images/comment.svg') }}"> 3.2K
                                                            Comments</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                            // Initialize Owl Carousel for images
                            $('.owl-carousel').owlCarousel({
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
                        });
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("error: ", xhr, "========", textStatus, "========",
                        errorThrown);
                    toastr.error("Error retrieving posts.");
                }
            });
        };
        // Call the function to get posts when the document is ready
        getPosts();
        // Search functionality
        $('#PostSearch').on('keyup', function() {
            const searchTerm = $(this).val();
            getPosts(searchTerm); // Call getPosts function with the search term
        });
    });
    $(document).on('click', '.managecommunity-btn', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this post?')) {
            let postId = $(this).data('postId');
            let $postCard = $(this).closest('.jwj-posts-posts-card');
            $.ajax({
                type: 'post',
                url: "{{ route('admin.community-management.post.delete') }}",
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
        }
    });
</script>
@endpush