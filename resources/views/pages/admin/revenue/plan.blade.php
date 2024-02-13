@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/revenue.css') }}">
@endpush

@section('title','Journey with Journals - Subscription Plan')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Manage Subscription Plan</h4>
    <div class="search-filter wd2">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ route('admin.revenue-management.plans') }}" class="btn-bl">Sync Plan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="body-main-content">
    <div class="revenue-page-section">
        <div class="revenue-content">
            <div class="row">

                <?php $count = 0; ?>

                @forelse($plan as $val)
                <?php $count++; ?>
                <div class="col-md-4">
                    <div class="membership-list-item">
                        <div class="membership-header">
                            <div class="membership-plan-image">
                                @if($count == 1)
                                <img src="{{ assets('assets/images/freeplan.svg') }}">
                                @elseif($count == 2)
                                <img src="{{ assets('assets/images/goldplan.svg') }}">
                                @elseif($count == 3)
                                <img src="{{ assets('assets/images/platinumplan.svg') }}">
                                @endif
                            </div>
                            <div class="membership-text">
                                <div class="membership-title">{{ $val->name ?? 'NA' }}</div>
                                <div class="membership-day-price">@if($val->monthly_price == 0) <span></span> @else @if($val->currency == 'usd')$@endif{{number_format((float)$val->monthly_price, 2, '.', '')}}/month @endif</div>
                            </div>
                        </div>
                        <div class="membership-month-price">
                            <p>@if($val->anually_price == 0) Free <span></span> @else @if($val->currency == 'usd')$@endif{{number_format((float)$val->anually_price, 2, '.', '')}} <span>Per Year</span> @endif</p>
                        </div>
                        <div class="membership-body">
                            <div class="membership-list">
                                <ul>
                                    <li><i class="las la-check-circle"></i>{{ $val->entries_per_day }} Entry Per Day/{{ $val->words }} Words</li>
                                    <li><i class="las la-check-circle"></i>{{ $val->routines }} Routines With Ability To Share</li>
                                    <li><i class="las la-check-circle"></i>Add {{ $val->picture_per_day }} Picture Per Day</li>
                                    <li><i class="las la-check-circle"></i>
                                        @if($val->community == 1) View Community
                                        @elseif($val->community == 2) Participate In Communities
                                        @elseif($val->community == 3) Submit Your Own Communities/ App Approval Required.
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            <a class="Buy-btn" id="editPlan" data-id="{{encrypt_decrypt('encrypt', $val->id)}}">Edit Plan</a>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse

            </div>
        </div>
    </div>
</div>

<!-- Subscribe Now -->
<div class="modal lm-modal fade" id="EditPlan" tabindex="-1" aria-labelledby="EditPlanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="plan-modal-form">
                    <h2 id="planName"></h2>
                    <form action="{{ route('admin.revenue-management.update.plan') }}" method="post" id="updatePlan">@csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="entries">Entries per day</label>
                                    <input type="number" min="1" id="entries" class="form-control" name="entries" placeholder="Entries per day">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="words">Words per entries</label>
                                    <input type="number" min="1" class="form-control" id="words" name="words" placeholder="Words per entries">
                                    <input type="hidden" name="id" value="">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="picture">Picture per day</label>
                                    <input type="number" min="1" class="form-control" id="picture" name="picture" placeholder="Picture per day">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="routine">Routines with ability to share</label>
                                    <input type="number" min="1" class="form-control" id="routine" name="routine" placeholder="Routines with ability to share">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Community</label>
                                    <ul class="plan-list form-group">
                                        <li>
                                            <div class="plancheckbox">
                                                <input type="radio" id="community1" value="1" name="community">
                                                <label for="community1">
                                                    View community
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="radio" id="community2" value="2" name="community">
                                                <label for="community2">
                                                    Participate in community
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="radio" id="community3" value="3" name="community">
                                                <label for="community3">
                                                    Create your own community
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>


                            <!-- <div class="col-md-12">
                                <div class="form-group">
                                    <ul class="plan-list">
                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="3 Entries Per Day/ 250 Words Each" name="feature">
                                                <label for="3 Entries Per Day/ 250 Words Each">
                                                    3 Entries Per Day/ 250 Words Each
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="Add 1 Picture Per Day" name="feature">
                                                <label for="Add 1 Picture Per Day">
                                                    Add 1 Picture Per Day
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="10 Routines With Ability To Share" name="feature">
                                                <label for="10 Routines With Ability To Share">
                                                    10 Routines With Ability To Share
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="Participate In Communities" name="feature">
                                                <label for="Participate In Communities">
                                                    Participate In Communities
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="1 Entry Per Day/250 Words" name="feature">
                                                <label for="1 Entry Per Day/250 Words">
                                                    1 Entry Per Day/250 Words
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="3 Routine Tasks" name="feature">
                                                <label for="3 Routine Tasks">
                                                    3 Routine Tasks
                                                </label>
                                            </div>
                                        </li>


                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="View Community" name="feature">
                                                <label for="View Community">
                                                    View Community
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="Participate In Preset Communities" name="feature">
                                                <label for="Participate In Preset Communities">
                                                    Participate In Preset Communities
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="Up To 5 Entries Per Day/500  Words Each" name="feature">
                                                <label for="Up To 5 Entries Per Day/500  Words Each">
                                                    Up To 5 Entries Per Day/500 Words Each
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="Add 3 Pictures Per Day" name="feature">
                                                <label for="Add 3 Pictures Per Day">
                                                    Add 3 Pictures Per Day
                                                </label>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="Unlimited Routines With Ability To Share" name="feature">
                                                <label for="Unlimited Routines With Ability To Share">
                                                    Unlimited Routines With Ability To Share
                                                </label>
                                            </div>
                                        </li>


                                        <li>
                                            <div class="plancheckbox">
                                                <input type="checkbox" id="Submit Your Own Communities/ App Approval Required." name="feature">
                                                <label for="Submit Your Own Communities/ App Approval Required.">
                                                    Submit Your Own Communities/ App Approval Required.
                                                </label>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                            </div> -->

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Discard</button>
                                    <button type="submit" class="save-btn">Save Change</button>
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
    $('#updatePlan').validate({
        rules: {
            entries: {
                required: true,
            },
            words: {
                required: true,
            },
            picture: {
                required: true,
            },
            routine: {
                required: true,
            },
            community: {
                required: true,
            },
        },
        messages: {
            entries: {
                required: 'Please enter number of entries per day',
            },
            words: {
                required: 'Please enter number of words per entries',
            },
            picture: {
                required: 'Please enter number of picture per day',
            },
            routine: {
                required: 'Please enter number of routines',
            },
            community: {
                required: 'Please select community',
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
                complete: function() {
                    $("#preloader").hide()
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

    $(document).on('click', "#editPlan", function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $.ajax({
            type: 'get',
            url: "{{ route('admin.revenue-management.plan.details') }}",
            data: {
                id,
                _token: "{{ csrf_token() }}"
            },
            dataType: 'json',
            beforeSend: function() {
                $("#preloader").show()
            },
            success: function(result) {
                console.log(result);
                if (result.status) {
                    $("#planName").html("Manage " + result.data.name);
                    $("input[name='id']").val(id);
                    $("input[name='entries']").val(result.data.entries_per_day);
                    $("input[name='words']").val(result.data.words);
                    $("input[name='routine']").val(result.data.routines);
                    $("input[name='picture']").val(result.data.picture_per_day);
                    $(`input[id='community${result.data.community}']`).attr('checked', true);
                    $("#EditPlan").modal('show');
                } else {
                    toastr.error(result.message);
                    return false;
                }
            },
            complete: function() {
                $("#preloader").hide()
            },
            error: function(data, textStatus, errorThrown) {
                jsonValue = jQuery.parseJSON(data.responseText);
                console.error(jsonValue.message);
            }
        });
    });
</script>
@endpush