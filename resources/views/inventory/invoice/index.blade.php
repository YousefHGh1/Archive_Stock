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
        :root {
            --accent: #7c5cff;
            --accent-dark: #6445ec;
            --accent-soft: #f0ecff;
            --accent-border: #c1ffe5;
            --ink: #3f4254;
            --muted: #7e8299;
            --line: #e9ecef;
            --bg-soft: #f8f9fb;
        }

        /* ===== Page header ===== */
        .page-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .page-head-title { display: flex; align-items: center; gap: 1rem; }
        .page-head-icon { width: 52px; height: 52px; border-radius: 14px; background: var(--accent-soft); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; }
        .page-head h1 { font-size: 1.35rem; font-weight: 800; color: var(--ink); margin: 0 0 .15rem; }
        .page-head p { margin: 0; color: var(--muted); font-size: .9rem; }
        .head-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: center; }
        .btn-solid { background: var(--accent); color: #fff; border: 1px solid var(--accent); padding: .6rem 1.1rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: .45rem; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-solid:hover { background: var(--accent-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(124,92,255,.3); text-decoration: none; }
        .btn-warn { background: #fff4de; color: #b77700; border: 1px solid #ffd98a; padding: .6rem 1.1rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: .45rem; text-decoration: none; transition: all .2s; }
        .btn-warn:hover { background: #ffa800; color: #fff; text-decoration: none; }
        .btn-soft { background: #fff; color: var(--ink); border: 1px solid #d9dce6; padding: .6rem 1.1rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: .45rem; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-soft:hover { background: var(--bg-soft); color: var(--ink); text-decoration: none; }

        /* ===== Stat cards ===== */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { position: relative; background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 1.1rem 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,.04); overflow: hidden; }
        .stat-card::before { content: ""; position: absolute; inset-block: 0; inset-inline-start: 0; width: 4px; background: var(--accent); }
        .stat-icon { width: 46px; height: 46px; border-radius: 12px; background: var(--accent-soft); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
        .stat-value { font-size: 1.4rem; font-weight: 800; color: var(--ink); line-height: 1.2; }
        .stat-label { font-size: .85rem; color: var(--muted); font-weight: 500; margin-top: .15rem; }
        .stat-card.is-green::before { background: #1bc5bd; } .stat-card.is-green .stat-icon { background: #e1f8f6; color: #1bc5bd; }
        .stat-card.is-orange::before { background: #ffa800; } .stat-card.is-orange .stat-icon { background: #fff4de; color: #ffa800; }

        /* ===== Cards ===== */
        .panel-card { background: #fff; border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,.05); margin-bottom: 1.5rem; overflow: hidden; }
        .panel-card-header { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--line); background: linear-gradient(180deg, #fff 0%, var(--bg-soft) 100%); }
        .panel-card-title { font-size: 1.05rem; font-weight: 700; color: var(--ink); margin: 0; display: flex; align-items: center; gap: .6rem; }
        .panel-card-title i { color: var(--accent); font-size: 1.3rem; }
        .panel-card-body { padding: 1.5rem; }

        /* ===== Filter ===== */
        .filter-form { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem 1.25rem; align-items: end; }
        .filter-label { display: block; font-size: .82rem; font-weight: 600; color: var(--muted); margin-bottom: .4rem; }
        .filter-input { width: 100%; padding: .6rem .9rem; border: 1px solid #d9dce6; border-radius: 8px; font-size: .92rem; background: #fff; color: var(--ink); transition: all .2s; }
        .filter-input:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 0 3px rgba(124,92,255,.15); }
        .filter-actions { display: flex; gap: .6rem; }
        .filter-actions .btn-solid, .filter-actions .btn-soft { flex: 1; justify-content: center; }

        /* ===== Datatable ===== */
        .dataTables_wrapper .dataTable th, .dataTables_wrapper .dataTable td { padding: .85rem .75rem !important; }
        /* ترويسة الجدول: خلفية كحلية ونص أبيض واضح (تتغلب على ألوان قالب DataTables) */
        #example thead th,
        #example thead tr th,
        .dataTables_wrapper table.dataTable thead th,
        .dataTables_wrapper .dataTable thead th {
            background-color: #2f3447 !important;
            color: #fff !important;
            text-align: right;
            font-weight: 700;
            font-size: .9rem;
            white-space: nowrap;
            border: none !important;
            border-bottom: 3px solid var(--accent) !important;
        }
        #example thead th.sorting,
        #example thead th.sorting_asc,
        #example thead th.sorting_desc,
        #example thead th.sorting_disabled { color: #fff !important; }
        #example thead .sorting:before, #example thead .sorting:after,
        #example thead .sorting_asc:before, #example thead .sorting_asc:after,
        #example thead .sorting_desc:before, #example thead .sorting_desc:after { color: #fff !important; opacity: .55; }
        #example thead .sorting_asc:before, #example thead .sorting_desc:after { opacity: 1; }
        .dataTables_wrapper .dataTable tfoot th { text-align: right; }
        #example { border: 1px solid var(--line); }
        #example tbody td { color: #5e6278; border-bottom: 1px solid #f1f3f7; vertical-align: middle; }
        #example tbody tr:nth-child(even) { background: #fbfcfe; }
        #example tbody tr:hover { background: var(--accent-soft); }
        .dataTables_wrapper .dataTables_filter input, .dataTables_wrapper .dataTables_length select { border: 1px solid #d9dce6; border-radius: 8px; padding: .4rem .7rem; }
        div.dt-buttons { padding: .5rem 0; text-align: right; }
        div.dt-buttons .dt-button { margin: 0 2px !important; }
        .voucher-chip { display: inline-block; padding: .2rem .65rem; border-radius: 6px; background: var(--accent-soft); color: var(--accent-dark); font-weight: 700; font-size: .85rem; }
        .action-group { display: inline-flex; gap: .35rem; }
        .act-btn { width: 34px; height: 34px; border-radius: 8px; border: none; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; font-size: 1.1rem; text-decoration: none; }
        .act-btn:hover { transform: translateY(-2px); text-decoration: none; }
        .act-view { background: #e8f3ff; color: #3699ff; } .act-view:hover { background: #3699ff; color: #fff; }
        .act-edit { background: #e1f8f6; color: #1bc5bd; } .act-edit:hover { background: #1bc5bd; color: #fff; }
        .act-del { background: #ffeef0; color: #f64e60; } .act-del:hover { background: #f64e60; color: #fff; }

        /* ===== Modals ===== */
        .quick-add-modal { direction: rtl; text-align: right; }
        .quick-add-modal .modal-content { border: 1px solid #e5e0ef; border-radius: 14px; box-shadow: 0 12px 36px rgba(25,30,45,.18); }
        .quick-add-modal .modal-header, .quick-add-modal .modal-footer { justify-content: flex-start; }
        .quick-add-modal .modal-header .close { margin: -1rem auto -1rem -1rem; }
        .quick-add-heading { border-right: 3px solid var(--accent); padding-right: .75rem; }
        .quick-add-context { margin: .2rem 0 0; color: #596579; font-size: .8rem; line-height: 1.4; }
        .quick-add-modal label { font-weight: 600; }
        .quick-add-modal .modal-body { max-height: calc(100vh - 220px); overflow-y: auto; }
        .quick-add-modal input, .quick-add-modal select,
        #invoiceModal input, #invoiceModal select, #invoiceModal textarea { border: 1px solid var(--accent-border) !important; border-radius: 8px; }
        .quick-add-modal [data-form-error] { display: none; }
        .quick-add-modal [data-field-error] { display: block; }
        .quick-add-modal [data-field-error]:empty { display: none !important; }

        #invoiceModal .modal-content { border-radius: 14px; border: none; }
        #invoiceModal .modal-header { background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%); color: #fff; border-radius: 14px 14px 0 0; }
        #invoiceModal .modal-header .modal-title { color: #fff; font-weight: 700; }
        #invoiceModal .modal-header .close { color: #fff; opacity: .9; text-shadow: none; }
        #invoiceModal .modal-body { background: var(--bg-soft); }
        #invoiceModal .modal-footer { background: #fff; border-radius: 0 0 14px 14px; }
        .modal-section { background: #fff; border: 1px solid var(--line); border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; }
        .modal-section-title { font-weight: 700; color: var(--ink); margin-bottom: 1rem; display: flex; align-items: center; gap: .5rem; }
        .modal-section-title i { color: var(--accent); }
        .modal-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem 1.25rem; }
        .modal-grid .span-all { grid-column: 1 / -1; }
        .modal-grid label { display: block; font-size: .85rem; font-weight: 700; color: var(--ink); margin-bottom: .35rem; }
        .field-with-add { display: flex; gap: .5rem; align-items: stretch; }
        .field-with-add > .bootstrap-select, .field-with-add > select { flex: 1; width: auto !important; min-width: 0; }
        .btn-add-inline { width: 42px; flex-shrink: 0; border: 1px dashed var(--accent); background: var(--accent-soft); color: var(--accent-dark); border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; padding: 0; }
        .btn-add-inline:hover { background: var(--accent); color: #fff; border-style: solid; }
        .btn-tool { background: var(--accent-soft); color: var(--accent-dark); border: none; padding: .4rem .8rem; border-radius: 8px; font-weight: 600; font-size: .82rem; display: inline-flex; align-items: center; gap: .3rem; cursor: pointer; text-decoration: none; transition: all .2s; }
        .btn-tool:hover { background: var(--accent); color: #fff; text-decoration: none; }
        .modal-items-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 10px; }
        #modal-products-table { margin: 0; min-width: 760px; border: none; }
        #modal-products-table thead th { background: #2f3447; color: #fff; border: none; padding: .8rem 1rem; font-weight: 600; white-space: nowrap; }
        #modal-products-table tbody td { padding: .65rem .75rem; vertical-align: top; border-color: #f1f3f7; }
        #modal-products-table .row-total, #modal-products-table .grand-total { background: var(--accent-soft); font-weight: 800; color: var(--accent-dark); text-align: center; }
        #modal-products-table tfoot td { background: var(--bg-soft); vertical-align: middle; }

        /* أزرار الإضافة والحذف */
        .action-buttons { display: flex; gap: 5px; align-items: center; }
        .btn-icon-sm { width: 34px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: all .3s ease; font-size: 14px; }
        .btn-icon-sm:hover { transform: scale(1.1); box-shadow: 0 4px 8px rgba(0,0,0,.2); }
        .btn-icon-sm i { font-size: 16px; }
        .btn-icon-sm:active { transform: scale(.95); }
        .btn-add-custom { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; color: #fff; }
        .btn-add-custom:hover { background: linear-gradient(135deg, #218838 0%, #1aa885 100%); color: #fff; }
        .btn-delete-custom { background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%); border: none; color: #fff; }
        .btn-delete-custom:hover { background: linear-gradient(135deg, #c82333 0%, #d63031 100%); color: #fff; }

        @media (max-width: 575.98px) {
            .quick-add-modal .modal-dialog { width: calc(100% - 1rem); margin: .5rem; }
            .head-actions { width: 100%; }
            .filter-actions { flex-direction: column; }
        }
    </style>
@endsection

@section('content')
    @php
        $invoiceCount = $invoices->count();
        $supplierCount = collect($invoices)->pluck('supplier_item_name')->filter()->unique()->count();
        $latestDate = collect($invoices)->max('voucher_date');
    @endphp

    <div class="container">

        <!-- Page Header -->
        <div class="page-head">
            <div class="page-head-title">
                <div class="page-head-icon"><i class="la la-file-invoice-dollar"></i></div>
                <div>
                    <h1>عرض فواتير الشراء</h1>
                    <p>إدارة سندات الإدخال وفواتير الموردين</p>
                </div>
            </div>
            <div class="head-actions">
                <button type="button" class="btn-solid" data-toggle="modal" data-target="#invoiceModal">
                    <i class="la la-plus-circle"></i> إنشاء فاتورة جديدة
                </button>
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('invoice.skipped-vouchers') }}" class="btn-warn">
                        <i class="la la-step-forward"></i> السندات التي تم تخطيها
                    </a>
                @endif
                <x-add-resource-button />
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="la la-file-invoice"></i></div>
                <div>
                    <div class="stat-value">{{ $invoiceCount }}</div>
                    <div class="stat-label">عدد الفواتير</div>
                </div>
            </div>
            <div class="stat-card is-green">
                <div class="stat-icon"><i class="la la-truck"></i></div>
                <div>
                    <div class="stat-value">{{ $supplierCount }}</div>
                    <div class="stat-label">عدد الموردين</div>
                </div>
            </div>
            <div class="stat-card is-orange">
                <div class="stat-icon"><i class="la la-calendar-check"></i></div>
                <div>
                    <div class="stat-value">{{ $latestDate ?? '-' }}</div>
                    <div class="stat-label">تاريخ آخر فاتورة</div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="panel-card">
            <div class="panel-card-header">
                <h3 class="panel-card-title"><i class="la la-search"></i> بحث متقدم بالتاريخ</h3>
            </div>
            <div class="panel-card-body">
                <form action="{{ url('inventory/invoice/searchdate') }}" method="POST" class="filter-form">
                    @csrf
                    <div>
                        <label class="filter-label" for="start_date">من تاريخ</label>
                        <input name="start_date" type="date" class="filter-input" id="start_date" value="{{ request('start_date') }}" />
                    </div>
                    <div>
                        <label class="filter-label" for="end_date">إلى تاريخ</label>
                        <input name="end_date" type="date" class="filter-input" id="end_date" value="{{ request('end_date') }}" />
                    </div>
                    <div>
                        <div class="filter-actions">
                            <button type="submit" class="btn-solid"><i class="la la-search"></i> بحث</button>
                            <a href="{{ url('inventory/invoice') }}" class="btn-soft"><i class="la la-refresh"></i> إعادة ضبط</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoices table -->
        <div class="panel-card">
            <div class="panel-card-header">
                <h3 class="panel-card-title"><i class="la la-table"></i> قائمة الفواتير</h3>
            </div>
            <div class="panel-card-body">
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
                                <td><span class="voucher-chip">{{ $invoice->voucher_no }}</span></td>
                                <td>{{ $invoice->voucher_date }}</td>
                                <td>{{ $invoice->invoice_no }}</td>
                                <td>{{ $invoice->supplier_item_name }}</td>
                                <td>{{ $invoice->name }}</td>
                                <td>{{ $invoice->notes ?? '-' }}</td>
                                <td>{!! $invoice->products !!}</td>
                                <td data-field="Actions" data-autohide-disabled="false" aria-label="null" class="datatable-cell">
                                    <span class="action-group">
                                        <a href="{{ url('/inventory/invoice/' . $invoice->id) }}" class="act-btn act-view" title="عرض الفاتورة">
                                            <i class="la la-eye"></i>
                                        </a>
                                        <button type="button" class="act-btn act-del" title="حذف الفاتورة"
                                            onclick="deleteInvoice({{ $invoice->id }})">
                                            <i class="la la-trash"></i>
                                        </button>
                                        @canany(['invoices-update', 'admin', 'stock', 'Super-User'])
                                            <button type="button" class="act-btn act-edit" title="تعديل الفاتورة"
                                                onclick="editInvoice({{ $invoice->id }})">
                                                <i class="la la-edit"></i>
                                            </button>
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
    </div>

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
                        <input type="hidden" name="skip_voucher_no" id="modal_skip_voucher_no" value="0">
                        <div class="alert alert-danger mb-4" id="invoiceFormError" role="alert" style="display:none"></div>

                        <div class="modal-section">
                            <div class="modal-section-title"><i class="la la-clipboard-list"></i> بيانات السند</div>
                            <div class="modal-grid">
                                <div>
                                    <label for="modal_voucher_no">رقم السند</label>
                                    <input type="number" class="form-control" id="modal_voucher_no" value="{{ $newVoucherNo ?? '' }}" disabled>
                                </div>
                                <div>
                                    <label for="modal_voucher_date">تاريخ السند</label>
                                    <input type="date" class="form-control" id="modal_voucher_date" max="{{ date('Y-m-d') }}" required>
                                </div>
                                <div>
                                    <label for="modal_invoice_no">رقم الفاتورة</label>
                                    <input type="number" class="form-control" id="modal_invoice_no" required>
                                </div>
                                <div>
                                    <label for="modal_supplier_item_id">المورد</label>
                                    <div class="field-with-add">
                                        <select class="form-control selectpicker" id="modal_supplier_item_id" data-live-search="true" required>
                                            <option value="">اختر المورد</option>
                                            @foreach ($supplier_item as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_item_name }} {{ $supplier->supplier_item_num }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn-add-inline" title="إضافة مورد جديد" data-toggle="modal" data-target="#quickAddSupplierModal">
                                            <i class="ki ki-solid-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="modal_currency_id">معامل الصرف</label>
                                    <div class="field-with-add">
                                        <select class="form-control" id="modal_currency_id" required>
                                            <option value="">اختر معامل الصرف</option>
                                            @foreach ($currency as $curr)
                                                <option value="{{ $curr->id }}">{{ $curr->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn-add-inline" title="إضافة معامل صرف جديد" data-toggle="modal" data-target="#quickAddCurrencyModal">
                                            <i class="ki ki-solid-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="span-all">
                                    <label for="modal_notes">ملاحظات</label>
                                    <textarea class="form-control" id="modal_notes" rows="2" maxlength="5000"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-section mb-0">
                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3" style="gap:.5rem">
                                <div class="modal-section-title mb-0"><i class="la la-boxes"></i> الأصناف</div>
                                <div class="d-flex" style="gap:.5rem">
                                    <button type="button" class="btn-tool" data-toggle="modal" data-target="#quickAddItemModal">
                                        <i class="ki ki-solid-plus"></i> صنف جديد
                                    </button>
                                    <a href="{{ url('inventory/item') }}" class="btn-tool" title="عرض الأصناف" target="_blank">
                                        <i class="far fa-eye"></i> عرض الأصناف
                                    </a>
                                </div>
                            </div>
                            <div class="modal-items-wrap">
                                <table class="table table-bordered" id="modal-products-table">
                                    <thead>
                                        <tr>
                                            <th width="45%">الصنف</th>
                                            <th>الكمية</th>
                                            <th>السعر</th>
                                            <th>الإجمالي</th>
                                            <th width="8%">إجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="product-row">
                                            <td>
                                                <select name="product[]" class="form-control selectpicker product-select"
                                                    data-live-search="true" data-size="7" title="اختر الصنف">
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
                                                <input type="number" step="0.01" min="0.1" name="quantity[]" class="form-control quantity-input" oninput="calculateRowTotal(this)">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" name="price[]" class="form-control price-input" oninput="calculateRowTotal(this)">
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
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" id="saveInvoiceButton" onclick="saveInvoice()">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Item Modal (إضافة صنف كامل) -->
    <div class="modal fade quick-add-modal" id="quickAddItemModal" tabindex="-1" role="dialog" aria-hidden="true">
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

    <!-- Quick Add Supplier Modal -->
    <div class="modal fade quick-add-modal" id="quickAddSupplierModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('supplier_item.store') }}" method="post" data-ajax-quick-add data-entity="supplier">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title">إضافة مورد جديد</h5>
                            <p class="quick-add-context">سيتم اختيار المورد الجديد تلقائياً.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-4" role="alert" data-form-error></div>
                        <div class="form-group">
                            <label for="quick_supplier_name">اسم المورد</label>
                            <input id="quick_supplier_name" class="form-control" type="text" name="supplier_item_name" maxlength="255" required>
                            <div class="invalid-feedback" data-field-error="supplier_item_name"></div>
                        </div>
                        <div class="form-group">
                            <label for="quick_supplier_address">العنوان</label>
                            <input id="quick_supplier_address" class="form-control" type="text" name="address" maxlength="255" required>
                            <div class="invalid-feedback" data-field-error="address"></div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="quick_supplier_phone">رقم الهاتف</label>
                            <input id="quick_supplier_phone" class="form-control" type="tel" inputmode="numeric" name="phone" maxlength="30" required>
                            <div class="invalid-feedback" data-field-error="phone"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary" data-submit-label="حفظ المورد">حفظ المورد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Add Currency Modal -->
    <div class="modal fade quick-add-modal" id="quickAddCurrencyModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('currency.store') }}" method="post" data-ajax-quick-add data-entity="currency">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title">إضافة معامل صرف جديد</h5>
                            <p class="quick-add-context">سيتم اختيار معامل الصرف الجديد تلقائياً.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-4" role="alert" data-form-error></div>
                        <div class="form-group">
                            <label for="quick_currency_name">اسم العملة</label>
                            <input id="quick_currency_name" class="form-control" type="text" name="name" maxlength="255" required>
                            <div class="invalid-feedback" data-field-error="name"></div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="quick_currency_value">قيمة معامل الصرف</label>
                            <input id="quick_currency_value" class="form-control" type="number" name="value" min="0.000001" step="any" required>
                            <div class="invalid-feedback" data-field-error="value"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary" data-submit-label="حفظ معامل الصرف">حفظ معامل الصرف</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/features/miscellaneous/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/data-json.js') }}" type="text/javascript"></script>

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

        // تحديث رقم الصنف عند اختيار العائلة (آخر رقم + 1)
        $('#quick_item_category').on('change', function() {
            const categoryId = $(this).val();
            $('#quick_item_num').val('');
            if (categoryId) {
                $.ajax({
                    url: "{{ route('category.last_item', ':id') }}".replace(':id', categoryId),
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        const last = data && data.last_item_num !== undefined
                            ? parseInt(data.last_item_num, 10)
                            : NaN;
                        if (!isNaN(last)) {
                            $('#quick_item_num').val(String(last + 1));
                        } else {
                            $('#quick_item_num').val(categoryId + '01');
                        }
                    },
                    error: function() {
                        $('#quick_item_num').val('');
                    }
                });
            }
        });

        // نسخة ثابتة من خيارات الأصناف (لا تعتمد على وجود صف في الجدول)
        let PRODUCT_OPTIONS_HTML = $('#modal-products-table select.product-select').first().html() || '';

        function createProductSelect() {
            return $('<select>', {
                name: 'product[]',
                class: 'form-control selectpicker product-select',
                'data-live-search': 'true',
                'data-size': '7',
                'data-width': '100%',
                title: 'اختر الصنف'
            }).html(PRODUCT_OPTIONS_HTML);
        }

        // إضافة صف جديد للجدول
        function addProductRow(itemId = null, quantity = '', price = '') {
            const $newSelect = createProductSelect();
            $newSelect.find('option').prop('selected', false);
            $newSelect.val('');

            const $newRow = $('<tr class="product-row">');
            $newRow.append($('<td>').append($newSelect));
            $newRow.append($('<td>').append($('<input>', {
                type: 'number', step: '0.01', min: '0.1', name: 'quantity[]',
                class: 'form-control quantity-input',
                oninput: 'calculateRowTotal(this)', value: quantity
            })));
            $newRow.append($('<td>').append($('<input>', {
                type: 'number', step: '0.01', min: '0', name: 'price[]',
                class: 'form-control price-input',
                oninput: 'calculateRowTotal(this)', value: price
            })));
            $newRow.append($('<td>').append($('<input>', {
                type: 'number', step: '0.01',
                class: 'form-control row-total', readonly: true,
                value: ((parseFloat(quantity) || 0) * (parseFloat(price) || 0)).toFixed(2)
            })));
            $newRow.append($('<td>').append($('<div class="action-buttons">')
                .append($('<button type="button" class="btn-icon-sm btn-add-custom add-row" title="إضافة صف">')
                    .append('<i class="ki ki-plus"></i>'))
                .append($('<button type="button" class="btn-icon-sm btn-delete-custom delete-row" title="حذف الصف">')
                    .append('<i class="ki ki-trash"></i>'))));

            $('#modal-products-table tbody').append($newRow);
            $newSelect.selectpicker();

            if (itemId) {
                $newSelect.val(String(itemId)).selectpicker('refresh');
            }

            calculateGrandTotal();
            return $newSelect;
        }

        // إعادة تهيئة نموذج الفاتورة لوضع الإضافة
        function resetInvoiceForm() {
            $('#invoice_id').val('');
            $('#invoiceModalTitle').text('إضافة فاتورة شراء');
            $('#invoiceFormError').hide().text('');
            $('#modal_voucher_date').val(new Date().toISOString().slice(0, 10));
            $('#modal_invoice_no').val('');
            $('#modal_notes').val('');
            $('#modal_supplier_item_id').val('').selectpicker('refresh');
            $('#modal_currency_id').val('');
            $('#modal-products-table tbody').empty();
            addProductRow();
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
                        $('#modal_notes').val(data.invoice.notes);

                        $('#modal_supplier_item_id').val(String(data.invoice.supplier_item_id)).selectpicker('refresh');
                        $('#modal_currency_id').val(String(data.invoice.currency_id));

                        // تحميل الأصناف
                        $('#modal-products-table tbody').empty();
                        data.invoice.products.forEach(function(product, index) {
                            addProductRow(product.item_id, product.quantity, product.price);
                        });
                        if (!$('#modal-products-table tbody tr').length) {
                            addProductRow();
                        }

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
                const productId = $(this).find('select.product-select').val();
                const quantity = $(this).find('.quantity-input').val();
                const price = $(this).find('.price-input').val();

                if (productId && quantity !== '' && price !== '') {
                    products.push(productId);
                    quantities.push(quantity);
                    prices.push(price);
                }
            });

            const requiredFields = [
                ['modal_voucher_date', 'تاريخ السند'],
                ['modal_invoice_no', 'رقم الفاتورة'],
                ['modal_supplier_item_id', 'المورد'],
                ['modal_currency_id', 'معامل الصرف']
            ];
            for (const [fieldId, label] of requiredFields) {
                if (!$('#' + fieldId).val()) {
                    Swal.fire('تنبيه', 'يرجى إدخال ' + label, 'warning');
                    $('#' + fieldId).trigger('focus');
                    return;
                }
            }

            const rows = $('#modal-products-table tbody tr');
            let invalidRow = false;
            rows.each(function() {
                const productId = $(this).find('select.product-select').val();
                const quantity = $(this).find('.quantity-input').val();
                const price = $(this).find('.price-input').val();
                if (!productId && !quantity && !price) return;
                let missing = null;
                if (!productId) missing = 'الصنف';
                else if (!quantity || Number(quantity) < 0.1) missing = 'الكمية';
                else if (price === '' || Number(price) < 0) missing = 'السعر';
                if (missing) {
                    invalidRow = missing;
                    return false;
                }
            });
            if (invalidRow) {
                Swal.fire('تنبيه', 'الحقل الناقص في أحد الصفوف: ' + invalidRow + '. أكمله أو احذف الصف.', 'warning');
                return;
            }

            if (products.length === 0) {
                Swal.fire('تنبيه', 'يجب إضافة صنف واحد على الأقل', 'warning');
                return;
            }

            const formData = {
                _token: $('#invoiceForm input[name="_token"]').val(),
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
            } else {
                formData.skip_voucher_no = $('#modal_skip_voucher_no').val() || '0';
            }

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                dataType: "json",
                beforeSend: function(xhr) {
                    const csrfToken = $('#invoiceForm input[name="_token"]').val();
                    if (csrfToken) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                    }
                    $('#saveInvoiceButton').prop('disabled', true).text('جارٍ الحفظ...');
                },
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
                    } else if (xhr.status === 422 && xhr.responseJSON?.message) {
                        $('#invoiceFormError').text(xhr.responseJSON.message).show();
                    } else {
                        const serverMessage = xhr.responseJSON?.message;
                        const message = serverMessage || ('تعذر حفظ الفاتورة (HTTP ' + xhr.status + '). راجع سجل Laravel لمعرفة السبب.');
                        $('#invoiceFormError').text(message).show();
                    }
                },
                complete: function() {
                    $('#saveInvoiceButton').prop('disabled', false).text('حفظ');
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
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'DELETE'
                        },
                        dataType: "json",
                        success: function(data) {
                            if (data.success) {
                                Swal.fire('تم الحذف', data.message, 'success');
                                location.reload();
                            } else {
                                Swal.fire('خطأ', data.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message ||
                                ('تعذر حذف الفاتورة (HTTP ' + xhr.status + '). حدّث الصفحة وحاول مجددًا.');
                            Swal.fire('خطأ', message, 'error');
                        }
                    });
                }
            });
        }

        // ضمان وجود صف واحد على الأقل عند فتح النافذة
        $('#invoiceModal').on('show.bs.modal', function() {
            if (!$('#modal-products-table tbody tr').length) {
                addProductRow();
            }
        });

        // تنظيف النموذج عند الإغلاق ليعود لوضع الإضافة
        $('#invoiceModal').on('hidden.bs.modal', function(e) {
            if (e.target !== this) return;
            resetInvoiceForm();
        });

        $('#invoiceModal').on('shown.bs.modal', function() {
            $(this).find('select.product-select').each(function() {
                const $select = $(this);
                if (!$select.data('selectpicker')) {
                    $select.selectpicker();
                } else {
                    $select.selectpicker('refresh');
                }
            });
            $(this).find('#modal_supplier_item_id').selectpicker('refresh');
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

        // معالجة الإضافة السريعة للبيانات المرتبطة بالفاتورة
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
                } else if ($form.data('entity') === 'supplier') {
                    addOption('#modal_supplier_item_id', entity.id,
                        entity.supplier_item_name + ' ' + (entity.supplier_item_num || ''));
                    $('#modal_supplier_item_id').trigger('change');
                } else if ($form.data('entity') === 'currency') {
                    addOption('#modal_currency_id', entity.id, entity.name);
                    $('#modal_currency_id').trigger('change');
                } else if ($form.data('entity') === 'item') {
                    const label = entity.item_num + ' ' + entity.item_name + ' الرصيد: ' + entity.balance;
                    if (PRODUCT_OPTIONS_HTML.indexOf('value="' + entity.id + '"') === -1) {
                        PRODUCT_OPTIONS_HTML += $('<option>').val(String(entity.id)).text(label).prop('outerHTML');
                    }
                    const productSelects = $('#modal-products-table tbody select.product-select');

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

                    let target = productSelects.filter(function() {
                        return !this.value;
                    }).first();
                    if (!target.length) {
                        target = addProductRow();
                    }
                    target.val(String(entity.id)).trigger('change').selectpicker('refresh');
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

        $('.quick-add-modal').on('hidden.bs.modal', function() {
            const form = $(this).find('[data-ajax-quick-add]')[0];
            if (form) {
                const $form = $(form);
                $form.find('[data-form-error]').hide().text('');
                $form.find('[data-field-error]').text('');
                $form.find('.is-invalid').removeClass('is-invalid');
            }
            // عند إغلاق نافذة متداخلة، أبقِ تمرير الصفحة مقفلاً إذا كانت نافذة أخرى ما زالت مفتوحة
            if ($('.modal.show').length) {
                $('body').addClass('modal-open');
            }
        });

        $('#quickAddItemModal').on('shown.bs.modal', function() {
            $(this).find('.selectpicker').selectpicker('refresh');
        });
    </script>
@endsection