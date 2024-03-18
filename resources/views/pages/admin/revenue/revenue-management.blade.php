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
                            <div class="TotalRequestoverview"><img src="{{ assets('assets/images/dollar-circle.svg') }}"> Total Amount Received: <span>${{ number_format((float)$paymentReceived, 2, '.', '') }}</span> </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="date" name="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <!-- <div class="form-group">
                            <a href="javascript:void(0)" class="btn-bl"><img src="{{ assets('assets/images/download.svg') }}"> Download report</a>
                        </div> -->
                    </div>
                    <div class="col-md-1">
                        <!-- <div class="form-group">
                            <select class="form-control">
                                <option>Show All</option>
                            </select>
                        </div> -->
                    </div>
                    <div class="col-md-1">
                        <!-- <div class="form-group">
                            <select class="form-control">
                                <option>Choose Plan</option>
                                <option>Plan A</option>
                                <option>Plan B</option>
                                <option>Plan C</option>
                            </select>
                        </div> -->
                    </div>
                    <div class="col-md-3">
                        <!-- <div class="form-group">
                            <div class="search-form-group">
                                <input type="text" name="" class="form-control" placeholder="Search by name, amount & transaction ID">
                                <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                            </div>
                        </div> -->
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
                                        @forelse($list as $key => $val)
                                        <tr>
                                            <td>
                                                <div class="sno">{{ $key+1 }}</div>
                                            </td>
                                            <td>
                                                {{ $val->user_name }}
                                            </td>

                                            <td>
                                                @if($val->name == 'Plan B')
                                                <img src="{{ assets('assets/images/goldplan.svg') }}" height="24">
                                                @elseif($val->name == 'Plan A')
                                                <img src="{{ assets('assets/images/freeplan.svg') }}" height="24">
                                                @elseif($val->name == 'Plan C')
                                                <img src="{{ assets('assets/images/platinumplan.svg') }}" height="24">
                                                @else
                                                <img src="{{ assets('assets/images/goldplan.svg') }}" height="24">
                                                @endif
                                                {{ $val->name }}
                                            </td>
                                            <td>
                                                ${{ number_format((float)$val->amount, 2, '.', '') }}
                                            </td>
                                            <td>
                                                Montly
                                            </td>
                                            <td>
                                                {{ date('d M, Y', strtotime($val->activated_date)) }}
                                            </td>
                                            <td>
                                                {{ date('d M, Y', strtotime($val->renewal_date)) }}
                                            </td>
                                            <td>
                                                {{ $val->transaction_id ?? 'NA' }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr class="text-center">
                                            <td colspan="8">No record found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="jwj-table-pagination">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection