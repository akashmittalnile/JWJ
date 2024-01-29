@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/revenue.css') }}">
@endpush

@section('title','Journey with Journals - Revenue Management')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Revenue Management</h4>
    <div class="search-filter wd2">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ route('admin.revenue-management.plans') }}" class="btn-bl">Manage Subscription Plan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="revenue-page-section">
        <div class="revenue-head-section">
            <div class="search-filter">
                <div class="row g-1">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="TotalRequestoverview"><img src="{{ assets('assets/images/dollar-circle.svg') }}"> Total Request Received: <span>5689</span> </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <a href="javascript:void(0)" class="btn-bl"><img src="{{ assets('assets/images/download.svg') }}"> Download report</a>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="date" name="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="month" name="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Show All</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <select class="form-control">
                                <option>Choose Plan</option>
                                <option>Plan A</option>
                                <option>Plan B</option>
                                <option>Plan C</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="search-form-group">
                                <input type="text" name="" class="form-control" placeholder="Search by name, amount & transaction ID">
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
                                            <th>Billing Due Date</th>
                                            <th>Amount Recieved On</th>
                                            <th>Transaction ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="sno">1</div>
                                            </td>
                                            <td>
                                                Jane Doe
                                            </td>

                                            <td>
                                                <img src="{{ assets('assets/images/goldplan.svg') }}" height="24"> Plan B
                                            </td>
                                            <td>
                                                $299.00
                                            </td>
                                            <td>
                                                Montly
                                            </td>
                                            <td>
                                                1st of every Month
                                            </td>
                                            <td>
                                                03 Sep, 2023, 09:33:12 am
                                            </td>
                                            <td>
                                                76375873874
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="sno">2</div>
                                            </td>
                                            <td>
                                                Jane Doe
                                            </td>

                                            <td>
                                                <img src="{{ assets('assets/images/platinumplan.svg') }}" height="24"> Plan C
                                            </td>
                                            <td>
                                                $299.00
                                            </td>
                                            <td>
                                                Annually
                                            </td>
                                            <td>
                                                1st of every Month
                                            </td>
                                            <td>
                                                03 Sep, 2023, 09:33:12 am
                                            </td>
                                            <td>
                                                76375873874
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="jwj-table-pagination">
                                <ul class="jwj-pagination">
                                    <li class="disabled" id="example_previous">
                                        <a href="javascript:void(0)" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                    </li>
                                    <li class="active">
                                        <a href="javascript:void(0)" class="page-link">1</a>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0)" aria-controls="example" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                                    </li>
                                    <li class="">
                                        <a href="javascript:void(0)" aria-controls="example" data-dt-idx="3" tabindex="0" class="page-link">3</a>
                                    </li>
                                    <li class="next" id="example_next">
                                        <a href="javascript:void(0)" aria-controls="example" data-dt-idx="7" tabindex="0" class="page-link">Next</a>
                                    </li>
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