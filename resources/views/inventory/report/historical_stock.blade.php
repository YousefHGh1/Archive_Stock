@extends('layout.master')

@section('title', 'الرصيد التاريخي للمخازن')
@section('page_title', 'التقارير')
@section('sub_main', 'المخازن')
@section('sub_title', 'الرصيد التاريخي')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">الرصيد التاريخي للمخازن</h3></div>
                <div class="card-toolbar">
                    <a class="btn btn-light-success mr-2" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">تصدير Excel</a>
                    <a class="btn btn-light-danger" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">تصدير PDF</a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.historical-stock') }}" class="form-inline mb-5">
                    <label for="as_of" class="mr-2">كما في</label>
                    <input type="date" id="as_of" name="as_of" class="form-control mr-2" value="{{ $asOf }}" required>
                    <label for="item_id" class="mr-2">الصنف</label>
                    <select id="item_id" name="item_id" class="form-control mr-2">
                        <option value="">جميع الأصناف</option>
                        @foreach ($itemOptions as $option)
                            <option value="{{ $option->id }}" @selected(request('item_id') == $option->id)>{{ $option->item_num }} — {{ $option->item_name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary mr-2" type="submit">عرض</button>
                    <a class="btn btn-light" href="{{ route('inventory.reports.historical-stock') }}">مسح</a>
                </form>
                <p class="text-muted">يُحسب الرصيد من الرصيد الافتتاحي مضافًا إليه الوارد الفعّال ومطروحًا منه الصادر الفعّال حتى التاريخ المحدد.</p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>رقم الصنف</th><th>الصنف</th><th>الفئة</th><th>الوحدة</th><th>الافتتاحي</th><th>الوارد</th><th>الصادر</th><th>الرصيد كما في {{ $asOf }}</th></tr></thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>{{ $item->item_num }}</td><td>{{ $item->item_name }}</td>
                                    <td>{{ $item->category_name ?? '—' }}</td><td>{{ $item->unit_name ?? '—' }}</td>
                                    <td>{{ number_format((float) $item->open_balance, 2) }}</td>
                                    <td>{{ number_format((float) $item->total_incoming, 2) }}</td>
                                    <td>{{ number_format((float) $item->total_outgoing, 2) }}</td>
                                    <td>{{ number_format((float) $item->historical_balance, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">لا توجد أصناف مطابقة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($selectedItem)
                    <h4 class="mt-8">تفاصيل حركة {{ $selectedItem->item_name }} حتى {{ $asOf }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead><tr><th>التاريخ</th><th>نوع الحركة</th><th>رقم السند</th><th>رقم الفاتورة</th><th>المورد/القسم</th><th>الوارد</th><th>الصادر</th><th>الرصيد التراكمي</th></tr></thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->movement_date }}</td><td>{{ $transaction->movement_type }}</td>
                                        <td>{{ $transaction->voucher_no }}</td><td>{{ $transaction->invoice_no }}</td>
                                        <td>{{ $transaction->party }}</td>
                                        <td>{{ $transaction->movement_type === 'وارد' ? number_format((float) $transaction->quantity, 2) : '—' }}</td>
                                        <td>{{ $transaction->movement_type === 'صادر' ? number_format((float) $transaction->quantity, 2) : '—' }}</td>
                                        <td>{{ number_format((float) $transaction->running_balance, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center">لا توجد حركات حتى التاريخ المحدد.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted">عند تساوي التاريخ، يُرتب الوارد قبل الصادر ترتيبًا ثابتًا لعرض الرصيد التراكمي.</p>
                @else
                    <p class="text-muted">اختر صنفًا لعرض تفاصيل حركاته والرصيد التراكمي.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
