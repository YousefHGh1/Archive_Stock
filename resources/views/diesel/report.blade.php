@extends('layout.master')

@section('title', 'تقارير الوارد')

@section('page_title', 'لوحة التحكم')

@section('sub_main', 'التقارير')

@section('sub_title', 'وارد المحروقات')

@section('css')
    <style>
        .fr-page { --fr-accent: #1BC5BD; --fr-accent-2: #0BB7AF; }
        .fr-page .fr-hero { box-shadow: 0 10px 30px -12px rgba(27, 197, 189, .6); }
        .fr-page .fr-qty { background: rgba(27, 197, 189, .12); }
        .fr-page .fr-month.is-current { box-shadow: 0 0 0 3px rgba(27, 197, 189, .15); }
    </style>
@endsection

@section('content')
    @php
        $fmt = function ($n) {
            $s = number_format((float) $n, 2);
            return rtrim(rtrim($s, '0'), '.');
        };

        $filterLabels = [
            'start_date' => 'من تاريخ',
            'end_date' => 'إلى تاريخ',
            'start_voucher' => 'من سند',
            'end_voucher' => 'إلى سند',
            'invoice_num' => 'رقم الفاتورة',
            'typesfuel_id' => 'نوع المحروقات',
            'supplier_id' => 'المورد',
        ];
        $activeFilters = collect($filterLabels)->filter(fn($label, $key) => request()->filled($key));
        $filterValue = function ($key) use ($typesfuel, $suppliers) {
            $value = request($key);
            if ($key === 'typesfuel_id') {
                return optional($typesfuel->firstWhere('id', $value))->name ?? $value;
            }
            if ($key === 'supplier_id') {
                return optional($suppliers->firstWhere('id', $value))->name_supplier ?? $value;
            }
            return $value;
        };
    @endphp

    <div class="container fr-page">

        {{-- ===== Header ===== --}}
        <div class="fr-hero">
            <div class="d-flex align-items-center">
                <span class="fr-hero-icon"><i class="la la-truck-loading"></i></span>
                <div>
                    <h2>تقارير وارد المحروقات</h2>
                    <p>متابعة كميات التوريد للسنة الحالية {{ date('Y') }} مع إمكانية البحث والتصدير</p>
                </div>
            </div>
            <div class="fr-hero-actions fr-no-print">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'excel', 'page' => null]) }}" class="btn">
                    <i class="la la-file-excel"></i> تصدير Excel
                </a>
                <button type="button" class="btn" onclick="window.print()">
                    <i class="la la-print"></i> طباعة
                </button>
                <a href="{{ route('diesel.index') }}" class="btn">
                    <i class="la la-arrow-right"></i> رجوع للرئيسية
                </a>
            </div>
        </div>

        {{-- ===== KPIs ===== --}}
        <div class="row">
            <div class="mb-6 col-6 col-xl-3">
                <div class="fr-stat">
                    <span class="fr-stat-icon bg-light-primary"><i class="la la-list-ol text-primary"></i></span>
                    <div>
                        <div class="fr-stat-label">عدد سندات الوارد</div>
                        <div class="fr-stat-value">{{ number_format($totalEntries) }}</div>
                    </div>
                </div>
            </div>
            <div class="mb-6 col-6 col-xl-3">
                <div class="fr-stat">
                    <span class="fr-stat-icon bg-light-success"><i class="la la-gas-pump text-success"></i></span>
                    <div>
                        <div class="fr-stat-label">مجموع الوارد</div>
                        <div class="fr-stat-value">{{ $fmt($totalReceived) }} <small>لتر</small></div>
                    </div>
                </div>
            </div>
            <div class="mb-6 col-6 col-xl-3">
                <div class="fr-stat">
                    <span class="fr-stat-icon bg-light-warning"><i class="la la-calendar text-warning"></i></span>
                    <div>
                        <div class="fr-stat-label">الرصيد السابق</div>
                        <div class="fr-stat-value">{{ $fmt($previousBalance) }} <small>لتر</small></div>
                    </div>
                </div>
            </div>
            <div class="mb-6 col-6 col-xl-3">
                <div class="fr-stat">
                    <span class="fr-stat-icon bg-light-info"><i class="la la-balance-scale text-info"></i></span>
                    <div>
                        <div class="fr-stat-label">إجمالي المتبقي</div>
                        <div class="fr-stat-value {{ $totalRemaining < 0 ? 'text-danger' : '' }}">{{ $fmt($totalRemaining) }} <small>لتر</small></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Filters ===== --}}
        <div class="fr-card fr-filters-card">
            <div class="fr-card-header fr-filter-toggle {{ $activeFilters->isEmpty() ? 'collapsed' : '' }}"
                data-toggle="collapse" data-target="#frFilters" aria-expanded="{{ $activeFilters->isEmpty() ? 'false' : 'true' }}">
                <h3 class="fr-card-title">
                    <i class="la la-filter"></i> بحث متقدم
                    @if ($activeFilters->isNotEmpty())
                        <span class="label label-inline label-light-success font-weight-bold">{{ $activeFilters->count() }} فلتر مفعّل</span>
                    @endif
                </h3>
                <i class="la la-angle-down fr-chevron text-muted"></i>
            </div>

            <div id="frFilters" class="collapse {{ $activeFilters->isNotEmpty() ? 'show' : '' }}">
                <div class="fr-card-body fr-filters">
                    <form action="{{ route('diesel.report') }}" method="GET">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">
                        <div class="form-row">
                            <div class="form-group col-12 col-md-6 col-xl-4">
                                <label>التاريخ</label>
                                <div class="fr-range">
                                    <input name="start_date" type="date" class="form-control" value="{{ request('start_date') }}" />
                                    <span><i class="la la-ellipsis-h"></i></span>
                                    <input name="end_date" type="date" class="form-control" value="{{ request('end_date') }}" />
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 col-xl-4">
                                <label>سند التوريد</label>
                                <div class="fr-range">
                                    <input name="start_voucher" type="text" class="form-control" placeholder="من" value="{{ request('start_voucher') }}" />
                                    <span><i class="la la-ellipsis-h"></i></span>
                                    <input name="end_voucher" type="text" class="form-control" placeholder="إلى" value="{{ request('end_voucher') }}" />
                                </div>
                            </div>
                            <div class="form-group col-12 col-md-6 col-xl-4">
                                <label>رقم الفاتورة</label>
                                <input name="invoice_num" type="text" class="form-control" placeholder="ابحث برقم الفاتورة" value="{{ request('invoice_num') }}" />
                            </div>
                            <div class="form-group col-12 col-md-6 col-xl-4">
                                <label>نوع المحروقات</label>
                                <select name="typesfuel_id" class="form-control">
                                    <option value="">جميع الأنواع</option>
                                    @foreach ($typesfuel as $type)
                                        <option value="{{ $type->id }}" {{ request('typesfuel_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 col-xl-4">
                                <label>المورد</label>
                                <select name="supplier_id" class="form-control selectpicker" data-live-search="true" data-size="7">
                                    <option value="">جميع الموردين</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name_supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="fr-filter-actions">
                            <a href="{{ route('diesel.report') }}" class="btn btn-light">
                                <i class="la la-undo"></i> إعادة تعيين
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="la la-search"></i> بحث
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===== Table ===== --}}
        <div class="fr-card">
            <div class="fr-card-header">
                <h3 class="fr-card-title"><i class="la la-gas-pump"></i> سجل الوارد</h3>
                <form method="GET" action="{{ route('diesel.report') }}" class="fr-perpage fr-no-print">
                    @foreach (request()->except(['per_page', 'page', 'export']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <span>عدد السجلات بالصفحة</span>
                    <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="fr-card-body">
                @if ($activeFilters->isNotEmpty())
                    <div class="mb-3">
                        @foreach ($activeFilters as $key => $label)
                            <span class="fr-chip">{{ $label }}: <b>{{ $filterValue($key) }}</b></span>
                        @endforeach
                    </div>
                @endif

                <div class="fr-table-wrap">
                    <table class="table fr-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نوع المحروقات</th>
                                <th>اسم المورد</th>
                                <th>كمية التوريد</th>
                                <th>جهة التوريد</th>
                                <th>سند التوريد</th>
                                <th>رقم الفاتورة</th>
                                <th>تاريخ التوريد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dieselEntries as $entry)
                                <tr>
                                    <td data-label="#" class="text-muted">{{ $dieselEntries->firstItem() + $loop->index }}</td>
                                    <td data-label="نوع المحروقات"><span class="fr-fuel">{{ $entry->typesfuel->name ?? '-' }}</span></td>
                                    <td data-label="اسم المورد">{{ $entry->supplier->name_supplier ?? '-' }}</td>
                                    <td data-label="كمية التوريد"><span class="fr-qty">{{ $fmt($entry->quantity) }} لتر</span></td>
                                    <td data-label="جهة التوريد">{{ $entry->type ?: '-' }}</td>
                                    <td data-label="سند التوريد">{{ $entry->voucher ?: '-' }}</td>
                                    <td data-label="رقم الفاتورة">{{ $entry->invoice_num ?: '-' }}</td>
                                    <td data-label="تاريخ التوريد" class="fr-date">{{ $entry->date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="fr-empty">
                                        <i class="la la-inbox"></i>
                                        لا توجد سجلات وارد مطابقة لمعايير البحث
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($dieselEntries->count())
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="fr-foot-spacer">مجموع الصفحة الحالية</td>
                                    <td data-label="مجموع الصفحة الحالية"><span class="fr-qty">{{ $fmt($dieselEntries->sum('quantity')) }} لتر</span></td>
                                    <td colspan="4" class="fr-foot-spacer"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="fr-foot-spacer">مجموع المحروقات الوارد (كل النتائج)</td>
                                    <td data-label="مجموع الوارد (كل النتائج)"><span class="fr-qty">{{ $fmt($totalReceived) }} لتر</span></td>
                                    <td colspan="4" class="fr-foot-spacer"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                {{ $dieselEntries->onEachSide(1)->links('partials.pagination') }}
            </div>
        </div>

        {{-- ===== Monthly summary ===== --}}
        @include('diesel.partials.report-summary', ['movementLabel' => 'الوارد'])
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            if ($.fn.selectpicker) {
                $('.fr-filters .selectpicker').selectpicker();
            }
        });
    </script>
@endsection
