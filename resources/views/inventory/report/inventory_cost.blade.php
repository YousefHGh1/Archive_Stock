@extends('layout.master')

@section('title', 'تكلفة المخزون')
@section('page_title', 'التقارير')
@section('sub_main', 'المخازن')
@section('sub_title', 'تكلفة المخزون')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">تكلفة المخزون — متوسط الكلفة المرجح</h3></div>
                @include('inventory.report.partials.report_export_buttons')
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.inventory-cost') }}" class="form-row mb-4">
                    <div class="form-group col-md-3">
                        <label for="category_id">الفئة</label>
                        <select id="category_id" name="category_id" class="form-control">
                            <option value="">جميع الفئات</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="item_id">الصنف</label>
                        <select id="item_id" name="item_id" class="form-control">
                            <option value="">جميع الأصناف</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" @selected(request('item_id') == $item->id)>{{ $item->item_num }} — {{ $item->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-5 d-flex align-items-end">
                        <button class="btn btn-primary mr-2" type="submit">تصفية</button>
                        <a class="btn btn-light" href="{{ route('inventory.reports.inventory-cost') }}">مسح</a>
                    </div>
                </form>

                <div class="alert alert-info py-2">
                    إجمالي قيمة المخزون المقيَّم: <b>{{ number_format((float) $totalValue, 2) }}</b>
                    @if ($missingCostCount > 0)
                        <span class="mr-2">— {{ $missingCostCount }} صنفًا بلا مشتريات مسجلة، تُعرض كلفته بـ «—» ولا تدخل في الإجمالي.</span>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>رقم الصنف</th>
                                <th>اسم الصنف</th>
                                <th>الفئة</th>
                                <th>الرصيد الحالي</th>
                                <th>الوحدة</th>
                                <th>متوسط كلفة الوحدة</th>
                                <th>قيمة الرصيد</th>
                                <th>عدد فواتير الشراء</th>
                                <th>آخر شراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                @php($item = $row['item'])
                                <tr>
                                    <td>{{ $item->item_num }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ optional($item->category)->category_name ?? '—' }}</td>
                                    <td>{{ number_format((float) $item->balance, 2) }}</td>
                                    <td>{{ optional($item->unit)->unit_name ?? '—' }}</td>
                                    <td>{{ $row['avg_cost'] === null ? '—' : number_format($row['avg_cost'], 2) }}</td>
                                    <td>{{ $row['total_value'] === null ? '—' : number_format($row['total_value'], 2) }}</td>
                                    <td>{{ $row['purchases_count'] }}</td>
                                    <td>{{ $row['last_purchase_date'] ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center">لا توجد أصناف مطابقة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
