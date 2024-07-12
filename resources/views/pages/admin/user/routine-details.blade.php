@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community-management.css') }}">
@endpush

@section('title','Journey with Journals - Routine Details')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Routine Details</h4>
    <div class="search-filter wd1">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ route('admin.users.routines', encrypt_decrypt('encrypt', $data->created_by)) }}" class="btn-bl">Back</a>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="community-details-page-section">
        <div class="community-content-details">
            <div class="row">
                <div class="col-md-12">
                    <div class="jwj-posts-posts-card">
                        <div class="jwj-posts-head">
                            <div class="post-member-item">
                                <div class="post-member-image">
                                    <img src="{{ (isset($data->category->logo) && file_exists(public_path('uploads/routine/'.$data->category->logo)) ) ? assets('uploads/routine/'.$data->category->logo) : assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="post-member-text">
                                    <h3 style="margin: 0px 0px -3px 0px;">{{ $data->category->name ?? 'NA' }}</h3>
                                </div>
                            </div>
                            <div class="jwjcard-group-action">
                                <div class="jwjcard-member-item">
                                    <p style="color: #1079c0;" class="mx-3">{{ config('constant.frequency')[$data->schedule->frequency] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="jwj-posts-body">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <div class="jwjcard-body">
                                        <div class="community-post-description mb-1">
                                            <h3>{{ $data->name ?? 'NA' }}</h3>
                                            <p style="color: #1079c0;">{{ $data->subtitle ?? '' }}</p>
                                            <p>{{ $data->description ?? 'NA' }}</p>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="community-page-section">
        <div class="row">
            <div class="col-md-6">
                <div class="sidebar-member-info">
                    <div class="sidebar-member-head">
                        <h2>Schedule Date & Time</h2>
                    </div>
                    <div class="sidebar-member-body">
                        <div class="sidebar-member-list-card" style="min-height: 37px; max-height: 520px; overflow-y: auto; overflow-x: hidden;">
                            @if($data->schedule->frequency == 'C')
                                @php  $arr = array();  @endphp 
                                @foreach($data->schedule->interval as $item)
                                    @php array_push($arr, $item->interval_weak); @endphp
                                @endforeach
                                @for($i = strtotime($data->schedule->schedule_startdate); $i <= strtotime($data->schedule->schedule_enddate); $i += 86400 )
                                    @if(in_array(config('constant.day_num')[date('N', $i)], $arr))
                                        <div class="sidebar-member-item">
                                            <div class="sidebar-member-item-image" style="padding: 16px;">
                                                <img src="{{ assets('assets/images/calendar.svg') }}">
                                            </div>
                                            <div class="sidebar-member-item-text">
                                                <h2>{{ date('m-d-Y D', $i) }}</h2>
                                                <div class="sidebar-member-plan">{{ date('h:i A', strtotime($data->schedule->interval[0]->interval_time)) }}</div>
                                            </div>
                                        </div>
                                    @endif
                                @endfor
                            @else
                                @forelse($data->schedule->interval as $item)
                                <div class="sidebar-member-item">
                                    <div class="sidebar-member-item-image" style="padding: 16px;">
                                        <img src="{{ assets('assets/images/calendar.svg') }}">
                                    </div>
                                    <div class="sidebar-member-item-text">
                                        @if($data->schedule->frequency == 'D')
                                        <h2>Daily</h2>
                                        <div class="sidebar-member-plan">{{ date('h:i A', strtotime($item->interval_time)) }}</div>
                                        @else
                                        <h2>{{ date('m-d-Y D', strtotime($item->created_at)) }}</h2>
                                        <div class="sidebar-member-plan">{{ date('h:i A', strtotime($item->interval_time)) }}</div>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <div class="sidebar-member-item" style="border: none;">
                                    <div class="mx-auto">
                                        No schedules found
                                    </div>
                                </div>
                                @endforelse
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="sidebar-member-info">
                    <div class="sidebar-member-head">
                        <h2>Routine Shared To</h2>
                    </div>
                    <div class="sidebar-member-body">
                        <div class="sidebar-member-list-card" style="min-height: 37px; max-height: 520px; overflow-y: auto; overflow-x: hidden;">
                            @forelse($share as $item)
                            <div class="sidebar-member-item">
                                <div class="sidebar-member-item-image">
                                    <img src="{{ (isset($item->user->profile) && file_exists(public_path('uploads/profile/'.$item->user->profile)) ) ? assets('uploads/profile/'.$item->user->profile) : assets('assets/images/no-image.jpg') }}">
                                </div>
                                <div class="sidebar-member-item-text">
                                    <h2>{{ $item->user->user_name ?? 'NA' }}</h2>
                                    <div class="sidebar-member-plan"></div>
                                </div>
                            </div>
                            @empty
                            <div class="sidebar-member-item" style="border: none;">
                                <div class="mx-auto">
                                    No shared users found
                                </div>
                            </div>
                            @endforelse
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

    </script>
@endpush