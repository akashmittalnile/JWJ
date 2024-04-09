@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/users.css') }}">
@endpush

@section('title','Journey with Journals - User Details')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>User Details</h4>
    <div class="search-filter wd5">
        <div class="row g-1">
            <div class="col-md-2">
                <div class="form-group">
                    <a href="{{ route('admin.users.list') }}" class="btn-bl">Back</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <a href="{{ route('admin.users.routines', encrypt_decrypt('encrypt', $user->id)) }}" class="btn-bl">VIEW ALL ROUTINES</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <a href="{{ route('admin.community-management.list') }}" class="btn-bl">VIEW ALL CREATED COMMUNITY</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="User-Management-section">
        <div class="User-profile-section">
            <div class="row g-1 align-items-center">
                <div class="col-md-2">
                    <div class="side-profile-item">
                        <div class="side-profile-media"><img src="{{ isset($user->profile) ? assets('uploads/profile/'.$user->profile) : assets('assets/images/no-image.jpg') }}"></div>
                        <div class="side-profile-text">
                            <h2>{{ $user->name ?? 'NA' }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="User-contact-info">
                                <div class="User-contact-info-icon">
                                    <img src="{{ assets('assets/images/users.svg') }}">
                                </div>
                                <div class="User-contact-info-content">
                                    <h2>Username</h2>
                                    <p>{{ $user->user_name ?? 'NA' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="User-contact-info">
                                <div class="User-contact-info-icon">
                                    <a href="mailto:{{$user->email ?? 'NA'}}"><img src="{{ assets('assets/images/sms1.svg') }}"></a>
                                </div>
                                <div class="User-contact-info-content">
                                    <h2>Email Address</h2>
                                    <p>{{ $user->email ?? 'NA' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="User-contact-info">
                                <div class="User-contact-info-icon">
                                    <a href="tel:{{$user->mobile ?? 'NA'}}"><img src="{{ assets('assets/images/call1.svg') }}"></a>
                                </div>
                                <div class="User-contact-info-content">
                                    <h2>Phone Number</h2>
                                    <p>{{ $user->country_code ?? '' }} {{ $user->mobile ?? 'NA' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="User-contact-info">
                                <div class="User-contact-info-content">
                                    <h2>Mark as</h2>
                                    <div class="switch-toggle">
                                        <p style="color: #8C9AA1;">Inactive</p>
                                        <div class="">
                                            <label class="toggle" for="myToggle">
                                                <input class="toggle__input" value="1" name="status" @if($user->status==1) checked @endif type="checkbox" id="myToggle">
                                                <div class="toggle__fill"></div>
                                            </label>
                                        </div>
                                        <p style="color: #8C9AA1;">Active</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="overview-section">
            <div class="row">
                <div class="col-md-6">
                    <div class="plan-card">
                        <div class="plan-content">
                            <div class="plan-content-text">
                                <p>Total Subscription Payment:</p>
                                @if(isset($plan->amount))
                                <h2>${{ number_format((float)$plan->amount ?? 0, 2, '.', '') }} / Monthly</h2>
                                <!-- <h2>${{ number_format((float)$plan->amount ?? 0, 2, '.', '') }} / Monthly <span>$0 / day</span></h2> -->
                                @else
                                <h2>$0 / Monthly</h2>
                                @endif
                            </div>
                            <div class="plan-content-icon">
                                @if(isset($plan->name))
                                @if($plan->name == 'Plan B')
                                <img src="{{ assets('assets/images/goldplan.svg') }}">
                                @elseif($plan->name == 'Plan A')
                                <img src="{{ assets('assets/images/freeplan.svg') }}">
                                @elseif($plan->name == 'Plan C')
                                <img src="{{ assets('assets/images/platinumplan.svg') }}">
                                @else
                                <img src="{{ assets('assets/images/goldplan.svg') }}">
                                @endif
                                @else
                                <img src="{{ assets('assets/images/goldplan.svg') }}">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="overview-card">
                        <div class="overview-card-body">
                            <div class="overview-content">
                                <div class="overview-content-text">
                                    <p>Total Subscription Payment:</p>
                                    @if(isset($plan->amount))
                                    <h2>${{ number_format((float)$plan->amount ?? 0, 2, '.', '') }}</h2>
                                    @else
                                    <h2>$0</h2>
                                    @endif
                                </div>
                                <div class="overview-content-icon">
                                    <img src="{{ assets('assets/images/dollar-circle.svg') }}" height="50">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overview-section">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-filter-title-section">
                        <!-- <h4>How Are You Feeling Today?</h4> -->
                        <div class="search-filter wd2">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="month" name="date" id="date" value="{{ date('Y-m') }}" max="{{date('Y-m')}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="feeling-card">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="feeling-content-item">
                                    <div class="feeling-emoj-icon"><img src="{{ assets('assets/images/happy.png') }}"></div>
                                    <p>Happy</p>
                                    <h2 id="happy">{{ ($avgMood['happy'] == 0 || !isset($avgMood['happy'])) ? 0 : $avgMood['happy'] }}%</h2>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="feeling-content-item">
                                    <div class="feeling-emoj-icon"><img src="{{ assets('assets/images/sad.png') }}"></div>
                                    <p>Sad</p>
                                    <h2 id="sad">{{ ($avgMood['sad'] == 0 || !isset($avgMood['sad'])) ? 0 : $avgMood['sad'] }}%</h2>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="feeling-content-item">
                                    <div class="feeling-emoj-icon"><img src="{{ assets('assets/images/anger.png') }}"></div>
                                    <p>Anger</p>
                                    <h2 id="anger">{{ ($avgMood['anger'] == 0 || !isset($avgMood['anger'])) ? 0 : $avgMood['anger'] }}%</h2>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="feeling-content-item">
                                    <div class="feeling-emoj-icon"><img src="{{ assets('assets/images/anxiety.png') }}"></div>
                                    <p>Anxiety</p>
                                    <h2 id="anxiety">{{ ($avgMood['anxiety'] == 0 || !isset($avgMood['anxiety'])) ? 0 : $avgMood['anxiety'] }}%</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="page-filter-title-section">
                    <h4>Subscription Transaction History</h4>
                    <!-- <div class="search-filter wd6">
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
                                    <a href="javascript:void(0)" class="btn-bl"><img src="{{ assets('assets/images/download.svg') }}"> Download report</a>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="jwjcard">
                    <div class="card-body">
                        <div class="jwj-table">
                            <table class="table xp-table" id="customer-table">
                                <thead>
                                    <tr class="table-hd">
                                        <th>Sr No.</th>
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
                                        <td>{{ $val->name }}</td>
                                        <td>${{ number_format((float)$val->amount, 2, '.', '') }}</td>
                                        <td>Montly</td>
                                        <td>{{ date('d M, Y', strtotime($val->activated_date)) }}</td>
                                        <td>{{ date('d M, Y', strtotime($val->renewal_date)) }}</td>
                                        <td>{{ $val->transaction_id ?? 'NA' }}</td>
                                    </tr>
                                    @empty
                                    <tr class="text-center">
                                        <td colspan="7">No record found</td>
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
@endsection

@push('js')
<script type="text/javascript">
    $(document).on('change', "#date", function(e) {
        e.preventDefault();
        let date = $(this).val();
        $.ajax({
            type: 'get',
            url: "{{ route('admin.users.change.mood.data', encrypt_decrypt('encrypt', $user->id)) }}",
            data: {
                date,
                '_token': "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(result) {
                if (result.status) {
                    // toastr.success(result.message);
                    // console.log(result.data);
                    $("#happy").text(result.data.happy + "%");
                    $("#sad").text(result.data.sad + "%");
                    $("#anger").text(result.data.anger + "%");
                    $("#anxiety").text(result.data.anxiety + "%");
                } else {
                    toastr.error(result.message);
                    return false;
                }
            },
            error: function(data, textStatus, errorThrown) {
                jsonValue = jQuery.parseJSON(data.responseText);
                console.error(jsonValue.message);
            },
        });
    });

    $(document).on('change', '#myToggle', function(e) {
        e.preventDefault();
        let status = ($(this).is(":checked")) ? 1 : 2;
        $.ajax({
            type: 'post',
            url: "{{ route('admin.users.change.status') }}",
            data: {
                id: "{{ encrypt_decrypt('encrypt', $user->id) }}",
                status,
                '_token': "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(result) {
                if (result.status) {
                    toastr.success(result.message);
                    window.location.reload();
                } else {
                    toastr.error(result.message);
                    return false;
                }
            },
            error: function(data, textStatus, errorThrown) {
                jsonValue = jQuery.parseJSON(data.responseText);
                console.error(jsonValue.message);
            },
        });
    });
</script>
@endpush