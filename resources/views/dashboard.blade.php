@extends('layout.master')

@section('title', 'لوحة التحكم')

@section('page_title', 'لوحة التحكم')

@section('sub_main', 'الرئيسية')

@section('css')
    <style>
        /* ===== Hero ===== */
        .db-hero { position: relative; overflow: hidden; border-radius: 18px; padding: 1.75rem 2rem; margin-bottom: 1.5rem; color: #fff; background: linear-gradient(135deg, #1E1E2D 0%, #2B2B40 55%, #3699FF 160%); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem; }
        .db-hero::before, .db-hero::after { content: ""; position: absolute; border-radius: 50%; background: rgba(54, 153, 255, .18); pointer-events: none; }
        .db-hero::before { width: 260px; height: 260px; left: -60px; top: -120px; }
        .db-hero::after { width: 160px; height: 160px; left: 180px; bottom: -110px; background: rgba(137, 80, 252, .18); }
        .db-hero > * { position: relative; z-index: 1; }
        .db-hero-hello { display: flex; align-items: center; gap: .5rem; color: #A2A3B7; font-weight: 600; margin-bottom: .25rem; }
        .db-hero-hello i { color: #FFA800; font-size: 1.3rem; }
        .db-hero h2 { color: #fff; font-weight: 800; font-size: 1.65rem; margin: 0 0 .35rem; }
        .db-hero p { color: #B5B5C3; margin: 0; }
        .db-hero-actions { display: flex; flex-wrap: wrap; gap: .5rem; }
        .db-hero-actions a { display: inline-flex; align-items: center; gap: .45rem; padding: .6rem 1rem; border-radius: 11px; background: rgba(255, 255, 255, .08); border: 1px solid rgba(255, 255, 255, .12); color: #fff; font-weight: 600; transition: background .15s ease, transform .15s ease; }
        .db-hero-actions a:hover { background: #fff; color: #1E1E2D; text-decoration: none; transform: translateY(-1px); }
        .db-hero-actions a i { font-size: 1.15rem; }

        /* ===== KPI ===== */
        .db-kpi { background: #fff; border: 1px solid var(--app-border); border-radius: 16px; padding: 1.25rem; height: 100%; box-shadow: var(--app-shadow); position: relative; overflow: hidden; }
        .db-kpi-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: .9rem; }
        .db-kpi-icon { width: 46px; height: 46px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; }
        .db-kpi-icon i { font-size: 1.5rem; }
        .db-kpi-label { color: var(--app-muted); font-weight: 600; font-size: .95rem; }
        .db-kpi-value { font-size: 1.75rem; font-weight: 800; color: var(--app-dark); line-height: 1.1; }
        .db-kpi-value small { font-size: .85rem; color: var(--app-muted); font-weight: 600; }
        .db-kpi-foot { margin-top: .6rem; font-size: .86rem; color: var(--app-muted); display: flex; gap: .75rem; flex-wrap: wrap; }
        .db-kpi-foot b { color: var(--app-text); }
        .db-kpi-value a { color: inherit; text-decoration: none; }
        .db-kpi-value a:hover { color: var(--app-primary); text-decoration: none; }
        .db-up { color: #1BC5BD !important; }
        .db-down { color: #F64E60 !important; }

        /* ===== Section title ===== */
        .db-section-title { display: flex; align-items: center; justify-content: space-between; margin: .5rem 0 1rem; }
        .db-section-title h3 { margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--app-dark); display: flex; align-items: center; gap: .5rem; }
        .db-section-title h3 i { color: var(--app-primary); }

        /* ===== Departments ===== */
        .db-depts { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .db-dept { background: #fff; border: 1px solid var(--app-border); border-radius: 16px; padding: 1.1rem; box-shadow: var(--app-shadow); transition: transform .15s ease, box-shadow .15s ease; display: flex; flex-direction: column; }
        .db-dept:hover { transform: translateY(-3px); box-shadow: 0 12px 28px -12px rgba(24, 28, 50, .25); }
        .db-dept-head { display: flex; align-items: center; gap: .7rem; margin-bottom: .9rem; }
        .db-dept-icon { width: 42px; height: 42px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; color: #fff; flex-shrink: 0; }
        .db-dept-icon i { font-size: 1.35rem; }
        .db-dept-name { font-weight: 700; color: var(--app-dark); line-height: 1.2; }
        .db-dept-total { color: var(--app-muted); font-size: .85rem; }
        .db-dept-split { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; margin-bottom: .75rem; }
        .db-dept-split a { display: block; border-radius: 10px; padding: .5rem .6rem; background: var(--app-soft); color: var(--app-text); transition: background .15s ease; }
        .db-dept-split a:hover { background: #E1F0FF; text-decoration: none; }
        .db-dept-split small { display: block; color: var(--app-muted); font-size: .78rem; }
        .db-dept-split b { font-size: 1.1rem; color: var(--app-dark); }
        .db-ratio { height: 6px; border-radius: 6px; background: #FFE2E5; overflow: hidden; margin-top: auto; }
        .db-ratio.is-empty { background: var(--app-soft); }
        .db-ratio span { display: block; height: 100%; background: #1BC5BD; border-radius: 6px; }

        /* ===== Panels ===== */
        .db-panel { background: #fff; border: 1px solid var(--app-border); border-radius: 16px; box-shadow: var(--app-shadow); margin-bottom: 1.5rem; height: calc(100% - 1.5rem); display: flex; flex-direction: column; }
        .db-panel-head { display: flex; align-items: center; justify-content: space-between; gap: .75rem; padding: 1rem 1.25rem; border-bottom: 1px solid var(--app-border); }
        .db-panel-head h4 { margin: 0; font-size: 1.05rem; font-weight: 700; color: var(--app-dark); display: flex; align-items: center; gap: .5rem; }
        .db-panel-head h4 i { font-size: 1.25rem; color: var(--app-primary); }
        .db-panel-head .db-link { font-size: .88rem; font-weight: 600; color: var(--app-primary); white-space: nowrap; }
        .db-panel-body { padding: 1.25rem; flex: 1; }
        .db-chart { min-height: 300px; }
        .db-chart-sm { min-height: 250px; }
        .db-empty { min-height: 220px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--app-muted); font-weight: 600; text-align: center; }
        .db-empty i { font-size: 2.6rem; opacity: .4; margin-bottom: .5rem; }

        /* ===== Fuel ===== */
        .db-fuel-total { border-radius: 14px; padding: 1.1rem 1.25rem; background: linear-gradient(135deg, #FFA800 0%, #F64E60 100%); color: #fff; margin-bottom: 1rem; }
        .db-fuel-total small { opacity: .85; display: block; }
        .db-fuel-total b { font-size: 1.8rem; font-weight: 800; }
        .db-fuel-type { display: flex; align-items: center; justify-content: space-between; padding: .7rem .2rem; border-bottom: 1px dashed var(--app-border); }
        .db-fuel-type:last-child { border-bottom: 0; }
        .db-fuel-type span { display: inline-flex; align-items: center; gap: .5rem; font-weight: 600; color: var(--app-text); }
        .db-fuel-type span::before { content: ""; width: 9px; height: 9px; border-radius: 50%; background: var(--dot, #FFA800); }
        .db-fuel-type b { color: var(--app-dark); }
        .db-mini-stats { display: grid; grid-template-columns: 1fr 1fr; gap: .6rem; margin-top: 1rem; }
        .db-mini-stats div { background: var(--app-soft); border-radius: 12px; padding: .7rem .8rem; }
        .db-mini-stats small { color: var(--app-muted); display: block; font-size: .8rem; }
        .db-mini-stats b { color: var(--app-dark); }

        /* ===== Inventory tiles ===== */
        .db-tiles { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .75rem; }
        .db-tile { border-radius: 14px; padding: 1rem; display: flex; align-items: center; gap: .8rem; color: var(--app-text); transition: transform .15s ease; }
        .db-tile:hover { transform: translateY(-2px); text-decoration: none; color: var(--app-text); }
        .db-tile i { font-size: 1.6rem; }
        .db-tile small { display: block; color: var(--app-muted); font-weight: 600; }
        .db-tile b { font-size: 1.35rem; color: var(--app-dark); }
        .db-chips { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: 1rem; }
        .db-chips a { display: inline-flex; align-items: center; gap: .3rem; padding: .35rem .7rem; border-radius: 30px; background: var(--app-soft); color: var(--app-text); font-size: .86rem; font-weight: 600; }
        .db-chips a:hover { background: #E1F0FF; color: var(--app-primary); text-decoration: none; }

        /* ===== Messages ===== */
        .db-msg { display: flex; align-items: center; gap: .85rem; padding: .7rem .5rem; border-radius: 12px; color: var(--app-text); transition: background .15s ease; }
        .db-msg:hover { background: var(--app-soft); text-decoration: none; color: var(--app-text); }
        .db-msg-icon { width: 40px; height: 40px; border-radius: 11px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .db-msg-icon i { font-size: 1.3rem; }
        .db-msg-body { flex: 1; min-width: 0; }
        .db-msg-body div { display: flex; justify-content: space-between; font-weight: 600; margin-bottom: .35rem; }
        .db-msg-bar { height: 5px; border-radius: 5px; background: var(--app-soft); overflow: hidden; }
        .db-msg-bar span { display: block; height: 100%; border-radius: 5px; }

        /* ===== Latest movements ===== */
        .db-move { display: flex; align-items: center; gap: .8rem; padding: .7rem 0; border-bottom: 1px dashed var(--app-border); }
        .db-move:last-child { border-bottom: 0; }
        .db-move-icon { width: 38px; height: 38px; border-radius: 11px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .db-move-body { flex: 1; min-width: 0; }
        .db-move-body b { display: block; color: var(--app-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .db-move-body small { color: var(--app-muted); }
        .db-move-qty { font-weight: 800; white-space: nowrap; }

        /* ===== Quick links ===== */
        .db-quick { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .65rem; }
        .db-quick a { display: flex; align-items: center; gap: .7rem; padding: .75rem; border-radius: 12px; border: 1px solid var(--app-border); color: var(--app-text); transition: border-color .15s ease, background .15s ease; }
        .db-quick a:hover { border-color: var(--app-primary); background: #F8FAFF; text-decoration: none; }
        .db-quick a span.db-q-icon { width: 36px; height: 36px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .db-quick a span.db-q-icon i { font-size: 1.2rem; }
        .db-quick a b { display: block; font-size: .93rem; color: var(--app-dark); }
        .db-quick a small { color: var(--app-muted); font-size: .8rem; }

        /* ===== Entrance animation ===== */
        .db-fade { animation: dbFade .45s ease both; }
        @keyframes dbFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
        @media (prefers-reduced-motion: reduce) { .db-fade { animation: none; } }

        /* ===== Responsive ===== */
        @media (max-width: 1399.98px) { .db-depts { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 991.98px) { .db-depts { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 575.98px) {
            .db-hero { padding: 1.25rem; }
            .db-hero h2 { font-size: 1.3rem; }
            .db-hero-actions { width: 100%; }
            .db-hero-actions a { flex: 1 1 calc(50% - .5rem); justify-content: center; }
            .db-kpi-value { font-size: 1.4rem; }
            .db-depts { grid-template-columns: 1fr; }
            .db-quick { grid-template-columns: 1fr; }
        }
    </style>
@endsection

@section('content')
    @php
        $s = $dashboardStats ?? [];
        $charts = $dashboardCharts ?? [];
        $fuel = $fuelSummary ?? [];
        $num = fn($v) => number_format((float) $v);
        $liters = function ($v) {
            $f = number_format((float) $v, 2);
            return rtrim(rtrim($f, '0'), '.');
        };

        $departments = [
            ['name' => 'الأرشيف المركزي', 'icon' => 'la-archive', 'color' => '#3699FF', 'in' => $s['archiveIncoming'] ?? 0, 'out' => $s['archiveOutgoing'] ?? 0, 'inRoute' => 'archive.index', 'outRoute' => 'archiveExport.index'],
            ['name' => 'الحاسوب', 'icon' => 'la-desktop', 'color' => '#1BC5BD', 'in' => $s['computerIncoming'] ?? 0, 'out' => $s['computerOutgoing'] ?? 0, 'inRoute' => 'computer.index', 'outRoute' => 'computerExport.index'],
            ['name' => 'الرقابة', 'icon' => 'la-eye', 'color' => '#8950FC', 'in' => $s['censorshipIncoming'] ?? 0, 'out' => $s['censorshipOutgoing'] ?? 0, 'inRoute' => 'censorship.index', 'outRoute' => 'censorshipExport.index'],
            ['name' => 'الشؤون القانونية', 'icon' => 'la-balance-scale', 'color' => '#F64E60', 'in' => $s['legalIncoming'] ?? 0, 'out' => $s['legalOutgoing'] ?? 0, 'inRoute' => 'legal.index', 'outRoute' => 'legalexport.index'],
            ['name' => 'الجباية', 'icon' => 'la-coins', 'color' => '#FFA800', 'in' => $s['jibayaIncoming'] ?? 0, 'out' => $s['jibayaOutgoing'] ?? 0, 'inRoute' => 'jibaya.index', 'outRoute' => 'jibayaexport.index'],
        ];

        $messages = [
            ['name' => 'مراسلات الأرشيف', 'icon' => 'la-archive', 'color' => '#3699FF', 'bg' => 'bg-light-primary', 'count' => $s['archiveMessages'] ?? 0, 'url' => url('/sendNotification/archiveNot')],
            ['name' => 'مراسلات الشؤون القانونية', 'icon' => 'la-balance-scale', 'color' => '#F64E60', 'bg' => 'bg-light-danger', 'count' => $s['legalMessages'] ?? 0, 'url' => url('/sendNotification/legalNot')],
            ['name' => 'مراسلات الحاسوب', 'icon' => 'la-desktop', 'color' => '#1BC5BD', 'bg' => 'bg-light-success', 'count' => $s['computerMessages'] ?? 0, 'url' => url('/sendNotification/computerNot')],
            ['name' => 'مراسلات الرقابة', 'icon' => 'la-eye', 'color' => '#8950FC', 'bg' => 'bg-light-info', 'count' => $s['censorshipMessages'] ?? 0, 'url' => url('/sendNotification/censorshipNot')],
            ['name' => 'مراسلات الجباية', 'icon' => 'la-coins', 'color' => '#FFA800', 'bg' => 'bg-light-warning', 'count' => $s['jibayaMessages'] ?? 0, 'url' => url('/sendNotification/jibayaNot')],
        ];
        $messagesMax = max(1, collect($messages)->max('count'));

        $fuelDots = ['#FFA800', '#F64E60', '#3699FF', '#1BC5BD', '#8950FC'];
        $userName = Auth::user()->employee_name ?? '';
    @endphp

    <div class="container">

        {{-- ===== Hero ===== --}}
        <div class="db-hero db-fade">
            <div>
                <div class="db-hero-hello"><i class="la la-sun" id="db_greeting_icon"></i> <span id="db_greeting">مرحباً</span></div>
                <h2>{{ $userName }}، أهلاً بك في نظام بلدية جباليا النزلة</h2>
                <p><i class="la la-calendar-alt"></i> <span id="db_today"></span></p>
            </div>
            <div class="db-hero-actions">
                <a href="{{ route('diesel.create') }}"><i class="la la-gas-pump"></i> وارد محروقات</a>
                <a href="{{ route('dieselexport.create') }}"><i class="la la-file-export"></i> صرف محروقات</a>
                <a href="{{ url('inventory/invoice/create') }}"><i class="la la-file-invoice"></i> فاتورة وارد</a>
                <a href="{{ url('inventory/invoice_export/create') }}"><i class="la la-dolly"></i> سند صرف</a>
            </div>
        </div>

        {{-- ===== KPIs ===== --}}
        <div class="row">
            <div class="mb-6 col-6 col-xl db-fade" style="animation-delay: .05s">
                <div class="db-kpi">
                    <div class="db-kpi-top">
                        <span class="db-kpi-label">معاملات الأرشيف</span>
                        <span class="db-kpi-icon bg-light-primary"><i class="la la-folder-open text-primary"></i></span>
                    </div>
                    <div class="db-kpi-value">{{ $num($s['totalArchiveTransactions'] ?? 0) }}</div>
                    <div class="db-kpi-foot"><span>في 5 دوائر</span></div>
                </div>
            </div>
            <div class="mb-6 col-6 col-xl db-fade" style="animation-delay: .1s">
                <div class="db-kpi">
                    <div class="db-kpi-top">
                        <span class="db-kpi-label">حركة المخازن</span>
                        <span class="db-kpi-icon bg-light-success"><i class="la la-boxes text-success"></i></span>
                    </div>
                    <div class="db-kpi-value">
                        <a href="{{ route('inventory.reports.monthly-stock') }}" title="عرض تقرير الحركة الشهرية للمخزون">{{ $num($s['totalInventoryTransactions'] ?? 0) }}</a>
                    </div>
                    <div class="db-kpi-foot">
                        <span><i class="la la-arrow-down db-up"></i> <b>{{ $num($s['inventoryIncoming'] ?? 0) }}</b> وارد</span>
                        <span><i class="la la-arrow-up db-down"></i> <b>{{ $num($s['inventoryOutgoing'] ?? 0) }}</b> صادر</span>
                        <span>هذا العام: <b>{{ $num($s['inventoryYearIncoming'] ?? 0) }}</b> وارد / <b>{{ $num($s['inventoryYearOutgoing'] ?? 0) }}</b> صادر</span>
                    </div>
                </div>
            </div>
            <div class="mb-6 col-6 col-xl db-fade" style="animation-delay: .15s">
                <div class="db-kpi">
                    <div class="db-kpi-top">
                        <span class="db-kpi-label">الأصناف الحرجة</span>
                        <span class="db-kpi-icon bg-light-danger"><i class="la la-exclamation-triangle text-danger"></i></span>
                    </div>
                    <div class="db-kpi-value {{ ($s['criticalItems'] ?? 0) > 0 ? 'text-danger' : '' }}">
                        <a href="{{ route('inventory.reports.low-stock') }}" title="عرض تقرير الأصناف تحت الحد الأدنى">{{ $num($s['criticalItems'] ?? 0) }}</a>
                    </div>
                    <div class="db-kpi-foot">
                        <span>رصيدها ≤ الحد الأدنى</span>
                        <span>من أصل <b>{{ $num($s['inventoryItems'] ?? 0) }}</b> صنف</span>
                    </div>
                </div>
            </div>
            <div class="mb-6 col-6 col-xl db-fade" style="animation-delay: .2s">
                <div class="db-kpi">
                    <div class="db-kpi-top">
                        <span class="db-kpi-label">رصيد المحروقات</span>
                        <span class="db-kpi-icon bg-light-warning"><i class="la la-gas-pump text-warning"></i></span>
                    </div>
                    <div class="db-kpi-value {{ ($fuel['totalRemaining'] ?? 0) < 0 ? 'text-danger' : '' }}">
                        <a href="{{ route('inventory.reports.historical-fuel') }}" title="عرض الرصيد التاريخي للمحروقات">{{ $liters($fuel['totalRemaining'] ?? 0) }} <small>لتر</small></a>
                    </div>
                    <div class="db-kpi-foot">
                        <span><i class="la la-arrow-down db-up"></i> <b>{{ $liters($fuel['yearIn'] ?? 0) }}</b> هذا العام</span>
                    </div>
                </div>
            </div>
            <div class="mb-6 col-6 col-xl db-fade" style="animation-delay: .25s">
                <div class="db-kpi">
                    <div class="db-kpi-top">
                        <span class="db-kpi-label">المراسلات الداخلية</span>
                        <span class="db-kpi-icon bg-light-info"><i class="la la-envelope-open-text text-info"></i></span>
                    </div>
                    <div class="db-kpi-value">{{ $num($s['internalMessages'] ?? 0) }}</div>
                    <div class="db-kpi-foot"><span>الأصناف <b>{{ $num($s['inventoryItems'] ?? 0) }}</b></span><span>العهد <b>{{ $num($s['custodies'] ?? 0) }}</b></span></div>
                </div>
            </div>
        </div>

        {{-- ===== Departments ===== --}}
        <div class="db-section-title">
            <h3><i class="la la-layer-group"></i> الدوائر</h3>
        </div>
        <div class="db-depts">
            @foreach ($departments as $dept)
                @php
                    $deptTotal = $dept['in'] + $dept['out'];
                    $inPercent = $deptTotal ? round($dept['in'] / $deptTotal * 100) : null;
                @endphp
                <div class="db-dept db-fade" style="animation-delay: {{ 0.05 * $loop->index }}s">
                    <div class="db-dept-head">
                        <span class="db-dept-icon" style="background: {{ $dept['color'] }}"><i class="la {{ $dept['icon'] }}"></i></span>
                        <div>
                            <div class="db-dept-name">{{ $dept['name'] }}</div>
                            <div class="db-dept-total">{{ $num($deptTotal) }} معاملة</div>
                        </div>
                    </div>
                    <div class="db-dept-split">
                        <a href="{{ Route::has($dept['inRoute']) ? route($dept['inRoute']) : '#' }}">
                            <small><i class="la la-arrow-down db-up"></i> وارد</small>
                            <b>{{ $num($dept['in']) }}</b>
                        </a>
                        <a href="{{ Route::has($dept['outRoute']) ? route($dept['outRoute']) : '#' }}">
                            <small><i class="la la-arrow-up db-down"></i> صادر</small>
                            <b>{{ $num($dept['out']) }}</b>
                        </a>
                    </div>
                    @if ($inPercent === null)
                        <div class="db-ratio is-empty" title="لا توجد معاملات"></div>
                    @else
                        <div class="db-ratio" title="نسبة الوارد {{ $inPercent }}%"><span style="width: {{ $inPercent }}%"></span></div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ===== Overall charts ===== --}}
        <div class="row">
            <div class="col-xl-8">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-chart-area"></i> إجمالي الحركات الشهرية</h4>
                        <span class="text-muted font-size-sm">الأرشيف + المخازن + المحروقات</span>
                    </div>
                    <div class="db-panel-body"><div id="overall-monthly-chart" class="db-chart"></div></div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-chart-pie"></i> المعاملات حسب القسم</h4>
                    </div>
                    <div class="db-panel-body"><div id="department-totals-chart" class="db-chart"></div></div>
                </div>
            </div>
        </div>

        {{-- ===== Fuel ===== --}}
        <div class="row">
            <div class="col-xl-8">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-gas-pump"></i> حركة المحروقات الشهرية (لتر)</h4>
                        <a href="{{ route('diesel.report') }}" class="db-link">تقارير الوارد <i class="la la-angle-left"></i></a>
                    </div>
                    <div class="db-panel-body"><div id="fuel-flow-chart" class="db-chart"></div></div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-balance-scale"></i> أرصدة المحروقات</h4>
                        <a href="{{ route('dieselexport.report') }}" class="db-link">تقارير الصادر <i class="la la-angle-left"></i></a>
                    </div>
                    <div class="db-panel-body">
                        <div class="db-fuel-total">
                            <small>إجمالي المتبقي في المخزون</small>
                            <b>{{ $liters($fuel['totalRemaining'] ?? 0) }}</b> لتر
                        </div>
                        @foreach ($fuel['typesfuel'] ?? [] as $type)
                            @php $remaining = $fuel['remainingByType'][$type->id] ?? 0; @endphp
                            <div class="db-fuel-type" style="--dot: {{ $fuelDots[$loop->index % count($fuelDots)] }}">
                                <span>{{ $type->name }}</span>
                                <b class="{{ $remaining < 0 ? 'text-danger' : '' }}">{{ $liters($remaining) }} لتر</b>
                            </div>
                        @endforeach
                        <div class="db-mini-stats">
                            <div><small>إجمالي الوارد</small><b class="db-up">{{ $liters($fuel['totalIn'] ?? 0) }}</b></div>
                            <div><small>إجمالي الصادر</small><b class="db-down">{{ $liters($fuel['totalOut'] ?? 0) }}</b></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Fuel by section ===== --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-gas-pump"></i> صرف المحروقات حسب الدائرة (لتر)</h4>
                        <a href="{{ route('inventory.reports.fuel-outgoing') }}" class="db-link">تقرير صرف المحروقات <i class="la la-angle-left"></i></a>
                    </div>
                    <div class="db-panel-body"><div id="section-fuel-chart" class="db-chart"></div></div>
                </div>
            </div>
        </div>

        {{-- ===== Archive charts ===== --}}
        <div class="row">
            <div class="col-xl-8">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-archive"></i> حركة الوارد والصادر في الأرشيف</h4>
                    </div>
                    <div class="db-panel-body"><div id="archive-monthly-chart" class="db-chart"></div></div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-layer-group"></i> توزيع معاملات الدوائر</h4>
                    </div>
                    <div class="db-panel-body"><div id="archive-distribution-chart" class="db-chart"></div></div>
                </div>
            </div>
        </div>

        {{-- ===== Inventory + Messages ===== --}}
        <div class="row">
            <div class="col-xl-6">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-boxes"></i> المخازن</h4>
                        <a href="{{ url('/inventory/totalreport') }}" class="db-link">تقارير المجاميع <i class="la la-angle-left"></i></a>
                    </div>
                    <div class="db-panel-body">
                        <div class="db-tiles">
                            <a href="{{ url('inventory/item') }}" class="db-tile bg-light-warning">
                                <i class="la la-boxes text-warning"></i>
                                <span><small>الأصناف</small><b>{{ $num($s['inventoryItems'] ?? 0) }}</b></span>
                            </a>
                            <a href="{{ url('inventory/invoice') }}" class="db-tile bg-light-primary">
                                <i class="la la-file-invoice text-primary"></i>
                                <span><small>فواتير الوارد</small><b>{{ $num($s['inventoryIncoming'] ?? 0) }}</b></span>
                            </a>
                            <a href="{{ url('inventory/invoice_export') }}" class="db-tile bg-light-danger">
                                <i class="la la-dolly text-danger"></i>
                                <span><small>سندات الصرف</small><b>{{ $num($s['inventoryOutgoing'] ?? 0) }}</b></span>
                            </a>
                            <a href="{{ url('inventory/custody') }}" class="db-tile bg-light-success">
                                <i class="la la-clipboard-check text-success"></i>
                                <span><small>العهد</small><b>{{ $num($s['custodies'] ?? 0) }}</b></span>
                            </a>
                        </div>
                        <div id="inventory-flow-chart" class="mt-4 db-chart-sm"></div>
                        <div class="db-chips">
                            <a href="{{ url('inventory/item/create') }}"><i class="la la-plus-circle"></i> صنف</a>
                            <a href="{{ url('inventory/category/create') }}"><i class="la la-plus-circle"></i> عائلة</a>
                            <a href="{{ url('inventory/unit/create') }}"><i class="la la-plus-circle"></i> وحدة</a>
                            <a href="{{ url('inventory/store/create') }}"><i class="la la-plus-circle"></i> مخزن</a>
                            <a href="{{ url('inventory/supplier_item/create') }}"><i class="la la-plus-circle"></i> مورد</a>
                            <a href="{{ url('inventory/custody/create') }}"><i class="la la-plus-circle"></i> عهدة</a>
                            <a href="{{ url('inventory/transactions_report') }}"><i class="la la-history"></i> حركة الصنف</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-envelope-open-text"></i> المراسلات الداخلية</h4>
                        <span class="text-muted font-size-sm">{{ $num($s['internalMessages'] ?? 0) }} مراسلة</span>
                    </div>
                    <div class="db-panel-body">
                        @foreach ($messages as $message)
                            <a href="{{ $message['url'] }}" class="db-msg">
                                <span class="db-msg-icon {{ $message['bg'] }}"><i class="la {{ $message['icon'] }}" style="color: {{ $message['color'] }}"></i></span>
                                <span class="db-msg-body">
                                    <div><span>{{ $message['name'] }}</span><span style="color: {{ $message['color'] }}">{{ $num($message['count']) }}</span></div>
                                    <span class="db-msg-bar d-block"><span style="width: {{ round($message['count'] / $messagesMax * 100) }}%; background: {{ $message['color'] }}"></span></span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Latest fuel + Quick links ===== --}}
        <div class="row">
            <div class="col-xl-6">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-history"></i> آخر حركات المحروقات</h4>
                        <a href="{{ route('diesel.index') }}" class="db-link">عرض الكل <i class="la la-angle-left"></i></a>
                    </div>
                    <div class="db-panel-body">
                        @forelse ($fuel['latest'] ?? [] as $move)
                            @php $isIn = $move->direction === 'in'; @endphp
                            <div class="db-move">
                                <span class="db-move-icon {{ $isIn ? 'bg-light-success' : 'bg-light-danger' }}">
                                    <i class="la {{ $isIn ? 'la-arrow-down text-success' : 'la-arrow-up text-danger' }}"></i>
                                </span>
                                <div class="db-move-body">
                                    <b>{{ $isIn ? 'وارد' : 'صرف' }} {{ $move->fuel ?? '' }} — {{ $move->party ?? '-' }}</b>
                                    <small>{{ $move->date }}</small>
                                </div>
                                <span class="db-move-qty {{ $isIn ? 'db-up' : 'db-down' }}">{{ $isIn ? '+' : '−' }}{{ $liters($move->quantity) }} لتر</span>
                            </div>
                        @empty
                            <div class="db-empty"><i class="la la-gas-pump"></i> لا توجد حركات محروقات بعد</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="db-panel">
                    <div class="db-panel-head">
                        <h4><i class="la la-bolt"></i> التنقل السريع</h4>
                        <span class="text-muted font-size-sm">أو اضغط <kbd>Ctrl</kbd> + <kbd>K</kbd> للبحث</span>
                    </div>
                    <div class="db-panel-body">
                        <div class="db-quick">
                            <a href="{{ route('diesel.index') }}"><span class="db-q-icon bg-light-warning"><i class="la la-gas-pump text-warning"></i></span><span><b>وارد المحروقات</b><small>سجل التوريد</small></span></a>
                            <a href="{{ route('dieselexport.index') }}"><span class="db-q-icon bg-light-danger"><i class="la la-file-export text-danger"></i></span><span><b>صادر المحروقات</b><small>سجل الصرف</small></span></a>
                            <a href="{{ url('inventory/item') }}"><span class="db-q-icon bg-light-primary"><i class="la la-boxes text-primary"></i></span><span><b>الأصناف</b><small>عرض وإدارة الأصناف</small></span></a>
                            <a href="{{ url('inventory/invoice') }}"><span class="db-q-icon bg-light-success"><i class="la la-file-invoice text-success"></i></span><span><b>فواتير الوارد</b><small>الأصناف الواردة</small></span></a>
                            <a href="{{ url('inventory/invoice_export') }}"><span class="db-q-icon bg-light-info"><i class="la la-dolly text-info"></i></span><span><b>سندات الصرف</b><small>الأصناف الصادرة</small></span></a>
                            <a href="{{ url('inventory/totalreport') }}"><span class="db-q-icon bg-light-primary"><i class="la la-chart-bar text-primary"></i></span><span><b>تقارير المجاميع</b><small>تقارير المخازن</small></span></a>
                            <a href="{{ route('archive.index') }}"><span class="db-q-icon bg-light-primary"><i class="la la-archive text-primary"></i></span><span><b>الأرشيف المركزي</b><small>الوارد</small></span></a>
                            <a href="{{ url('/profile') }}"><span class="db-q-icon bg-light-dark"><i class="la la-user text-dark"></i></span><span><b>حسابي</b><small>البيانات الشخصية</small></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function() {
            /* ===== التحية والتاريخ حسب وقت جهاز المستخدم ===== */
            var now = new Date();
            var hour = now.getHours();
            var greeting = hour < 12 ? 'صباح الخير' : (hour < 18 ? 'مساء الخير' : 'مساء النور');
            var icon = hour >= 6 && hour < 18 ? 'la-sun' : 'la-moon';
            document.getElementById('db_greeting').textContent = greeting;
            document.getElementById('db_greeting_icon').className = 'la ' + icon;
            try {
                document.getElementById('db_today').textContent = now.toLocaleDateString('ar-u-nu-latn', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                });
            } catch (e) {
                document.getElementById('db_today').textContent = now.toLocaleDateString();
            }
        })();

        document.addEventListener('DOMContentLoaded', function() {
            var charts = @json($dashboardCharts ?? []);
            if (typeof ApexCharts === 'undefined') {
                return;
            }

            var monthLabels = charts.monthLabels || [];
            var font = 'Cairo, sans-serif';
            var muted = '#7E8299';

            function sum(values) {
                return (values || []).reduce(function(t, v) { return t + Number(v || 0); }, 0);
            }

            function empty(selector, text) {
                var el = document.querySelector(selector);
                if (el) {
                    el.innerHTML = '<div class="db-empty"><i class="la la-chart-area"></i>' + (text || 'لا توجد بيانات كافية لعرض المخطط') + '</div>';
                }
            }

            function render(selector, options, groups) {
                var el = document.querySelector(selector);
                if (!el) {
                    return;
                }
                if (!(groups || []).some(function(g) { return sum(g) > 0; })) {
                    empty(selector);
                    return;
                }
                options.chart = Object.assign({ fontFamily: font, toolbar: { show: false }, animations: { speed: 500 } }, options.chart || {});
                new ApexCharts(el, options).render();
            }

            function areaOptions(series, colors, height, unit) {
                return {
                    series: series,
                    chart: { type: 'area', height: height || 300 },
                    colors: colors,
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2.5 },
                    fill: { type: 'gradient', gradient: { opacityFrom: .3, opacityTo: .02 } },
                    grid: { borderColor: '#EBEDF3', strokeDashArray: 4 },
                    xaxis: { categories: monthLabels, labels: { style: { colors: muted } }, axisBorder: { show: false }, axisTicks: { show: false } },
                    yaxis: { labels: { style: { colors: muted }, formatter: function(v) { return Math.round(v).toLocaleString('en'); } } },
                    tooltip: { y: { formatter: function(v) { return Number(v).toLocaleString('en') + ' ' + (unit || 'معاملة'); } } },
                    legend: { position: 'top', horizontalAlign: 'right', fontWeight: 600 }
                };
            }

            var overall = charts.overallMonthly || {};
            render('#overall-monthly-chart', areaOptions([
                { name: 'الوارد', data: overall.incoming || [] },
                { name: 'الصادر', data: overall.outgoing || [] }
            ], ['#3699FF', '#1BC5BD']), [overall.incoming, overall.outgoing]);

            var dept = charts.departmentTotals || {};
            render('#department-totals-chart', {
                series: dept.values || [],
                labels: dept.labels || [],
                chart: { type: 'donut', height: 300 },
                colors: ['#3699FF', '#1BC5BD', '#8950FC', '#F64E60', '#FFA800', '#181C32', '#FF6F00'],
                dataLabels: { enabled: false },
                stroke: { width: 3 },
                legend: { position: 'bottom', fontWeight: 600 },
                plotOptions: { pie: { donut: { size: '70%', labels: { show: true, total: { show: true, label: 'الإجمالي', formatter: function(w) {
                    return w.globals.seriesTotals.reduce(function(t, v) { return t + v; }, 0).toLocaleString('en');
                } } } } } }
            }, [dept.values]);

            var fuel = charts.fuelFlow || {};
            render('#fuel-flow-chart', {
                series: [
                    { name: 'الوارد', data: fuel.incoming || [] },
                    { name: 'الصادر', data: fuel.outgoing || [] }
                ],
                chart: { type: 'bar', height: 300 },
                colors: ['#FFA800', '#F64E60'],
                plotOptions: { bar: { columnWidth: '45%', borderRadius: 5 } },
                dataLabels: { enabled: false },
                grid: { borderColor: '#EBEDF3', strokeDashArray: 4 },
                xaxis: { categories: monthLabels, labels: { style: { colors: muted } }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { labels: { style: { colors: muted }, formatter: function(v) { return Math.round(v).toLocaleString('en'); } } },
                tooltip: { y: { formatter: function(v) { return Number(v).toLocaleString('en') + ' لتر'; } } },
                legend: { position: 'top', horizontalAlign: 'right', fontWeight: 600 }
            }, [fuel.incoming, fuel.outgoing]);

            var archive = charts.monthlyArchive || {};
            render('#archive-monthly-chart', areaOptions([
                { name: 'الوارد', data: archive.incoming || [] },
                { name: 'الصادر', data: archive.outgoing || [] }
            ], ['#3699FF', '#F64E60']), [archive.incoming, archive.outgoing]);

            var dist = charts.archiveDistribution || {};
            render('#archive-distribution-chart', {
                series: [
                    { name: 'الوارد', data: dist.incoming || [] },
                    { name: 'الصادر', data: dist.outgoing || [] }
                ],
                chart: { type: 'bar', height: 300, stacked: true },
                plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '55%' } },
                colors: ['#1BC5BD', '#FFA800'],
                dataLabels: { enabled: false },
                xaxis: { categories: dist.labels || [], labels: { style: { colors: muted } } },
                yaxis: { labels: { style: { colors: '#3F4254', fontWeight: 600 } } },
                grid: { borderColor: '#EBEDF3', strokeDashArray: 4 },
                legend: { position: 'top', horizontalAlign: 'right', fontWeight: 600 }
            }, [dist.incoming, dist.outgoing]);

            var inventory = charts.inventoryFlow || {};
            render('#inventory-flow-chart', areaOptions([
                { name: 'فواتير الوارد', data: inventory.incoming || [] },
                { name: 'سندات الصرف', data: inventory.outgoing || [] }
            ], ['#3699FF', '#F64E60'], 230, 'سند'), [inventory.incoming, inventory.outgoing]);

            var sectionFuel = charts.sectionFuelOutgoing || {};
            render('#section-fuel-chart', {
                series: [{ name: 'الصرف (لتر)', data: sectionFuel.values || [] }],
                chart: { type: 'bar', height: 320 },
                plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '55%' } },
                colors: ['#FFA800'],
                dataLabels: { enabled: false },
                xaxis: { categories: sectionFuel.labels || [], labels: { style: { colors: muted } } },
                yaxis: { labels: { style: { colors: '#3F4254', fontWeight: 600 } } },
                grid: { borderColor: '#EBEDF3', strokeDashArray: 4 },
                tooltip: { y: { formatter: function(v) { return Number(v).toLocaleString('en') + ' لتر'; } } },
                legend: { show: false }
            }, [sectionFuel.values]);
        });
    </script>
@endsection
