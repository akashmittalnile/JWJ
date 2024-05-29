@extends('layouts.app')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/reviews.css') }}">
@endpush
@section('title', 'Journey with Journals - Rating & Reviews')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Rating & Reviews</h4>
    <div class="search-filter wd6">
        <div class="row g-1">
            <div class="col-md-4">
                <div class="form-group">
                    <a href="{{ route('admin.rating-review.download.report') }}" id="download-report" class="btn-bl"><img src="{{ assets('assets/images/download.svg') }}"> Download report</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="search-form-group">
                        <select class="form-control" id="searchSelect" name="">
                            <option value="">All Stars</option>
                            <option value="5">5 Star</option>
                            <option value="4">4 Star</option>
                            <option value="3">3 Star</option>
                            <option value="2">2 Star</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <div class="search-form-group">
                        <input type="text" id="searchInput" name="" class="form-control" placeholder="Search by name">
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
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th>Action</th>
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

<div class="modal lm-modal fade" id="deleteRatingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form text-center">
                    <h2>Are You Sure?</h2>
                    <p>You want to delete this rating!</p>
                    <form action="{{ route('admin.rating-review.delete') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="id" value="" id="ratingId">
                                <div class="form-group">
                                    <button class="cancel-btn" data-bs-dismiss="modal" aria-label="Close" type="button">Cancel</button>
                                    <button type="submit" class="save-btn">Yes! Delete</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script type="text/javascript">
    $(document).on('click', '#delete-button', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#ratingId').val(id); 
        $('#deleteRatingModal').modal('show');
    });

    $(document).ready(function() {
        const getList = (page, search = null, star = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.rating-review.list') }}",
                data: {
                    page,
                    search,
                    star
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        let html = ``;
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
                                        <td colspan="5"><img width="350" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data"></td>
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
        $(document).on('keyup', '#searchInput', function() {
            let star = $("#searchSelect").val();
            let search = $(this).val();
            getList($(this).data('page'), search, star);
            $("#download-report").attr('href', "{{url('/')}}/admin/rating-reports?search=" + search + "&star=" + star);
        });
        $(document).on('change', "#searchSelect", function() {
            let star = $("#searchSelect").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, star);
            $("#download-report").attr('href', "{{url('/')}}/admin/rating-reports?search=" + search + "&star=" + star);
        });
    })
</script>
@endpush