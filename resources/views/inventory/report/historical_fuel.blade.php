@extends('layout.master')

@section('title', 'الرصيد التاريخي للمحروقات')
@section('page_title', 'التقارير')
@section('sub_main', 'المحروقات')
@section('sub_title', 'الرصيد التاريخي')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">الرصيد التاريخي للمحروقات</h3></div>
                <div class="card-toolbar">
                    <a class="btn btn-light-success mr-2" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">تصدير Excel</a>
                    <a class="btn btn-light-danger" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">تصدير PDF</a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.historical-fuel') }}" class="form-inline mb-5">
                    <label for="as_of" class="mr-2">كما في</label>
                    <input type="date" id="as_of" name="as_of" class="form-control mr-2" value="{{ $asOf }}" required>
                    <label for="typesfuel_id" class="mr-2">نوع المحروقات</label>
                    <select id="typesfuel_id" name="typesfuel_id" class="form-control mr-2">
                        <option value="">جميع الأنواع</option>
                        @foreach ($allTypes as $type)
                            <option value="{{ $type->id }}" @selected($selectedType == $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary mr-2" type="submit">عرض</button>
                    <a class="btn btn-light" href="{{ route('inventory.reports.historical-fuel') }}">مسح</a>
                </form>
                <p class="text-muted">لا يحتوي مخطط أنواع المحروقات الحالي على حقل رصيد افتتاحي؛ لذلك يبدأ هذا التقرير من صفر ويجمع الحركات المسجلة حتى التاريخ المحدد.</p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>نوع المحروقات</th><th>الوارد حتى التاريخ (لتر)</th><th>الصادر حتى التاريخ (لتر)</th><th>الرصيد كما في {{ $asOf }} (لتر)</th></tr></thead>
                        <tbody>
                            @forelse ($types as $type)
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ number_format((float) $type->total_incoming, 2) }}</td>
                                    <td>{{ number_format((float) $type->total_outgoing, 2) }}</td>
                                    <td>{{ number_format((float) $type->historical_balance, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">لا توجد أنواع محروقات مطابقة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($selectedType)
                    <h4 class="mt-8">تفاصيل الحركات حتى {{ $asOf }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead><tr><th>التاريخ</th><th>الحركة</th><th>رقم السند</th><th>المورد/القسم المستلم</th><th>الكمية (لتر)</th><th>الرصيد التراكمي (لتر)</th></tr></thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->movement_date }}</td><td>{{ $transaction->movement_type }}</td>
                                        <td>{{ $transaction->voucher }}</td><td>{{ $transaction->party }}</td>
                                        <td>{{ number_format((float) $transaction->quantity, 2) }}</td>
                                        <td>{{ number_format((float) $transaction->running_balance, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">لا توجد حركات حتى التاريخ المحدد.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted">عند تساوي التاريخ، يُرتب الوارد قبل الصادر ترتيبًا ثابتًا لعرض الرصيد التراكمي.</p>
                @else
                    <p class="text-muted">اختر نوعًا لعرض تفاصيل حركاته والرصيد التراكمي.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
