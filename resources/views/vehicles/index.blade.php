@extends('layout.master')
@section('title') عرض المركبات @endsection
@section('content')
<div class="container">
<div class="card card-custom">
<div class="card-header flex-wrap pt-6 pb-0 border-0">
<div class="card-title"><h3 class="card-label">عرض المركبات</h3></div>
<div class="card-toolbar">
@can('vehicles-create')<a href="{{ route('vehicles.create') }}" class="btn btn-primary">إضافة مركبة</a>@endcan
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
@endsection
