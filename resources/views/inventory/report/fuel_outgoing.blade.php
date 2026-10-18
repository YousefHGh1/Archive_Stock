@extends('layout.master')

@section('title', 'تقرير الصرف الشامل للمحروقات')
@section('page_title', 'التقارير')
@section('sub_main', 'المحروقات')
@section('sub_title', 'تقرير الصرف الشامل')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">تقرير الصرف الشامل للمحروقات</h3></div>
                <div class="card-toolbar">
                    <a class="btn btn-light-success mr-2" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">تصدير Excel</a>
                    <a class="btn btn-light-danger" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">تصدير PDF</a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.fuel-outgoing') }}" class="form-row mb-5">
                    <div class="form-group col-md-3"><label for="start_date">من تاريخ</label><input id="start_date" name="start_date" type="date" class="form-control" value="{{ request('start_date') }}"></div>
                    <div class="form-group col-md-3"><label for="end_date">إلى تاريخ</label><input id="end_date" name="end_date" type="date" class="form-control" value="{{ request('end_date') }}"></div>
                    <div class="form-group col-md-2"><label for="typesfuel_id">النوع</label><select id="typesfuel_id" name="typesfuel_id" class="form-control"><option value="">الكل</option>@foreach ($types as $type)<option value="{{ $type->id }}" @selected(request('typesfuel_id') == $type->id)>{{ $type->name }}</option>@endforeach</select></div>
                    <div class="form-group col-md-2"><label for="section_id">الدائرة</label><select id="section_id" name="section_id" class="form-control"><option value="">الكل</option>@foreach ($sections as $section)<option value="{{ $section->id }}" @selected(request('section_id') == $section->id)>{{ $section->name_section }}</option>@endforeach</select></div>
                    <div class="form-group col-md-2"><label for="sub_section_id">القسم الفرعي</label><select id="sub_section_id" name="sub_section_id" class="form-control"><option value="">الكل</option>@foreach ($subSections as $subSection)<option value="{{ $subSection->id }}" @selected(request('sub_section_id') == $subSection->id)>{{ $subSection->name }}</option>@endforeach</select></div>
                    <div class="col-12"><button class="btn btn-primary mr-2" type="submit">تصفية</button><a class="btn btn-light" href="{{ route('inventory.reports.fuel-outgoing') }}">مسح</a></div>
                </form>

                <h4>الإجمالي السنوي</h4>
                <div class="table-responsive mb-5">
                    <table class="table table-sm table-bordered"><thead><tr><th>السنة</th><th>إجمالي الصادر (لتر)</th></tr></thead><tbody>
                        @forelse ($annualTotals as $total)<tr><td>{{ $total->period }}</td><td>{{ number_format((float) $total->quantity, 2) }}</td></tr>
                        @empty<tr><td colspan="2" class="text-center">لا توجد حركات في النطاق المحدد.</td></tr>@endforelse
                    </tbody></table>
                </div>
                <h4>الإجمالي الشهري</h4>
                <div class="table-responsive mb-5">
                    <table class="table table-sm table-bordered"><thead><tr><th>الشهر</th><th>إجمالي الصادر (لتر)</th></tr></thead><tbody>
                        @forelse ($monthlyTotals as $total)<tr><td>{{ $total->period }}</td><td>{{ number_format((float) $total->quantity, 2) }}</td></tr>
                        @empty<tr><td colspan="2" class="text-center">لا توجد حركات في النطاق المحدد.</td></tr>@endforelse
                    </tbody></table>
                </div>
                <h4>تفاصيل سندات الصرف ({{ number_format($entryCount) }} سند)</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>نوع المحروقات</th><th>الدائرة</th><th>القسم الفرعي</th><th>رقم السند</th><th>التاريخ</th><th>الكمية (لتر)</th><th>الجهة المستلمة</th><th>رقم الدفتر</th></tr></thead>
                        <tbody>
                            @forelse ($entries as $entry)
                                <tr>
                                    <td>{{ optional($entry->typesfuel)->name ?? '—' }}</td>
                                    <td>{{ optional($entry->section)->name_section ?? '—' }}</td>
                                    <td>{{ optional($entry->subSection)->name ?? '—' }}</td>
                                    <td>{{ $entry->voucher }}</td><td>{{ $entry->date }}</td>
                                    <td>{{ number_format((float) $entry->quantity, 2) }}</td>
                                    <td>{{ $entry->num_section }}</td><td>{{ $entry->num_note }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">لا توجد سندات مطابقة.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot><tr><th colspan="5">إجمالي الصرف في النتائج</th><th>{{ number_format((float) $totalOutgoing, 2) }} لتر</th><th colspan="2"></th></tr></tfoot>
                    </table>
                </div>
                {{ $entries->onEachSide(1)->links('partials.pagination') }}
            </div>
        </div>
    </div>
@endsection
