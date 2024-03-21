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
                            <a href="javascript:void(0)" class="btn-bl"><img src="{{ assets('assets/images/download.svg') }}"> Download report</a>
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
                                <option>Select Status</option>
                                <option value="1">Closed</option>
                                <option value="2">In-Progress</option>
                                <option value="3">Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control" id="searchSelect" name="">
                                <option value="" >Show All Inquiry Type</option>
                                <option value="1" >Plans Related</option>
                                <option value="2" >Billing Related</option>
                                <option value="3" >General Inquiry</option>
                                <option value="4" >Community Related</option>
                                <option value="5" >Community Guidelines</option>
                                <option value="6" >Community Creation</option>
                                <option value="7" >Task Management</option>
                                <option value="8" >Journals Related</option>
                                <option value="9" >Journaling Functionality</option>
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

<!-- Reply -->
<div class="modal lm-modal fade" id="Sendreply" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                            <span>J</span>
                                        </div>
                                        <div class="review-rating-user-text">
                                            <h3>John</h3>
                                            <div class="review-rating">
                                                <div class="review-rating-icon">
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class="activerating"><i class="las la-star"></i></span>
                                                    <span class=""><i class="las la-star"></i></span>
                                                </div>
                                                <div class="review-rating-text">5.0 Rating</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="jwj-review-card-body">
                                        <span class="review-quotes-shape"></span>
                                        <div class="review-desc">I Recently Had The Pleasure Of Visiting This Furniture Store, And I Must Say, I Was Thoroughly Impressed With …</div>
                                        <div class="review-date">01 Wed, 09:30</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <select class="form-control">
                                    <option>Select Status</option>
                                    <option>Closed</option>
                                    <option>In-Progress</option>
                                    <option>Pending</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea type="text" class="form-control" placeholder="Type Your Reply Message Here.."></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button class="save-btn">SEND REPLY</button>
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
<script type="text/javascript">
    
    $(document).ready(function() {
        const getList = (page, search = null, inquiry = null, status = null, date = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.support') }}",
                data: {
                    page, search, inquiry, status, date
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
        });
        $(document).on('change', "#searchSelect", function() {
            let inquiry = $("#searchSelect").val();
            let status = $("#searchSelect2").val();
            let search = $("#searchInput").val();
            let date = $("#selectMonth").val();
            getList($(this).data('page'), search, inquiry, status, date);
        });
        $(document).on('change', "#searchSelect2", function() {
            let inquiry = $("#searchSelect").val();
            let status = $("#searchSelect2").val();
            let search = $("#searchInput").val();
            let date = $("#selectMonth").val();
            getList($(this).data('page'), search, inquiry, status, date);
        });
        $(document).on('change', "#selectMonth", function() {
            let inquiry = $("#searchSelect").val();
            let status = $("#searchSelect2").val();
            let date = $("#selectMonth").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, inquiry, status, date);
        });
    });

</script>
@endpush