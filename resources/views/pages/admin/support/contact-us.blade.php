@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/user.css') }}">
<style>
    .dropdown-item:hover{
        background-color: #1079c0 !important;
        color: white !important;
    }
</style>
@endpush

@section('title','Journey with Journals - Contact Us')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Contact Us</h4>
    <div class="search-filter wd2">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="search-form-group">
                        <input type="text" id="searchInput" name="" class="form-control" placeholder="Search by name, email & phone no.">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<div class="body-main-content">
    <div class="booking-availability-section">
        <div class="row">
            <div class="col-md-12">
                <div class="jwjcard">
                    <div class="card-body">
                        <div class="jwj-table">
                            <table class="table xp-table" id="customer-table">
                                <thead>
                                    <tr class="table-hd">
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Email ID</th>
                                        <th>Contact Number</th>
                                        <th>Message</th>
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
@endsection

@push('js')
<script type="text/javascript">
    
    $(document).ready(function() {
        const getList = (page, search = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.contact.list') }}",
                data: {
                    page, search
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        let html = ``;
                        $("#appendData").html(result.data.html);
                        
                        $("#appendPagination").html('');
                        if(result.data.lastPage!=1){
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
                                        <td colspan="8"> 
                                            <img width="350" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data">
                                        </td>
                                    </tr>`;
                        $("#appendData").html(html);
                        $("#appendPagination").html('');
                    }
                },
                error: function(data, textStatus, errorThrown) {
                    jsonValue = jQuery.parseJSON( data.responseText );
                    console.error(jsonValue.message);
                },
            });
        };
        getList(1);
        $(document).on('click', '.page-link', function (e) {
            e.preventDefault();
            getList($(this).data('page'));
        })
        $(document).on('keyup', '#searchInput', function () {
            let status = $("#searchSelect").val();
            let search = $(this).val();
            getList($(this).data('page'), search);
        });
    })
</script>
@endpush