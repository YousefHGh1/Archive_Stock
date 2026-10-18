@extends('layout.master')

@section('title', 'مقارنة أسعار المحروقات')
@section('page_title', 'التقارير')
@section('sub_main', 'المحروقات')
@section('sub_title', 'مقارنة أسعار المحروقات بين الموردين')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">مقارنة أسعار المحروقات بين الموردين</h3></div>
                @include('inventory.report.partials.report_export_buttons')
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.fuel-price-comparison') }}" class="form-row mb-4">
                    <div class="form-group col-md-3">
                        <label for="typesfuel_id">النوع</label>
                        <select id="typesfuel_id" name="typesfuel_id" class="form-control">
                            <option value="">جميع الأنواع</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" @selected(request('typesfuel_id') == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="supplier_id">المورد</label>
                        <select id="supplier_id" name="supplier_id" class="form-control">
                            <option value="">جميع الموردين</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(request('supplier_id') == $supplier->id)>{{ $supplier->name_supplier }}</option>
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
                        <a class="btn btn-light" href="{{ route('inventory.reports.fuel-price-comparison') }}">مسح</a>
                    </div>
                </form>

                <div class="alert alert-info py-2">
                    تُعرض جميع الموردين حتى بلا توريدات مطابقة للفلاتر (قيم صفرية)، ويُحسب متوسط السعر من الكميات المسعرة فقط.
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>نوع المحروقات</th>
                                <th>المورد</th>
                                <th>إجمالي الكمية (لتر)</th>
                                <th>الكمية المسعرة (لتر)</th>
                                <th>متوسط سعر الوحدة</th>
                                <th>إجمالي القيمة</th>
                                <th>توريدات بلا سعر</th>
                                <th>أول توريد</th>
                                <th>آخر توريد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row->fuel_name ?? '—' }}</td>
                                    <td>{{ $row->name_supplier }}</td>
                                    <td>{{ number_format((float) $row->total_qty, 2) }}</td>
                                    <td>{{ number_format((float) $row->priced_qty, 2) }}</td>
                                    <td>{{ $row->priced_qty > 0 ? number_format((float) $row->total_value / (float) $row->priced_qty, 2) : '—' }}</td>
                                    <td>{{ number_format((float) $row->total_value, 2) }}</td>
                                    <td>{{ (int) $row->unpriced_rows }}</td>
                                    <td>{{ $row->first_supply ?? '—' }}</td>
                                    <td>{{ $row->last_supply ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center">لا توجد نتائج مطابقة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
