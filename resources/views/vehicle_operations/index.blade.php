@extends('layout.master')
@section('title') عمليات المركبات @endsection
@section('content')
<div class="container"><div class="card card-custom">
<div class="card-header flex-wrap pt-6 pb-0 border-0"><div class="card-title"><h3 class="card-label">عمليات المركبات</h3></div>
<div class="card-toolbar">@can('vehicle-operations-create')<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#operationCreateModal">تسجيل عملية</button>@endcan</div></div>
<div class="card-body">
<form method="GET" class="row mb-4">
<div class="col-md-3"><select name="vehicle_id" class="form-control"><option value="">كل المركبات</option>@foreach($vehicles as $v)<option value="{{ $v->id }}" @selected(request('vehicle_id')==$v->id)>{{ $v->plate_number }}</option>@endforeach</select></div>
<div class="col-md-3"><select name="driver_id" class="form-control"><option value="">كل السائقين</option>@foreach($drivers as $d)<option value="{{ $d->id }}" @selected(request('driver_id')==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-2"><select name="event_type" class="form-control"><option value="">كل الأنواع</option><option value="diesel" @selected(request('event_type')=='diesel')>سولار</option><option value="oil" @selected(request('event_type')=='oil')>زيت</option><option value="recycled_oil" @selected(request('event_type')=='recycled_oil')>أضف زيت مكرر</option><option value="maintenance" @selected(request('event_type')=='maintenance')>صيانة</option></select></div>
<div class="col-md-2"><input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control"></div>
<div class="col-md-2"><input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control"></div>
<div class="col-md-12 mt-2"><button class="btn btn-light-primary">بحث</button></div>
</form>
<div class="table-responsive"><table class="table"><thead><tr><th>النوع</th><th>المركبة</th><th>السائق</th><th>سند الصرف</th><th>اللترات</th><th>التكلفة</th><th>التاريخ</th><th>إجراءات</th></tr></thead>
<tbody>@forelse($operations as $o)<tr><td>{{ $o->type_label }}</td><td>{{ $o->vehicle->vehicle_type ?? '—' }}</td><td>{{ $o->driver->name ?? '—' }}</td><td>{{ $o->invoiceExport->voucher_no ?? $o->dieselExport->voucher ?? '—' }}</td><td>{{ $o->liters ?? '—' }}</td><td>{{ $o->maintenance_cost ?? '—' }}</td><td>{{ $o->operation_date->format('Y-m-d') }}</td>
<td>@can('vehicle-operations-read')<a href="{{ route('vehicle-operations.show',$o->id) }}" class="btn btn-sm btn-clean btn-icon"><i class="la la-eye"></i></a>@endcan
@can('vehicle-operations-update')<a href="{{ route('vehicle-operations.edit',$o->id) }}" class="btn btn-sm btn-clean btn-icon"><i class="la la-edit"></i></a>@endcan
@can('vehicle-operations-delete')<form action="{{ route('vehicle-operations.destroy',$o->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد الحذف؟')">@csrf @method('DELETE')<button class="btn btn-sm btn-clean btn-icon"><i class="la la-trash"></i></button></form>@endcan</td></tr>
@empty<tr><td colspan="7" class="text-center">لا توجد بيانات</td></tr>@endforelse</tbody></table></div>
{!! $operations->withQueryString()->links('pagination::bootstrap-5') !!}
</div></div></div>

@can('vehicle-operations-create')
<div class="modal fade" id="operationCreateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تسجيل عملية مركبة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="POST" action="{{ route('vehicle-operations.store') }}">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4"><label>نوع الحدث *</label><select id="modal_event_type" name="event_type" class="form-control" required><option value="diesel" @selected(old('event_type')=='diesel')>سولار</option><option value="oil" @selected(old('event_type')=='oil')>زيت</option><option value="recycled_oil" @selected(old('event_type')=='recycled_oil')>أضف زيت مكرر</option><option value="maintenance" @selected(old('event_type')=='maintenance')>صيانة</option></select>@error('event_type')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-4"><label>المركبة *</label><div class="input-group"><select name="vehicle_id" class="form-control" required><option value="">اختر</option>@foreach($vehicles as $v)<option value="{{ $v->id }}" @selected(old('vehicle_id')==$v->id)>{{ $v->plate_number }} - {{ $v->vehicle_type }}</option>@endforeach</select><div class="input-group-append"><button type="button" class="btn btn-outline-primary" title="إضافة مركبة" data-toggle="modal" data-target="#quickAddVehicleModal"><i class="ki ki-solid-plus"></i></button></div></div>@error('vehicle_id')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-4"><label>السائق</label><div class="input-group"><select name="driver_id" class="form-control"><option value="">بدون سائق</option>@foreach($drivers as $d)<option value="{{ $d->id }}" @selected(old('driver_id')==$d->id)>{{ $d->name }}</option>@endforeach</select><div class="input-group-append"><button type="button" class="btn btn-outline-primary" title="إضافة سائق" data-toggle="modal" data-target="#quickAddDriverModal"><i class="ki ki-solid-plus"></i></button></div></div>@error('driver_id')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-4" id="modal_export_wrap"><label>سند الصادر (اختياري)</label><select name="diesel_export_id" class="form-control"><option value="">اختر سند صادر</option>@foreach(\App\Models\DieselExport::with(['section', 'subSection', 'typesfuel'])->orderByDesc('date')->orderByDesc('id')->get() as $de)<option value="{{ $de->id }}" @selected(old('diesel_export_id')==$de->id)>{{ $de->date }} - {{ $de->voucher }} - {{ $de->quantity }} لتر - {{ $de->typesfuel->name ?? '' }}</option>@endforeach</select>@error('diesel_export_id')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-6" id="modal_liters_wrap"><label>كمية اللترات *</label><input type="number" step="0.01" min="0.01" name="liters" value="{{ old('liters') }}" class="form-control">@error('liters')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-6" id="modal_cost_wrap" style="display:none"><label>تكلفة الصيانة بالشيكل *</label><input type="number" step="0.01" min="0" name="maintenance_cost" value="{{ old('maintenance_cost') }}" class="form-control">@error('maintenance_cost')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-6"><label>تاريخ العملية *</label><input type="date" name="operation_date" value="{{ old('operation_date', date('Y-m-d')) }}" class="form-control" required></div>
                        <div class="form-group col-12"><label>ملاحظات</label><textarea name="notes" class="form-control">{{ old('notes') }}</textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection
@section('scripts')
<style>
.quick-add-modal .modal-header { align-items: center; }
.quick-add-modal .modal-title { margin: 0; }
.quick-add-modal .modal-body { color: #5e6775; }
.quick-add-modal .quick-add-context { margin-bottom: 1rem; color: #5e6775; line-height: 1.6; }
.quick-add-modal [data-field-error]:empty { display: none !important; }
</style>
<script>
(function(){
var t=document.getElementById('modal_event_type');if(!t)return;
var l=document.getElementById('modal_liters_wrap'),c=document.getElementById('modal_cost_wrap');
function g(){var m=t.value==='maintenance';l.style.display=m?'none':'';c.style.display=m?'':'none';}
t.addEventListener('change',g);g();
})();

function addOption(select, id, label) {
    var $select = $(select);
    var value = String(id);
    if (!$select.find('option').filter(function() {
            return this.value === value;
        }).length) {
        $select.append(new Option(label, value));
    }
    $select.val(value).trigger('change');
}

function resetFeedback(form) {
    var $form = $(form);
    $form.find('[data-form-error]').hide().text('');
    $form.find('[data-field-error]').text('');
    $form.find('.is-invalid').removeClass('is-invalid');
}

$(document).on('submit', '[data-ajax-quick-add]', function(event) {
    event.preventDefault();
    var form = this;
    var $form = $(form);
    var $submit = $form.find('[type="submit"]');
    var label = $submit.data('submit-label') || $submit.text();

    resetFeedback(form);
    $submit.prop('disabled', true).text('جارٍ الحفظ...');

    $.ajax({
        url: form.action,
        method: 'POST',
        data: $form.serialize(),
        dataType: 'json',
        headers: {
            Accept: 'application/json'
        }
    }).done(function(entity) {
        var entityType = $form.data('entity');
        if (entityType === 'vehicle') {
            $('#operationCreateModal').find('select[name="vehicle_id"]').each(function() {
                addOption(this, entity.id, entity.plate_number + ' - ' + entity.vehicle_type);
            });
        }
        if (entityType === 'driver') {
            $('#operationCreateModal').find('select[name="driver_id"]').each(function() {
                addOption(this, entity.id, entity.name);
            });
        }
        form.reset();
        $form.closest('.modal').modal('hide');
        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'تمت الإضافة بنجاح',
                showConfirmButton: false,
                timer: 2200
            });
        }
    }).fail(function(xhr) {
        var errors = xhr.responseJSON && xhr.responseJSON.errors;
        var assignedError = false;
        if (errors) {
            $.each(errors, function(field, messages) {
                var $input = $form.find('[name]').filter(function() {
                    return this.name === field;
                }).first();
                var $feedback = $form.find('[data-field-error]').filter(function() {
                    return $(this).data('field-error') === field;
                }).first();
                if ($input.length && $feedback.length) {
                    $input.addClass('is-invalid');
                    $feedback.text(Array.isArray(messages) ? messages[0] : messages);
                    assignedError = true;
                }
            });
        }
        if (!assignedError) {
            $form.find('[data-form-error]').text(
                (xhr.responseJSON && xhr.responseJSON.message) ||
                'تعذر الاتصال بالخادم. تحقق من الاتصال وحاول مرة أخرى.'
            ).show();
        }
    }).always(function() {
        $submit.prop('disabled', false).text(label);
    });
});

$(document).on('input change', '[data-ajax-quick-add] [name]', function() {
    $(this).removeClass('is-invalid');
    $(this).siblings('[data-field-error]').text('');
});

$('.quick-add-modal').on('hidden.bs.modal', function() {
    var form = $(this).find('[data-ajax-quick-add]')[0];
    if (form) {
        resetFeedback(form);
    }
});
</script>
@if($errors->any() && old('event_type') !== null)
<script>$(function(){ $('#operationCreateModal').modal('show'); });</script>
@endif
@if(request('open') === 'create')
<script>$(function(){ $('#operationCreateModal').modal('show'); });</script>
@endif

<!-- Quick Add Vehicle Modal -->
<div class="modal fade quick-add-modal" id="quickAddVehicleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title quick-add-heading">إضافة مركبة جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="quick-add-context">سيتم اختيار المركبة تلقائياً بعد الإضافة.</p>
                <form action="{{ route('vehicles.store') }}" method="POST" data-ajax-quick-add data-entity="vehicle">
                    @csrf
                    <div data-form-error class="alert alert-danger"></div>

                    <div class="form-group">
                        <label>رقم اللوحة *</label>
                        <input type="text" name="plate_number" class="form-control" required minlength="2" maxlength="50" />
                        <div data-field-error="plate_number" class="text-danger"></div>
                    </div>

                    <div class="form-group">
                        <label>نوع المركبة *</label>
                        <input type="text" name="vehicle_type" class="form-control" required minlength="2" maxlength="100" />
                        <div data-field-error="vehicle_type" class="text-danger"></div>
                    </div>

                    <div class="form-group">
                        <label>الموديل</label>
                        <input type="text" name="model" class="form-control" maxlength="100" />
                        <div data-field-error="model" class="text-danger"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" data-submit-label="حفظ">حفظ</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quick Add Driver Modal -->
<div class="modal fade quick-add-modal" id="quickAddDriverModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title quick-add-heading">إضافة سائق جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="quick-add-context">سيتم اختيار السائق تلقائياً بعد الإضافة.</p>
                <form action="{{ route('drivers.store') }}" method="POST" data-ajax-quick-add data-entity="driver">
                    @csrf
                    <div data-form-error class="alert alert-danger"></div>

                    <div class="form-group">
                        <label>اسم السائق *</label>
                        <input type="text" name="name" class="form-control" required minlength="2" maxlength="255" />
                        <div data-field-error="name" class="text-danger"></div>
                    </div>

                    <div class="form-group">
                        <label>رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control" maxlength="20" />
                        <div data-field-error="phone" class="text-danger"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" data-submit-label="حفظ">حفظ</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
