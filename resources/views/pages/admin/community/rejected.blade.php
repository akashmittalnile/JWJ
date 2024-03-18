@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community-approval.css') }}">
@endpush

@section('title','Journey with Journals - Rejcted Community')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Rejected Community</h4>
    <div class="search-filter wd4">
        <div class="row g-1">
            <div class="col-md-3">
                <div class="form-group">
                    <a href="{{ route('admin.community-management.approval') }}" class="btn-bl">Back</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="form-group">
                    <div class="search-form-group">
                        <input type="text" name="" id="searchInput" class="form-control" placeholder="Search by community name, user name, email or phone no.">
                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="community-page-section">
        <div class="community-content">
            <div class="row" id="appendData">

               

            </div>
        </div>

        <div class="jwj-table-pagination">
            <ul class="jwj-pagination" id="appendPagination">

            </ul>
        </div>

    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        const getList = (page, search = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.community-management.rejected') }}",
                data: {
                    page,
                    search
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        let userData = result.data.html.data;
                        let html = result.data.html;
                        $("#appendData").html(result.data.html);
                        $('.communitycarousel1').owlCarousel({
                            loop: true,
                            margin: 10,
                            nav: false,
                            dots: false,
                            responsive:{
                                1000:{
                                    items:1
                                }
                            }
                        });
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
            let role = $("#searchSelect").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search);
        })
    })
</script>
@endpush