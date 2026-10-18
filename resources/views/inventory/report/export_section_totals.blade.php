@extends('layout.master')

@section('title')
    إجمالي فواتير القسم
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
        .dataTables_wrapper .dataTable th,
        .dataTables_wrapper .dataTable td {
            padding-right: 10px !important;
            margin: 10px !important;
        }

        .dataTables_wrapper .dataTable tfoot th,
        .dataTables_wrapper .dataTable thead th {
            padding-right: 0px;
            padding-left: 0px;
            text-align: right;
        }

        .dataTables_wrapper .dataTable thead th {
            background-color: #E4E6EF;
        }

        .card-body {
            padding: 10px 12px !important;
        }

        .total-highlight {
            font-size: 1.4rem;
            font-weight: bolder;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="flex-wrap pb-0 border-0 card-header">
                <div class="card-title">
                    <h3 class="card-label">إجمالي فواتير القسم</h3>
                </div>
            </div>

            <div class="accordion accordion-light" id="accordionExample5" style="direction: rtl;">
                        <div class="card">
                            <div class="card-header" id="headingOne5">
                                <div class="card-title">
                                    <div data-toggle="collapse" data-target="#collapseOne5"
                                        class="btn btn-primary advance_search font-weight-bolder">
                                        <i class="flaticon-search"></i>بحث متقدم
                                    </div>
                                </div>
                            </div>
                            <div id="collapseOne5" class="pl-5 collapse show" data-parent="#accordionExample5"
                                style="direction: rtl;">
                                @include('inventory.report.partials.export_report_filter', [
                                    'action' => route('export-report.section-totals'),
                                    'showItem' => false,
                                    'method' => 'get',
                                ])
                            </div>
                        </div>
                    </div>

                    <div class="pt-0 card-body">
                        <!-- بطاقات الإجماليات العامة -->
                        <div class="row mb-8">
                            <div class="col-lg-4">
                                <div class="p-6 bg-light text-center rounded">
                                    <div class="text-muted mb-2">عدد الفواتير</div>
                                    <div class="total-highlight">{{ number_format($totals->sum('count')) }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="p-6 bg-light text-center rounded">
                                    <div class="text-muted mb-2">إجمالي الكميات المصروفة</div>
                                    <div class="total-highlight">{{ number_format($grandTotalQty, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="p-6 bg-light text-center rounded">
                                    <div class="text-muted mb-2">إجمالي القيمة الكلي</div>
                                    <div class="total-highlight text-primary">{{ number_format($grandTotalVal, 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <table id="example" class="table table-striped dt-responsive" style="width:100%">
                            <thead class="thead_dark">
                                <tr>
                                    <th>القسم</th>
                                    <th>عدد الفواتير</th>
                                    <th>إجمالي الكمية</th>
                                    <th>إجمالي القيمة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($totals as $total)
                                    <tr>
                                        <td>{{ $total['section_name'] }}</td>
                                        <td>{{ $total['count'] }}</td>
                                        <td>{{ number_format($total['total_qty'], 2) }}</td>
                                        <td>{{ number_format($total['total_value'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">لا توجد فواتير صرف مطابقة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">الإجمالي العام</th>
                                    <th>{{ $totals->sum('count') }}</th>
                                    <th>{{ number_format($grandTotalQty, 2) }}</th>
                                    <th>{{ number_format($grandTotalVal, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endsection

        @section('scripts')
            <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
            <script src="{{ asset('assets/js/pages/features/miscellaneous/sweetalert2.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/js/pages/data-json.js') }}" type="text/javascript"></script>
        @endsection
