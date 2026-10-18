@extends('layout.master')

@section('title')
    تقرير المصروفات حسب الصنف
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
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="flex-wrap pb-0 border-0 card-header">
                <div class="card-title">
                    <h3 class="card-label">تقرير المصروفات حسب الصنف</h3>
                </div>
            </div>

            <!-- البحث المتقدم -->
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
                    <div id="collapseOne5" class="pl-5 collapse show" data-parent="#accordionExample5" style="direction: rtl;">
                        @include('inventory.report.partials.export_report_filter', [
                            'action' => route('export-report.items'),
                            'showItem' => true,
                            'method' => 'get',
                        ])
                    </div>
                </div>
            </div>

            <div class="pt-0 card-body">
                <table id="example" class="table table-striped dt-responsive" style="width:100%">
                    <thead class="thead_dark">
                        <tr>
                            <th>رقم الصنف</th>
                            <th>الصنف</th>
                            <th>رقم الفاتورة</th>
                            <th>رقم السند</th>
                            <th>التاريخ</th>
                            <th>الكمية</th>
                            <th>القسم الرئيسي</th>
                            <th>القسم الفرعي</th>
                            <th>متوسط الكلفة</th>
                            <th>القيمة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $transaction)
                            <tr>
                                <td>{{ $transaction->item_num }}</td>
                                <td>{{ $transaction->item_name }}</td>
                                <td>{{ $transaction->invoice_no }}</td>
                                <td>{{ $transaction->voucher_no }}</td>
                                <td>{{ $transaction->voucher_date }}</td>
                                <td>{{ $transaction->quantity }}</td>
                                <td>{{ $transaction->section_name }}</td>
                                <td>{{ $transaction->sub_section_name }}</td>
                                <td>{{ number_format($transaction->avg_cost, 2) }}</td>
                                <td>{{ number_format($transaction->value, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">لا توجد حركات صرف مطابقة</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-center">الإجمالي</th>
                            <th>{{ number_format($rows->sum('quantity'), 2) }}</th>
                            <th colspan="3"></th>
                            <th>{{ number_format($rows->sum('value'), 2) }}</th>
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