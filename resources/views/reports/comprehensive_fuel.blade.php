@extends('layout.master')

@section('title', 'التقرير الشامل للمحروقات')

@section('page_title', 'لوحة التحكم')

@section('sub_main', 'التقارير')

@section('sub_title', 'التقرير الشامل للمحروقات')

@section('css')
    <style>
        .cf-page { --cf-accent: #3699FF; --cf-accent-2: #5B8DEF; }
        .cf-page .cf-hero { box-shadow: 0 10px 30px -12px rgba(54, 153, 255, .6); }
        .cf-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,.08); margin-bottom: 20px; overflow: hidden; }
        .cf-card-header { padding: 20px 25px; border-bottom: 1px solid #e9ecef; background: linear-gradient(180deg, #fff 0%, #f8f9fb 100%); }
        .cf-card-title { font-size: 1.25rem; font-weight: 700; color: #3f4254; margin: 0; display: flex; align-items: center; gap: 10px; }
        .cf-card-title i { color: var(--cf-accent); font-size: 1.5rem; }
        .cf-card-body { padding: 25px; }
        .cf-stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .cf-stat { background: #fff; border: 1px solid #e9ecef; border-radius: 10px; padding: 20px; display: flex; align-items: center; gap: 15px; transition: all .3s; }
        .cf-stat:hover { box-shadow: 0 5px 15px rgba(54, 153, 255, .15); transform: translateY(-2px); }
        .cf-stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; }
        .cf-stat-icon.import { background: rgba(27, 197, 189, .1); color: #1BC5BD; }
        .cf-stat-icon.export { background: rgba(246, 78, 96, .1); color: #F64E60; }
        .cf-stat-icon.balance { background: rgba(54, 153, 255, .1); color: #3699FF; }
        .cf-stat-icon.previous { background: rgba(245, 158, 11, .1); color: #F59E0B; }
        .cf-stat-info h4 { margin: 0 0 5px 0; font-size: .85rem; color: #7e8299; font-weight: 500; }
        .cf-stat-info .value { font-size: 1.5rem; font-weight: 800; color: #3f4254; }
        .cf-table { width: 100%; border-collapse: collapse; }
        .cf-table thead th { background: #2f3447; color: #fff; padding: 15px; text-align: right; font-weight: 600; white-space: nowrap; }
        .cf-table tbody td { padding: 15px; border-bottom: 1px solid #e9ecef; }
        .cf-table tbody tr:nth-child(even) { background: #f8f9fb; }
        .cf-table tbody tr:hover { background: #e9ecef; }
        .cf-table .import { color: #1BC5BD; font-weight: 700; }
        .cf-table .export { color: #F64E60; font-weight: 700; }
        .cf-table .balance { color: #3699FF; font-weight: 700; }
        .cf-table .positive { color: #1BC5BD; }
        .cf-table .negative { color: #F64E60; }
        .cf-summary-row { background: linear-gradient(180deg, #3699FF 0%, #5B8DEF 100%) !important; color: #fff; font-weight: 700; }
        .cf-summary-row:hover { background: linear-gradient(180deg, #2a7fd1 0%, #4a73cf 100%) !important; }
        .cf-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .cf-btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all .3s; }
        .cf-btn-primary { background: var(--cf-accent); color: #fff; }
        .cf-btn-primary:hover { background: var(--cf-accent-2); }
        .cf-btn-secondary { background: #fff; color: #3f4254; border: 1px solid #e9ecef; }
        .cf-btn-secondary:hover { background: #f8f9fb; }
        @media print {
            .cf-actions { display: none; }
        }
    </style>
@endsection

@section('content')
    <div class="container cf-page">

        {{-- ===== Header ===== --}}
        <div class="cf-hero" style="padding: 30px; background: linear-gradient(135deg, #3699FF 0%, #5B8DEF 100%); border-radius: 16px; margin-bottom: 30px; color: #fff;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span style="font-size: 3rem; margin-left: 20px;"><i class="la la-chart-bar"></i></span>
                    <div>
                        <h2 style="margin: 0; font-size: 1.75rem;">التقرير الشامل للمحروقات</h2>
                        <p style="margin: 5px 0 0 0; opacity: 0.9;">حركة وارد وصادر المحروقات لكل الأنواع للسنة {{ $currentYear }}</p>
                    </div>
                </div>
                <div class="cf-actions">
                    <button type="button" class="cf-btn cf-btn-secondary" onclick="window.print()">
                        <i class="la la-print"></i> طباعة
                    </button>
                    <a href="{{ route('diesel.report') }}" class="cf-btn cf-btn-secondary">
                        <i class="la la-arrow-right"></i> تقارير الوارد
                    </a>
                    <a href="{{ route('dieselexport.report') }}" class="cf-btn cf-btn-secondary">
                        <i class="la la-arrow-right"></i> تقارير الصادر
                    </a>
                </div>
            </div>
        </div>

        {{-- ===== Filters ===== --}}
        <div class="cf-card">
            <div class="cf-card-header">
                <h3 class="cf-card-title"><i class="la la-filter"></i> البحث المتقدم</h3>
            </div>
            <div class="cf-card-body">
                @if(request()->filled('typesfuel_id') || request()->filled('start_date') || request()->filled('end_date'))
                    <div class="alert alert-info mb-3">
                        <strong>الفلاتر النشطة:</strong>
                        @if(request()->filled('typesfuel_id'))
                            <span class="badge badge-primary ml-2">نوع المحروقات: {{ $typesFuel->firstWhere('id', request('typesfuel_id'))->name ?? '' }}</span>
                        @endif
                        @if(request()->filled('start_date'))
                            <span class="badge badge-primary ml-2">من: {{ request('start_date') }}</span>
                        @endif
                        @if(request()->filled('end_date'))
                            <span class="badge badge-primary ml-2">إلى: {{ request('end_date') }}</span>
                        @endif
                    </div>
                @endif
                <form action="{{ route('reports.comprehensive-fuel') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>نوع المحروقات</label>
                                <select name="typesfuel_id" class="form-control">
                                    <option value="">جميع الأنواع</option>
                                    @foreach ($typesFuel as $type)
                                        <option value="{{ $type->id }}" {{ request('typesfuel_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>من تاريخ</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>إلى تاريخ</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="la la-search"></i> بحث
                                    </button>
                                    <a href="{{ route('reports.comprehensive-fuel') }}" class="btn btn-secondary">
                                        <i class="la la-undo"></i> إعادة
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===== Overall Statistics ===== --}}
        <div class="cf-card">
            <div class="cf-card-header">
                <h3 class="cf-card-title"><i class="la la-chart-pie"></i> الإحصائيات الإجمالية</h3>
            </div>
            <div class="cf-card-body">
                <div class="cf-stat-grid">
                    <div class="cf-stat">
                        <div class="cf-stat-icon import"><i class="la la-truck-loading"></i></div>
                        <div class="cf-stat-info">
                            <h4>إجمالي الوارد</h4>
                            <div class="value">{{ number_format($totalImported, 2) }} <small>لتر</small></div>
                        </div>
                    </div>
                    <div class="cf-stat">
                        <div class="cf-stat-icon export"><i class="la la-sign-out-alt"></i></div>
                        <div class="cf-stat-info">
                            <h4>إجمالي الصادر</h4>
                            <div class="value">{{ number_format($totalExported, 2) }} <small>لتر</small></div>
                        </div>
                    </div>
                    <div class="cf-stat">
                        <div class="cf-stat-icon previous"><i class="la la-history"></i></div>
                        <div class="cf-stat-info">
                            <h4>الرصيد السابق</h4>
                            <div class="value {{ $totalPreviousBalance < 0 ? 'negative' : 'positive' }}">{{ number_format($totalPreviousBalance, 2) }} <small>لتر</small></div>
                        </div>
                    </div>
                    <div class="cf-stat">
                        <div class="cf-stat-icon balance"><i class="la la-balance-scale"></i></div>
                        <div class="cf-stat-info">
                            <h4>الرصيد الحالي</h4>
                            <div class="value {{ $totalCurrentBalance < 0 ? 'negative' : 'positive' }}">{{ number_format($totalCurrentBalance, 2) }} <small>لتر</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Detailed Table ===== --}}
        <div class="cf-card">
            <div class="cf-card-header">
                <h3 class="cf-card-title"><i class="la la-table"></i> تفاصيل حركة المحروقات حسب النوع</h3>
            </div>
            <div class="cf-card-body">
                <table class="cf-table">
                    <thead>
                        <tr>
                            <th>نوع المحروقات</th>
                            <th>الوارد</th>
                            <th>عدد سندات الوارد</th>
                            <th>الصادر</th>
                            <th>عدد سندات الصادر</th>
                            <th>الرصيد السابق</th>
                            <th>الرصيد الحالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($typesFuel as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td class="import">{{ number_format($importData[$type->id]['total_imported'] ?? 0, 2) }} لتر</td>
                                <td>{{ $importData[$type->id]['entries_count'] ?? 0 }}</td>
                                <td class="export">{{ number_format($exportData[$type->id]['total_exported'] ?? 0, 2) }} لتر</td>
                                <td>{{ $exportData[$type->id]['entries_count'] ?? 0 }}</td>
                                <td class="{{ $previousBalanceByType[$type->id] < 0 ? 'negative' : 'positive' }}">
                                    {{ number_format($previousBalanceByType[$type->id] ?? 0, 2) }} لتر
                                </td>
                                <td class="{{ $currentBalanceByType[$type->id] < 0 ? 'negative' : 'positive' }}">
                                    {{ number_format($currentBalanceByType[$type->id] ?? 0, 2) }} لتر
                                </td>
                            </tr>
                        @endforeach
                        <tr class="cf-summary-row">
                            <td>الإجمالي</td>
                            <td>{{ number_format($totalImported, 2) }} لتر</td>
                            <td>{{ array_sum(array_column($importData, 'entries_count')) }}</td>
                            <td>{{ number_format($totalExported, 2) }} لتر</td>
                            <td>{{ array_sum(array_column($exportData, 'entries_count')) }}</td>
                            <td>{{ number_format($totalPreviousBalance, 2) }} لتر</td>
                            <td>{{ number_format($totalCurrentBalance, 2) }} لتر</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
