@extends('layout.master')

@section('title')
    لوحة المتابعة - المشتريات
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المشتريات
@endsection
@section('sub_title')
    لوحة المتابعة
@endsection

@section('content')
    @php
        $statusLabels = config('purchase.status_labels');
        $monthlyTotals = $monthlyStats->sum('total');
        $monthlyApproved = $monthlyStats->sum('approved');
        $monthlyRejected = $monthlyStats->sum('rejected');
    @endphp

    <div class="container-fluid">
        <div class="card card-custom shadow-sm border-0">
            <div class="card-header border-0 px-6 py-5"
                style="background: linear-gradient(135deg, #f5f9ff 0%, #eef5ff 100%);">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between w-100">
                    <div>
                        <h3 class="card-title text-dark font-weight-bolder mb-1">لوحة المتابعة - المشتريات</h3>
                        <p class="text-muted mb-0">نظرة سريعة على مؤشرات الطلبات والأنشطة اليومية</p>
                    </div>
                    <div class="d-flex flex-wrap justify-content-end align-items-center mt-3 mt-md-0">
                        <a href="{{ route('purchase-report.export-excel') }}"
                            class="btn btn-light-success font-weight-bolder mr-2 mb-2 mb-md-0" title="تصدير الطلبات Excel">
                            <i class="la la-file-excel-o"></i> طلبات Excel
                        </a>
                        <a href="{{ route('purchase-report.export-pdf') }}"
                            class="btn btn-light-danger font-weight-bolder mr-2 mb-2 mb-md-0" title="تصدير الطلبات PDF">
                            <i class="la la-file-pdf-o"></i> طلبات PDF
                        </a>
                        <a href="{{ route('purchase-report.export-orders-excel') }}"
                            class="btn btn-light-warning font-weight-bolder mr-2 mb-2 mb-md-0"
                            title="تصدير أوامر الشراء Excel">
                            <i class="la la-file-excel-o"></i> أوامر Excel
                        </a>
                        <a href="{{ route('purchase-report.export-orders-pdf') }}"
                            class="btn btn-light-primary font-weight-bolder mr-2 mb-2 mb-md-0"
                            title="تصدير أوامر الشراء PDF">
                            <i class="la la-file-pdf-o"></i> أوامر PDF
                        </a>
                        <button onclick="window.print()" class="btn btn-light-info font-weight-bolder mr-2 mb-2 mb-md-0">
                            <i class="la la-print"></i> طباعة
                        </button>
                        <a href="{{ route('purchase-report.index') }}"
                            class="btn btn-light-primary font-weight-bolder mb-2 mb-md-0">
                            <i class="la la-arrow-right"></i> العودة للتقارير
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body px-6 py-6">

                @if (session('warning'))
                    <div class="alert alert-warning rounded-pill border-0">{{ session('warning') }}</div>
                @endif

                <div class="row mb-6">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted font-weight-bold">إجمالي الطلبات</span>
                                    <span class="symbol symbol-40 symbol-light-primary">
                                        <i class="la la-shopping-cart text-primary"></i>
                                    </span>
                                </div>
                                <h2 class="display-4 font-weight-bolder text-dark mb-1">{{ $monthlyTotals }}</h2>
                                <p class="text-muted mb-0">خلال آخر 12 شهرًا</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted font-weight-bold">الموافق عليها</span>
                                    <span class="symbol symbol-40 symbol-light-success">
                                        <i class="la la-check text-success"></i>
                                    </span>
                                </div>
                                <h2 class="display-4 font-weight-bolder text-success mb-1">{{ $monthlyApproved }}</h2>
                                <p class="text-muted mb-0">تمت الموافقة عليها بنجاح</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted font-weight-bold">المرفوضة</span>
                                    <span class="symbol symbol-40 symbol-light-danger">
                                        <i class="la la-times text-danger"></i>
                                    </span>
                                </div>
                                <h2 class="display-4 font-weight-bolder text-danger mb-1">{{ $monthlyRejected }}</h2>
                                <p class="text-muted mb-0">طلبات تم رفضها</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-6">
                    <div class="card-header border-0 bg-white">
                        <h3 class="card-title text-dark font-weight-bolder">مطابقة SLA (اتفاقية مستوى الخدمة)</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($slaCompliance as $status => $data)
                                @php
                                    $label = $statusLabels[$status] ?? $status;
                                    $complianceColor =
                                        $data['compliance_rate'] >= 80
                                            ? 'success'
                                            : ($data['compliance_rate'] >= 50
                                                ? 'warning'
                                                : 'danger');
                                    $statusBadge =
                                        $data['compliance_rate'] >= 80
                                            ? 'ممتاز'
                                            : ($data['compliance_rate'] >= 50
                                                ? 'جيد'
                                                : 'needs-attention');
                                @endphp
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <div class="card card-stretch border-0 bg-light h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-4">
                                                <div>
                                                    <div class="text-muted font-weight-bold">{{ $label }}</div>
                                                    <div
                                                        class="text-{{ $complianceColor }} font-weight-bolder display-4 mt-2">
                                                        {{ $data['compliance_rate'] }}%
                                                    </div>
                                                </div>
                                                <span
                                                    class="label label-inline label-light-{{ $complianceColor }} rounded-pill px-3 py-2">
                                                    {{ $statusBadge == 'needs-attention' ? 'يحتاج متابعة' : $statusBadge }}
                                                </span>
                                            </div>
                                            <div class="separator separator-dashed my-3"></div>
                                            <div class="d-flex justify-content-between text-muted small">
                                                <span>إجمالي</span>
                                                <span class="font-weight-bolder text-dark">{{ $data['total'] }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between text-muted small mt-2">
                                                <span>متأخر</span>
                                                <span class="font-weight-bolder text-dark">{{ $data['overdue'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-6">
                    <div class="card-header border-0 bg-white">
                        <h3 class="card-title text-dark font-weight-bolder">إحصائيات شهرية (آخر 12 شهر)</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-head-custom table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>الشهر</th>
                                        <th>إجمالي الطلبات</th>
                                        <th>تمت الموافقة</th>
                                        <th>مرفوض</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monthlyStats as $stat)
                                        @php
                                            $monthName = \Carbon\Carbon::create($stat->year, $stat->month, 1)->locale(
                                                'ar',
                                            )->monthName;
                                        @endphp
                                        <tr>
                                            <td class="font-weight-bolder text-dark">{{ $monthName }}
                                                {{ $stat->year }}</td>
                                            <td>{{ $stat->total }}</td>
                                            <td class="text-success font-weight-bolder">{{ $stat->approved }}</td>
                                            <td class="text-danger font-weight-bolder">{{ $stat->rejected }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header border-0 bg-white">
                                <h3 class="card-title text-dark font-weight-bolder">أكثر مقدمي الطلبات</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-head-custom table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>مقدم الطلب</th>
                                                <th>عدد الطلبات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topRequesters as $requester)
                                                <tr>
                                                    <td class="font-weight-bolder text-dark">
                                                        {{ $requester->requester_name }}</td>
                                                    <td>{{ $requester->count }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header border-0 bg-white">
                                <h3 class="card-title text-dark font-weight-bolder">توزيع الحالات</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-head-custom table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>الحالة</th>
                                                <th>العدد</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($statusDistribution as $status)
                                                <tr>
                                                    <td class="font-weight-bolder text-dark">
                                                        {{ $statusLabels[$status->status] ?? $status->status }}</td>
                                                    <td>{{ $status->count }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 bg-white">
                        <h3 class="card-title text-dark font-weight-bolder">النشاط الأخير</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-head-custom table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>رقم الطلب</th>
                                        <th>من</th>
                                        <th>إلى</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentActivity as $activity)
                                        @php
                                            $fromLabel =
                                                $statusLabels[$activity->from_status] ?? $activity->from_status;
                                            $toLabel = $statusLabels[$activity->to_status] ?? $activity->to_status;
                                        @endphp
                                        <tr>
                                            <td class="font-weight-bolder text-dark">
                                                {{ $activity->purchase->serial_number ?? 'N/A' }}</td>
                                            <td>{{ $fromLabel }}</td>
                                            <td>{{ $toLabel }}</td>
                                            <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
