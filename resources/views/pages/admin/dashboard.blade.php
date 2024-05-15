@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/home.css') }}">
@endpush

@section('title','Journey with Journals - Dashboard')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Dashboard</h4>
</div>
<div class="body-main-content">
    <div class="overview-section">
        <div class="row row-cols-xl-5 row-cols-xl-3 row-cols-md-2 g-2">
            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>Total Users</p>
                                <h2>{{ $userCount ?? 0 }}</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/users.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>Total Community</p>
                                <h2>{{ $communityCount ?? 0 }}</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/community.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col flex-fill">
                <div class="overview-card">
                    <div class="overview-card-body">
                        <div class="overview-content">
                            <div class="overview-content-text">
                                <p>Community Engagement</p>
                                <h2>{{ $communityFollowCount ?? 0 }} Followers</h2>
                            </div>
                            <div class="overview-content-icon">
                                <img src="{{ assets('assets/images/communityengagement.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="subscription-section">
        <div class="row">
            <div class="col-md-6">
                <div class="subscription-card1">
                    <div class="subscription-content">
                        <h2 class="subscription-title">Total Subscription Payment received</h2>
                        <p class="subscription-price month">${{ number_format((float)$monthReceived, 2, '.', '') }}</p>
                        <p class="subscription-price year d-none">${{ number_format((float)$yearReceived, 2, '.', '') }}</p>
                        <div class="subscription-button">
                            <a href="javascript:void(0)" data-name="month" class="Plan-btn active">Monthly</a>
                            <a href="javascript:void(0)" data-name="year" class="Plan-btn">Annually</a>
                        </div>
                    </div>
                    <div class="subscription-chart">
                        <div id="chartBar1"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="subscription-card">
                    <div class="subscription-content">
                        <h2 class="subscription-title">Total Subscription Payment received</h2>
                        @foreach($plan as $key => $val)
                        <p class="subscription-price {{ $key }} @if($key != 0) d-none @endif">${{ number_format((float)$val->total_amt, 2, '.', '') }}</p>
                        @endforeach
                        <p class="subscription-text">{{ $subscribeUserCount ?? 0 }} Users Subscribed</p>
                        <div class="subscription-button">
                            @foreach($plan as $key => $val)
                            <a href="javascript:void(0)" data-name="{{ $key }}" class="Plan-btn @if($key == 0) active @endif">{{ $val->name ?? 'NA' }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="earnings-section">
        <div class="row">
            <div class="col-md-12">
                <div class="jwjcard">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="me-auto">
                                <h4 class="heading-title">Earnings</h4>
                            </div>
                            <div class="search-filter wd3">
                                <div class="search-filter">
                                    <div class="row g-1">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="date" placeholder="MM-DD-YYYY" name="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <a href="javascript:void(0)" class="btn-bl">Download report</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="kik-chart">
                            <div id="chartBar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="earnings-section">
        <div class="row">
            <div class="col-md-12">
                <div class="jwjcard">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="me-auto">
                                <h4 class="heading-title">Rating & Reviews</h4>
                            </div>
                            <div class="search-filter wd5">
                                <div class="search-filter">
                                    <div class="row g-1">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="date" name="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="month" name="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <a href="{{ route('admin.rating-review.list') }}" class="btn-bl">View All</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="owl-carousel owl-theme" id="review-carousel">
                            <!-- Ratings cards will be populated here -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>

<!-- Manage Dates popup -->
<div class="modal kik-modal fade" id="ManageDates" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="iot-modal-form">
                    <h3>Manage Dates</h3>
                    <div class="form-group">
                        <h4>Selected Date</h4>
                        <input type="date" class="form-control">
                    </div>
                    <div class="form-group">
                        <ul class="kik-datesstatus-list">
                            <li>
                                <div class="kikradio">
                                    <input type="radio" name="datesstatustype" id="Not Available">
                                    <label for="Not Available">Not Available</label>
                                </div>
                            </li>
                            <li>
                                <div class="kikradio">
                                    <input type="radio" name="datesstatustype" id="Available">
                                    <label for="Available">Available</label>
                                </div>
                            </li>
                            <li>
                                <div class="kikradio">
                                    <input type="radio" name="datesstatustype" id="Tour Bookings">
                                    <label for="Tour Bookings">Tour Bookings</label>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="kik-modal-action">
                        <button class="yesbtn">Confirm & Save</button>
                        <button class="Cancelbtn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" data-json="{{json_encode($data1Graph)}}" id="data1_graph">
<input type="hidden" data-json="{{json_encode($plancGraph)}}" id="planc_graph">
<input type="hidden" data-json="{{json_encode($planbGraph)}}" id="planb_graph">
@endsection

@push('js')
<script type="text/javascript">
    let data1 = [];
    let planC = [];
    let planB = [];
    $(document).ready(function() {
        let arrOver1 = $("#data1_graph").data('json');
        arrOver1.map(ele => {
            data1.push(ele.toFixed(2));
        });

        let arrPlanC = $("#planc_graph").data('json');
        arrPlanC.map(ele => {
            planC.push(ele.toFixed(2));
        });
        let arrPlanB = $("#planb_graph").data('json');
        arrPlanB.map(ele => {
            planB.push(ele.toFixed(2));
        });
    });

    $(function() {
        var options1 = {
            chart: {
                type: "line",
                height: 240,
                width: 250,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                show: true,
                curve: "smooth",
                lineCap: "butt",
                colors: ["#1079c0"],
                width: 2,
                dashArray: 0,
            },

            series: [{
                name: "Payment received",
                data: data1,
            }, ],
            yaxis: {
                min: 0,
                show: false,
                axisBorder: {
                    show: false,
                },
            },
            xaxis: {
                categories: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            tooltip: {
                enabled: true,
            },
            colors: ["#1079c0"],
        };
        var chart = new ApexCharts(document.querySelector("#chartBar1"), options1);
        chart.render();
    });

    $(function() {
        var options2 = {
            series: [
                {
                    name: "Plan B",
                    data: planB,
                },
                {
                    name: "Plan C",
                    data: planC,
                },
            ],
            chart: {
                height: 350,
                type: "line",
                foreColor: "#000",

                toolbar: {
                    show: false,
                },
                zoom: {
                    enabled: false,
                },
            },

            stroke: {
                curve: "smooth",
                width: [2, 2, 2],
                colors: ["#1079c0", "#EE9E00", "#505a61"],
                lineCap: "round",
            },
            grid: {
                borderColor: "#edeef1",
                strokeDashArray: 2,
            },

            colors: ["#1079c0", "#EE9E00", "#505a61"],

            dataLabels: {
                enabled: false,
            },
            legend: {
                markers: {
                    fillColors: ["#1079c0", "#EE9E00", "#505a61"],
                },
            },
            tooltip: {
                marker: {
                    fillColors: ["#1079c0", "#EE9E00", "#505a61"],
                },
            },

            title: {
                text: "Total Received Amount",
                align: "left",
            },

            fill: {
                colors: ["#1079c0", "#EE9E00", "#505a61"],
            },

            markers: {
                colors: ["#1079c0", "#EE9E00", "#505a61"],
            },

            xaxis: {
                categories: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },

            yaxis: {
                tickAmount: 4,
                floating: false,
                labels: {
                    style: {
                        colors: "#000",
                    },
                    offsetY: -7,
                    offsetX: 0,
                },
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#chartBar"), options2);
        chart.render();
    });

    $(document).on('click', '.subscription-card1 .Plan-btn', function() {
        $('.subscription-card1 .Plan-btn').removeClass('active');
        $('.subscription-card1 .subscription-price').addClass('d-none');
        $('.subscription-card1 .subscription-price' + '.' + $(this).data('name')).removeClass('d-none');
        $(this).addClass('active');
    });
    $(document).on('click', '.subscription-card .Plan-btn', function() {
        $('.subscription-card .Plan-btn').removeClass('active');
        $('.subscription-card .subscription-price').addClass('d-none');
        $('.subscription-card .subscription-price' + '.' + $(this).data('name')).removeClass('d-none');
        $(this).addClass('active');
    });

    $("#date").daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,
        showDropdowns: true,
        autoApply: true,
        locale: {
            format: 'MM-DD-YYYY'
        }
    }).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM-DD-YYYY'));
    });

    $(document).ready(function() {
        const updateDashboardReviewsCarousel = (data) => {
            const carouselContainer = $('#review-carousel');
            carouselContainer.empty();
            data.forEach((review) => {
                // Calculate the number of filled stars
                const filledStars = Math.floor(review.rating);
                // Determine if there is a half star
                const halfStar = review.rating - filledStars >= 0.5;
                // Calculate the number of empty stars
                const emptyStars = 5 - filledStars - (halfStar ? 1 : 0);
                // Generate the HTML for the star rating
                let starHtml = '';
                for (let i = 0; i < filledStars; i++) {
                    starHtml += '<span class="activerating"><i class="las la-star"></i></span>';
                }
                if (halfStar) {
                    starHtml +=
                        '<span class="activerating"><i class="las la-star-half-alt"></i></span>';
                }
                for (let i = 0; i < emptyStars; i++) {
                    starHtml += '<span><i class="las la-star"></i></span>';
                }
                carouselContainer.append(`
                            <div class="jwj-review-card">
                                <div class="jwj-review-card-head">
                                    <div class="review-rating-user-avtar">
                                        <span>${review.name.charAt(0)}</span>
                                    </div>
                                    <div class="review-rating-user-text">
                                        <h3>${review.name}</h3>
                                        <div class="review-rating">
                                            <div class="review-rating-icon">
                                                ${starHtml} <!-- Insert the star rating HTML here -->
                                            </div>
                                            <div class="review-rating-text">${review.rating} Star</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jwj-review-card-body">
                                    <span class="review-quotes-shape"></span>
                                    <div class="review-desc">${review.description}</div>
                                    <div class="review-date">${review.created_at.split(' ')[0].split('-').map((part, index) => index === 1 ? part : part.padStart(2, '0')).reverse().join('-')}</div>
                                </div>
                            </div>
                      `);
            });
            // Initialize Owl Carousel after adding all items
            carouselContainer.owlCarousel({
                items: 3,
                margin: 10,
                loop: true,
                nav: true,
                dots: false,
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>']
            });
        };
        // Function to fetch reviews data
        const fetchReviewsData = (page, search = null, rating = null) => {
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
                        updateDashboardReviewsCarousel(result.data.html.data);
                    } else {
                        // Handle no result
                    }
                },
                error: function(error) {
                    console.log("Error:", error);
                    toastr.error(error.message);
                },
            });
        };
        const initReviews = () => {
            fetchReviewsData(1);
        };
        // Initial call to getReviews without search term
        initReviews();
        $('#dashboardRatingFilter').change(function() {
            let selectedRating = $(this).val();
            let search = null;
            if (selectedRating === "") {
                selectedRating = null;
            }
            fetchReviewsData(1, search, selectedRating);
        });
    });
</script>
@endpush