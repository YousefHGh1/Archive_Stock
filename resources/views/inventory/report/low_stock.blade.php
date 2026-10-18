@extends('layout.master')

@section('title', 'الأصناف تحت الحد الأدنى')
@section('page_title', 'التقارير')
@section('sub_main', 'المخازن')
@section('sub_title', 'الأصناف تحت الحد الأدنى')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title">
                    <h3 class="card-label">الأصناف تحت الحد الأدنى</h3>
                </div>
                <div class="card-toolbar">
                    <a class="btn btn-light-success mr-2" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">تصدير Excel</a>
                    <a class="btn btn-light-danger" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">تصدير PDF</a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.low-stock') }}" class="form-inline mb-5">
                    <label for="category_id" class="mr-2">الفئة</label>
                    <select name="category_id" id="category_id" class="form-control mr-2">
                        <option value="">جميع الفئات</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary mr-2" type="submit">تصفية</button>
                    <a class="btn btn-light" href="{{ route('inventory.reports.low-stock') }}">مسح</a>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>رقم الصنف</th><th>اسم الصنف</th><th>الفئة</th><th>الوحدة</th>
                                <th>الرصيد الحالي</th><th>الحد الأدنى</th><th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>{{ $item->item_num }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ optional($item->category)->category_name ?? '—' }}</td>
                                    <td>{{ optional($item->unit)->unit_name ?? '—' }}</td>
                                    <td>{{ number_format((float) $item->balance, 2) }}</td>
                                    <td>{{ number_format((float) $item->low_limit, 2) }}</td>
                                    <td>
                                        <span class="label label-inline {{ $item->balance <= 0 ? 'label-light-danger' : 'label-light-warning' }}">
                                            {{ $item->balance <= 0 ? 'نافد' : 'منخفض' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">لا توجد أصناف تحت الحد الأدنى.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
