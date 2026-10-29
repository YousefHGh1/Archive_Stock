@extends('layout.master')

@section('title')
    تقارير الصادر
@endsection

@section('page_title')
    لوحة التحكم
@endsection

@section('sub_main')
    الصادر
@endsection

@section('sub_title')
    تقارير المجاميع
@endsection

@section('css')
    <link href="{{ asset('assets/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.foundation.min.css') }}">

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
        .stat-card.is-green::before { background: #1bc5bd; } .stat-card.is-green .stat-icon { background: #e1f8f6; color: #1bc5bd; }

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

        /* ===== Table (works with and without DataTables) ===== */
        .totals-wrap { overflow-x: auto; }
        #example { width: 100% !important; border-collapse: collapse; font-size: .92rem; border: 1px solid var(--line); border-radius: 10px; }
        #example thead th { background: #2f3447 !important; color: #fff; padding: .9rem 1rem !important; text-align: right; font-weight: 600; white-space: nowrap; border: none; }
        #example tbody td { padding: .85rem 1rem !important; color: #5e6278; border-bottom: 1px solid #f1f3f7; text-align: right; vertical-align: middle; }
        #example tbody tr:nth-child(even) { background: #fbfcfe; }
        #example tbody tr:hover { background: var(--accent-soft); }
        #example tfoot th { background: var(--accent-soft); color: var(--accent-dark); font-weight: 800; padding: 1rem !important; text-align: right; border-top: 2px solid var(--accent); font-size: 1rem; }
        .chip { display: inline-block; padding: .2rem .65rem; border-radius: 6px; background: var(--accent-soft); color: var(--accent-dark); font-weight: 700; font-size: .82rem; }
        .qty { font-weight: 700; color: var(--ink); white-space: nowrap; }
        .qty small { color: var(--muted); font-weight: 500; }
        .dataTables_wrapper .dataTables_filter input, .dataTables_wrapper .dataTables_length select { border: 1px solid #d9dce6; border-radius: 8px; padding: .4rem .7rem; }
        div.dt-buttons { padding: .5rem 0; text-align: right; }

        /* ===== Empty ===== */
        .empty-state { text-align: center; padding: 3.5rem 1rem; color: var(--muted); }
        .empty-state i { font-size: 3.5rem; color: #d1d3e0; display: block; margin-bottom: .75rem; }
        .empty-state h4 { font-size: 1.2rem; color: var(--ink); margin-bottom: .4rem; }

        @media (max-width: 768px) {
            .head-actions { width: 100%; }
            .head-actions .btn-soft, .head-actions .btn-solid { flex: 1; justify-content: center; }
            .filter-actions { flex-direction: column; }
        }
        @media print {
            .head-actions, .filter-card, .dt-buttons, .dataTables_filter, .dataTables_length, .dataTables_paginate { display: none !important; }
            .report-card, .stat-card { box-shadow: none !important; break-inside: avoid; }
            #example thead th { background: #eee !important; color: #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
@endsection

@section('content')
    @php
        $fmt = fn($n) => rtrim(rtrim(number_format((float) $n, 2), '0'), '.');
        $groups = $dieselexport->groupBy('voucher');
        $totalVouchers = $groups->count();
        $totalReceipts = $dieselexport->count();
        $totalQuantity = $dieselexport->sum('quantity');
    @endphp

    <div class="container">

        <!-- Page Header -->
        <div class="page-head">
            <div class="page-head-title">
                <div class="page-head-icon"><i class="la la-calculator"></i></div>
                <div>
                    <h1>تقارير المجاميع</h1>
                    <p>مجموع كميات وإيصالات الصادر مجمّعة حسب السند</p>
                </div>
            </div>
            <div class="head-actions">
                <a class="btn-soft" href="{{ url('/dashboard') }}"><i class="la la-home"></i> الرئيسية</a>
                <button type="button" class="btn-soft" onclick="history.back()"><i class="la la-arrow-right"></i> رجوع</button>
                <button type="button" class="btn-solid" onclick="window.print()"><i class="la la-print"></i> طباعة</button>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="stat-grid">
            <div class="stat-card is-blue">
                <div class="stat-icon"><i class="la la-file-invoice"></i></div>
                <div>
                    <div class="stat-value">{{ $fmt($totalVouchers) }}</div>
                    <div class="stat-label">عدد السندات</div>
                </div>
            </div>
            <div class="stat-card is-green">
                <div class="stat-icon"><i class="la la-receipt"></i></div>
                <div>
                    <div class="stat-value">{{ $fmt($totalReceipts) }}</div>
                    <div class="stat-label">إجمالي الإيصالات</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="la la-tint"></i></div>
                <div>
                    <div class="stat-value">{{ $fmt($totalQuantity) }}<small>لتر</small></div>
                    <div class="stat-label">إجمالي كمية الصادر</div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="report-card filter-card">
            <div class="report-card-header">
                <h3 class="report-card-title"><i class="la la-search"></i> بحث متقدم بنطاق السندات</h3>
            </div>
            <div class="report-card-body">
                <form action="{{ url('dieselexport/searchtotal') }}" method="POST" class="filter-form">
                    @csrf
                    <div class="filter-field">
                        <label class="filter-label" for="start_voucher">من سند</label>
                        <input name="start_voucher" id="start_voucher" type="text" class="filter-input" placeholder="رقم السند الأول" value="{{ request('start_voucher') }}">
                    </div>
                    <div class="filter-field">
                        <label class="filter-label" for="end_voucher">إلى سند</label>
                        <input name="end_voucher" id="end_voucher" type="text" class="filter-input" placeholder="رقم السند الأخير" value="{{ request('end_voucher') }}">
                    </div>
                    <div class="filter-field">
                        <div class="filter-actions">
                            <button type="submit" class="btn-solid"><i class="la la-search"></i> بحث</button>
                            <a href="{{ url()->current() }}" class="btn-soft"><i class="la la-refresh"></i> إعادة ضبط</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Totals Table -->
        <div class="report-card">
            <div class="report-card-header">
                <h3 class="report-card-title"><i class="la la-table"></i> مجاميع الصادر حسب السند</h3>
                @if($totalVouchers > 0)
                    <span class="report-card-badge">{{ $totalVouchers }} سند</span>
                @endif
            </div>
            <div class="report-card-body">
                @if($totalVouchers > 0)
                    <div class="totals-wrap">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>سند الصادر</th>
                                    <th>عدد الإيصالات</th>
                                    <th>كمية الصادر</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groups as $group)
                                    <tr>
                                        <td><span class="chip">{{ $group->first()->voucher }}</span></td>
                                        <td>{{ $fmt($group->count()) }}</td>
                                        <td class="qty">{{ $fmt($group->sum('quantity')) }} <small>لتر</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>المجموع الكلي</th>
                                    <th>{{ $fmt($totalReceipts) }}</th>
                                    <th>{{ $fmt($totalQuantity) }} لتر</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="la la-inbox"></i>
                        <h4>لا توجد سجلات</h4>
                        <p class="mb-0">لم يتم العثور على أي بيانات صادر تطابق نطاق السندات المحدد.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/features/miscellaneous/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/data-json.js') }}" type="text/javascript"></script>
@endsection