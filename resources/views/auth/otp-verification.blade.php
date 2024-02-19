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
    input {
        border: none;
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
                            <h2>Admin Forgot Password - OTP Verification</h2>
                            <form class="pt-4" method="post" action="{{ route('admin.send.verify') }}" id="login-form">@csrf
                                <div class="row text-center mx-auto" style="width: 80%;">
                                    <div class="col-md-3">
                                        <div class="form-group auth-form-group ">
                                            <input style="padding: 10px; width: 45px; font-size: 1.2rem; border-radius: 5px;" class="form-controls inputstab" name="otp1" type="text" id="n0" maxlength="1" autocomplete="off" autofocus data-next="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group auth-form-group ">
                                            <input style="padding: 10px; width: 45px; font-size: 1.2rem; border-radius: 5px;" class="form-controls inputstab" name="otp2" type="text" id="n1" maxlength="1" autocomplete="off" autofocus data-next="2" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group auth-form-group ">
                                            <input style="padding: 10px; width: 45px; font-size: 1.2rem; border-radius: 5px;" class="form-controls inputstab" name="otp3" type="text" id="n2" maxlength="1" autocomplete="off" autofocus data-next="3" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group auth-form-group ">
                                            <input style="padding: 10px; width: 45px; font-size: 1.2rem; border-radius: 5px;" class="form-controls inputstab" name="otp4" type="text" id="n3" maxlength="1" autocomplete="off" autofocus data-next="4" required>
                                        </div>
                                    </div>
                                    <input type="hidden" name="email" value="{{ $email ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="auth-form-btn">Verify</button>
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
        $('.inputstab').keyup(function(e) {
            if (this.value.length === this.maxLength) {
                let next = $(this).data('next');
                $('#n' + next).focus();
            }
        });

        $.validator.addMethod("emailValidate", function(value) {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
        }, 'Please enter valid email address.');

        $('#login-form').validate({
            rules: {
                otp1: {
                    required: true,
                },
                otp2: {
                    required: true,
                },
                otp3: {
                    required: true,
                },
                otp4: {
                    required: true,
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
                element.css({"border": "3px solid #be1b1b"});
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).css({"border": "none"});;
            },
        });
    
</script>
@endpush