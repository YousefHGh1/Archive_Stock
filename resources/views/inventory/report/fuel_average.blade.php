@extends('layout.master')

@section('title', 'متوسط استهلاك المحروقات')
@section('page_title', 'التقارير')
@section('sub_main', 'المحروقات')
@section('sub_title', 'متوسط الاستهلاك')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">متوسط استهلاك المحروقات</h3></div>
                <div class="card-toolbar">
                    <a class="btn btn-light-success mr-2" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">تصدير Excel</a>
                    <a class="btn btn-light-danger" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">تصدير PDF</a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.fuel-average') }}" class="form-row mb-5">
                    <div class="form-group col-md-2"><label for="start_date">من تاريخ</label><input id="start_date" name="start_date" type="date" class="form-control" value="{{ $startDate }}"></div>
                    <div class="form-group col-md-2"><label for="end_date">إلى تاريخ</label><input id="end_date" name="end_date" type="date" class="form-control" value="{{ $endDate }}"></div>
                    <div class="form-group col-md-2">
                        <label for="typesfuel_id">نوع المحروقات</label>
                        <select id="typesfuel_id" name="typesfuel_id" class="form-control">
                            <option value="">الكل</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" @selected(request('typesfuel_id') == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="section_id">الدائرة</label>
                        <select id="section_id" name="section_id" class="form-control">
                            <option value="">الكل</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" @selected(request('section_id') == $section->id)>{{ $section->name_section }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
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
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary mr-2" type="submit">تصفية</button>
                        <a class="btn btn-light" href="{{ route('inventory.reports.fuel-average') }}">مسح</a>
                    </div>
                </form>
                <div class="alert alert-info">
                    المتوسط = إجمالي الصرف ÷ عدد الأشهر التقويمية في الفترة؛ ويشمل الأشهر التي لم يحدث فيها صرفًا.
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead><tr><th>نوع المحروقات</th><th>الشهر</th><th>الصرف (لتر)</th><th>متوسط الصرف الشهري للفترة (لتر)</th></tr></thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row->type_name }}</td>
                                    <td>{{ $row->period }}</td>
                                    <td>{{ number_format($row->quantity, 2) }}</td>
                                    <td>{{ number_format($row->period_average, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">لا توجد أنواع محروقات أو حركات مطابقة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
