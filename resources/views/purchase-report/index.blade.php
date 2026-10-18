@extends('layout.master')

@section('title')
    تقارير المشتريات
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المشتريات
@endsection
@section('sub_title')
    التقارير
@endsection

@section('css')
    <style>
        .report-hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8e 50%, #3a7bd5 100%);
            border-radius: 12px;
            padding: 28px 30px;
            margin-bottom: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(30, 58, 95, 0.25);
        }

        .report-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .report-hero::after {
            content: '';
            position: absolute;
            bottom: -60%;
            right: 10%;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .report-hero h2 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }

        .report-hero p {
            opacity: 0.85;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }

        .report-hero .btn-hero {
            background: #fff;
            color: #1e3a5f;
            font-weight: 700;
            border-radius: 8px;
            padding: 10px 22px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            margin-right: 8px;
        }

        .report-hero .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            color: #1e3a5f;
        }

        .toolbar-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 1px solid #eef0f3;
            padding: 14px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .toolbar-card .toolbar-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e3a5f;
            margin: 0;
        }

        .toolbar-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-export {
            border-radius: 8px;
            font-weight: 600;
            padding: 8px 16px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .btn-excel {
            background: linear-gradient(135deg, #107c41, #185C37);
            color: #fff;
            border: none;
        }

        .btn-excel:hover {
            color: #fff;
            box-shadow: 0 4px 12px rgba(16, 124, 65, 0.3);
        }

        .btn-pdf {
            background: linear-gradient(135deg, #d32f2f, #b71c1c);
            color: #fff;
            border: none;
        }

        .btn-pdf:hover {
            color: #fff;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        .btn-print {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            color: #fff;
            border: none;
        }

        .btn-print:hover {
            color: #fff;
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }

        .card-custom {
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: none;
        }

        .card-custom .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #eef0f3;
            border-radius: 12px 12px 0 0 !important;
            padding: 18px 24px;
        }

        .card-custom .card-header .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e3a5f;
        }

        .stat-box {
            border-radius: 10px;
            padding: 18px 20px;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-box .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
            position: absolute;
            bottom: 8px;
            left: 12px;
        }

        .stat-box .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .stat-box .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-top: 2px;
        }

        .stat-total {
            background: linear-gradient(135deg, #1e3a5f, #3a7bd5);
        }

        .stat-pending {
            background: linear-gradient(135deg, #f39c12, #f1c40f);
        }

        .stat-approved {
            background: linear-gradient(135deg, #0bb783, #1bc5bd);
        }

        .stat-rejected {
            background: linear-gradient(135deg, #f64e60, #e63946);
        }

        .stat-withdrawn {
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .card-body {
            padding: 20px 24px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Hero Banner -->
        <div class="report-hero d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h2><i class="la la-bar-chart"></i> تقارير المشتريات</h2>
                <p>إحصائيات شاملة عن طلبات الشراء وأوامر الشراء مع إمكانية الطباعة والتصدير</p>
            </div>
            <div>
                <a href="{{ url('/purchase') }}" class="btn btn-hero">
                    <i class="la la-arrow-right"></i> العودة للقائمة
                </a>
                <a href="{{ route('purchase-report.dashboard') }}" class="btn btn-hero">
                    <i class="la la-dashboard"></i> لوحة المتابعة
                </a>
            </div>
        </div>

        <!-- Export Toolbar -->
        <div class="toolbar-card">
            <h3 class="toolbar-title">
                <i class="la la-download"></i> تصدير التقارير
            </h3>
            <div class="toolbar-actions">
                <a href="{{ route('purchase-report.export-excel') }}" class="btn btn-export btn-excel">
                    <i class="la la-file-excel-o"></i> تصدير الطلبات Excel
                </a>
                <a href="{{ route('purchase-report.export-pdf') }}" class="btn btn-export btn-pdf">
                    <i class="la la-file-pdf-o"></i> تصدير الطلبات PDF
                </a>
                <a href="{{ route('purchase-report.export-orders-excel') }}" class="btn btn-export btn-excel">
                    <i class="la la-file-excel-o"></i> تصدير أوامر الشراء Excel
                </a>
                <a href="{{ route('purchase-report.export-orders-pdf') }}" class="btn btn-export btn-pdf">
                    <i class="la la-file-pdf-o"></i> تصدير أوامر الشراء PDF
                </a>
                <button onclick="window.print()" class="btn btn-export btn-print">
                    <i class="la la-print"></i> طباعة الصفحة
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="stat-box stat-total">
                    <div class="stat-number">{{ $totalPurchases }}</div>
                    <div class="stat-label">إجمالي الطلبات</div>
                    <i class="la la-list-alt stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="stat-box stat-pending">
                    <div class="stat-number">{{ $pendingPurchases }}</div>
                    <div class="stat-label">قيد الانتظار</div>
                    <i class="la la-clock stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="stat-box stat-approved">
                    <div class="stat-number">{{ $approvedPurchases }}</div>
                    <div class="stat-label">تمت الموافقة</div>
                    <i class="la la-check-circle stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="stat-box stat-rejected">
                    <div class="stat-number">{{ $rejectedPurchases + $withdrawnPurchases }}</div>
                    <div class="stat-label">مرفوض / مسحوب</div>
                    <i class="la la-times-circle stat-icon"></i>
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="row mt-8">
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title"><i class="la la-pie-chart"></i> توزيع حالات الطلبات</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>الحالة</th>
                                        <th>العدد</th>
                                        <th>النسبة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $statusLabels = config('purchase.status_labels');
                                        $total = $totalPurchases;
                                    @endphp
                                    @foreach ($statusBreakdown as $status)
                                        @php
                                            $label = $statusLabels[$status->status] ?? $status->status;
                                            $percentage = $total > 0 ? round(($status->count / $total) * 100, 2) : 0;
                                            $barColor = match ($status->status) {
                                                'pending' => 'warning',
                                                'purchase_order' => 'primary',
                                                'tender_committee' => 'info',
                                                'rent_repair' => 'secondary',
                                                'rejected' => 'danger',
                                                'approved_stock' => 'success',
                                                default => 'dark',
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td><span class="font-weight-bolder">{{ $status->count }}</span></td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-{{ $barColor }}" role="progressbar"
                                                        style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                                        aria-valuemin="0" aria-valuemax="100">
                                                        {{ $percentage }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-custom">
                    <div class="card-header">
                        <h3 class="card-title"><i class="la la-link"></i> روابط سريعة</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('purchase-report.dashboard') }}"
                                class="btn btn-light-info btn-block font-weight-bolder">
                                <i class="la la-dashboard"></i> لوحة المتابعة التفصيلية
                            </a>
                        </div>
                        <div class="mb-3">
                            <a href="{{ url('/purchase') }}" class="btn btn-light-primary btn-block font-weight-bolder">
                                <i class="la la-list"></i> عرض جميع الطلبات
                            </a>
                        </div>
                        <div class="mb-3">
                            <a href="{{ url('/purchase-order') }}"
                                class="btn btn-light-success btn-block font-weight-bolder">
                                <i class="la la-file-text"></i> عرض أوامر الشراء
                            </a>
                        </div>
                        <div class="mb-3">
                            <a href="{{ route('purchase-report.export-excel') }}"
                                class="btn btn-light-warning btn-block font-weight-bolder">
                                <i class="la la-file-excel-o"></i> تنزيل تقرير Excel
                            </a>
                        </div>
                        <div class="mb-3">
                            <a href="{{ route('purchase-report.export-pdf') }}"
                                class="btn btn-light-danger btn-block font-weight-bolder">
                                <i class="la la-file-pdf-o"></i> تنزيل تقرير PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
