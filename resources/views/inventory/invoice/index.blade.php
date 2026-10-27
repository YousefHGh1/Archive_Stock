@extends('layout.master')

@section('title')
    عرض فاتورة شراء
@endsection

@section('page_title')
    لوحة التحكم
@endsection

@section('sub_main')
    الفواتير
@endsection

@section('sub_title')
    عرض فاتورة شراء
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

        div.dt-buttons {
            top: 50%;
            left: 10%;
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

        .card-body {
            padding: 10px 12px !important;
        }

        .quick-add-modal {
            direction: rtl;
            text-align: right;
        }

        .quick-add-modal .modal-content {
            border: 1px solid #e5e0ef;
            box-shadow: 0 12px 36px rgba(25, 30, 45, .18);
        }

        .quick-add-modal .modal-header,
        .quick-add-modal .modal-footer {
            justify-content: flex-start;
        }

        .quick-add-modal .modal-header .close {
            margin: -1rem auto -1rem -1rem;
        }

        .quick-add-heading {
            border-right: 3px solid #496aa8;
            padding-right: .75rem;
        }

        .quick-add-context {
            margin: .2rem 0 0;
            color: #596579;
            font-size: .8rem;
            line-height: 1.4;
        }

        .quick-add-modal label {
            font-weight: 600;
        }

        .quick-add-modal .modal-body {
            max-height: calc(100vh - 220px);
            overflow-y: auto;
        }

        .quick-add-modal input,
        .quick-add-modal select {
            border: 1px solid #c1ffe5 !important;
        }

        .quick-add-modal [data-form-error] {
            display: none;
        }

        .quick-add-modal [data-field-error] {
            display: block;
        }

        .quick-add-modal [data-field-error]:empty {
            display: none !important;
        }

        @media (max-width: 575.98px) {
            .quick-add-modal .modal-dialog {
                width: calc(100% - 1rem);
                margin: .5rem;
            }
        }

        /* تحسين أزرار الإضافة والحذف */
        .action-buttons {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .btn-icon-sm {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-icon-sm:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-icon-sm i {
            font-size: 16px;
        }

        .btn-add-custom {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
        }

        .btn-add-custom:hover {
            background: linear-gradient(135deg, #218838 0%, #1aa885 100%);
            color: white;
        }

        .btn-delete-custom {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            border: none;
            color: white;
        }

        .btn-delete-custom:hover {
            background: linear-gradient(135deg, #c82333 0%, #d63031 100%);
            color: white;
        }

        .btn-icon-sm:active {
            transform: scale(0.95);
        }
    </style>
@endsection

@section('content')
    <div class="container">

        <!--begin::Card-->
        <div class="card card-custom">
            <div class="flex-wrap pb-0 border-0 card-header">
                <div class="card-title">
                    <h3 class="card-label">عرض فواتير الشراء </h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <button type="button" class="btn btn-success font-weight-bolder" data-toggle="modal" data-target="#invoiceModal">
                        <span class="svg-icon svg-icon-md">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <circle fill="#000000" cx="9" cy="15" r="6" />
                                    <path
                                        d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                        fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>إنشاء فاتورة جديدة</button>

                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('invoice.skipped-vouchers') }}" class="btn btn-warning font-weight-bolder">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M12 22 C6.4771525 22 2 17.5228475 2 12 C2 6.4771525 6.4771525 2 12 2 C17.5228475 2 22 6.4771525 22 12 C22 17.5228475 17.5228475 22 12 22 Z M12 20 C16.418278 20 20 16.418278 20 12 C20 7.581722 16.418278 4 12 4 C7.581722 4 4 7.581722 4 12 C4 16.418278 7.581722 20 12 20 Z" fill="#000000" opacity="0.3" />
                                        <path d="M10 8 L14 8 L14 16 L10 16 L10 8 Z M8 8 L9 8 L9 16 L8 16 L8 8 Z M15 8 L16 8 L16 16 L15 16 L15 8 Z" fill="#000000" />
                                    </g>
                                </svg>
                            </span>السندات التي تم تخطيها</a>
                    @endif

                        <x-add-resource-button />

                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <div class="accordion accordion-light " id="accordionExample5" style="direction: rtl;">
                    <div class="card">
                        <div class="card-header"
                            id="headingOne5">
                            <div class="card-title">
                                <div data-toggle="collapse" data-target="#collapseOne5"
                                    class="btn btn-primary advance_search font-weight-bolder "><i
                                        class="flaticon-search"></i>
                                    بحــــــــث متــقــــــــــــدم</div>
                            </div>
                        </div>
                        <div id="collapseOne5" class="pl-5 collapse" data-parent="#accordionExample5"
                            style="direction: rtl;">
                            <div class="card-body1">
                                <div class="mb-6 col-lg-9">
                                    <label>التاريخ:</label>
                                    <form action="{{ url('inventory/invoice/searchdate') }}" method="POST"
                                        class="pr-5 form-group">
                                        @csrf
                                        <div class="input-daterange input-group">
                                            <div class="p-0 col-4">
                                                <input name="start_date" type="date" class="form-control"
                                                    id="start_date" />
                                            </div>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                            </div>
                                            <div class="p-0 col-4">
                                                <input name="end_date" type="date" class="form-control"
                                                    id="end_date" />
                                            </div>
                                            <div class="mt-auto mb-auto col-lg-2">
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

                <!--begin: Datatable-->
                <table id="example" class="table dt-responsive" style="width:100%">
                    <thead class="thead_dark">
                        <tr>
                            <th width="5%">رقم السند</th>
                            <th width="7%">تاريخ الفاتورة</th>
                            <th width="6%">رقم الفاتورة</th>
                            <th width="14%">اسم المورد</th>
                            <th width="8%">معامل الصرف</th>
                            <th width="12%">ملاحظات</th>
                            <th width="30%">الأصناف</th>
                            <th width="13%">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->voucher_no }}</td>
                                <td>{{ $invoice->voucher_date }}</td>
                                <td>{{ $invoice->invoice_no }}</td>
                                <td>{{ $invoice->supplier_item_name }}</td>
                                <td>{{ $invoice->name }}</td>
                                <td>{{ $invoice->notes ?? '-' }}</td>
                                <td>{!! $invoice->products !!}</td>

                                <td data-field="Actions" data-autohide-disabled="false" aria-label="null"
                                    class="datatable-cell">
                                    <span style="overflow: visible; position: relative; width: 110px;">

                                        <a href="{{ url('/inventory/invoice/' . $invoice->id) }}"
                                            title="View invoices"><button class="btn btn-sm btn-clean btn-icon"> <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                    <path
                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                                </svg></button>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-clean btn-icon"
                                            title="Delete invoice"
                                            onclick="deleteInvoice({{ $invoice->id }})"> <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                </svg></button>
                                        @canany(['invoices-update', 'admin', 'stock', 'Super-User'])
                                            <button type="button" class="btn btn-sm btn-clean btn-icon"
                                                title="Edit details"
                                                onclick="editInvoice({{ $invoice->id }})"><i
                                                    class="la la-edit"></i></button>
                                        @endcanany

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

    <!-- Invoice Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalTitle">إضافة فاتورة شراء</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="invoiceForm">
                        @csrf
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">رقم السند:</label>
                            <div class="col-lg-3">
                                <input type="number" class="form-control" id="modal_voucher_no" disabled>
                            </div>

                            <label class="col-lg-2 col-form-label">تاريخ السند:</label>
                            <div class="col-lg-3">
                                <input type="date" class="form-control" id="modal_voucher_date" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">رقم الفاتورة:</label>
                            <div class="col-lg-3">
                                <input type="number" class="form-control" id="modal_invoice_no" required>
                            </div>

                            <label class="col-lg-2 col-form-label">المورد:</label>
                            <div class="col-lg-3">
                                <select class="form-control selectpicker" id="modal_supplier_item_id" data-live-search="true" required>
                                    <option value="">اختر المورد</option>
                                    @foreach ($supplier_item as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->supplier_item_name }} {{ $supplier->supplier_item_num }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">معامل الصرف:</label>
                            <div class="col-lg-3">
                                <select class="form-control" id="modal_currency_id" required>
                                    <option value="">اختر معامل الصرف</option>
                                    @foreach ($currency as $curr)
                                        <option value="{{ $curr->id }}">{{ $curr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">ملاحظات:</label>
                            <div class="col-lg-9">
                                <textarea class="form-control" id="modal_notes" rows="2" maxlength="5000"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>الأصناف:</label>
                            <table class="table table-bordered" id="modal-products-table">
                                <thead>
                                    <tr>
                                        <th width="50%">الصنف
                                            <button type="button" class="btn btn-link p-0" data-toggle="modal" data-target="#itemModal">
                                                <i class="p-3 ki ki-solid-plus icon-md"></i>
                                            </button>
                                            <a href="{{ url('inventory/item') }}" title="عرض الأصناف" target="_blank">
                                                <i class="p-3 far fa-eye icon-md"></i>
                                            </a>
                                        </th>
                                        <th>الكمية</th>
                                        <th>السعر</th>
                                        <th>الإجمالي</th>
                                        <th width="5%">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="product-row">
                                        <td>
                                            <select class="form-control selectpicker product-select" data-live-search="true" title="اختر الصنف">
                                                <option value="">اختر الصنف</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->item_num }}
                                                        {{ $product->item_name }}
                                                        {{ ' الرصيد: ' . $product->balance }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0.1" class="form-control quantity-input" oninput="calculateRowTotal(this)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="form-control price-input" oninput="calculateRowTotal(this)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control row-total" readonly>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" class="btn-icon-sm btn-add-custom add-row" title="إضافة صف">
                                                    <i class="ki ki-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">الإجمالي الكلي:</td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control grand-total" readonly>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="saveInvoice()">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Item Modal (إضافة صنف كامل) -->
    <div class="modal fade quick-add-modal" id="itemModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('item.store') }}" method="post" data-ajax-quick-add data-entity="item">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title">إضافة صنف جديد</h5>
                            <p class="quick-add-context">أكمل بيانات الصنف لإضافته إلى المخزون.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-4" role="alert" data-form-error></div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="quick_item_category">العائلة</label>
                                <div class="input-group">
                                    <select id="quick_item_category" class="form-control selectpicker" name="category_id" data-live-search="true" required>
                                        <option value="">اختر العائلة</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary"
                                            title="إضافة عائلة جديدة"
                                            data-toggle="modal" data-target="#quickAddCategoryModal">
                                            <i class="ki ki-solid-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="invalid-feedback" data-field-error="category_id"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="quick_item_unit">الوحدة</label>
                                <div class="input-group">
                                    <select id="quick_item_unit" class="form-control" name="unit_id" required>
                                        <option value="">اختر الوحدة</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary"
                                            title="إضافة وحدة جديدة"
                                            data-toggle="modal" data-target="#quickAddUnitModal">
                                            <i class="ki ki-solid-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="invalid-feedback" data-field-error="unit_id"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="quick_item_name">اسم الصنف</label>
                                <input id="quick_item_name" class="form-control" type="text" name="item_name"
                                    maxlength="255" required>
                                <div class="invalid-feedback" data-field-error="item_name"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="quick_item_num">رقم الصنف</label>
                                <input id="quick_item_num" class="form-control" type="number" name="item_num"
                                    step="1" required readonly>
                                <div class="invalid-feedback" data-field-error="item_num"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6">
                                <label for="quick_item_balance">الرصيد الافتتاحي</label>
                                <input id="quick_item_balance" class="form-control" type="number" name="open_balance"
                                    min="0" step="any" value="0" required>
                                <div class="invalid-feedback" data-field-error="open_balance"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="quick_item_low_limit">حد التنبيه</label>
                                <input id="quick_item_low_limit" class="form-control" type="number" name="low_limit"
                                    min="0" step="any" value="0">
                                <div class="invalid-feedback" data-field-error="low_limit"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary" data-submit-label="حفظ الصنف">حفظ الصنف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script>
        // حساب إجمالي الصف
        function calculateRowTotal(input) {
            const row = $(input).closest('tr');
            const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
            const price = parseFloat(row.find('.price-input').val()) || 0;
            const total = quantity * price;
            row.find('.row-total').val(total.toFixed(2));
            calculateGrandTotal();
        }

        // حساب الإجمالي الكلي
        function calculateGrandTotal() {
            let grandTotal = 0;
            $('.row-total').each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $('.grand-total').val(grandTotal.toFixed(2));
        }

        // تحديث رقم الصنف عند اختيار العائلة
        $('#quick_item_category').on('change', function() {
            const categoryId = $(this).val();
            if (categoryId) {
                $.ajax({
                    url: "{{ route('category.last_item', ':id') }}".replace(':id', categoryId),
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data && data.last_item_num !== undefined) {
                            const newItemNum = parseInt(data.last_item_num) + 1;
                            $('#quick_item_num').val(newItemNum);
                        } else {
                            $('#quick_item_num').val(categoryId + '1');
                        }
                    },
                    error: function() {
                        $('#quick_item_num').val('');
                    }
                });
            }
        });

        // إضافة صف جديد للجدول
        function addProductRow(itemId = null, quantity = '', price = '') {
            // الحصول على خيارات الصنف من الصف الأول
            const firstSelect = $('#modal-products-table tbody select.product-select').first();
            let optionsHtml = '';

            // نسخ جميع الخيارات مع التأكد من النسخ الصحيح
            firstSelect.find('option').each(function() {
                const value = $(this).attr('value');
                const text = $(this).text();
                optionsHtml += '<option value="' + value + '">' + text + '</option>';
            });

            const newRow = `
                <tr class="product-row">
                    <td>
                        <select class="form-control selectpicker product-select" data-live-search="true" title="اختر الصنف">
                            ${optionsHtml}
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0.1" class="form-control quantity-input" oninput="calculateRowTotal(this)" value="${quantity}">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" class="form-control price-input" oninput="calculateRowTotal(this)" value="${price}">
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control row-total" readonly value="${(quantity * price).toFixed(2)}">
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="btn-icon-sm btn-add-custom add-row" title="إضافة صف">
                                <i class="ki ki-plus"></i>
                            </button>
                            <button type="button" class="btn-icon-sm btn-delete-custom delete-row" title="حذف الصف">
                                <i class="ki ki-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            $('#modal-products-table tbody').append(newRow);

            // تحديث الـ selectpicker للعنصر الجديد فقط
            const newSelect = $('.product-select').last();
            newSelect.selectpicker('refresh');

            if (itemId) {
                newSelect.val(itemId);
                newSelect.selectpicker('refresh');
            }

            calculateGrandTotal();
        }

        // إضافة صف جديد عند النقر على زر +
        $(document).on('click', '.add-row', function(e) {
            e.preventDefault();
            addProductRow();
        });

        // حذف صف
        $(document).on('click', '.delete-row', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            calculateGrandTotal();
        });

        // تحميل بيانات الفاتورة للتعديل
        function editInvoice(id) {
            $.ajax({
                url: "{{ route('invoice.show', ':id') }}".replace(':id', id),
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        $('#invoiceModalTitle').text('تعديل فاتورة شراء');
                        $('#invoice_id').val(data.invoice.id);
                        $('#modal_voucher_no').val(data.invoice.voucher_no);
                        $('#modal_voucher_date').val(data.invoice.voucher_date);
                        $('#modal_invoice_no').val(data.invoice.invoice_no);
                        $('#modal_supplier_item_id').val(data.invoice.supplier_item_id);
                        $('#modal_currency_id').val(data.invoice.currency_id);
                        $('#modal_notes').val(data.invoice.notes);

                        $('#modal_supplier_item_id').selectpicker('refresh');

                        // تحميل الأصناف
                        $('#modal-products-table tbody').empty();
                        data.invoice.products.forEach(function(product, index) {
                            addProductRow(product.item_id, product.quantity, product.price);
                        });

                        $('#invoiceModal').modal('show');
                    } else {
                        Swal.fire('خطأ', data.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('خطأ', 'حدث خطأ أثناء تحميل البيانات', 'error');
                }
            });
        }

        // حفظ الفاتورة
        function saveInvoice() {
            const invoiceId = $('#invoice_id').val();
            const url = invoiceId ? "{{ route('invoice.update', ':id') }}".replace(':id', invoiceId) : "{{ route('invoice.store') }}";

            // جمع بيانات الأصناف
            const products = [];
            const quantities = [];
            const prices = [];

            $('#modal-products-table tbody tr').each(function() {
                const productId = $(this).find('.product-select').val();
                const quantity = $(this).find('.quantity-input').val();
                const price = $(this).find('.price-input').val();

                if (productId && quantity && price) {
                    products.push(productId);
                    quantities.push(quantity);
                    prices.push(price);
                }
            });

            if (products.length === 0) {
                Swal.fire('تنبيه', 'يجب إضافة صنف واحد على الأقل', 'warning');
                return;
            }

            const formData = {
                voucher_date: $('#modal_voucher_date').val(),
                invoice_no: $('#modal_invoice_no').val(),
                supplier_item_id: $('#modal_supplier_item_id').val(),
                currency_id: $('#modal_currency_id').val(),
                notes: $('#modal_notes').val(),
                product: products,
                quantity: quantities,
                price: prices,
            };

            if (invoiceId) {
                formData._method = 'PUT';
                formData.voucher_no = $('#modal_voucher_no').val();
            }

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        Swal.fire('نجاح', data.message, 'success');
                        $('#invoiceModal').modal('hide');
                        location.reload();
                    } else {
                        Swal.fire('خطأ', data.message, 'error');
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessage = '';
                        for (const field in errors) {
                            errorMessage += errors[field][0] + '\n';
                        }
                        Swal.fire('خطأ', errorMessage, 'error');
                    } else {
                        Swal.fire('خطأ', 'حدث خطأ أثناء الحفظ', 'error');
                    }
                }
            });
        }

        // حذف الفاتورة
        function deleteInvoice(id) {
            Swal.fire({
                title: 'تأكيد الحذف',
                text: 'هل تريد حذف هذه الفاتورة؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('invoice.destroy', ':id') }}".replace(':id', id),
                        type: "POST",
                        data: { _method: 'DELETE' },
                        dataType: "json",
                        success: function(data) {
                            if (data.success) {
                                Swal.fire('تم الحذف', data.message, 'success');
                                location.reload();
                            } else {
                                Swal.fire('خطأ', data.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('خطأ', 'حدث خطأ أثناء الحذف', 'error');
                        }
                    });
                }
            });
        }

        // إعادة تعيين النموذج عند فتح Modal للإضافة
        $('#invoiceModal').on('show.bs.modal', function(e) {
            if (!$(e.relatedTarget).data('edit-mode')) {
                $('#invoiceForm')[0].reset();
                $('#invoice_id').val('');
                $('#invoiceModalTitle').text('إضافة فاتورة شراء');
                $('#modal-products-table tbody').empty();
                addProductRow();
                $('.grand-total').val('0.00');
            }
        });

        // دالة عامة لإضافة خيار للقائمة المنسدلة
        function addOption(select, id, label) {
            const $select = $(select);
            const value = String(id);
            if (!$select.find('option').filter(function() {
                    return this.value === value;
                }).length) {
                $select.append(new Option(label, value));
            }
            $select.val(value).trigger('change');
            if ($select.hasClass('selectpicker')) {
                $select.selectpicker('refresh');
            }
        }

        // معالجة الإضافة السريعة للعائلة والوحدة والصنف
        $(document).on('submit', '[data-ajax-quick-add]', function(event) {
            event.preventDefault();
            const form = this;
            const $form = $(form);
            const $submit = $form.find('[type="submit"]');
            const label = $submit.data('submit-label');

            $form.find('[data-form-error]').hide().text('');
            $form.find('[data-field-error]').text('');
            $form.find('.is-invalid').removeClass('is-invalid');
            $submit.prop('disabled', true).text('جارٍ الحفظ...');

            $.ajax({
                url: form.action,
                method: 'POST',
                data: $form.serialize(),
                dataType: 'json',
                headers: {
                    Accept: 'application/json'
                }
            }).done(function(entity) {
                if ($form.data('entity') === 'category') {
                    addOption('#quick_item_category', entity.id,
                        entity.category_name + ' - ' + entity.category_num);
                } else if ($form.data('entity') === 'unit') {
                    addOption('#quick_item_unit', entity.id, entity.unit_name + ' - ' + entity.unit_num);
                } else if ($form.data('entity') === 'item') {
                    // إضافة الصنف الجديد لقوائم الاختيار
                    const label = entity.item_num + ' ' + entity.item_name + ' الرصيد: ' + entity.balance;
                    const productSelects = $('.product-select');

                    productSelects.each(function() {
                        const value = String(entity.id);
                        if (!$(this).find('option').filter(function() {
                                return this.value === value;
                            }).length) {
                            $(this).append(new Option(label, value));
                        }
                        if ($(this).hasClass('selectpicker')) {
                            $(this).selectpicker('refresh');
                        }
                    });

                    // إضافة صف جديد وتحديد الصنف
                    addProductRow(entity.id, '', '');
                }
                form.reset();
                $form.closest('.modal').modal('hide');
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'تمت الإضافة بنجاح',
                        showConfirmButton: false,
                        timer: 2200
                    });
                }
            }).fail(function(xhr) {
                const errors = xhr.responseJSON && xhr.responseJSON.errors;
                let assignedError = false;
                if (errors) {
                    $.each(errors, function(field, messages) {
                        const $input = $form.find('[name]').filter(function() {
                            return this.name === field;
                        }).first();
                        const $feedback = $form.find('[data-field-error]').filter(function() {
                            return $(this).data('field-error') === field;
                        }).first();
                        if ($input.length && $feedback.length) {
                            $input.addClass('is-invalid');
                            $feedback.text(Array.isArray(messages) ? messages[0] : messages);
                            assignedError = true;
                        }
                    });
                }
                if (!assignedError) {
                    $form.find('[data-form-error]').text(
                        (xhr.responseJSON && xhr.responseJSON.message) ||
                        'تعذر الاتصال بالخادم. تحقق من الاتصال وحاول مرة أخرى.'
                    ).show();
                }
            }).always(function() {
                $submit.prop('disabled', false).text(label);
            });
        });
    </script>

    <!-- Quick Add Category Modal -->
    <div class="modal fade quick-add-modal" id="quickAddCategoryModal" tabindex="-1" role="dialog"
        aria-labelledby="quickAddCategoryTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('category.store') }}" method="post" data-ajax-quick-add data-entity="category">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title" id="quickAddCategoryTitle">إضافة عائلة جديدة</h5>
                            <p class="quick-add-context">سيتم اختيار العائلة الجديدة تلقائياً.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-4" role="alert" data-form-error></div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6">
                                <label for="quick_category_name">اسم العائلة</label>
                                <input id="quick_category_name" class="form-control" type="text" name="category_name"
                                    minlength="2" maxlength="255" required>
                                <div class="invalid-feedback" data-field-error="category_name"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="quick_category_num">رقم العائلة</label>
                                <input id="quick_category_num" class="form-control" type="number" name="category_num"
                                    step="1" required>
                                <div class="invalid-feedback" data-field-error="category_num"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary" data-submit-label="حفظ العائلة">حفظ العائلة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Add Unit Modal -->
    <div class="modal fade quick-add-modal" id="quickAddUnitModal" tabindex="-1" role="dialog"
        aria-labelledby="quickAddUnitTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('unit.store') }}" method="post" data-ajax-quick-add data-entity="unit">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title" id="quickAddUnitTitle">إضافة وحدة جديدة</h5>
                            <p class="quick-add-context">سيتم اختيار الوحدة الجديدة تلقائياً.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-4" role="alert" data-form-error></div>
                        <div class="form-group mb-0">
                            <label for="quick_unit_name">اسم الوحدة</label>
                            <input id="quick_unit_name" class="form-control" type="text" name="unit_name"
                                minlength="2" maxlength="255" required>
                            <div class="invalid-feedback" data-field-error="unit_name"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary" data-submit-label="حفظ الوحدة">حفظ الوحدة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
