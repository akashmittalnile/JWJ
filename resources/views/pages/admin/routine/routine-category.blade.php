@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/user.css') }}">
<style>
    .dropdown-item:hover{
        background-color: #1079c0 !important;
        color: white !important;
    }
</style>
@endpush

@section('title','Journey with Journals - Routine Category')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Routine Category</h4>
    <div class="search-filter wd6">
        <div class="row g-1">
            <div class="col-md-3">
                <div class="form-group">
                    <div class="search-form-group">
                        <select class="form-control" id="searchSelect" name="">
                            <option value="">All Category</option>
                            <option value="1">Active Category</option>
                            <option value="2">Inactive Category</option>
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
            <div class="col-md-4">
                <div class="form-group">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addnewcommunity" id="download-report" class="btn-bl">Add New Category</a>
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
                                        <th>Category Image</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
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

<div class="modal lm-modal fade" id="openModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form text-center">
                    <h2>Are You Sure?</h2>
                    <p>You want to delete this routine category!</p>
                    <form action="{{ route('admin.routine.category.delete') }}" method="post">
                        @csrf
                        <input type="hidden" id="deleteId" name="id">
                        <div class="row">
                            <div class="col-md-12">
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

<div class="modal lm-modal fade" id="editcommunity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form">
                    <h2>EDIT NEW CATEGORY</h2>
                    <form action="{{ route('admin.routine.category.update') }}" method="post" id="editCategory" enctype="multipart/form-data">@csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="editName" name="title" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" name="status" id="editStatus">
                                        <option value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                    </select>
                                </div>
                                <input type="hidden" name="id" id="editId">
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <img width="75" height="45" style="object-fit: cover; object-position:center;" src="" id="editFile" alt="">
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <input type="file" class="form-control" id="editFile" accept="image/png, image/jpg, image/jpeg" name="file">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea type="text" name="description" id="editDescription" class="form-control" placeholder="Enter description here..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="submit" class="save-btn">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal lm-modal fade" id="addnewcommunity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="jwj-modal-form">
                    <h2>ADD NEW CATEGORY</h2>
                    <form action="{{ route('admin.routine.category.store') }}" method="post" id="newCategory" enctype="multipart/form-data">@csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="title" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control" name="status">
                                        <option value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
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
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    <button type="submit" class="save-btn">Add</button>
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
    $('#addnewcommunity, #editcommunity, #openModal').on('hidden.bs.modal', function(e) {
        $(this).find('form').trigger('reset');
    });

    $(document).on('click', "#editComm", function() {
        $("#editName").val($(this).data('name'));
        $("#editDescription").val($(this).data('description'));
        $("#editId").val($(this).data('id'));
        $("#editStatus").val($(this).data('status'));
        $("#editFile").attr('src', $(this).data('img'));
        $('#editcommunity').modal('show');
    });

    $(document).on('click', "#deleteComm", function() {
        $("#deleteId").val($(this).data('id'));
        $('#openModal').modal('show');
    });

    $(document).ready(function() {
        $('#newCategory').validate({
            rules: {
                title: {
                    required: true,
                },
                status: {
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
                    required: 'Please enter name',
                },
                status: {
                    required: 'Please select status',
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
                    beforeSend: function() {
                        $("#preloader").show()
                    },
                    complete: function() {
                        $("#preloader").hide()
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            setInterval(() => {window.location.reload()}, 2000);
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

        $('#editCategory').validate({
            rules: {
                title: {
                    required: true,
                },
                status: {
                    required: true,
                },
                description: {
                    required: true,
                },
            },
            messages: {
                title: {
                    required: 'Please enter name',
                },
                status: {
                    required: 'Please select status',
                },
                description: {
                    required: 'Please enter description',
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
                    beforeSend: function() {
                        $("#preloader").show()
                    },
                    complete: function() {
                        $("#preloader").hide()
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            setInterval(() => {window.location.reload()}, 2000);
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

        const getList = (page, search = null, ustatus = null) => {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.routine.category') }}",
                data: {
                    page,
                    search,
                    ustatus
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
                                        <td colspan="6"><img width="350" src="{{ assets('assets/images/no-data.svg') }}" alt="no-data"></td>
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
            let status = $("#searchSelect").val();
            let search = $(this).val();
            getList($(this).data('page'), search, status);
        });
        $(document).on('change', "#searchSelect", function() {
            let status = $("#searchSelect").val();
            let search = $("#searchInput").val();
            getList($(this).data('page'), search, status);
        });
    })
</script>
@endpush