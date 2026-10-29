@extends('layout.master')

@section('title')
    عرض الفاتورة
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    الفاتورة
@endsection
@section('sub_title')
    صفحة العرض
@endsection
@section('css')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
        }

        .invoice-header {
            background: linear-gradient(135deg, #1BC5BD 0%, #0F9E95 100%);
        }

        .invoice-info-item {
            padding: 0.75rem 0;
            border-bottom: 1px dashed #e4e6ef;
        }

        .invoice-info-item:last-child {
            border-bottom: none;
        }

        .invoice-info-label {
            color: #7E8299;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .invoice-info-value {
            color: #3F4254;
            font-size: 1rem;
            font-weight: 600;
        }

        .product-table thead th {
            background-color: #F3F6F9;
            color: #3F4254;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .product-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .product-table tbody tr:hover {
            background-color: #F3F6F9;
        }

        .product-table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .signature-section th {
            background-color: #F3F6F9;
            color: #3F4254;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
        }

        .signature-section td {
            padding: 1.5rem 1rem;
            vertical-align: middle;
        }

        .signature-line {
            border-bottom: 2px solid #3F4254;
            min-width: 150px;
            display: inline-block;
        }

        .total-summary {
            background: linear-gradient(135deg, #1BC5BD 0%, #0F9E95 100%);
            border-radius: 12px;
            padding: 1.5rem;
            color: white;
        }

        .total-summary-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .total-summary-value {
            font-size: 2rem;
            font-weight: 700;
        }

        .action-buttons .btn {
            min-width: 120px;
            margin: 0.25rem;
        }
    </style>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Invoice-->
                <div class="overflow-hidden card card-custom position-relative">
                    <!--begin::Invoice header-->
                    <div class="p-5 row justify-content-center invoice-header">
                        <div class="col-md-9">
                            <div class="d-flex justify-content-between align-items-md-center flex-column flex-md-row">
                                <div class="order-2 px-0 d-flex flex-column order-md-1">
                                    <!--begin::Logo-->
                                    <a href="{{ url('dashboard') }}" class="mb-4">
                                        <img alt="Logo" src="{{ asset('assets/media/logos/logo-light.png') }}"
                                            width="180px" class="img-fluid" />
                                    </a>
                                    <!--end::Logo-->
                                    <span class="text-white d-flex flex-column font-size-h5 font-weight-bold">
                                        <span>شمال غزة حباليا</span>
                                        <span class="font-size-sm opacity-75">بالقرب من مركز شهداء الأقصى الطبي</span>
                                    </span>
                                </div>
                                <div class="order-1 text-white text-center order-md-2">
                                    <h1 class="display-3 font-weight-boldest mb-2">فاتورة شراء</h1>
                                    <span class="font-size-h6 opacity-75">Voucher #{{ $invoice->voucher_no }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Invoice header-->
                    <div class="p-5 row justify-content-center">
                        <div class="col-md-9">
                            <!--begin::Invoice body-->
                            <div class="pb-5 row">
                                <div class="col-md-4 pr-md-10 py-md-10">
                                    <!--begin::Invoice Details-->
                                    <div class="card card-custom card-shadowless bg-light">
                                        <div class="card-body p-5">
                                            <h5 class="font-weight-bolder text-dark mb-4">تفاصيل الفاتورة</h5>

                                            <div class="invoice-info-item">
                                                <div class="invoice-info-label">رقم السند</div>
                                                <div class="invoice-info-value">{{ $invoice->voucher_no }}</div>
                                            </div>

                                            <div class="invoice-info-item">
                                                <div class="invoice-info-label">رقم الفاتورة</div>
                                                <div class="invoice-info-value">{{ $invoice->invoice_no }}</div>
                                            </div>

                                            <div class="invoice-info-item">
                                                <div class="invoice-info-label">تاريخ السند</div>
                                                <div class="invoice-info-value">{{ $invoice->voucher_date }}</div>
                                            </div>

                                            <div class="invoice-info-item">
                                                <div class="invoice-info-label">المورد</div>
                                                <div class="invoice-info-value">{{ $invoice->supplier_item->supplier_item_name }}</div>
                                            </div>

                                            <div class="invoice-info-item">
                                                <div class="invoice-info-label">العملة</div>
                                                <div class="invoice-info-value">{{ $invoice->currency->name }}</div>
                                            </div>

                                            @if ($invoice->notes)
                                                <div class="invoice-info-item">
                                                    <div class="invoice-info-label">ملاحظات</div>
                                                    <div class="invoice-info-value">{{ $invoice->notes }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="py-10 col-md-8 pl-md-10">
                                    <div class="table-responsive">
                                        <table class="table product-table">
                                            <thead>
                                                <tr>
                                                    <th>اسم الصنف</th>
                                                    <th class="text-right">الكمية</th>
                                                    <th class="text-right">السعر</th>
                                                    <th class="text-right">الإجمالي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total = 0;
                                                @endphp
                                                @foreach ($invoiceProducts as $invoiceProduct)
                                                    <tr>
                                                        <td>
                                                            <div class="font-weight-bold">{{ $invoiceProduct->item->item_name }}</div>
                                                        </td>
                                                        <td class="text-right">{{ $invoiceProduct->quantity }}</td>
                                                        <td class="text-right">{{ $invoiceProduct->price }}</td>
                                                        <td class="text-right font-weight-boldest font-size-h5">
                                                            {{ $subtotal = $invoiceProduct->quantity * $invoiceProduct->price * $invoice->currency_value_at_time }}
                                                            @php
                                                                $total += $subtotal;
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Total Summary -->
                                    <div class="total-summary mt-5">
                                        <div class="total-summary-label">إجمالي الفاتورة</div>
                                        <div class="total-summary-value">{{ number_format($total, 2) }}</div>
                                        <div class="font-size-sm opacity-75 mt-1">{{ $invoice->currency->name }}</div>
                                    </div>

                                    <hr class="mt-8 mb-5">

                                    <!-- Signature Section -->
                                    <table class="table signature-section table-borderless">
                                        <thead>
                                            <tr>
                                                <th>الاسم</th>
                                                <th class="text-center">التوقيع</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold">رئيس قسم المخازن</td>
                                                <td class="text-center">
                                                    <span class="signature-line"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">أمين المخازن</td>
                                                <td class="text-center">
                                                    <span class="signature-line"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end::Invoice body-->

                        </div>
                    </div>
                    <!-- begin: Invoice action-->
                    <div class="p-5 row justify-content-center border-top">
                        <div class="col-md-9">
                            <div class="flex-wrap d-flex justify-content-between align-items-center action-buttons">
                                <div class="no-print">
                                    <a href="{{ url('inventory/invoice') }}" class="btn btn-secondary font-weight-bolder">
                                        <i class="la la-arrow-right"></i> العودة للقائمة
                                    </a>
                                </div>
                                <div class="no-print">
                                    <a href="{{ url('inventory/invoice/' . $invoice->id . '/edit') }}" class="btn btn-primary font-weight-bolder">
                                        <i class="la la-edit"></i> تعديل
                                    </a>
                                    <button type="button" onclick="window.print();" class="btn btn-success font-weight-bolder">
                                        <i class="la la-print"></i> طباعة الفاتورة
                                    </button>
                                    <a href="{{ url('inventory/invoice/create') }}" class="btn btn-warning font-weight-bolder">
                                        <i class="la la-plus"></i> إنشاء فاتورة جديدة
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end: Invoice action-->
                </div>
                <!--end::Invoice-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection

@section('scripts')
@endsection
