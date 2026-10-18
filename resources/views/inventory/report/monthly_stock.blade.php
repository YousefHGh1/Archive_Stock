@extends('layout.master')

@section('title', 'الحركة الشهرية للمخزون')
@section('page_title', 'التقارير')
@section('sub_main', 'المخزون')
@section('sub_title', 'الحركة الشهرية')

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header flex-wrap">
                <div class="card-title"><h3 class="card-label">الحركة الشهرية للمخزون</h3></div>
                @if ($item)
                    <div class="card-toolbar">
                        <a class="btn btn-light-success mr-2" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">تصدير Excel</a>
                        <a class="btn btn-light-danger" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">تصدير PDF</a>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.reports.monthly-stock') }}" class="form-row mb-5">
                    <div class="form-group col-md-4">
                        <label for="item_id">الصنف</label>
                        <select id="item_id" name="item_id" class="form-control">
                            <option value="">اختر صنفًا</option>
                            @foreach ($items as $option)
                                <option value="{{ $option->id }}" @selected(request('item_id') == $option->id)>
                                    {{ $option->item_num }} - {{ $option->item_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="start_month">من شهر</label>
                        <input id="start_month" name="start_month" type="month" class="form-control" value="{{ $startMonth }}" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_month">إلى شهر</label>
                        <input id="end_month" name="end_month" type="month" class="form-control" value="{{ $endMonth }}" required>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary mr-2" type="submit">عرض</button>
                        <a class="btn btn-light" href="{{ route('inventory.reports.monthly-stock') }}">مسح</a>
                    </div>
                </form>

                @if ($item)
                    <p class="text-muted">
                        {{ $item->item_num }} - {{ $item->item_name }}
                        @if ($item->unit)
                            ({{ $item->unit->unit_name }})
                        @endif
                    </p>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>الشهر</th>
                                    <th>الرصيد الافتتاحي</th>
                                    <th>الوارد</th>
                                    <th>الصادر</th>
                                    <th>الرصيد الختامي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $row)
                                    <tr>
                                        <td>{{ $row->period }}</td>
                                        <td>{{ number_format($row->opening_balance, 2) }}</td>
                                        <td>{{ number_format($row->incoming, 2) }}</td>
                                        <td>{{ number_format($row->outgoing, 2) }}</td>
                                        <td>{{ number_format($row->closing_balance, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">لا توجد بيانات.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">اختر صنفًا لعرض حركته الشهرية. تُعرض الأشهر بلا حركة بأصفار مع استمرار الرصيد التراكمي.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
