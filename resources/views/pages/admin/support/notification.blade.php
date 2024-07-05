@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/notification.css') }}">
@endpush

@section('title','Journey with Journals - Notifcation')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4> Manage App Notifcation</h4>
    <div class="search-filter wd2">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="javascript:void(0)" class="btn-bl" data-bs-toggle="modal" data-bs-target="#CreateNotification">Push New App Notifcation</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="notification-page-section">
        <div class="notification-page-header">
            <div class="search-filter">
                <div class="row g-1">
                    <div class="col-md-4">
                        
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="month" id="selectMonth" name="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control" name="" id="searchSelect">
                                <option value="">Select Subscription Plan</option>
                                <option value="100">Select All Users</option>
                                @forelse($plan as $val)
                                <option value="{{ $val->id }}">{{ $val->name }} Users</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="search-form-group">
                                <input type="text" name="" id="searchInput" class="form-control" placeholder="Search by title">
                                <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="notification-content">
            <div class="row" id="appendData">


            </div>
        </div>

        <div class="jwj-table-pagination">
            <ul class="jwj-pagination" id="appendPagination">

            </ul>
        </div>

    </div>
</div>

<!-- Create  Notification -->
<div class="modal lm-modal fade" id="CreateNotification" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="notification-modal-form">
                    <h2>Push New App Notification</h2>
                    <div class="row">
                        <form action="{{ route('admin.notification.store') }}" method="post" id="notify-form" >@csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="text" placeholder="Notification Title">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" name="plan">
                                        <option value="100">Select All Users</option>
                                        @forelse($plan as $val)
                                        <option value="{{ $val->id }}">{{ $val->name }} Users</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="message" class="form-control" placeholder="Type Your Message Here.."></textarea>
                                </div>
                            </div>
    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="submit" class="save-btn">Push Notification</button>
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
<script type="text/javascript">
    $('#CreateNotification').on('hidden.bs.modal', function(e) {
        $(this).find('form').trigger('reset');
        $(".form-control").removeClass("is-invalid");
    });

    $(document).ready(function() {
        $('#notify-form').validate({
            rules: {
                text: {
                    required: true,
                },
                plan: {
                    required: true,
                },
                message: {
                    required: true,
                },
            },
            messages: {
                plan: {
                    required: 'Please select subscription plan',
                },
                message: {
                    required: 'Please enter your message',
                },
                text: {
                    required: 'Please enter your title',
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

        const getList = (page, search = null, inquiry = null, date = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.notification') }}",
                data: {
                    page,
                    search,
                    inquiry,
                    date
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        let userData = result.data.html.data;
                        let html = result.data.html;
                        $("#appendData").html(result.data.html);
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
                        $("#appendData").html(html);
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
            let inquiry = $("#searchSelect").val();
            let search = $("#searchInput").val();
            let date = $("#selectMonth").val();
            getList($(this).data('page'), search, inquiry, date);
        });
        $(document).on('change', "#searchSelect", function() {
            let inquiry = $("#searchSelect").val();
            let search = $("#searchInput").val();
            let date = $("#selectMonth").val();
            getList($(this).data('page'), search, inquiry, date);
        });
        $(document).on('change', "#selectMonth", function() {
            let inquiry = $("#searchSelect").val();
            let date = $("#selectMonth").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, inquiry, date);
        });
    });
</script>
@endpush