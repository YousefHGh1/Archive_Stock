@extends('layout.master')

@section('title')
    تقارير فواتير الصرف
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    الأصناف
@endsection
@section('sub_title')
    تقارير الصرف
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
        }
        .report-card-link:hover {
            box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.08);
            transform: translateY(-2px);
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="flex-wrap pb-0 border-0 card-header">
                <div class="card-title">
                    <h3 class="card-label">تقارير فواتير الصرف</h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('reports.index') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-th-large"></i> عرض جميع التقارير
                    </a>
                </div>
            </div>
            <div class="pt-0 card-body">
                <div class="alert alert-info d-flex align-items-center">
                    <i class="la la-info-circle font-size-h2 mr-3"></i>
                    <div>
                        <strong>ملاحظة:</strong> يمكنك الوصول إلى جميع تقارير الشراء والصرف من <a href="{{ route('reports.index') }}" class="alert-link">صفحة التقارير الموحدة</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 mb-8">
                        <a href="{{ route('export-report.items') }}" class="d-block p-8 bg-light report-card-link text-dark">
                            <h4 class="font-weight-bolder mb-3">1. المصروفات حسب الصنف</h4>
                            <p class="mb-0 text-muted">عرض جميع عمليات الصرف لصنف معيّن مع تفاصيل كل عملية (التاريخ، الكمية، القسم المستفيد، رقم الفاتورة).</p>
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-6 mb-8">
                        <a href="{{ route('export-report.section') }}" class="d-block p-8 bg-light report-card-link text-dark">
                            <h4 class="font-weight-bolder mb-3">2. المصروفات حسب القسم</h4>
                            <p class="mb-0 text-muted">عرض ما صُرف لقسم معيّن خلال فترة محّدة: الأصناف والكميات والتواريخ والقيم.</p>
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-6 mb-8">
                        <a href="{{ route('export-report.invoice-values') }}" class="d-block p-8 bg-light report-card-link text-dark">
                            <h4 class="font-weight-bolder mb-3">3. قيمة كل فاتورة لكل قسم</h4>
                            <p class="mb-0 text-muted">تفصيل كل فاتورة صرف مرتبطة بقسم معيّن مع بيان القيمة الإجمالية لكل فاتورة.</p>
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-6 mb-8">
                        <a href="{{ route('export-report.section-totals') }}" class="d-block p-8 bg-light report-card-link text-dark">
                            <h4 class="font-weight-bolder mb-3">4. إجمالي فواتير القسم</h4>
                            <p class="mb-0 text-muted">المجموع الكلي لقيم فواتير الصرف لكل قسم (كليًا أو ضمن فترة زمنية).</p>
                        </a>
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
@endsection