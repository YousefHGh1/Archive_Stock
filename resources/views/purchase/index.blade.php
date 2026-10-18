@extends('layout.master')

@section('title')
    عرض طلبات المشتريات
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المشتريات
@endsection
@section('sub_title')
    صفحة العرض
@endsection

@section('css')
    <link href="{{ asset('assets/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.foundation.min.css') }}">
    <style>
        /* ===== Modern Purchase Requests Design ===== */
        .purchase-hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8e 50%, #3a7bd5 100%);
            border-radius: 12px;
            padding: 28px 30px;
            margin-bottom: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(30, 58, 95, 0.25);
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

        .purchase-hero .btn-hero {
            background: #fff;
            color: #1e3a5f;
            font-weight: 700;
            border-radius: 8px;
            padding: 10px 22px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .purchase-hero .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            color: #1e3a5f;
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
            background: linear-gradient(135deg, #1e3a5f, #3a7bd5);
        }

        .stat-pending {
            background: linear-gradient(135deg, #f39c12, #f1c40f);
        }

        .stat-purchase {
            background: linear-gradient(135deg, #0bb783, #1bc5bd);
        }

        .stat-tender {
            background: linear-gradient(135deg, #3699ff, #0d8cf8);
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
            color: #1e3a5f;
        }

        /* Search Accordion */
        .advance_search {
            background: linear-gradient(135deg, #1e3a5f, #3a7bd5);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(30, 58, 95, 0.2);
            transition: all 0.3s ease;
        }

        .advance_search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(30, 58, 95, 0.3);
        }

        /* Table */
        .dataTables_wrapper .dataTable thead th {
            background-color: #1e3a5f;
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
            background: #f0f7ff !important;
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

        .badge-purchase_order {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-tender_committee {
            background: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

        .badge-rent_repair {
            background: #e2d9f3;
            color: #4b2e83;
            border: 1px solid #d1c4e9;
        }

        .badge-rejected {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .badge-approved_stock {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
            color: #1e3a5f;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            margin: 2px 0;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .file-link:hover {
            background: #1e3a5f;
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
                <h2><i class="la la-shopping-cart"></i> طلبات المشتريات</h2>
                <p>إدارة ومتابعة جميع طلبات الشراء في النظام</p>
            </div>
            <div>
                <a href="{{ route('purchase-report.export-excel') }}" class="btn btn-hero mr-2" title="تصدير Excel">
                    <i class="la la-file-excel-o"></i> Excel
                </a>
                <a href="{{ route('purchase-report.export-pdf') }}" class="btn btn-hero mr-2" title="تصدير PDF">
                    <i class="la la-file-pdf-o"></i> PDF
                </a>
                <a href="{{ url('/purchase/create') }}" class="btn btn-hero">
                    <i class="la la-plus"></i> إنشاء طلب شراء جديد
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        @php
            $totalCount = $purchases->count();
            $pendingCount = $purchases->where('status', 'pending')->count();
            $purchaseOrderCount = $purchases->where('status', 'purchase_order')->count();
            $tenderCount = $purchases->where('status', 'tender_committee')->count();
            $rejectedCount = $purchases->where('status', 'rejected')->count();
        @endphp
        <div class="row stats-row">
            <div class="col-lg-4 col-md-4 col-6 mb-3">
                <div class="stat-card stat-total">
                    <div class="stat-number">{{ $totalCount }}</div>
                    <div class="stat-label">إجمالي الطلبات</div>
                    <i class="la la-list-alt stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <div class="stat-card stat-pending">
                    <div class="stat-number">{{ $pendingCount }}</div>
                    <div class="stat-label">قيد الانتظار</div>
                    <i class="la la-clock stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <div class="stat-card stat-purchase">
                    <div class="stat-number">{{ $purchaseOrderCount }}</div>
                    <div class="stat-label">أوامر شراء</div>
                    <i class="la la-check-circle stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <div class="stat-card stat-tender">
                    <div class="stat-number">{{ $tenderCount }}</div>
                    <div class="stat-label">لجنة العطاءات</div>
                    <i class="la la-gavel stat-icon"></i>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <div class="stat-card stat-rejected">
                    <div class="stat-number">{{ $rejectedCount }}</div>
                    <div class="stat-label">مرفوضة</div>
                    <i class="la la-times-circle stat-icon"></i>
                </div>
            </div>
        </div>

        <!--begin::Card-->
        <div class="card card-custom">
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
                                            <form action="{{ url('purchase/search') }}" method="POST"
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
                                            <form action="{{ url('purchase/search') }}" method="POST"
                                                class="pr-5 form-group">
                                                @csrf
                                                <div class="input-daterange input-group">
                                                    <div class="p-0 col-4">
                                                        <select name="status" class="form-control">
                                                            <option value="">الكل</option>
                                                            <option value="pending">قيد الانتظار</option>
                                                            <option value="purchase_order">أمر شراء</option>
                                                            <option value="tender_committee">لجنة العطاءات</option>
                                                            <option value="rent_repair">استئجار + إصلاح</option>
                                                            <option value="rejected">مرفوض</option>
                                                            <option value="approved_stock">موافق عليه - مخازن</option>
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
                            <th width="18%">{{ 'الموضوع' }}</th>
                            <th width="15%">{{ 'القسم' }}</th>
                            <th width="10%">{{ 'مقدم الطلب' }}</th>
                            <th width="10%">{{ 'المرفقات' }}</th>
                            <th width="12%">{{ 'توقيع الرئيس' }}</th>
                            {{-- <th width="10%">{{ ' مقدم الطلب' }}</th> --}}
                            <th width="10%">{{ 'الحالة' }}</th>
                            <th width="15%">{{ 'الإجراءات' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td><strong>{{ $purchase->serial_number }}</strong></td>
                                <td>{{ $purchase->subject }}</td>
                                <td>{{ optional($purchase->section)->name_section ?? '-' }}</td>
                                <td>{{ $purchase->requester_name ?? '-' }}</td>
                                <td>
                                    @if ($purchase->files && count(json_decode($purchase->files, true)) > 0)
                                        @foreach (json_decode($purchase->files) as $file)
                                            <a href="{{ url('uploads/' . $file) }}" target="_blank" class="file-link"><i
                                                    class="la la-paperclip"></i> {{ $file }}</a>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($purchase->president_signature)
                                        <span class="badge-signature signed"><i class="la la-check"></i> تم التوقيع</span>
                                    @else
                                        <span class="badge-signature unsigned"><i class="la la-close"></i> لم يتم</span>
                                    @endif
                                </td>
                                {{-- <td>
                                    @if ($purchase->requester_signature)
                                        <span class="badge-signature signed"><i class="la la-check"></i> تم التوقيع</span>
                                    @else
                                        <span class="badge-signature unsigned"><i class="la la-close"></i> لم يتم</span>
                                    @endif
                                </td> --}}
                                <td>
                                    @php
                                        $statusLabels = [
                                            'pending' => 'قيد الانتظار',
                                            'purchase_order' => 'أمر شراء',
                                            'tender_committee' => 'لجنة العطاءات',
                                            'rent_repair' => 'استئجار + إصلاح',
                                            'rejected' => 'مرفوض',
                                            'approved_stock' => 'موافق عليه - مخازن',
                                        ];
                                        $statusClass = 'badge-' . $purchase->status;
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ $statusLabels[$purchase->status] ?? $purchase->status }}
                                    </span>
                                </td>
                                <td data-field="Actions" data-autohide-disabled="false" aria-label="null"
                                    class="datatable-cell">
                                    <span style="overflow: visible; position: relative; width: 110px;">
                                        <form method="POST" action="{{ url('/purchase' . '/' . $purchase->id) }}"
                                            accept-charset="UTF-8" style="display:inline">
                                            {{ method_field('DELETE') }} {{ csrf_field() }}
                                            <button type="submit" class="btn btn-sm btn-clean btn-icon" title="Delete"
                                                onclick="return confirm('هل تريد تأكيد عملية الحذف؟؟؟')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                </svg>
                                            </button>
                                        </form>
                                        <a href="{{ url('/purchase/' . $purchase->id . '/edit') }}"
                                            class="btn btn-sm btn-clean btn-icon" title="Edit details">
                                            <i class="la la-edit"></i>
                                        </a>
                                        <a href="{{ route('purchase-report.print', $purchase->id) }}"
                                            class="btn btn-sm btn-clean btn-icon" title="طباعة PDF" target="_blank">
                                            <i class="la la-print"></i>
                                        </a>
                                        <a href="{{ url('/purchase/' . $purchase->id) }}"
                                            class="btn btn-sm btn-clean btn-icon" title="Show details">
                                            <i class="la la-eye"></i>
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
