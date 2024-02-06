@extends('layouts.auth-app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/auth.css') }}">
@endpush

@section('title','Journey with Journals - Admin Login')
@section('content')
<div class="auth-section auth-height">
    <div class="auth-bg-video">
        <img src="{{ assets('assets/images/auth-bg.jpg') }}" id="background-video">
    </div>
    <div class="auth-content-card">
        <div class="container">
            <div class="auth-card">
                <div class="row justify-content-center">
                    <div class="col-md-12 auth-form-info">
                        <div class="auth-form">
                            <div class="brand-logo">
                                <img src="{{ assets('assets/images/logo.svg') }}" alt="logo">
                            </div>
                            <h2>Admin Login</h2>
                            <form class="pt-4" method="post" action="{{ route('admin.check.user') }}" id="login-form">@csrf
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email Address">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="auth-form-btn">Login</button>
                                </div>

                                <div class="mt-1 forgotpsw-text">
                                    <a href="javascript:void(0);">I forgot my password</a>
                                </div>
                            </form>
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
    
        $.validator.addMethod("emailValidate", function(value) {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
        }, 'Please enter valid email address.');

        $('#login-form').validate({
            rules: {
                email: {
                    required: true,
                    emailValidate: true,
                },
                password: {
                    required: true,
                },
            },
            messages: {
                email: {
                    required: 'Please enter email address',
                },
                password: {
                    required: 'Please enter your password',
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
                    beforeSend: function() {
                        $("#preloader").show()
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            window.location = response.route;
                            return false;
                        } else {
                            toastr.error(response.message);
                            return false;
                        }
                    },
                    error: function(data, textStatus, errorThrown) {
                        jsonValue = jQuery.parseJSON( data.responseText );
                        console.error(jsonValue.message);
                    },
                    complete: function() {
                        $("#preloader").hide()
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