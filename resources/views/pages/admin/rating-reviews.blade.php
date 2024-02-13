@extends('layouts.app')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/css/reviews.css') }}">
@endpush
@section('title', 'Journey with Journals - Rating & Reviews')
@section('content')
    <div class="container-fluid">
        <div class="page-breadcrumb-title-section">
            <h4>Rating & Reviews</h4>
            <div class="search-filter wd5">
                <div class="row g-1">
                    <div class="col-md-4">
                        <div class="form-group">
                            <a href="{{ route('admin.rating-review.download.report') }}" class="btn-bl"><img
                                    src="{{ assets('assets/images/download.svg') }}"> Download report</a>
                        </div>
                        {{-- <div class="form-group">
                            <a href="javascript:void(0)" class="btn-bl"><img
                                    src="{{ assets('assets/images/download.svg') }}">
                                Download report</a>
                        </div> --}}
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control" id="ratingFilter">
                                {{-- <option value="" disabled selected>Filter</option> --}}
                                <option value="" selected>All</option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="search-form-group">
                                <input type="text" name="" id="searchInput" class="form-control"
                                    placeholder="Search by name">
                                <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="customer-table">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Name</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Ratings data will be inserted here dynamically -->
                                </tbody>
                            </table>
                        </div>
                        <div class="jwj-table-pagination">
                            <ul class="jwj-pagination" id="appendPagination">
                                <!-- Pagination links will be inserted here dynamically -->
                            </ul>
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
            const updateReviewsHtml = (html) => {
                $('#customer-table tbody').html(html);
            };
            const updatePagination = (pagination) => {
                let paginate = '';
                if (pagination.lastPage > 1) { // Check if pagination is needed
                    paginate += `<ul class="pagination">`;
                    paginate += `<li class="${pagination.currentPage == 1 ? 'disabled' : ''}" id="example_previous">
                               <a href="javascript:void(0)" data-page="${pagination.currentPage - 1}" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                               </li>`;
                    for (let i = 1; i <= pagination.lastPage; i++) {
                        paginate += `<li class="${pagination.currentPage == i ? 'active' : ''}">
                               <a href="javascript:void(0)" data-page="${i}" class="page-link">${i}</a>
                               </li>`;
                    }
                    paginate += `<li class="${pagination.currentPage == pagination.lastPage ? 'disabled next' : 'next'}" id="example_next">
                               <a href="javascript:void(0)" data-page="${pagination.currentPage + 1}" aria-controls="example" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                               </li>`;
                    paginate += `</ul>`;
                }
                $("#appendPagination").html(paginate);
            };
            const handleNoResult = () => {
                $('#customer-table tbody').html(
                    '<tr><td colspan="5" class="text-center">No results found</td></tr>');
                $('#appendPagination').empty();
            };
            const getReviews = (page, search = null, rating = null) => {
                $.ajax({
                    type: 'get',
                    url: "{{ route('admin.rating-review.list') }}",
                    data: {
                        page,
                        search,
                        rating,
                    },
                    dataType: 'json',
                    success: function(result) {
                        if (result.status) {
                            console.log("result logged in", result);
                            let userData = result.data.html.data;
                            // Assuming the ratings data is an array of objects with attributes like 'name', 'rating', 'description'
                            let ratingsHtml = '';
                            userData.forEach((rating, index) => {
                                ratingsHtml += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${rating.name}</td>
                                        <td>
                                            <div class="review-rating">
                                                <div class="review-rating-icon">`;
                                                    // Adjust the star icons based on the rating value
                                                    for (let i = 1; i <= 5; i++) {
                                                        if (i <= rating.rating) {
                                                            ratingsHtml +=
                                                                `<span class="activerating"><i class="las la-star"></i></span>`;
                                                        } else {
                                                            ratingsHtml +=
                                                                `<span><i class="las la-star"></i></span>`;
                                                        }
                                                    }
                                                    ratingsHtml += `
                                                </div>
                                                <div class="review-rating-text">${rating.rating} Rating</div>
                                            </div>
                                        </td>
                                        <td>${rating.description}</td>
                                        <td>
                                            <a class="trash-btn" href="" value = ${rating.id} id="deleteRating" onclick="deleteRating( ${rating.id})"><img src="{{ assets('assets/images/trash.svg') }}"></a>
                                            <a class="reply-btn" href=""><img src="{{ assets('assets/images/reply.svg') }}"></a>
                                        </td>
                                    </tr>`;
                            });
                            updateReviewsHtml(ratingsHtml);
                            updatePagination(result.data);
                        } else {
                            handleNoResult();
                        }
                    },
                    error: function(error) {
                        console.log("Error:", error);
                        toastr.error(error.message);
                    },
                });
            };
            const initReviews = () => {
                getReviews(1);
            };
            // Initial call to getReviews without search term
            initReviews();
            // Click event for pagination
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                getReviews($(this).data('page'));
            });
            // Keyup event for search input
            $(document).on('keyup', '#searchInput', function() {
                let search = $(this).val();
                getReviews(1, search);
            });
            $('#ratingFilter').change(function() {
                let selectedRating = $(this).val();
                let search = $('#searchInput').val();
                if (selectedRating === "") {
                    // If "All" is selected, pass null instead of the rating value
                    selectedRating = null;
                }
                getReviews(1, search, selectedRating);
            });
        });
        $(document).on('click', '#deleteRating', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this rating?')) {
                let ratingId = $(this).attr('value'); // Retrieve rating ID from the value attribute
                let $this = $(this); // Cache the reference to $(this) for later use inside AJAX success function
                $.ajax({
                    type: 'post',
                    url: "{{ route('admin.rating-review.delete') }}",
                    data: {
                        ratingId,
                        '_token': "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            // If successful, remove the row from the table
                            $this.closest('td').parent('tr')
                                .remove(); // Use cached reference to $(this)
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        toastr.error('Error deleting rating. Please try again later.');
                    }
                });
            }
        });
    </script>
@endpush