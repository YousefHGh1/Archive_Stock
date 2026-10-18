@extends('layout.master')

@section('title', 'الأصناف الأكثر صرفًا')
@section('page_title', 'التقارير')
@section('sub_main', 'المخزون')
@section('sub_title', 'الأصناف الأكثر صرفًا')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">الأصناف الأكثر صرفًا</h3></div>
                <div class="card-toolbar">
                    <a class="btn btn-light-success mr-2" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">تصدير Excel</a>
                    <a class="btn btn-light-danger" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">تصدير PDF</a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.top-issued-items') }}" class="form-row mb-5">
                    <div class="form-group col-md-2"><label for="start_date">من تاريخ</label><input id="start_date" name="start_date" type="date" class="form-control" value="{{ request('start_date') }}"></div>
                    <div class="form-group col-md-2"><label for="end_date">إلى تاريخ</label><input id="end_date" name="end_date" type="date" class="form-control" value="{{ request('end_date') }}"></div>
                    <div class="form-group col-md-3">
                        <label for="section_id">الدائرة</label>
                        <select id="section_id" name="section_id" class="form-control">
                            <option value="">الكل</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" @selected(request('section_id') == $section->id)>{{ $section->name_section }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="sub_section_id">القسم الفرعي</label>
                        <select id="sub_section_id" name="sub_section_id" class="form-control">
                            <option value="">الكل</option>
                            @foreach ($subSections as $subSection)
                                <option value="{{ $subSection->id }}" @selected(request('sub_section_id') == $subSection->id)>
                                    {{ optional($subSection->section)->name_section }} — {{ $subSection->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="limit">عدد النتائج</label>
                        <select id="limit" name="limit" class="form-control">
                            @foreach ([10, 20, 50, 100] as $count)
                                <option value="{{ $count }}" @selected($limit === $count)>{{ $count }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary mr-2" type="submit">تصفية</button>
                        <a class="btn btn-light" href="{{ route('inventory.reports.top-issued-items') }}">مسح</a>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead><tr><th>الترتيب</th><th>رقم الصنف</th><th>الصنف</th><th>الوحدة</th><th>إجمالي الكمية المصروفة</th></tr></thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->item_num }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->unit_name ?? '—' }}</td>
                                    <td>{{ number_format((float) $item->total_quantity, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">لا توجد حركات صرف مطابقة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
