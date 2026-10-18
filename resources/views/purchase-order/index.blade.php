@extends('layout.master')

@section('title')
    عرض أوامر الشراء
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المشتريات
@endsection
@section('sub_title')
    أوامر الشراء
@endsection

@section('css')
    <link href="{{ asset('assets/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.foundation.min.css') }}">
    <style>
        /* ===== Modern Purchase Orders Design ===== */
        .purchase-hero {
            background: linear-gradient(135deg, #0d4f4f 0%, #0a6b6b 50%, #0bb783 100%);
            border-radius: 12px;
            padding: 28px 30px;
            margin-bottom: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(13, 79, 79, 0.25);
        }

        .purchase-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .purchase-hero::after {
            content: '';
            position: absolute;
            bottom: -60%;
            right: 10%;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .purchase-hero h2 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }

        .purchase-hero p {
            opacity: 0.85;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }

        /* Stats Cards */
        .stats-row {
            margin-bottom: 24px;
        }

        .stat-card {
            border-radius: 10px;
            padding: 18px 20px;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
            position: absolute;
            bottom: 8px;
            left: 12px;
        }

        .stat-card .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .stat-card .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-top: 2px;
        }

        .stat-total {
            background: linear-gradient(135deg, #0d4f4f, #0bb783);
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

        /* Main Card */
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

        .card-custom .card-header .card-label {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0d4f4f;
        }

        /* Search Accordion */
        .advance_search {
            background: linear-gradient(135deg, #0d4f4f, #0bb783);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(13, 79, 79, 0.2);
            transition: all 0.3s ease;
        }

        .advance_search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(13, 79, 79, 0.3);
        }

        /* Table */
        .dataTables_wrapper .dataTable thead th {
            background-color: #0d4f4f;
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            padding: 12px 10px !important;
        }

        .dataTables_wrapper .dataTable tbody tr {
            transition: background 0.2s ease;
        }

        .dataTables_wrapper .dataTable tbody tr:hover {
            background: #f0faf7 !important;
        }

        .dataTables_wrapper .dataTable tbody td {
            padding: 12px 10px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        /* Badges */
        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .badge-approved {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-rejected {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Action buttons */
        .btn-clean {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-clean:hover {
            background: #e8f0fe;
            transform: scale(1.1);
        }

        /* Signature badges */
        .badge-signature {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-signature.signed {
            background: #d4edda;
            color: #155724;
        }

        .badge-signature.unsigned {
            background: #f8d7da;
            color: #721c24;
        }

        /* File links */
        .file-link {
            display: inline-block;
            background: #eef2f7;
            color: #0d4f4f;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            margin: 2px 0;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .file-link:hover {
            background: #0d4f4f;
            color: #fff;
        }

        .card-body {
            padding: 20px 24px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Hero Banner -->
        <div class="purchase-hero d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h2><i class="la la-file-text"></i> أوامر الشراء</h2>
                <p>إدارة ومتابعة جميع أوامر الشراء في النظام</p>
            </div>
            <div>
                <a href="{{ route('purchase-report.export-orders-excel') }}" class="btn btn-hero mr-2" title="تصدير Excel">
                    <i class="la la-file-excel-o"></i> Excel
                </a>
                <a href="{{ route('purchase-report.export-orders-pdf') }}" class="btn btn-hero mr-2" title="تصدير PDF">
                    <i class="la la-file-pdf-o"></i> PDF
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        @php
            $totalCount = $purchaseOrders->count();
            $pendingCount = $purchaseOrders->where('status', 'pending')->count();
            $approvedCount = $purchaseOrders->where('status', 'approved')->count();
            $rejectedCount = $purchaseOrders->where('status', 'rejected')->count();
        @endphp
        <div class="row stats-row">
            <div class="col-lg-3 col-md-6 col-6 mb-3">
                <div class="stat-card stat-total">
                    <div class="stat-number">{{ $totalCount }}</div>
                    <div class="stat-label">إجمالي الأوامر</div>
                    <i class="la la-list-alt stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-3">
                <div class="stat-card stat-pending">
                    <div class="stat-number">{{ $pendingCount }}</div>
                    <div class="stat-label">قيد الانتظار</div>
                    <i class="la la-clock stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-3">
                <div class="stat-card stat-approved">
                    <div class="stat-number">{{ $approvedCount }}</div>
                    <div class="stat-label">موافق عليه</div>
                    <i class="la la-check-circle stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-6 mb-3">
                <div class="stat-card stat-rejected">
                    <div class="stat-number">{{ $rejectedCount }}</div>
                    <div class="stat-label">مرفوضة</div>
                    <i class="la la-times-circle stat-icon"></i>
                </div>
            </div>
        </div>

        <!--begin::Card-->
        <div class="card card-custom">
            <div class="flex-wrap pb-0 border-0 card-header">
                <div class="card-title">
                    <h3 class="card-label">قائمة أوامر الشراء</h3>
                </div>
            </div>
            <div class="card-body">
                <!--begin: Search Form -->
                <div class="accordion accordion-light" id="accordionExample5" style="direction: rtl;">
                    <div class="card">
                        <div class="card-header" id="headingOne5">
                            <div class="card-title">
                                <div data-toggle="collapse" data-target="#collapseOne5"
                                    class="btn advance_search font-weight-bolder">
                                    <i class="flaticon-search"></i>
                                    بحث متقدم
                                </div>
                            </div>
                        </div>
                        <div id="collapseOne5" class="pl-5 collapse" data-parent="#accordionExample5"
                            style="direction: rtl;">
                            <div class="card-body1">
                                <div class="mb-6 col-lg-12">
                                    <div class="mb-0 row search_input">
                                        <div class="mb-6 col-lg-6">
                                            <label>بحث:</label>
                                            <form action="{{ url('purchase-order/search') }}" method="POST"
                                                class="pr-5 form-group">
                                                @csrf
                                                <div class="input-daterange input-group">
                                                    <div class="p-0 col-6">
                                                        <input name="search" type="text" class="form-control"
                                                            placeholder="رقم المسلسل أو الموضوع" />
                                                    </div>
                                                    <div class="mt-auto mb-auto col-lg-3">
                                                        <input type="submit" class="btn btn-primary btn-primary--icon"
                                                            value="بحث" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="mb-6 col-lg-6">
                                            <label>حالة الطلب:</label>
                                            <form action="{{ url('purchase-order/search') }}" method="POST"
                                                class="pr-5 form-group">
                                                @csrf
                                                <div class="input-daterange input-group">
                                                    <div class="p-0 col-4">
                                                        <select name="status" class="form-control">
                                                            <option value="">الكل</option>
                                                            <option value="pending">قيد الانتظار</option>
                                                            <option value="approved">موافق عليه</option>
                                                            <option value="rejected">مرفوض</option>
                                                        </select>
                                                    </div>
                                                    <div class="mt-auto mb-auto col-lg-3">
                                                        <input type="submit" class="btn btn-primary btn-primary--icon"
                                                            value="بحث" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End: Search Form -->

                <!--begin: Datatable-->
                <table id="example" class="table dt-responsive" style="width:100%; padding:0%">
                    <thead class="thead_dark">
                        <tr>
                            <th width="10%">{{ 'رقم المسلسل' }}</th>
                            <th width="15%">{{ 'طلب الشراء الأصلي' }}</th>
                            <th width="10%">{{ 'التاريخ' }}</th>
                            <th width="10%">{{ 'المرفقات' }}</th>
                            <th width="10%">{{ 'توقيع المشتريات' }}</th>
                            <th width="10%">{{ 'توقيع المالية' }}</th>
                            <th width="10%">{{ 'توقيع مقدم الطلب' }}</th>
                            <th width="10%">{{ 'الحالة' }}</th>
                            <th width="10%">{{ 'الإجراءات' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrders as $order)
                            <tr>
                                <td><strong>{{ $order->serial_number }}</strong></td>
                                <td>{{ optional($order->purchase)->serial_number ?? '-' }}</td>
                                <td>{{ $order->purchase_date ?? '-' }}</td>
                                <td>
                                    @if ($order->purchase_attachments && count(json_decode($order->purchase_attachments, true)) > 0)
                                        @foreach (json_decode($order->purchase_attachments) as $file)
                                            <a href="{{ url('uploads/' . $file) }}" target="_blank" class="file-link"><i
                                                    class="la la-paperclip"></i> {{ $file }}</a>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->purchases_signature)
                                        <span class="badge-signature signed"><i class="la la-check"></i> تم التوقيع</span>
                                    @else
                                        <span class="badge-signature unsigned"><i class="la la-close"></i> لم يتم</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->finance_signature)
                                        <span class="badge-signature signed"><i class="la la-check"></i> تم التوقيع</span>
                                    @else
                                        <span class="badge-signature unsigned"><i class="la la-close"></i> لم يتم</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->requester_signature)
                                        <span class="badge-signature signed"><i class="la la-check"></i> تم التوقيع</span>
                                    @else
                                        <span class="badge-signature unsigned"><i class="la la-close"></i> لم يتم</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusLabels = [
                                            'pending' => 'قيد الانتظار',
                                            'approved' => 'موافق عليه',
                                            'rejected' => 'مرفوض',
                                        ];
                                        $statusClass = 'badge-' . $order->status;
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td data-field="Actions" data-autohide-disabled="false" aria-label="null"
                                    class="datatable-cell">
                                    <span style="overflow: visible; position: relative; width: 110px;">
                                        <a href="{{ route('purchase-report.print-order', $order->id) }}"
                                            class="btn btn-sm btn-clean btn-icon" title="طباعة PDF" target="_blank">
                                            <i class="la la-print"></i>
                                        </a>
                                        <a href="{{ url('/purchase-order/' . $order->id) }}"
                                            class="btn btn-sm btn-clean btn-icon" title="Show details">
                                            <i class="la la-eye"></i>
                                        </a>
                                        <a href="{{ url('/purchase-order/' . $order->id . '/edit') }}"
                                            class="btn btn-sm btn-clean btn-icon" title="Edit details">
                                            <i class="la la-edit"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/features/miscellaneous/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/data-json.js') }}" type="text/javascript"></script>
@endsection
