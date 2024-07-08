@extends('layouts.app')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ assets('assets/css/revenue.css') }}">
@endpush

@section('title','Journey with Journals - Subscription Plan')
@section('content')
<div class="page-breadcrumb-title-section">
    <h4>Manage Subscription Plan</h4>
    <div class="search-filter wd1">
        <div class="row g-1">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ route('admin.subscription.plans') }}" class="btn-bl">Sync Plan</a>
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
                                <div class="membership-day-price">
                                    @if($val->monthly_price == $val->anually_price) One Time<span></span> 
                                    @else 
                                        @if($val->currency == 'usd')$@endif{{number_format((float)$val->monthly_price, 2, '.', '')}}/month 
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="membership-month-price">
                            <p>
                                @if($val->monthly_price == $val->anually_price) @if($val->currency == 'usd')$@endif{{number_format((float)$val->monthly_price, 2, '.', '')}} <span></span> 
                                @else 
                                    @if($val->currency == 'usd')$@endif{{number_format((float)$val->anually_price, 2, '.', '')}} <span>Per Year</span> 
                                @endif
                            </p>
                        </div>
                        <div class="membership-body">
                            <div class="membership-list">
                                <ul>
                                    <li><i class="las la-check-circle"></i>{{ $val->entries_per_day }} Journals Per Day</li>
                                    <li><i class="las la-check-circle"></i>{{ $val->routines }} Routines Per Day</li>
                                    <li><i class="las la-check-circle"></i>
                                        @if($val->community == 1) View Community
                                        @elseif($val->community == 2) Participate In Communities
                                        @elseif($val->community == 3) Submit Your Own Communities/ App Approval Required.
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            <a style="cursor: pointer;" class="Buy-btn" id="editPlan" data-id="{{encrypt_decrypt('encrypt', $val->id)}}">Edit Plan</a>
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
                    <form action="{{ route('admin.subscription.update.plan') }}" method="post" id="updatePlan">@csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="journals">Journals per day</label>
                                    <input type="number" min="1" id="journals" class="form-control" name="journal" placeholder="Journals per day">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="routine">Routines per day</label>
                                    <input type="number" min="1" class="form-control" id="routine" name="routine" placeholder="Routines per day">
                                    <input type="hidden" name="id" value="">
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

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
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
    $('#EditPlan').on('hidden.bs.modal', function(e) {
        $("input[name='community']").removeAttr('checked');
        $(this).find('form').trigger('reset');
    })

    $('#updatePlan').validate({
        rules: {
            journal: {
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
            journal: {
                required: 'Please enter number of journals per day',
            },
            routine: {
                required: 'Please enter number of routines per day',
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
                        setInterval(() => {
                            window.location.reload()
                        }, 2000);
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
            url: "{{ route('admin.subscription.plan.details') }}",
            data: {
                id,
                _token: "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(result) {
                console.log(result);
                if (result.status) {
                    $("#planName").html("Manage " + result.data.name);
                    $("input[name='id']").val(id);
                    $("input[name='journal']").val(result.data.entries_per_day);
                    $("input[name='routine']").val(result.data.routines);
                    $("#community" + result.data.community).attr('checked', true);
                    $("#EditPlan").modal('show');
                } else {
                    toastr.error(result.message);
                    return false;
                }
            },
            error: function(data, textStatus, errorThrown) {
                jsonValue = jQuery.parseJSON(data.responseText);
                console.error(jsonValue.message);
            }
        });
    });
</script>
@endpush