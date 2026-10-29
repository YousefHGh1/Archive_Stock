@extends('layout.master')

@section('title')
    عرض الصادر التاريخي
@endsection

@section('page_title')
    التقارير
@endsection

@section('sub_main')
    التقارير
@endsection

@section('sub_title')
    صادر المحروقات التاريخي
@endsection

@section('css')
    <style>
        :root {
            --accent: #f64e60;
            --accent-dark: #d93a4c;
            --accent-soft: #ffeef0;
            --ink: #3f4254;
            --muted: #7e8299;
            --line: #e9ecef;
            --bg-soft: #f8f9fb;
        }

        /* ===== Page header ===== */
        .page-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .page-head-title { display: flex; align-items: center; gap: 1rem; }
        .page-head-icon { width: 52px; height: 52px; border-radius: 14px; background: var(--accent-soft); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; }
        .page-head h1 { font-size: 1.35rem; font-weight: 800; color: var(--ink); margin: 0 0 .15rem; }
        .page-head p { margin: 0; color: var(--muted); font-size: .9rem; }
        .head-actions { display: flex; gap: .6rem; flex-wrap: wrap; }
        .btn-soft { background: #fff; color: var(--ink); border: 1px solid #d9dce6; padding: .6rem 1.1rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: .45rem; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-soft:hover { background: var(--bg-soft); color: var(--ink); text-decoration: none; }
        .btn-solid { background: var(--accent); color: #fff; border: 1px solid var(--accent); padding: .6rem 1.1rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: .45rem; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-solid:hover { background: var(--accent-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(246,78,96,.3); text-decoration: none; }

        /* ===== Stat cards ===== */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { position: relative; background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 1.1rem 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,.04); overflow: hidden; }
        .stat-card::before { content: ""; position: absolute; inset-block: 0; inset-inline-start: 0; width: 4px; background: var(--accent); }
        .stat-icon { width: 46px; height: 46px; border-radius: 12px; background: var(--accent-soft); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
        .stat-value { font-size: 1.45rem; font-weight: 800; color: var(--ink); line-height: 1.2; }
        .stat-value small { font-size: .8rem; font-weight: 600; color: var(--muted); margin-inline-start: .25rem; }
        .stat-label { font-size: .85rem; color: var(--muted); font-weight: 500; margin-top: .15rem; }
        .stat-card.is-blue::before { background: #3699ff; } .stat-card.is-blue .stat-icon { background: #e8f3ff; color: #3699ff; }
        .stat-card.is-orange::before { background: #ffa800; } .stat-card.is-orange .stat-icon { background: #fff4de; color: #ffa800; }

        /* ===== Cards ===== */
        .report-card { background: #fff; border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,.05); margin-bottom: 1.5rem; overflow: hidden; }
        .report-card-header { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--line); background: linear-gradient(180deg, #fff 0%, var(--bg-soft) 100%); }
        .report-card-title { font-size: 1.05rem; font-weight: 700; color: var(--ink); margin: 0; display: flex; align-items: center; gap: .6rem; }
        .report-card-title i { color: var(--accent); font-size: 1.3rem; }
        .report-card-badge { background: var(--accent-soft); color: var(--accent); font-weight: 700; font-size: .8rem; padding: .3rem .8rem; border-radius: 20px; }
        .report-card-body { padding: 1.5rem; }

        /* ===== Filters ===== */
        .filter-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem 1.25rem; align-items: end; }
        .filter-field { display: flex; flex-direction: column; }
        .filter-label { font-size: .82rem; font-weight: 600; color: var(--muted); margin-bottom: .4rem; }
        .filter-input { width: 100%; padding: .6rem .9rem; border: 1px solid #d9dce6; border-radius: 8px; font-size: .92rem; background: #fff; color: var(--ink); transition: all .2s; }
        .filter-input:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 0 3px rgba(246,78,96,.15); }
        .filter-actions { display: flex; gap: .6rem; }
        .filter-actions .btn-solid, .filter-actions .btn-soft { flex: 1; justify-content: center; }

        /* ===== Main table ===== */
        .table-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 10px; }
        .report-table { width: 100%; border-collapse: collapse; font-size: .92rem; }
        .report-table thead th { background: #2f3447; color: #fff; padding: .9rem 1rem; text-align: right; font-weight: 600; white-space: nowrap; position: sticky; top: 0; }
        .report-table tbody td { padding: .85rem 1rem; color: #5e6278; border-bottom: 1px solid #f1f3f7; text-align: right; vertical-align: middle; }
        .report-table tbody tr:nth-child(even) { background: #fbfcfe; }
        .report-table tbody tr:hover { background: var(--accent-soft); }
        .report-table tfoot th, .report-table tfoot td { background: var(--accent-soft); color: var(--accent-dark); font-weight: 800; padding: 1rem; text-align: right; border-top: 2px solid var(--accent); }
        .qty { font-weight: 700; color: var(--ink); white-space: nowrap; }
        .qty small { color: var(--muted); font-weight: 500; }
        .chip { display: inline-block; padding: .2rem .65rem; border-radius: 6px; background: var(--accent-soft); color: var(--accent-dark); font-weight: 700; font-size: .82rem; }
        .chip-fuel { background: #fff4de; color: #b77700; }
        .date-cell { white-space: nowrap; color: var(--muted); }

        /* ===== Monthly table ===== */
        .monthly-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 10px; }
        .monthly-table { width: 100%; min-width: 980px; border-collapse: collapse; font-size: .85rem; }
        .monthly-table thead th { background: var(--accent); color: #fff; padding: .8rem .5rem; text-align: center; font-weight: 600; white-space: nowrap; }
        .monthly-table tbody td { padding: .9rem .5rem; text-align: center; color: var(--ink); border-bottom: 1px solid #f1f3f7; vertical-align: bottom; }
        .monthly-table tbody td.row-label { font-weight: 700; background: var(--bg-soft); }
        .monthly-table tbody td.total-cell { background: var(--accent-soft); color: var(--accent-dark); font-weight: 800; }
        .bar-cell { min-width: 62px; }
        .bar-track { height: 46px; display: flex; align-items: flex-end; justify-content: center; margin-bottom: .4rem; }
        .bar-fill { width: 60%; border-radius: 6px 6px 0 0; background: linear-gradient(180deg, #ff9aa6 0%, var(--accent) 100%); min-height: 2px; transition: height .3s; }
        .bar-fill.is-zero { background: #e4e6ef; }
        .bar-num { font-weight: 700; font-size: .82rem; }
        .bar-num.is-zero { color: #b5b5c3; font-weight: 500; }

        /* ===== Empty ===== */
        .empty-state { text-align: center; padding: 3.5rem 1rem; color: var(--muted); }
        .empty-state i { font-size: 3.5rem; color: #d1d3e0; display: block; margin-bottom: .75rem; }
        .empty-state h4 { font-size: 1.2rem; color: var(--ink); margin-bottom: .4rem; }

        /* ===== Responsive / print ===== */
        @media (max-width: 768px) {
            .head-actions { width: 100%; }
            .head-actions .btn-soft, .head-actions .btn-solid { flex: 1; justify-content: center; }
            .filter-actions { flex-direction: column; }
        }
        @media print {
            .head-actions, .filter-card, .pagination-wrap { display: none !important; }
            .report-card, .stat-card { box-shadow: none !important; break-inside: avoid; }
            .report-table thead th { background: #eee !important; color: #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .report-table tbody tr:hover { background: transparent; }
        }
    </style>
@endsection

@section('content')
    @php
        $fmt = fn($n) => rtrim(rtrim(number_format((float) $n, 2), '0'), '.');
        $monthNames = [1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل', 5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس', 9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'];
        $monthlyMax = max(1, (float) collect($dieselData ?? [])->max());
        $fuelTypes = \App\Models\TypesFuel::all();
        $sections = \App\Models\Section::all();
    @endphp

    <div class="container">

        <!-- Page Header -->
        <div class="page-head">
            <div class="page-head-title">
                <div class="page-head-icon"><i class="la la-share-square"></i></div>
                <div>
                    <h1>تقارير صادر المحروقات التاريخي</h1>
                    <p>عرض وتحليل سجلات الصرف من المحروقات</p>
                </div>
            </div>
            <div class="head-actions">
                <a class="btn-soft" href="{{ route('diesel.index') }}"><i class="la la-home"></i> الرئيسية</a>
                <button type="button" class="btn-soft" onclick="history.back()"><i class="la la-arrow-right"></i> رجوع</button>
                <button type="button" class="btn-solid" onclick="window.print()"><i class="la la-print"></i> طباعة التقرير</button>
            </div>
        </div>

        <!-- Stat Cards -->
        @if(isset($totalExported))
            <div class="stat-grid">
                <div class="stat-card is-blue">
                    <div class="stat-icon"><i class="la la-file-invoice"></i></div>
                    <div>
                        <div class="stat-value">{{ $fmt($totalEntries ?? 0) }}</div>
                        <div class="stat-label">إجمالي السندات</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="la la-tint"></i></div>
                    <div>
                        <div class="stat-value">{{ $fmt($totalExported ?? 0) }}<small>لتر</small></div>
                        <div class="stat-label">إجمالي الصادر</div>
                    </div>
                </div>
                @if(isset($totalDiesel))
                    <div class="stat-card is-orange">
                        <div class="stat-icon"><i class="la la-gas-pump"></i></div>
                        <div>
                            <div class="stat-value">{{ $fmt($totalDiesel) }}<small>لتر</small></div>
                            <div class="stat-label">إجمالي السولار</div>
                        </div>
                    </div>
                @endif
                @if(isset($totalGasoline))
                    <div class="stat-card is-orange">
                        <div class="stat-icon"><i class="la la-fire"></i></div>
                        <div>
                            <div class="stat-value">{{ $fmt($totalGasoline) }}<small>لتر</small></div>
                            <div class="stat-label">إجمالي البنزين</div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Filter Section -->
        <div class="report-card filter-card">
            <div class="report-card-header">
                <h3 class="report-card-title"><i class="la la-filter"></i> معايير البحث والتصفية</h3>
            </div>
            <div class="report-card-body">
                <form action="{{ route('historical.exportindex') }}" method="GET" class="filter-form">
                    <div class="filter-field">
                        <label class="filter-label">التاريخ من</label>
                        <input name="start_date" type="date" class="filter-input" value="{{ request('start_date') }}">
                    </div>
                    <div class="filter-field">
                        <label class="filter-label">التاريخ إلى</label>
                        <input name="end_date" type="date" class="filter-input" value="{{ request('end_date') }}">
                    </div>
                    <div class="filter-field">
                        <label class="filter-label">نوع المحروقات</label>
                        <select name="fuel_type" class="filter-input">
                            <option value="">جميع الأنواع</option>
                            @foreach($fuelTypes as $ft)
                                <option value="{{ $ft->id }}" {{ request('fuel_type') == $ft->id ? 'selected' : '' }}>{{ $ft->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-field">
                        <label class="filter-label">السند</label>
                        <input name="voucher" type="text" class="filter-input" placeholder="رقم السند..." value="{{ request('voucher') }}">
                    </div>
                    <div class="filter-field">
                        <label class="filter-label">القسم</label>
                        <select name="section_id" class="filter-input">
                            <option value="">جميع الأقسام</option>
                            @foreach($sections as $sec)
                                <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>{{ $sec->name_section }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-field">
                        <div class="filter-actions">
                            <button type="submit" class="btn-solid"><i class="la la-search"></i> تطبيق</button>
                            <a href="{{ route('historical.exportindex') }}" class="btn-soft"><i class="la la-refresh"></i> إعادة ضبط</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Data Table -->
        <div class="report-card">
            <div class="report-card-header">
                <h3 class="report-card-title"><i class="la la-table"></i> سجلات الصادر</h3>
                @if(isset($dieselExports) && $dieselExports->count() > 0)
                    <span class="report-card-badge">{{ $dieselExports->count() }} سجل</span>
                @endif
            </div>
            <div class="report-card-body">
                @if(isset($dieselExports) && $dieselExports->count() > 0)
                    <div class="table-wrap">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>نوع المحروقات</th>
                                    <th>القسم</th>
                                    <th>القسم الفرعي</th>
                                    <th>سند الصادر</th>
                                    <th>كمية الصادر</th>
                                    <th>رقم الإيصال</th>
                                    <th>رقم الدفتر</th>
                                    <th>تاريخ الصادر</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dieselExports as $dieselexport)
                                    <tr>
                                        <td><span class="chip chip-fuel">{{ $dieselexport->typesfuel->name ?? 'غير محدد' }}</span></td>
                                        <td>{{ $dieselexport->section->name_section ?? 'غير محدد' }}</td>
                                        <td>{{ $dieselexport->subSection->name ?? 'غير محدد' }}</td>
                                        <td><span class="chip">{{ $dieselexport->voucher }}</span></td>
                                        <td class="qty">{{ $fmt($dieselexport->quantity) }} <small>لتر</small></td>
                                        <td>{{ $dieselexport->num_section }}</td>
                                        <td>{{ $dieselexport->num_note }}</td>
                                        <td class="date-cell">{{ $dieselexport->date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">إجمالي الصادر</th>
                                    <th colspan="4">{{ $fmt($totalExported ?? 0) }} لتر</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if(method_exists($dieselExports, 'links'))
                        <div class="pagination-wrap mt-4">{{ $dieselExports->withQueryString()->links() }}</div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="la la-inbox"></i>
                        <h4>لا توجد سجلات صادر</h4>
                        <p class="mb-0">لم يتم العثور على أي بيانات صادرة تطابق معايير البحث.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="report-card">
            <div class="report-card-header">
                <h3 class="report-card-title"><i class="la la-calendar"></i> الكميات الشهرية للمحروقات</h3>
            </div>
            <div class="report-card-body">
                <div class="monthly-wrap">
                    <table class="monthly-table">
                        <thead>
                            <tr>
                                <th>البيان</th>
                                @foreach ($monthNames as $name)
                                    <th>{{ $name }}</th>
                                @endforeach
                                <th>عدد السندات</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-label">الكمية (لتر)</td>
                                @for ($i = 1; $i <= 12; $i++)
                                    @php
                                        $val = (float) ($dieselData[$i] ?? 0);
                                        $h = $val > 0 ? max(4, round(($val / $monthlyMax) * 100)) : 0;
                                    @endphp
                                    <td class="bar-cell">
                                        <div class="bar-track">
                                            <div class="bar-fill {{ $val > 0 ? '' : 'is-zero' }}" style="height: {{ $h }}%"></div>
                                        </div>
                                        <div class="bar-num {{ $val > 0 ? '' : 'is-zero' }}">{{ $fmt($val) }}</div>
                                    </td>
                                @endfor
                                <td class="total-cell">{{ $fmt($totalEntries ?? 0) }}</td>
                                <td class="total-cell">{{ $fmt($totalExported ?? 0) }} لتر</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection