@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/profile.css') }}">
@endpush

@section('title','Journey with Journals - Profile')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Manage Profile</h4>
</div>

<div class="body-main-content">
    <div class="myprofile-section">
        <div class="row">
            <div class="col-md-4">
                <div class="user-side-profile">
                    <div class="side-profile-item">
                        <div class="side-profile-media"><img src="{{ isset(auth()->user()->profile) ? assets('uploads/profile/'.auth()->user()->profile) : assets('assets/images/no-image.jpg') }}"></div>
                        <div class="side-profile-text">
                            <h2>{{ auth()->user()->name ?? 'NA' }}</h2>
                            <p>Administrator</p>
                        </div>
                    </div>

                    <div class="side-profile-overview-info">
                        <div class="row g-1">
                            <div class="col-md-12">
                                <div class="side-profile-total-order">
                                    <div class="side-profile-total-icon">
                                        <img src="{{ assets('assets/images/sms1.svg') }}">
                                    </div>
                                    <div class="side-profile-total-content">
                                        <h2>Email Address</h2>
                                        <p>{{ auth()->user()->email ?? 'NA' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="side-profile-total-order">
                                    <div class="side-profile-total-icon">
                                        <img src="{{ assets('assets/images/call1.svg') }}">
                                    </div>
                                    <div class="side-profile-total-content">
                                        <h2>Phone Number</h2>
                                        <p>{{ auth()->user()->country_code ?? '' }} {{ auth()->user()->mobile ?? 'NA' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="side-profile-total-order">
                                    <div class="side-profile-total-icon">
                                        <img src="{{ assets('assets/images/location.svg') }}">
                                    </div>
                                    <div class="side-profile-total-content">
                                        <h2>Address</h2>
                                        <p>{{ auth()->user()->address ?? 'NA' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="myprofile-form-section">
                    <div class="myprofile-form-heading">
                        <h3>Edit Profile Details</h3>
                    </div>
                    <div class="myprofile-form">
                        <form action="{{ route('admin.update.profile') }}" method="post" enctype="multipart/form-data" id="profile-form">@csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>Name</h4>
                                        <input type="text" class="form-control" value="{{ auth()->user()->name ?? '' }}" name="name" placeholder="Enter Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>Email Address</h4>
                                        <input type="email" class="form-control" value="{{ auth()->user()->email ?? '' }}" name="email" placeholder="Enter Email" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>Phone</h4>
                                        <input type="tel" class="form-control" value="{{ auth()->user()->mobile ?? '' }}" name="mobile" placeholder="Enter Phone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>Address</h4>
                                        <input type="text" class="form-control" value="{{ auth()->user()->address ?? '' }}" name="address" placeholder="Enter Address">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>Zip Code</h4>
                                        <input type="text" class="form-control" value="{{ auth()->user()->zipcode ?? '' }}" name="zipcode" placeholder="Enter Zip Code">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>Profile Image</h4>
                                        <input type="file" class="form-control" accept="image/png, image/jpg, image/jpeg" name="file">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="Savebtn">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="myprofile-form-section">
                    <div class="myprofile-form-heading">
                        <h3>Change Password</h3>
                    </div>
                    <div class="myprofile-form">
                        <form action="{{ route('admin.update.password') }}" method="post" id="password_form">@csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>Old Password</h4>
                                        <input type="password" class="form-control" name="current_password" placeholder="Enter Old Password">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>New Password</h4>
                                        <input type="password" class="form-control" name="password" placeholder="Enter New Password">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>Confirm New Password</h4>
                                        <input type="password" class="form-control" name="new_password" placeholder="Enter Confirm New Password">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="Savebtn">Change Password</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $.validator.addMethod("emailValidate", function(value) {
        return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
    }, 'Please enter valid email address.');

    $.validator.addMethod("notEqual", function(value, element, param) {
        return this.optional(element) || value != $(param).val();
    }, 'Old password and New password should not same.');

    $.validator.addMethod("AtLeastOnenumber", function(value) {
        return /(?=.*[0-9])/.test(value);
    }, 'At least 1 number is required.');

    $.validator.addMethod("AtLeastOneUpperChar", function(value) {
        return /^(?=.*[A-Z])/.test(value);
    }, 'At least 1 uppercase character is required.');

    $.validator.addMethod("AtLeastOneSpecialChar", function(value) {
        return !/^[A-Za-z0-9 ]+$/.test(value);
    }, 'At least 1 special character is required.');

    $.validator.addMethod("AtLeastOneLowerChar", function(value) {
        return /^(?=.*[a-z])/.test(value);
    }, 'At least 1 lower character is required.');

    $.validator.addMethod("phoneValidate", function(value) {
        return /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(value);
    }, 'Please enter valid phone number.');

    $('#profile-form').validate({
        rules: {
            name: {
                required: true,
            },
            mobile: {
                required: true,
                phoneValidate: true
            },
            address: {
                required: true,
            },
            zipcode: {
                required: true,
            },
            @if(!isset(auth()->user()->profile))
            file: {
                required: true,
            },
            @endif
        },
        messages: {
            name: {
                required: 'Please enter your name',
            },
            mobile: {
                required: 'Please enter your phone number',
            },
            address: {
                required: 'Please enter your address',
            },
            zipcode: {
                required: 'Please enter your zipcode',
            },
            @if(!isset(auth()->user()->profile))
            file: {
                required: 'Please upload profile image',
            },
            @endif
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
                beforeSend: function() {
                    $("#preloader").show()
                },
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
                complete: function() {
                    $("#preloader").hide()
                },
                error: function(data, textStatus, errorThrown) {
                    jsonValue = jQuery.parseJSON( data.responseText );
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

    $('#password_form').validate({
        rules: {
            current_password: {
                required: true,
                minlength: 6,
                remote: {
                    type: 'get',
                    url: "{{ route('admin.check.password') }}",
                    dataType: 'json'
                }
            },
            password: {
                required: true,
                maxlength: 15,
                minlength: 6,
                notEqual: "input[name='current_password']",
                AtLeastOnenumber: true,
                AtLeastOneUpperChar: true,
                AtLeastOneLowerChar: true,
                AtLeastOneSpecialChar: true
            },
            new_password: {
                required: true,
                equalTo: "input[name='password']"
            },
        },
        messages: {
            current_password: {
                required: 'Please enter your old password',
            },
            password: {
                required: 'Please enter your new password',
            },
            new_password: {
                required: 'Please re-enter new Password',
            }
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
                beforeSend: function() {
                    $("#preloader").show()
                },
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
                complete: function() {
                    $("#preloader").hide()
                },
                error: function(data, textStatus, errorThrown) {
                    jsonValue = jQuery.parseJSON( data.responseText );
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