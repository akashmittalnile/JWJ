@extends('layouts.auth-app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/auth.css') }}">
<style>
    .toast.toast-error{
        background-color: #eb3316;
    }
    .toast.toast-success{
        background-color: #26e35b;
    }
</style>
@endpush

@section('title','Journey with Journals - Admin Forgot Password')
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
                            <h2>Admin Forgot Password</h2>
                            <form class="pt-4" method="post" action="{{ route('admin.send.otp') }}" id="login-form">@csrf
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email Address" value="{{ old('email') ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="auth-form-btn">Send OTP</button>
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
            },
            messages: {
                email: {
                    required: 'Please enter email address',
                },
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                $("#preloader").show();
                form.submit();
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