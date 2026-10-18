@extends('layout.master')

@section('title')
    المشتريات حسب الصنف
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    الأصناف
@endsection
@section('sub_title')
    تقارير الشراء
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
                    <h3 class="card-label">تقرير المشتريات حسب الصنف</h3>
                </div>
                @include('inventory.report.partials.report_export_buttons')
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
                    <div id="collapseOne5" class="pl-5 collapse show" data-parent="#accordionExample5" style="direction: rtl;">
                        @include('inventory.report.partials.purchase_report_filter', [
                            'action' => route('invoice-report.items'),
                            'showItem' => true,
                            'method' => 'get',
                        ])
                    </div>
                </div>
            </div>

            <div class="pt-0 card-body">
                @include('inventory.report.partials.purchase_value_note')
                <table id="example" class="table table-striped dt-responsive" style="width:100%">
                    <thead class="thead_dark">
                        <tr>
                            <th>رقم الصنف</th>
                            <th>الصنف</th>
                            <th>المورد</th>
                            <th>رقم الفاتورة</th>
                            <th>رقم السند</th>
                            <th>التاريخ</th>
                            <th>الكمية</th>
                            <th>سعر الوحدة بعد تحويل العملة</th>
                            <th>الإجمالي بعد تحويل العملة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $transaction)
                            <tr>
                                <td>{{ $transaction->item_num }}</td>
                                <td>{{ $transaction->item_name }}</td>
                                <td>{{ $transaction->supplier_name }}</td>
                                <td>{{ $transaction->invoice_no }}</td>
                                <td>{{ $transaction->voucher_no }}</td>
                                <td>{{ $transaction->voucher_date }}</td>
                                <td>{{ $transaction->quantity }}</td>
                                <td>{{ number_format((float) $transaction->price * (float) ($transaction->currency_value_at_time ?? 1), 2) }}</td>
                                <td class="font-weight-bolder">{{ number_format($transaction->total_price, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">لا توجد حركات شراء مطابقة</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-center">الإجمالي العام</th>
                            <th>{{ number_format($rows->sum('quantity'), 2) }}</th>
                            <th></th>
                            <th>{{ number_format($rows->sum('total_price'), 2) }}</th>
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