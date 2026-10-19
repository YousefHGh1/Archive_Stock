@extends('layout.master')
@section('title') إضافة سائق @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">إضافة سائق</h3></div>
<form method="POST" action="{{ route('drivers.store') }}"><div class="card-body">@csrf
<div class="form-group"><label>اسم السائق *</label><input name="name" value="{{ old('name') }}" class="form-control" required>@error('name')<span class="text-danger">{{ $message }}</span>@enderror</div>
<div class="form-group"><label>الحالة</label><select name="is_active" class="form-control"><option value="1" selected>نشط</option><option value="0">غير نشط</option></select></div>
<div class="form-group"><label>ملاحظات</label><textarea name="notes" class="form-control">{{ old('notes') }}</textarea></div>
</div><div class="card-footer"><button class="btn btn-primary">حفظ</button> <a href="{{ route('drivers.index') }}" class="btn btn-secondary">رجوع</a></div></form></div></div>
@endsection
