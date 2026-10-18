@extends('layout.master')

@section('title', 'تكلفة المحروقات')
@section('page_title', 'التقارير')
@section('sub_main', 'المحروقات')
@section('sub_title', 'تكلفة المحروقات')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">تكلفة المحروقات — متوسط سعر الشراء وقيمة الرصيد</h3></div>
                @include('inventory.report.partials.report_export_buttons')
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.fuel-cost') }}" class="form-row mb-4">
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
                        <label for="start_date">من تاريخ</label>
                        <input id="start_date" name="start_date" type="date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_date">إلى تاريخ</label>
                        <input id="end_date" name="end_date" type="date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="form-group col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary mr-2" type="submit">تصفية</button>
                        <a class="btn btn-light" href="{{ route('inventory.reports.fuel-cost') }}">مسح</a>
                    </div>
                </form>

                <div class="alert alert-info py-2">
                    تُحتسب القيم للتوريدات التي سُجّل لها سعر فقط، وقيمة الرصيد بتقدير متوسط سعر الشراء المرجح الكلي. التوريدات بلا سعر (بيانات تاريخية) موضحة بعددٍ في عمود مستقل ولا تدخل في المتوسطات.
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>نوع المحروقات</th>
                                <th>الوارد بالفترة (لتر)</th>
                                <th>الكمية المسعرة (لتر)</th>
                                <th>قيمة الوارد</th>
                                <th>متوسط سعر الفترة</th>
                                <th>متوسط سعر الشراء الكلي</th>
                                <th>الرصيد الحالي (لتر)</th>
                                <th>قيمة الرصيد (تقديرية)</th>
                                <th>توريدات بلا سعر</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row['type']->name }}</td>
                                    <td>{{ number_format($row['incoming_qty'], 2) }}</td>
                                    <td>{{ number_format($row['priced_qty'], 2) }}</td>
                                    <td>{{ number_format($row['incoming_value'], 2) }}</td>
                                    <td>{{ $row['period_avg'] === null ? '—' : number_format($row['period_avg'], 2) }}</td>
                                    <td>{{ $row['all_time_avg'] === null ? '—' : number_format($row['all_time_avg'], 2) }}</td>
                                    <td class="{{ $row['balance'] < 0 ? 'text-danger' : '' }}">{{ number_format($row['balance'], 2) }}</td>
                                    <td>{{ $row['balance_value'] === null ? '—' : number_format($row['balance_value'], 2) }}</td>
                                    <td>{{ $row['unpriced_rows'] }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center">لا توجد أنواع محروقات مطابقة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
