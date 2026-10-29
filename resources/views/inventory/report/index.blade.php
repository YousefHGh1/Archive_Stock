@extends('layout.master')

@section('title')
    التقارير الموحدة
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    الأصناف
@endsection
@section('sub_title')
    التقارير
@endsection

@section('css')
    <link href="{{ asset('assets/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.foundation.min.css') }}">
    <style>
        .report-card-link {
            transition: all .2s ease;
            border: 1px solid #E4E6EF;
            border-radius: .65rem;
            min-height: 120px;
            background: #F3F6F9;
        }
        .report-card-link:hover {
            box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.08);
            transform: translateY(-2px);
            background: #E1F0FF;
        }

        .nav-tabs .nav-link {
            font-weight: 600;
            color: #7E8299;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 1rem 1.5rem;
            margin-bottom: -1px;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            color: #3699FF;
            background: #F3F6F9;
        }

        .nav-tabs .nav-link.active {
            color: #3699FF;
            background: #ffffff;
            border-bottom: 3px solid #3699FF;
        }

        .tab-content {
            background: #ffffff;
            border-radius: 0.65rem;
            padding: 2rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        .report-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #3699FF;
        }

        .report-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #3699FF;
            margin-bottom: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="flex-wrap pb-0 border-0 card-header">
                <div class="card-title">
                    <h3 class="card-label">تقارير المخزون</h3>
                </div>
            </div>
            <div class="pt-0 card-body">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase-reports" type="button" role="tab" aria-controls="purchase-reports" aria-selected="true">
                            <i class="la la-arrow-down"></i> تقارير الشراء
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="export-tab" data-bs-toggle="tab" data-bs-target="#export-reports" type="button" role="tab" aria-controls="export-reports" aria-selected="false">
                            <i class="la la-arrow-up"></i> تقارير الصرف
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="reportTabsContent">
                    <!-- Purchase Reports Tab -->
                    <div class="tab-pane fade show active" id="purchase-reports" role="tabpanel" aria-labelledby="purchase-tab">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 mb-8">
                                <a href="{{ route('invoice-report.items') }}" class="d-block p-8 report-card-link text-dark">
                                    <div class="report-number">1</div>
                                    <div class="report-icon">
                                        <i class="la la-box"></i>
                                    </div>
                                    <h4 class="font-weight-bolder mb-3">المشتريات حسب الصنف</h4>
                                    <p class="mb-0 text-muted">جميع عمليات شراء صنف معيّن بين تاريخين مع المورد والسعر وإجمالي سعر كل صنف.</p>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-8">
                                <a href="{{ route('invoice-report.supplier') }}" class="d-block p-8 report-card-link text-dark">
                                    <div class="report-number">2</div>
                                    <div class="report-icon">
                                        <i class="la la-user"></i>
                                    </div>
                                    <h4 class="font-weight-bolder mb-3">المشتريات حسب المورد</h4>
                                    <p class="mb-0 text-muted">كل ما تم شراؤه من مورد معيّن خلال فترة: الأصناف والكميات والتواريخ والإجماليات.</p>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-8">
                                <a href="{{ route('invoice-report.invoice-values') }}" class="d-block p-8 report-card-link text-dark">
                                    <div class="report-number">3</div>
                                    <div class="report-icon">
                                        <i class="la la-file-text"></i>
                                    </div>
                                    <h4 class="font-weight-bolder mb-3">قيمة كل فاتورة لكل مورد</h4>
                                    <p class="mb-0 text-muted">تفصيل كل فاتورة شراء مرتبطة بمورد معيّن مع القيمة الإجمالية لكل فاتورة.</p>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-8">
                                <a href="{{ route('invoice-report.supplier-totals') }}" class="d-block p-8 report-card-link text-dark">
                                    <div class="report-number">4</div>
                                    <div class="report-icon">
                                        <i class="la la-calculator"></i>
                                    </div>
                                    <h4 class="font-weight-bolder mb-3">إجمالي فواتير المورد</h4>
                                    <p class="mb-0 text-muted">المجموع الكلي لقيم فواتير الشراء لكل مورد (كليًا أو بين تاريخين).</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Export Reports Tab -->
                    <div class="tab-pane fade" id="export-reports" role="tabpanel" aria-labelledby="export-tab">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 mb-8">
                                <a href="{{ route('export-report.items') }}" class="d-block p-8 report-card-link text-dark">
                                    <div class="report-number">1</div>
                                    <div class="report-icon">
                                        <i class="la la-box"></i>
                                    </div>
                                    <h4 class="font-weight-bolder mb-3">المصروفات حسب الصنف</h4>
                                    <p class="mb-0 text-muted">عرض جميع عمليات الصرف لصنف معيّن مع تفاصيل كل عملية (التاريخ، الكمية، القسم المستفيد، رقم الفاتورة).</p>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-8">
                                <a href="{{ route('export-report.section') }}" class="d-block p-8 report-card-link text-dark">
                                    <div class="report-number">2</div>
                                    <div class="report-icon">
                                        <i class="la la-building"></i>
                                    </div>
                                    <h4 class="font-weight-bolder mb-3">المصروفات حسب القسم</h4>
                                    <p class="mb-0 text-muted">عرض ما صُرف لقسم معيّن خلال فترة محّدة: الأصناف والكميات والتواريخ والقيم.</p>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-8">
                                <a href="{{ route('export-report.invoice-values') }}" class="d-block p-8 report-card-link text-dark">
                                    <div class="report-number">3</div>
                                    <div class="report-icon">
                                        <i class="la la-file-text"></i>
                                    </div>
                                    <h4 class="font-weight-bolder mb-3">قيمة كل فاتورة لكل قسم</h4>
                                    <p class="mb-0 text-muted">تفصيل كل فاتورة صرف مرتبطة بقسم معيّن مع بيان القيمة الإجمالية لكل فاتورة.</p>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-6 mb-8">
                                <a href="{{ route('export-report.section-totals') }}" class="d-block p-8 report-card-link text-dark">
                                    <div class="report-number">4</div>
                                    <div class="report-icon">
                                        <i class="la la-calculator"></i>
                                    </div>
                                    <h4 class="font-weight-bolder mb-3">إجمالي فواتير القسم</h4>
                                    <p class="mb-0 text-muted">المجموع الكلي لقيم فواتير الصرف لكل قسم (كليًا أو ضمن فترة زمنية).</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/features/miscellaneous/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/data-json.js') }}" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bootstrap 4 tabs functionality
            $('#reportTabs button').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Show first tab active by default
            $('#purchase-tab').tab('show');
        });
    </script>
@endsection
