@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community-management.css') }}">
@endpush

@section('title','Journey with Journals - Journal Details')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Journal Details</h4>
    <div class="search-filter wd4">
        <div class="row g-1">
            <div class="col-md-7">

            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <a href="{{ route('admin.journal.list') }}" class="btn-bl">Back</a>
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
                        <div class="jwj-posts-head d-flex justify-content-between">
                            <div class="post-member-item">
                                <div class="post-member-image">
                                    <img src="{{ (isset($data->user_image) && file_exists(public_path('uploads/profile/'.$data->user_image))) ? assets('uploads/profile/'.$data->user_image) : assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="post-member-text">
                                    <h3>{{ $data->user_name ?? 'NA' }}</h3>
                                    <div class="post-member-plan"><img src="{{ assets('assets/images/freeplan.svg') }}"> Plan A member</div>
                                </div>
                            </div>
                            <div class="post-member-item">
                                <div class="post-member-image">
                                    <img src="{{ isset($data->mood->logo) ? assets('assets/images/'.$data->mood->logo) : assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="post-member-text">
                                    <h3>{{$data->mood->name ?? 'NA'}}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="jwj-posts-body">
                            <div class="row g-1">
                                <div class="col-md-4">
                                    <div id="communitycarousel1" class="communitycarousel1 owl-carousel owl-theme">
                                        @forelse($data->images as $item)
                                        <div class="item">
                                            <div class="community-posts-media">
                                                <a data-fancybox="" href="{{ assets('uploads/journal/'.$item->name) }}">
                                                    <img src="{{ assets('uploads/journal/'.$item->name) }}">
                                                </a>
                                            </div>
                                        </div>
                                        @empty
                                        <div class='item'>
                                            <div class='community-posts-media'>
                                                <img src="{{ assets('assets/images/no-image.jpg') }}">
                                            </div>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="jwjcard-body">
                                        <div class="community-post-description">
                                            <h3>{{ $data->title ?? 'NA' }}</h3>
                                            @forelse($data->searchCriteria as $value)
                                            <div class='admincommunity-text' style='margin-right: 5px;'>{{$value->criteria->name}}</div>
                                            @empty
                                            @endforelse
                                            <p>{{ $data->content ?? 'NA' }}</p>
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
                                            <div class="jwjcard-member-item">
                                                <!-- <div class="jwjcard-member-info">
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
                                                <p>0 Member Follows</p> -->
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
            url: "{{ route('admin.journal.change.status') }}",
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