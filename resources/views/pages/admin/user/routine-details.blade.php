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
                                    <img src="{{ isset($data->category->logo) ? assets('uploads/routine/'.$data->category->logo) : assets('assets/images/no-image.jpg') }}">
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
            <div class="col-md-4">
                <div class="sidebar-member-info">
                    <div class="sidebar-member-head">
                        <h2>Schedule List</h2>
                    </div>
                    <div class="sidebar-member-body">
                        <div class="sidebar-member-list-card" style="min-height: 37px; max-height: 520px; overflow-y: auto; overflow-x: hidden;">
                            @forelse($data->schedule->interval as $item)
                            <div class="sidebar-member-item">
                                <div class="sidebar-member-item-image"><img src="{{ isset($user->profile) ? assets('uploads/profile/'.$user->profile) : assets('assets/images/no-image.jpg') }}"></div>
                                <div class="sidebar-member-item-text">
                                    <h2>{{ $item->interval_weak ?? "NA" }}</h2>
                                    <div class="sidebar-member-plan">{{ $item->interval_time ?? "NA" }}</div>
                                </div>
                            </div>
                            @empty
                            <div class="sidebar-member-item" style="border: none;">
                                <div class="mx-auto">
                                    No followers
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="page-posts-title-section">
                    <h4>Total Posts</h4>
                    <div class="search-filter wd7">
                        <div class="row g-1">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <div class="search-form-group">
                                        <input type="text" name="search" id="PostSearch" class="form-control" placeholder="Search by name, tags">
                                        <span class="search-icon"><img src="{{ assets('assets/images/search-icon.svg') }}"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <a data-bs-toggle="modal" data-bs-target="#PostOnCommunity" class="btn-bl"> Post
                                        On Community</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="post-card-lists">
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