@extends('layout.master')

@section('title', 'مقارنة أسعار الموردين')
@section('page_title', 'التقارير')
@section('sub_main', 'المخازن')
@section('sub_title', 'مقارنة أسعار الموردين')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">مقارنة أسعار المشتريات بين الموردين</h3></div>
                @include('inventory.report.partials.report_export_buttons')
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.supplier-comparison') }}" class="form-row mb-4">
                    <div class="form-group col-md-3">
                        <label for="item_id">الصنف</label>
                        <select id="item_id" name="item_id" class="form-control">
                            <option value="">جميع الأصناف</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" @selected(request('item_id') == $item->id)>{{ $item->item_num }} — {{ $item->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="supplier_id">المورد</label>
                        <select id="supplier_id" name="supplier_id" class="form-control">
                            <option value="">جميع الموردين</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(request('supplier_id') == $supplier->id)>{{ $supplier->supplier_item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="start_date">من تاريخ</label>
                        <input id="start_date" name="start_date" type="date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="end_date">إلى تاريخ</label>
                        <input id="end_date" name="end_date" type="date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary mr-2" type="submit">تصفية</button>
                        <a class="btn btn-light" href="{{ route('inventory.reports.supplier-comparison') }}">مسح</a>
                    </div>
                </form>

                <div class="alert alert-info py-2">
                    تُعرض جميع الموردين حتى إن لم تشملهم المشتريات المطابقة للفلاتر (بقيم صفرية)، وتُحسب القيم بعد تطبيق معامل العملة المحفوظ وقت الفاتورة.
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>رقم الصنف</th>
                                <th>الصنف</th>
                                <th>المورد</th>
                                <th>إجمالي الكمية</th>
                                <th>الوحدة</th>
                                <th>متوسط سعر الوحدة</th>
                                <th>إجمالي القيمة</th>
                                <th>عدد الفواتير</th>
                                <th>أول شراء</th>
                                <th>آخر شراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row->item_num ?? '—' }}</td>
                                    <td>{{ $row->item_name ?? '—' }}</td>
                                    <td>{{ $row->supplier_item_name }}</td>
                                    <td>{{ number_format((float) $row->total_quantity, 2) }}</td>
                                    <td>{{ $row->unit_name ?? '—' }}</td>
                                    <td>{{ $row->total_quantity > 0 ? number_format((float) $row->total_value / (float) $row->total_quantity, 2) : '—' }}</td>
                                    <td>{{ number_format((float) $row->total_value, 2) }}</td>
                                    <td>{{ (int) $row->invoices_count }}</td>
                                    <td>{{ $row->first_purchase ?? '—' }}</td>
                                    <td>{{ $row->last_purchase ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="10" class="text-center">لا توجد نتائج مطابقة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
