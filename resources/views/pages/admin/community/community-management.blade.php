@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/community-management.css') }}">
@endpush

@section('title','Journey with Journals - Community Management')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Community Management</h4>
    <div class="search-filter wd8">
        <div class="row g-1">
            <div class="col-md-3">
                <div class="form-group">
                    <a data-bs-toggle="modal" data-bs-target="#addnewcommunity" class="btn-bl"> Add New Community</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <a href="{{ route('admin.community-management.approval') }}" class="btn-bl"> New Community Approval</a>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group">
                    <select class="form-control" id="searchSelect" name="">
                        <option selected value="">Show All Community</option>
                        <option value="1">User Created Community </option>
                        <option value="2">Admin Created Community </option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
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


<!-- ADD NEW COMMUNITY -->
<div class="modal lm-modal fade" id="addnewcommunity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form">
                    <h2>ADD NEW COMMUNITY</h2>
                    <form action="{{ route('admin.community-management.store.data') }}" method="post" id="newCommunity" enctype="multipart/form-data">@csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="title" placeholder="Title">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" name="subscription">
                                        <option value="">Select Subscription Plan</option>
                                        @foreach($plan as $val)
                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="file" class="form-control" accept="image/png, image/jpg, image/jpeg" name="file">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="description" class="form-control" placeholder="Enter description here..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Discard</button>
                                    <button type="submit" class="save-btn">Save</button>
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
<script>
    $('#newCommunity').validate({
        rules: {
            title: {
                required: true,
            },
            subscription: {
                required: true,
            },
            description: {
                required: true,
            },
            file: {
                required: true,
            },
        },
        messages: {
            title: {
                required: 'Please enter title',
            },
            subscription: {
                required: 'Please select subscription plan',
            },
            description: {
                required: 'Please enter description',
            },
            file: {
                required: 'Please upload file',
            },
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            let formData = new FormData(form);
            $.ajax({
                type: 'post',
                url: form.action,
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        window.location.reload();
                        return false;
                    } else {
                        toastr.error(response.message);
                        return false;
                    }
                },
                error: function(data, textStatus, errorThrown) {
                    jsonValue = jQuery.parseJSON(data.responseText);
                    console.error(jsonValue.message);
                },
            })
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    $(document).on('change', '.toggle__input', function (e) {
        let status = ($(this).is(":checked")) ? 1 : 2;
        let id = $(this).data('id');
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: "{{ route('admin.community-management.change.status') }}",
            data: {id, status, '_token': "{{ csrf_token() }}"},
            dataType: 'json',
            success: function (result) {
                if(result.status) {
                    toastr.success(result.message);
                    window.location.reload();
                } else {
                    toastr.error(result.message);
                    return false;
                }
            },
            error: function(data, textStatus, errorThrown) {
                jsonValue = jQuery.parseJSON( data.responseText );
                console.error(jsonValue.message);
            },
        });
    });

    $(document).ready(function() {
        const getList = (page, search = null, role = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.community-management.list') }}",
                data: {
                    page,
                    search, role
                },
                dataType: 'json',
                success: function(result) {
                    if (result.status) {
                        let userData = result.data.html.data;
                        let html = result.data.html;
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
                                        <td colspan="5"> No record found</td>
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
        $(document).on('keyup', "#searchInput", function() {
            let role = $("#searchSelect").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, role);
        })
        $(document).on('change', "#searchSelect", function() {
            let role = $("#searchSelect").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, role);
        })
    })
</script>
@endpush