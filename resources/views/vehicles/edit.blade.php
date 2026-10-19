@extends('layout.master')
@section('title') تعديل مركبة @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">تعديل مركبة</h3></div>
<form method="POST" action="{{ route('vehicles.update',$vehicle->id) }}" enctype="multipart/form-data"><div class="card-body">
@csrf @method('PUT')
<div class="row">
<div class="form-group col-md-6"><label>نوع المركبة *</label><input name="vehicle_type" value="{{ old('vehicle_type',$vehicle->vehicle_type) }}" class="form-control" required></div>
<div class="form-group col-md-6"><label>رقم المركبة *</label><input name="plate_number" value="{{ old('plate_number',$vehicle->plate_number) }}" class="form-control" required>@error('plate_number')<span class="text-danger">{{ $message }}</span>@enderror</div>
<div class="form-group col-md-6"><label>رقم الشاصي *</label><input name="vin" value="{{ old('vin',$vehicle->vin) }}" class="form-control" required>@error('vin')<span class="text-danger">{{ $message }}</span>@enderror</div>
<div class="form-group col-md-6"><label>سنة الإنتاج</label><input type="number" name="manufacture_year" value="{{ old('manufacture_year',$vehicle->manufacture_year) }}" class="form-control"></div>
<div class="form-group col-md-6"><label>اللون</label><input name="color" value="{{ old('color',$vehicle->color) }}" class="form-control"></div>
<div class="form-group col-md-6"><label>صورة جديدة</label><input type="file" name="image" class="form-control" accept="image/*">@if($vehicle->image_path)<small>الصورة الحالية محفوظة</small>@endif</div>
<div class="form-group col-md-6"><label>الحالة</label><select name="is_active" class="form-control"><option value="1" @selected($vehicle->is_active)>نشطة</option><option value="0" @selected(!$vehicle->is_active)>غير نشطة</option></select></div>
<div class="form-group col-12"><label>ملاحظات</label><textarea name="notes" class="form-control">{{ old('notes',$vehicle->notes) }}</textarea></div>
</div></div>
<div class="card-footer"><button class="btn btn-primary">حفظ</button> <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">رجوع</a></div>
</form></div></div>
@endsection
