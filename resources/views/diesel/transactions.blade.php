@extends('layout.master')

@section('title', 'تقرير حركة الوارد والصادر')

@section('page_title', 'لوحة التحكم')

@section('sub_main', 'التقارير')

@section('sub_title', 'حركة الوارد والصادر - ' . ($fuelType->name ?? 'N/A'))

@section('css')
    <link href="{{ asset('assets/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.foundation.min.css') }}">
    <style>
        .dataTables_wrapper .dataTable th,
        .dataTables_wrapper .dataTable td {
            padding-right: 50px !important;
            margin: 10px !important;
        }

        .dataTables_wrapper .dataTable tfoot th,
        .dataTables_wrapper .dataTable thead th {
            padding-right: 0px;
            padding-left: 0px;
            text-align: right;
        }

        div.dt-buttons {
            top: 50%;
            left: 8%;
            width: 200px;
            margin-left: -100px;
            margin-top: -20px;
            text-align: center;
            padding: 1rem 0
        }

        div.dt-buttons .dt-button {
            margin: -2px !important;
        }

        .dataTables_wrapper .dataTable thead th {
            background-color: #E4E6EF;
        }

        .transaction-import {
            background-color: #e8f5e9;
        }

        .transaction-export {
            background-color: #ffebee;
        }

        .summary-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-import {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
            color: white;
        }

        .summary-export {
            background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
            color: white;
        }

        .summary-balance {
            background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 100%);
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="flex-wrap pt-5 pb-0 border-0 card-header">
                <div class="card-title">
                    <h3 class="card-label">تقرير حركة الوارد والصادر - {{ $fuelType->name ?? 'N/A' }}</h3>
                </div>
                <div style="direction: ltr;" class="pt-5">
                    <a class="px-6 btn btn-light-primary font-weight-bold" href="{{ route('diesel.index') }}">رجوع للرئيسية</a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="summary-card summary-import">
                            <h4>إجمالي الوارد</h4>
                            <h2>{{ number_format($totalImports) }} لتر</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-card summary-export">
                            <h4>إجمالي الصادر</h4>
                            <h2>{{ number_format($totalExports) }} لتر</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-card summary-balance">
                            <h4>الرصيد الحالي</h4>
                            <h2>{{ number_format($balance) }} لتر</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Search -->
            <div class="accordion accordion-light" id="accordionExample5">
                <div class="card">
                    <div class="pl-5 card-header" style="cursor: auto" id="headingOne5">
                        <div class="card-title">
                            <button class="btn btn-primary advance_search font-weight-bolder" data-toggle="collapse"
                                data-target="#collapseOne5">
                                <i class="flaticon-search"></i> بحــــــث متــقـــــــــدم
                            </button>
                        </div>
                    </div>
                    <div id="collapseOne5" class="pl-5 collapse" data-parent="#accordionExample5">
                        <div class="row card-body1">
                            <div class="col-lg-12">
                                <form action="{{ route('diesel.transactions', $fuelType->id) }}" method="POST" class="pr-5 form-group">
                                    @csrf
                                    <!-- Date Range Inputs -->
                                    <div class="mb-3 input-group">
                                        <label class="m-2">التاريخ:</label>
                                        <input name="start_date" type="date" class="form-control col-4"
                                            value="{{ old('start_date') }}" />
                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                        <input name="end_date" type="date" class="form-control col-4"
                                            value="{{ old('end_date') }}" />
                                        <button type="submit" class="btn btn-primary">بحث</button>
                                    </div>

                                    <!-- Voucher Range Inputs -->
                                    <div class="mb-3 input-group">
                                        <label class="m-2">السند:</label>
                                        <input name="start_voucher" type="text" class="form-control col-4"
                                            value="{{ old('start_voucher') }}" placeholder="من" />
                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                        <input name="end_voucher" type="text" class="form-control col-4"
                                            value="{{ old('end_voucher') }}" placeholder="إلى" />
                                        <button type="submit" class="btn btn-primary">بحث</button>
                                    </div>

                                    <!-- Invoice Range Inputs -->
                                    <div class="mb-3 input-group">
                                        <label class="m-2">الإيصال:</label>
                                        <input name="start_invoice" type="text" class="form-control col-4"
                                            value="{{ old('start_invoice') }}" placeholder="من" />
                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                        <input name="end_invoice" type="text" class="form-control col-4"
                                            value="{{ old('end_invoice') }}" placeholder="إلى" />
                                        <button type="submit" class="btn btn-primary">بحث</button>
                                    </div>

                                    <!-- Transaction Type Filter -->
                                    <div class="mb-3 input-group">
                                        <label class="m-2">النوع:</label>
                                        <select name="transaction_type" class="form-control col-4">
                                            <option value="">الكل</option>
                                            <option value="import" {{ old('transaction_type') == 'import' ? 'selected' : '' }}>وارد</option>
                                            <option value="export" {{ old('transaction_type') == 'export' ? 'selected' : '' }}>صادر</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">بحث</button>
                                    </div>

                                    <div class="col-lg-1"></div>
                                    <a href="{{ route('diesel.transactions', $fuelType->id) }}" class="btn btn-secondary">إعادة تعيين</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="card-body">
                <table id="transactionsTable" class="table table-striped dt-responsive" style="width:100%">
                    <thead class="thead_dark">
                        <tr>
                            <th>النوع</th>
                            <th>التاريخ</th>
                            <th>السند</th>
                            <th>رقم الفاتورة</th>
                            <th>الكمية</th>
                            <th>الرصيد</th>
                            <th>المورد / القسم</th>
                            <th>القسم الفرعي</th>
                            <th>الجهة المستلمة</th>
                            <th>رقم الدفتر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allTransactions as $transaction)
                            <tr class="{{ $transaction['type'] == 'import' ? 'transaction-import' : 'transaction-export' }}">
                                <td>
                                    @if($transaction['type'] == 'import')
                                        <span class="badge badge-success">وارد</span>
                                    @else
                                        <span class="badge badge-danger">صادر</span>
                                    @endif
                                </td>
                                <td>{{ $transaction['date'] }}</td>
                                <td>{{ $transaction['voucher'] }}</td>
                                <td>{{ $transaction['invoice_num'] }}</td>
                                <td>{{ number_format($transaction['quantity']) }}</td>
                                <td>{{ number_format($transaction['balance']) }}</td>
                                <td>
                                    @if($transaction['type'] == 'import')
                                        {{ $transaction['supplier'] }}
                                    @else
                                        {{ $transaction['section'] }}
                                    @endif
                                </td>
                                <td>{{ $transaction['sub_section'] }}</td>
                                <td>
                                    @if($transaction['type'] == 'export')
                                        {{ $transaction['recipient'] }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($transaction['type'] == 'export')
                                        {{ $transaction['num_section'] }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    {{-- <tfoot>
                        <tr>
                            <th colspan="4">الإجمالي</th>
                            <th>{{ number_format($totalImports) }} وارد / {{ number_format($totalExports) }} صادر</th>
                            <th colspan="3">الرصيد: {{ number_format($balance) }} لتر</th>
                        </tr>
                    </tfoot> --}}
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/features/miscellaneous/sweetalert2.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            $('#transactionsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy',
                    'excel',
                    'print'
                ],
                language: {
                    search: "بحث عام:",
                    lengthMenu: "أظهر _MENU_",
                    info: "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                    paginate: {
                        first: "الأول",
                        last: "الأخير",
                        next: "التالي",
                        previous: "السابق"
                    }
                },
                order: [[1, 'asc']]
            });
        });
    </script>
@endsection
