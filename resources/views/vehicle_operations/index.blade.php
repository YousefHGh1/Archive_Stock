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
<div class="col-md-2"><select name="event_type" class="form-control"><option value="">كل الأنواع</option><option value="diesel" @selected(request('event_type')=='diesel')>سولار</option><option value="oil" @selected(request('event_type')=='oil')>زيت</option><option value="maintenance" @selected(request('event_type')=='maintenance')>صيانة</option></select></div>
<div class="col-md-2"><input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control"></div>
<div class="col-md-2"><input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control"></div>
<div class="col-md-12 mt-2"><button class="btn btn-light-primary">بحث</button></div>
</form>
<div class="table-responsive"><table class="table"><thead><tr><th>النوع</th><th>المركبة</th><th>السائق</th><th>سند الصادر</th><th>اللترات</th><th>التكلفة</th><th>التاريخ</th><th>إجراءات</th></tr></thead>
<tbody>@forelse($operations as $o)<tr><td>{{ $o->type_label }}</td><td>{{ $o->vehicle->plate_number ?? '—' }}</td><td>{{ $o->driver->name ?? '—' }}</td><td>{{ $o->dieselExport->voucher ?? '—' }}</td><td>{{ $o->liters ?? '—' }}</td><td>{{ $o->maintenance_cost ?? '—' }}</td><td>{{ $o->operation_date->format('Y-m-d') }}</td>
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
                        <div class="form-group col-md-4"><label>نوع الحدث *</label><select id="modal_event_type" name="event_type" class="form-control" required><option value="diesel" @selected(old('event_type')=='diesel')>سولار</option><option value="oil" @selected(old('event_type')=='oil')>زيت</option><option value="maintenance" @selected(old('event_type')=='maintenance')>صيانة</option></select>@error('event_type')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-4"><label>المركبة *</label><select name="vehicle_id" class="form-control" required><option value="">اختر</option>@foreach($vehicles as $v)<option value="{{ $v->id }}" @selected(old('vehicle_id')==$v->id)>{{ $v->plate_number }} - {{ $v->vehicle_type }}</option>@endforeach</select>@error('vehicle_id')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-4"><label>السائق *</label><select name="driver_id" class="form-control" required><option value="">اختر</option>@foreach($drivers as $d)<option value="{{ $d->id }}" @selected(old('driver_id')==$d->id)>{{ $d->name }}</option>@endforeach</select>@error('driver_id')<span class="text-danger">{{ $message }}</span>@enderror</div>
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
<script>
(function(){
var t=document.getElementById('modal_event_type');if(!t)return;
var l=document.getElementById('modal_liters_wrap'),c=document.getElementById('modal_cost_wrap');
function g(){var m=t.value==='maintenance';l.style.display=m?'none':'';c.style.display=m?'':'none';}
t.addEventListener('change',g);g();
})();
</script>
@if($errors->any() && old('event_type') !== null)
<script>$(function(){ $('#operationCreateModal').modal('show'); });</script>
@endif
@if(request('open') === 'create')
<script>$(function(){ $('#operationCreateModal').modal('show'); });</script>
@endif
@endsection
