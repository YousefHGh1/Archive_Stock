@extends('layout.master')
@section('title') تعديل سائق @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">تعديل سائق</h3></div>
<form method="POST" action="{{ route('drivers.update',$driver->id) }}"><div class="card-body">@csrf @method('PUT')
<div class="form-group"><label>اسم السائق *</label><input name="name" value="{{ old('name',$driver->name) }}" class="form-control" required></div>
<div class="form-group"><label>الحالة</label><select name="is_active" class="form-control"><option value="1" @selected($driver->is_active)>نشط</option><option value="0" @selected(!$driver->is_active)>غير نشط</option></select></div>
<div class="form-group"><label>ملاحظات</label><textarea name="notes" class="form-control">{{ old('notes',$driver->notes) }}</textarea></div>
</div><div class="card-footer"><button class="btn btn-primary">حفظ</button> <a href="{{ route('drivers.index') }}" class="btn btn-secondary">رجوع</a></div></form></div></div>
@endsection
