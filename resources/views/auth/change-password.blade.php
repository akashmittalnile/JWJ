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
                            <h2>Admin Forgot Password - Reset Password</h2>
                            <form class="pt-4" method="post" action="{{ route('admin.change.password') }}" id="login-form">@csrf
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="New Password">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="cnf_password" placeholder="Confirm Password">
                                </div>
                                <input type="hidden" name="email" value="{{ $email ?? '' }}">
                                <div class="form-group">
                                    <button type="submit" class="auth-form-btn">Submit</button>
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
    
        $.validator.addMethod("AtLeastOnenumber", function(value) {
            return /(?=.*[0-9])/.test(value);
        }, 'At least 1 number is required.');

        $.validator.addMethod("AtLeastOneUpperChar", function(value) {
            return /^(?=.*[A-Z])/.test(value);
        }, 'At least 1 uppercase character is required.');

        $.validator.addMethod("AtLeastOneLowerChar", function(value) {
            return /^(?=.*[a-z])/.test(value);
        }, 'At least 1 lower character is required.');

        $.validator.addMethod("AtLeastOneSpecialChar", function(value) {
            return !/^[A-Za-z0-9 ]+$/.test(value);
        }, 'At least 1 special character is required.');

        $('#login-form').validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8,
                    maxlength: 15,
                    AtLeastOnenumber: true,
                    AtLeastOneUpperChar: true,
                    AtLeastOneLowerChar: true,
                    AtLeastOneSpecialChar: true
                },
                cnf_password: {
                    required: true,
                    equalTo: "input[name='password']"
                },
            },
            messages: {
                password: {
                    required: 'Please enter your new password',
                },
                cnf_password: {
                    required: 'Please enter your confirm password',
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