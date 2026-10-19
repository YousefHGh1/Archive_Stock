@extends('layout.master')
@section('title') عرض المركبات @endsection
@section('content')
<div class="container">
<div class="card card-custom">
<div class="card-header flex-wrap pt-6 pb-0 border-0">
<div class="card-title"><h3 class="card-label">عرض المركبات</h3></div>
<div class="card-toolbar">
@can('vehicles-create')<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#vehicleCreateModal">إضافة مركبة</button>@endcan
</div></div>
<div class="card-body">
<form method="GET" class="row mb-4">
<div class="col-md-6"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث برقم المركبة / الشاصي / النوع"></div>
<div class="col-md-3"><select name="is_active" class="form-control"><option value="">كل الحالات</option><option value="1" @selected(request('is_active')==='1')>نشطة</option><option value="0" @selected(request('is_active')==='0')>غير نشطة</option></select></div>
<div class="col-md-3"><button class="btn btn-light-primary">بحث</button></div>
</form>
<div class="table-responsive"><table class="table">
<thead><tr><th>النوع</th><th>الرقم</th><th>الشاصي</th><th>السنة</th><th>الحالة</th><th>إجراءات</th></tr></thead>
<tbody>
@forelse($vehicles as $v)
<tr>
<td>{{ $v->vehicle_type }}</td><td>{{ $v->plate_number }}</td><td>{{ $v->vin }}</td><td>{{ $v->manufacture_year ?? '—' }}</td>
<td>{{ $v->is_active ? 'نشطة' : 'غير نشطة' }}</td>
<td>
@can('vehicles-read')<a href="{{ route('vehicles.show',$v->id) }}" class="btn btn-sm btn-clean btn-icon" title="عرض"><i class="la la-eye"></i></a>@endcan
@can('vehicles-update')<a href="{{ route('vehicles.edit',$v->id) }}" class="btn btn-sm btn-clean btn-icon" title="تعديل"><i class="la la-edit"></i></a>@endcan
@can('vehicles-delete')
<form action="{{ route('vehicles.destroy',$v->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد الحذف؟')">@csrf @method('DELETE')<button class="btn btn-sm btn-clean btn-icon" title="حذف"><i class="la la-trash"></i></button></form>
@endcan
</td></tr>
@empty<tr><td colspan="6" class="text-center">لا توجد بيانات</td></tr>@endforelse
</tbody></table></div>
{!! $vehicles->withQueryString()->links('pagination::bootstrap-5') !!}
</div></div></div>

@can('vehicles-create')
<div class="modal fade" id="vehicleCreateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة مركبة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="POST" action="{{ route('vehicles.store') }}" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6"><label>نوع المركبة *</label><input name="vehicle_type" value="{{ old('vehicle_type') }}" class="form-control" required>@error('vehicle_type')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-6"><label>رقم المركبة *</label><input name="plate_number" value="{{ old('plate_number') }}" class="form-control" required>@error('plate_number')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-6"><label>رقم الشاصي VIN *</label><input name="vin" value="{{ old('vin') }}" class="form-control" required>@error('vin')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-6"><label>سنة الإنتاج</label><input type="number" name="manufacture_year" value="{{ old('manufacture_year') }}" class="form-control">@error('manufacture_year')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-6"><label>اللون</label><input name="color" value="{{ old('color') }}" class="form-control"></div>
                        <div class="form-group col-md-6"><label>صورة المركبة (jpg/png/webp حتى 2MB)</label><input type="file" name="image" class="form-control" accept="image/*">@error('image')<span class="text-danger">{{ $message }}</span>@enderror</div>
                        <div class="form-group col-md-6"><label>الحالة</label><select name="is_active" class="form-control"><option value="1" selected>نشطة</option><option value="0">غير نشطة</option></select></div>
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
@if($errors->any() && old('vehicle_type') !== null)
<script>$(function(){ $('#vehicleCreateModal').modal('show'); });</script>
@endif
@if(request('open') === 'create')
<script>$(function(){ $('#vehicleCreateModal').modal('show'); });</script>
@endif
@endsection

