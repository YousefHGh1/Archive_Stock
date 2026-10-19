@extends('layout.master')
@section('title') عرض السائقين @endsection
@section('content')
<div class="container"><div class="card card-custom">
<div class="card-header flex-wrap pt-6 pb-0 border-0"><div class="card-title"><h3 class="card-label">عرض السائقين</h3></div>
<div class="card-toolbar">@can('drivers-create')<a href="{{ route('drivers.create') }}" class="btn btn-primary">إضافة سائق</a>@endcan</div></div>
<div class="card-body">
<form method="GET" class="row mb-4"><div class="col-md-6"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث بالاسم"></div>
<div class="col-md-3"><select name="is_active" class="form-control"><option value="">كل الحالات</option><option value="1" @selected(request('is_active')==='1')>نشط</option><option value="0" @selected(request('is_active')==='0')>غير نشط</option></select></div>
<div class="col-md-3"><button class="btn btn-light-primary">بحث</button></div></form>
<div class="table-responsive"><table class="table"><thead><tr><th>الاسم</th><th>الحالة</th><th>ملاحظات</th><th>إجراءات</th></tr></thead>
<tbody>@forelse($drivers as $d)<tr><td>{{ $d->name }}</td><td>{{ $d->is_active?'نشط':'غير نشط' }}</td><td>{{ $d->notes ?? '—' }}</td>
<td>@can('drivers-read')<a href="{{ route('drivers.show',$d->id) }}" class="btn btn-sm btn-clean btn-icon"><i class="la la-eye"></i></a>@endcan
@can('drivers-update')<a href="{{ route('drivers.edit',$d->id) }}" class="btn btn-sm btn-clean btn-icon"><i class="la la-edit"></i></a>@endcan
@can('drivers-delete')<form action="{{ route('drivers.destroy',$d->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد الحذف؟')">@csrf @method('DELETE')<button class="btn btn-sm btn-clean btn-icon"><i class="la la-trash"></i></button></form>@endcan</td></tr>
@empty<tr><td colspan="4" class="text-center">لا توجد بيانات</td></tr>@endforelse</tbody></table></div>
{!! $drivers->withQueryString()->links('pagination::bootstrap-5') !!}
</div></div></div>
@endsection
