@extends('layout.master')

@section('title', 'تقارير الصادر')

@section('page_title', 'لوحة التحكم')

@section('sub_main', 'التقارير')

@section('sub_title', 'صادر المحروقات')

@section('css')
    <style>
        .fr-page { --fr-accent: #F64E60; --fr-accent-2: #FF6F80; }
        .fr-page .fr-hero { box-shadow: 0 10px 30px -12px rgba(246, 78, 96, .55); }
        .fr-page .fr-qty { background: rgba(246, 78, 96, .1); }
        .fr-page .fr-month.is-current { box-shadow: 0 0 0 3px rgba(246, 78, 96, .12); }
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
            'section_id' => 'الدائرة',
            'sub_section_id' => 'القسم الفرعي',
            'start_voucher' => 'من إيصال',
            'end_voucher' => 'إلى إيصال',
            'num_section' => 'الجهة المستلمة',
            'num_note' => 'رقم الدفتر',
            'typesfuel_id' => 'نوع المحروقات',
        ];
        $activeFilters = collect($filterLabels)->filter(fn($label, $key) => request()->filled($key));
        $filterValue = function ($key) use ($typesfuel, $section, $subSection) {
            $value = request($key);
            if ($key === 'typesfuel_id') {
                return optional($typesfuel->firstWhere('id', $value))->name ?? $value;
            }
            if ($key === 'section_id') {
                return optional($section->firstWhere('id', $value))->name_section ?? $value;
            }
            if ($key === 'sub_section_id') {
                return optional($subSection->firstWhere('id', $value))->name ?? $value;
            }
            return $value;
        };
    @endphp

    <div class="container fr-page">

        {{-- ===== Header ===== --}}
        <div class="fr-hero">
            <div class="d-flex align-items-center">
                <span class="fr-hero-icon"><i class="la la-sign-out-alt"></i></span>
                <div>
                    <h2>تقارير صادر المحروقات</h2>
                    <p>متابعة كميات الصرف للدوائر والأقسام للسنة الحالية {{ date('Y') }}</p>
                </div>
            </div>
            <div class="fr-hero-actions fr-no-print">
                <a href="{{ request()->fullUrlWithQuery(['export' => 'excel', 'page' => null]) }}" class="btn">
                    <i class="la la-file-excel"></i> تصدير Excel
                </a>
                <button type="button" class="btn" onclick="window.print()">
                    <i class="la la-print"></i> طباعة
                </button>
                <a href="{{ route('dieselexport.totalreport') }}" class="btn">
                    <i class="la la-layer-group"></i> تقارير المجاميع
                </a>
                @can('report-read')
                    <a href="{{ route('inventory.reports.fuel-outgoing') }}" class="btn">
                        <i class="la la-chart-bar"></i> تقرير الصرف الشامل
                    </a>
                    <a href="{{ route('inventory.reports.historical-fuel') }}" class="btn">
                        <i class="la la-history"></i> الرصيد التاريخي
                    </a>
                @endcan
                <a href="{{ route('dieselexport.index') }}" class="btn">
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
                        <div class="fr-stat-label">عدد إيصالات الصادر</div>
                        <div class="fr-stat-value">{{ number_format($totalEntries) }}</div>
                    </div>
                </div>
            </div>
            <div class="mb-6 col-6 col-xl-3">
                <div class="fr-stat">
                    <span class="fr-stat-icon bg-light-danger"><i class="la la-gas-pump text-danger"></i></span>
                    <div>
                        <div class="fr-stat-label">مجموع الصادر</div>
                        <div class="fr-stat-value">{{ $fmt($totalExported) }} <small>لتر</small></div>
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
                        <span class="label label-inline label-light-danger font-weight-bold">{{ $activeFilters->count() }} فلتر مفعّل</span>
                    @endif
                </h3>
                <i class="la la-angle-down fr-chevron text-muted"></i>
            </div>

            <div id="frFilters" class="collapse {{ $activeFilters->isNotEmpty() ? 'show' : '' }}">
                <div class="fr-card-body fr-filters">
                    <form action="{{ route('dieselexport.report') }}" method="GET">
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
                                <label>الدائرة</label>
                                <select name="section_id" class="form-control selectpicker" data-size="7" data-live-search="true">
                                    <option value="">جميع الدوائر</option>
                                    @foreach ($section as $sections)
                                        <option value="{{ $sections->id }}" {{ request('section_id') == $sections->id ? 'selected' : '' }}>{{ $sections->name_section }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 col-xl-4">
                                <label>القسم الفرعي</label>
                                <select name="sub_section_id" class="form-control">
                                    <option value="">جميع الأقسام</option>
                                    @foreach ($subSection as $subSections)
                                        <option value="{{ $subSections->id }}" {{ request('sub_section_id') == $subSections->id ? 'selected' : '' }}>{{ $subSections->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-6 col-xl-4">
                                <label>رقم الإيصال</label>
                                <div class="fr-range">
                                    <input name="start_voucher" type="text" class="form-control" placeholder="من" value="{{ request('start_voucher') }}" />
                                    <span><i class="la la-ellipsis-h"></i></span>
                                    <input name="end_voucher" type="text" class="form-control" placeholder="إلى" value="{{ request('end_voucher') }}" />
                                </div>
                            </div>
                            <div class="form-group col-6 col-md-3 col-xl-2">
                                <label>الجهة المستلمة</label>
                                <input name="num_section" type="text" class="form-control" value="{{ request('num_section') }}" />
                            </div>
                            <div class="form-group col-6 col-md-3 col-xl-2">
                                <label>رقم الدفتر</label>
                                <input name="num_note" type="text" class="form-control" value="{{ request('num_note') }}" />
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
                        </div>

                        <div class="fr-filter-actions">
                            <a href="{{ route('dieselexport.report') }}" class="btn btn-light">
                                <i class="la la-undo"></i> إعادة تعيين
                            </a>
                            <button type="submit" class="btn btn-danger">
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
                <h3 class="fr-card-title"><i class="la la-gas-pump"></i> سجل الصادر</h3>
                <form method="GET" action="{{ route('dieselexport.report') }}" class="fr-perpage fr-no-print">
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
                                <th>الدائرة</th>
                                <th>القسم الفرعي</th>
                                <th>رقم الإيصال</th>
                                <th>كمية الصادر</th>
                                <th>الجهة المستلمة</th>
                                <th>رقم الدفتر</th>
                                <th>تاريخ الصادر</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dieselExports as $dieselexport)
                                <tr>
                                    <td data-label="#" class="text-muted">{{ $dieselExports->firstItem() + $loop->index }}</td>
                                    <td data-label="نوع المحروقات"><span class="fr-fuel">{{ $dieselexport->typesfuel->name ?? '-' }}</span></td>
                                    <td data-label="الدائرة">{{ $dieselexport->section->name_section ?? '-' }}</td>
                                    <td data-label="القسم الفرعي">{{ $dieselexport->subSection->name ?? '-' }}</td>
                                    <td data-label="رقم الإيصال">{{ $dieselexport->voucher ?: '-' }}</td>
                                    <td data-label="كمية الصادر"><span class="fr-qty">{{ $fmt($dieselexport->quantity) }} لتر</span></td>
                                    <td data-label="الجهة المستلمة">{{ $dieselexport->num_section ?: '-' }}</td>
                                    <td data-label="رقم الدفتر">{{ $dieselexport->num_note ?: '-' }}</td>
                                    <td data-label="تاريخ الصادر" class="fr-date">{{ $dieselexport->date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="fr-empty">
                                        <i class="la la-inbox"></i>
                                        لا توجد سجلات صادر مطابقة لمعايير البحث
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($dieselExports->count())
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="fr-foot-spacer">مجموع الصفحة الحالية</td>
                                    <td data-label="مجموع الصفحة الحالية"><span class="fr-qty">{{ $fmt($dieselExports->sum('quantity')) }} لتر</span></td>
                                    <td colspan="3" class="fr-foot-spacer"></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="fr-foot-spacer">مجموع المحروقات الصادر (كل النتائج)</td>
                                    <td data-label="مجموع الصادر (كل النتائج)"><span class="fr-qty">{{ $fmt($totalExported) }} لتر</span></td>
                                    <td colspan="3" class="fr-foot-spacer"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                {{ $dieselExports->onEachSide(1)->links('partials.pagination') }}
            </div>
        </div>

        {{-- ===== Monthly summary ===== --}}
        @include('diesel.partials.report-summary', ['movementLabel' => 'الصادر'])
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            if ($.fn.selectpicker) {
                $('.fr-filters .selectpicker').selectpicker();
            }

            // تحميل الأقسام الفرعية حسب الدائرة المختارة
            var $sub = $('select[name="sub_section_id"]');
            $('select[name="section_id"]').on('change', function() {
                var sectionId = $(this).val();
                $sub.empty().append('<option value="">جميع الأقسام</option>');
                if (!sectionId) {
                    return;
                }
                $.getJSON("{{ URL::to('getsub_section') }}/" + sectionId, function(data) {
                    $.each(data, function(id, name) {
                        $sub.append($('<option>', { value: id, text: name }));
                    });
                });
            });
        });
    </script>
@endsection
