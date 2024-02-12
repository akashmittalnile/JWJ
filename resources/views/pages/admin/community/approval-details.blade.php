@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community-approval.css') }}">
@endpush

@section('title','Journey with Journals - Community Details')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>@if($data->status==3) Rejected @else Approval @endif Community Details</h4>
    <div class="search-filter wd4">
        <div class="row g-1">
            <div class="col-md-7">

            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <a href="{{ route('admin.community-management.approval') }}" class="btn-bl">Back</a>
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
                                    <img src="{{ assets('uploads/profile/'.$data->user_image) }}">
                                </div>
                                <div class="post-member-text">
                                    <h3>{{ $data->user_name ?? 'NA' }}</h3>
                                    <div class="post-member-plan"><img src="{{ assets('assets/images/freeplan.svg') }}"> Plan A member</div>
                                </div>
                            </div>
                            @if($data->status == 3)
                            <div class="jwjcard-group-action">
                                <div class="Rejected-status">Rejected</div>
                            </div>
                            @endif
                        </div>
                        <div class="jwj-posts-body">
                            <div class="row g-1">
                                <div class="col-md-4">
                                    <div id="communitycarouse" class="communitycarouse owl-carouse owl-them">
                                        <div class="item">
                                            <div class="community-posts-media">
                                                <img src="{{ assets('uploads/community/'.$data->image_name) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="jwjcard-body">
                                        <div class="community-post-description">
                                            <h3>{{ $data->name ?? 'NA' }}</h3>
                                            <div class="admincommunity-text">User Community</div>
                                            <p>{{ $data->description ?? 'NA' }}</p>
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
                                                <p>0 Member Follows</p>
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
                @if($data->status==3)
                <div class="col-md-12">
                    <div class="jwj-reject-resion-card">
                        <h3>Rejection Reason</h3>
                        <p>{{ $data->reject_reason ?? 'NA' }}</p>                                    
                    </div>    
                </div>
                @endif
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
                    <form action="{{ route('admin.community-management.change.status') }}" method="post" id="rejectform" >@csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" class="form-control" name="reason" placeholder="Type Your Reply Message Here.."></textarea>
                                </div>
                                <input type="hidden" name="id" value="{{ encrypt_decrypt('encrypt', $data->id) }}">
                                <input type="hidden" name="status" value="3">
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="submit" class="save-btn">Submit</button>
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
    $(document).on('click', '.approvecommunity-btn', function(e) {
        let status = 1;
        let id = "{{ encrypt_decrypt('encrypt', $data->id) }}";
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
                    window.location.href = "{{ route('admin.community-management.list') }}";
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

    $('#rejectform').validate({
        rules: {
            reason: {
                required: true,
            },
        },
        messages: {
            reason: {
                required: 'Please enter the reason',
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
                        window.location.href = "{{ route('admin.community-management.list') }}";
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
</script>
@endpush