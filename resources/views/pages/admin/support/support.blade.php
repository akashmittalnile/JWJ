@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/support.css') }}">
@endpush

@section('title','Journey with Journals - Support & Communication')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Support & Communication</h4>
    <div class="search-filter wd2">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ route('admin.notification') }}" class="btn-bl">Manage App Notifcation</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="support-page-section">
        <div class="support-page-header">
            <div class="search-filter ">
                <div class="row g-1">
                    <div class="col-md-2">
                        <div class="form-group">
                            <a href="{{ route('admin.support.download.report') }}" id="download-report" class="btn-bl"><img src="{{ assets('assets/images/download.svg') }}"> Download report</a>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="month" id="selectMonth" name="" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control" id="searchSelect2" name="">
                                <option value="">Select Status</option>
                                <option value="1">Closed</option>
                                <option value="2">In-Progress</option>
                                <option value="3">Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control" id="searchSelect" name="">
                                <option value="">Show All Inquiry Type</option>
                                <option value="1">Plans Related</option>
                                <option value="2">Billing Related</option>
                                <option value="3">General Inquiry</option>
                                <option value="4">Community Related</option>
                                <option value="5">Community Guidelines</option>
                                <option value="6">Community Creation</option>
                                <option value="7">Task Management</option>
                                <option value="8">Journals Related</option>
                                <option value="9">Journaling Functionality</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="search-form-group">
                                <input type="text" name="" id="searchInput" class="form-control" placeholder="Search by user name, email & Phone no.">
                                <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="support-content">
            <div class="row" id="appendData">

            </div>
        </div>

        <div class="jwj-table-pagination">
            <ul class="jwj-pagination" id="appendPagination">

            </ul>
        </div>

    </div>
</div>

<div class="modal lm-modal fade" id="seeReply" tabindex="-1" aria-labelledby="seeReplyLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Sendreply-modal-form">
                    <h2>Admin Reply</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="jwj-review-card">
                                    <div class="jwj-review-card-head">
                                        <div class="review-rating-user-avtar">
                                            <img src="{{ assets('assets/images/no-image.jpg') }}" id="modal2Img" alt="">
                                        </div>
                                        <div class="review-rating-user-text">
                                            <h3 id="modal2Name"></h3>
                                        </div>
                                    </div>
                                    <div class="jwj-review-card-body">
                                        <span class="review-quotes-shape"></span>
                                        <div class="review-desc" id="modal2Msg"></div>
                                        <div class="review-date" id="modal2Time"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="jwj-review-card">
                                    <div class="jwj-review-card-head">
                                        <div class="review-rating-user-avtar">
                                            <img src="{{ isset(auth()->user()->profile) ? assets('uploads/profile/'.auth()->user()->profile) : assets('assets/images/no-image.jpg') }}" alt="">
                                        </div>
                                        <div class="review-rating-user-text">
                                            <h3>{{ auth()->user()->name ?? 'NA' }}</h3>
                                            <p style="font-size: 11px; color: #074f7c;" class="mb-0">Administrator</p>
                                        </div>
                                    </div>
                                    <div class="jwj-review-card-body">
                                        <span class="review-quotes-shape"></span>
                                        <div class="review-desc" id="modalRplyMsg"></div>
                                        <div class="review-date" id="modalRplyTime"></div>
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

<!-- Reply -->
<div class="modal lm-modal fade" id="adminSendReply" tabindex="-1" aria-labelledby="adminSendReplyLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="Sendreply-modal-form">
                    <h2>Reply</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="jwj-review-card">
                                    <div class="jwj-review-card-head">
                                        <div class="review-rating-user-avtar">
                                            <img src="{{ assets('assets/images/no-image.jpg') }}" id="modalImg" alt="">
                                        </div>
                                        <div class="review-rating-user-text">
                                            <h3 id="modalName"></h3>
                                            <!-- <div class="review-rating">
                                                <div class="review-rating-icon">
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class=""><i class="las la-star"></i></span>
                                                </div>
                                                <div class="review-rating-text">5.0 Rating</div>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="jwj-review-card-body">
                                        <span class="review-quotes-shape"></span>
                                        <div class="review-desc" id="modalMsg"></div>
                                        <div class="review-date" id="modalTime"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('admin.support.send.reply') }}" method="post" id="replyForm"> @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" name="status">
                                        <option value="">Select Status</option>
                                        <option value="1">Closed</option>
                                        <option value="2">In-Progress</option>
                                        <option value="3">Pending</option>
                                    </select>
                                    <input type="hidden" name="id" id="modalId">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="message" class="form-control" placeholder="Type Your Reply Message Here.."></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="submit" class="save-btn">SEND REPLY</button>
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
    $('#seeReply, #adminSendReply').on('hidden.bs.modal', function(e) {
        $(this).find('form').trigger('reset');
    });

    $(document).on("click", "#sendRply", function() {
        $("#modalId").val($(this).data('id'));
        $("#modalMsg").text($(this).data('msg'));
        $("#modalTime").text($(this).data('time'));
        $("#modalName").text($(this).data('name'));
        $("#modalImg").attr("src", $(this).data('img'));
        $("#adminSendReply").modal('show');
    });
    
    $(document).on("click", "#seeRply", function() {
        $("#modal2Msg").text($(this).data('msg'));
        $("#modal2Time").text($(this).data('time'));
        $("#modal2Name").text($(this).data('name'));
        $("#modal2Img").attr("src", $(this).data('img'));
        $("#modalRplyMsg").text($(this).data('past'));
        $("#modalRplyTime").text($(this).data('updatetime'));
        $("#seeReply").modal('show');
    });

    $(document).ready(function() {
        $('#replyForm').validate({
            rules: {
                status: {
                    required: true,
                },
                message: {
                    required: true,
                },
            },
            messages: {
                status: {
                    required: 'Please select query status',
                },
                message: {
                    required: 'Please enter your message',
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
                            window.location.reload();
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

        const getList = (page, search = null, inquiry = null, status = null, date = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.support') }}",
                data: {
                    page,
                    search,
                    inquiry,
                    status,
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
            let status = $("#searchSelect2").val();
            let search = $("#searchInput").val();
            let date = $("#selectMonth").val();
            getList($(this).data('page'), search, inquiry, status, date);
            $("#download-report").attr('href', "{{url('/')}}/admin/support-communication-download-report?search="+search+"&status="+status+"&inquiry="+inquiry+"&date="+date);
        });
        $(document).on('change', "#searchSelect", function() {
            let inquiry = $("#searchSelect").val();
            let status = $("#searchSelect2").val();
            let search = $("#searchInput").val();
            let date = $("#selectMonth").val();
            getList($(this).data('page'), search, inquiry, status, date);
            $("#download-report").attr('href', "{{url('/')}}/admin/support-communication-download-report?search="+search+"&status="+status+"&inquiry="+inquiry+"&date="+date);
        });
        $(document).on('change', "#searchSelect2", function() {
            let inquiry = $("#searchSelect").val();
            let status = $("#searchSelect2").val();
            let search = $("#searchInput").val();
            let date = $("#selectMonth").val();
            getList($(this).data('page'), search, inquiry, status, date);
            $("#download-report").attr('href', "{{url('/')}}/admin/support-communication-download-report?search="+search+"&status="+status+"&inquiry="+inquiry+"&date="+date);
        });
        $(document).on('change', "#selectMonth", function() {
            let inquiry = $("#searchSelect").val();
            let status = $("#searchSelect2").val();
            let date = $("#selectMonth").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, inquiry, status, date);
            $("#download-report").attr('href', "{{url('/')}}/admin/support-communication-download-report?search="+search+"&status="+status+"&inquiry="+inquiry+"&date="+date);
        });
    });
</script>
@endpush