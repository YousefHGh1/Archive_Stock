@extends('layout.master')

@section('title', 'صرف المحروقات حسب الدائرة (تكلفة)')
@section('page_title', 'التقارير')
@section('sub_main', 'المحروقات')
@section('sub_title', 'توزيع صرف المحروقات على الأقسام')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">توزيع صرف المحروقات على الأقسام (قيمة تقديرية)</h3></div>
                @include('inventory.report.partials.report_export_buttons')
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.fuel-section-cost') }}" class="form-row mb-4">
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
                        <label for="section_id">الدائرة</label>
                        <select id="section_id" name="section_id" class="form-control">
                            <option value="">جميع الدوائر</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" @selected(request('section_id') == $section->id)>{{ $section->name_section }}</option>
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
                        <a class="btn btn-light" href="{{ route('inventory.reports.fuel-section-cost') }}">مسح</a>
                    </div>
                </form>

                <div class="alert alert-warning py-2">
                    القيم تقديرية: تُضرب الكمية المصروفة في متوسط سعر الشراء المرجح الحالي للنوع؛ لا يتوفر سعر الصرف وقت كل سند، لذا لا يمكن احتساب كلفة دقيقة لكل سند.
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>الدائرة</th>
                                <th>نوع المحروقات</th>
                                <th>الكمية المصروفة (لتر)</th>
                                <th>متوسط سعر الشراء</th>
                                <th>القيمة التقديرية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $entry)
                                <tr>
                                    <td>{{ $entry['row']->name_section }}</td>
                                    <td>{{ $entry['row']->fuel_name }}</td>
                                    <td>{{ number_format((float) $entry['row']->total_qty, 2) }}</td>
                                    <td>{{ $entry['avg_price'] === null ? '—' : number_format($entry['avg_price'], 2) }}</td>
                                    <td>{{ $entry['estimated_value'] === null ? '—' : number_format($entry['estimated_value'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">لا توجد أحكام صرف مطابقة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
