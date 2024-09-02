@extends('layouts.auth-app')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/auth.css') }}">
<style>
    .toast.toast-error {
        background-color: #eb3316;
    }

    .toast.toast-success {
        background-color: #26e35b;
    }
</style>
@endpush
@section('content')
<div class="w-100 d-flex align-items-center justify-content-center my-3">
    <img src="{{assets('assets/images/logo.svg')}}" alt="" width="120">
</div>
<div>
    <div class="text-center my-3" style="color: #1079c0;">
        <h3>Contact Us</h3>
    </div>
    <div class="auth-content-card">
        <div class="container d-flex justify-content-center">
            <div class="auth-card w-50">
                <div class="justify-content-center w-100">
                    <div class="col-md-12 auth-form-info">
                        <div class="auth-form" style="min-height: none; max-width: none;">
                            <form class="pt-5" method="post" action="{{ url('/api/contact-store') }}" id="login-form">@csrf
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="Name">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email Address">
                                </div>
                                <div class="form-group">
                                    <input type="tel" id="phoneNumber" class="form-control" name="phone" placeholder="Phone Number">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="message" placeholder="Your Message"></textarea>
                                </div>
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
    $('#phoneNumber').mask('(000) 000-0000');

    $.validator.addMethod("emailValidate", function(value) {
        return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
    }, 'Please enter valid email address.');

    $.validator.addMethod("phoneValidate", function(value) {
        return /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(value);
    }, 'Please enter valid phone number.');

    $('#login-form').validate({
        rules: {
            name: {
                required: true,
            },
            message: {
                required: true,
            },
            email: {
                required: true,
                emailValidate: true,
            },
            phone: {
                required: true,
                phoneValidate: true
            },
        },
        messages: {
            name: {
                required: 'Please enter name',
            },
            message: {
                required: 'Please enter your message',
            },
            email: {
                required: 'Please enter email address',
            },
            phone: {
                required: 'Please enter your phone number',
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