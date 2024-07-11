@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/revenue.css') }}">
@endpush

@section('title','Journey with Journals - Revenue Management')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Revenue Management</h4>
</div>

<div class="body-main-content">
    <div class="revenue-page-section">
        <div class="revenue-head-section">
            <div class="search-filter">
                <div class="row g-1">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="TotalRequestoverview"><img src="{{ assets('assets/images/dollar-circle.svg') }}"> Total Amount Received: <span>${{ number_format((float)$paymentReceived, 2, '.', '') }}</span> </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <a href="{{ route('admin.revenue-management.report') }}" id="download-report" class="btn-bl"><img src="{{ assets('assets/images/download.svg') }}"> Download report</a>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" class="form-control selector" name="date" id="selectDate" placeholder="mm-dd-yyyy" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control" name="subscription" id="selectStatus">
                                <option value="">Select Subscription Plan</option>
                                @foreach($plan as $val)
                                <option value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="search-form-group">
                                <input type="text" name="" id="searchInput" class="form-control" placeholder="Search by user name">
                                <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="revenue-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="jwjcard">
                        <div class="card-body">
                            <div class="jwj-table">
                                <table class="table xp-table  " id="customer-table">
                                    <thead>
                                        <tr class="table-hd">
                                            <th>Sr No.</th>
                                            <th>User Name</th>
                                            <th>Subscription Plan</th>
                                            <th>Amount Paid</th>
                                            <th>Billing Type</th>
                                            <th>Plan Activate On</th>
                                            <th>Amount Recieved On</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appendData">
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="jwj-table-pagination">
                                <ul class="jwj-pagination" id="appendPagination">

                                </ul>
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
    $(document).ready(function() {
        const getList = (page, search = null, status = null, planDate = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.revenue-management.list') }}",
                data: {
                    page, search, status, planDate
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
                        let html = `<tr class="text-center">
                                            <td colspan="8" class='no-record-found'><img width="350" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data"></td>
                                        </tr>`;
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
            let status = $("#selectStatus").val();
            let planDate = $("#selectDate").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, status, planDate);
            $("#download-report").attr('href', "{{url('/')}}/admin/revenue-management-reports?search="+search+"&planDate="+planDate+"&status="+status);
        });
        $(document).on('change', "#selectStatus", function() {
            let status = $("#selectStatus").val();
            let planDate = $("#selectDate").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, status, planDate);
            $("#download-report").attr('href', "{{url('/')}}/admin/revenue-management-reports?search="+search+"&planDate="+planDate+"&status="+status);
        });
        $(document).on('change', "#selectDate", function() {
            let status = $("#selectStatus").val();
            let planDate = $("#selectDate").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, status, planDate);
            $("#download-report").attr('href', "{{url('/')}}/admin/revenue-management-reports?search="+search+"&planDate="+planDate+"&status="+status);
        });
        $(".selector").datepicker({
            dateFormat: 'mm-dd-yy', //check change
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            closeText: 'Clear',
            onClose: function(dateText, inst) {
                if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
                    document.getElementById(this.id).value = '';
                    let status = $("#selectStatus").val();
                    let search = $("#searchInput").val();
                    getList($(this).data('page'), search, status, '');
                }
            }
        });
    })
</script>
@endpush