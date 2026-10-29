@extends('layout.master')

@section('title')
    اضافة فاتورة شراء
@endsection

@section('page_title')
    لوحة التحكم
@endsection

@section('sub_main')
    الفواتير
@endsection

@section('sub_title')
    اضافة فاتورة شراء
@endsection

@section('css')
    <style>
        :root {
            --accent: #7c5cff;
            --accent-dark: #6445ec;
            --accent-soft: #f0ecff;
            --accent-border: #d5c1ff;
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
        .btn-soft { background: #fff; color: var(--ink); border: 1px solid #d9dce6; padding: .6rem 1.1rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: .45rem; cursor: pointer; transition: all .2s; text-decoration: none; }
        .btn-soft:hover { background: var(--bg-soft); color: var(--ink); text-decoration: none; }

        /* ===== Form cards ===== */
        .form-card { background: #fff; border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,.05); margin-bottom: 1.5rem; overflow: hidden; }
        .form-card-header { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--line); background: linear-gradient(180deg, #fff 0%, var(--bg-soft) 100%); }
        .form-card-title { font-size: 1.05rem; font-weight: 700; color: var(--ink); margin: 0; display: flex; align-items: center; gap: .6rem; }
        .form-card-title i { color: var(--accent); font-size: 1.3rem; }
        .form-card-body { padding: 1.5rem; }
        .card-tools { display: flex; gap: .5rem; flex-wrap: wrap; }
        .btn-tool { background: var(--accent-soft); color: var(--accent-dark); border: none; padding: .45rem .9rem; border-radius: 8px; font-weight: 600; font-size: .85rem; display: inline-flex; align-items: center; gap: .35rem; cursor: pointer; text-decoration: none; transition: all .2s; }
        .btn-tool:hover { background: var(--accent); color: #fff; text-decoration: none; }

        /* ===== Fields ===== */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.1rem 1.5rem; }
        .form-grid .span-all { grid-column: 1 / -1; }
        .field-label { display: block; font-size: .85rem; font-weight: 700; color: var(--ink); margin-bottom: .4rem; }
        .field-label .req { color: #f64e60; margin-inline-start: .2rem; }
        .field-with-add { display: flex; gap: .5rem; align-items: stretch; }
        .field-with-add > .bootstrap-select, .field-with-add > select, .field-with-add > .form-control { flex: 1; width: auto !important; min-width: 0; }
        .btn-add-inline { width: 42px; flex-shrink: 0; border: 1px dashed var(--accent-border); background: var(--accent-soft); color: var(--accent-dark); border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; padding: 0; }
        .btn-add-inline:hover { background: var(--accent); color: #fff; border-style: solid; }

        .form-card .form-control, .form-card .btn.dropdown-toggle.btn-light { border: 1px solid var(--accent-border) !important; border-radius: 8px; }
        .form-card .form-control:focus { border-color: var(--accent) !important; box-shadow: 0 0 0 3px rgba(124,92,255,.15); }
        .form-card .form-control:disabled { background: var(--accent-soft); font-weight: 800; color: var(--accent-dark); }
        .btn.btn-light:hover:not(.btn-text):not(:disabled):not(.disabled),
        .btn.btn-light:focus:not(.btn-text),
        .btn.btn-light.focus:not(.btn-text),
        .btn.dropdown-toggle.btn-light.bs-placeholder { border: 1px solid var(--accent-border) !important; }

        .is-invalid { border-color: #dc3545 !important; }
        .invalid-feedback, .field-error { display: block !important; width: 100%; margin-top: 5px; font-size: 12px; }
        .product-row .is-invalid { border: 1px solid #dc3545 !important; }
        .bootstrap-select.is-invalid > .dropdown-toggle, .bootstrap-select .dropdown-toggle.is-invalid { border-color: #dc3545 !important; }

        /* ===== Items table ===== */
        .items-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 10px; }
        #products-table { margin: 0; min-width: 720px; border: none; }
        #products-table thead th { background: #2f3447; color: #fff; border: none; padding: .85rem 1rem; font-weight: 600; white-space: nowrap; }
        #products-table tbody td { padding: .7rem .8rem; vertical-align: top; border-color: #f1f3f7; }
        #products-table tbody tr:nth-child(even) { background: #fbfcfe; }
        #products-table .row-total { background: var(--accent-soft); font-weight: 800; color: var(--accent-dark); text-align: center; }
        .row-btn { width: 38px; height: 38px; border: none; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; color: #fff; cursor: pointer; transition: all .2s; font-size: 1.1rem; }
        .row-btn:hover { transform: scale(1.08); box-shadow: 0 4px 10px rgba(0,0,0,.2); }
        .row-btn.is-add { background: linear-gradient(135deg, #1bc5bd, #17a79f); }
        .row-btn.is-del { background: linear-gradient(135deg, #f64e60, #e0364a); }

        /* ===== Action bar ===== */
        .action-bar { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; padding: 1.1rem 1.5rem; background: var(--bg-soft); border-top: 1px solid var(--line); }
        .grand-total-box { display: flex; align-items: center; gap: .75rem; }
        .grand-total-box label { margin: 0; font-weight: 700; color: var(--ink); }
        .grand-total-box input { width: 160px; text-align: center; font-weight: 800; font-size: 1.1rem; color: var(--accent-dark); background: var(--accent-soft); }
        .action-buttons-bar { display: flex; gap: .6rem; }
        .btn-save { background: var(--accent); color: #fff; border: none; padding: .7rem 2rem; border-radius: 8px; font-weight: 700; transition: all .2s; display: inline-flex; align-items: center; gap: .4rem; }
        .btn-save:hover { background: var(--accent-dark); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(124,92,255,.35); color: #fff; }
        .btn-skip { background: #fff4de; color: #b77700; border: 1px solid #ffd98a; padding: .7rem 1.4rem; border-radius: 8px; font-weight: 700; transition: all .2s; display: inline-flex; align-items: center; gap: .4rem; }
        .btn-skip:hover { background: #ffa800; color: #fff; }

        /* ===== Quick-add modals ===== */
        .quick-add-modal { direction: rtl; text-align: right; }
        .quick-add-modal .modal-content { border: 1px solid #e5e0ef; border-radius: 14px; box-shadow: 0 12px 36px rgba(25,30,45,.18); }
        .quick-add-modal .modal-header, .quick-add-modal .modal-footer { justify-content: flex-start; }
        .quick-add-modal .modal-header .close { margin: -1rem auto -1rem -1rem; }
        .quick-add-heading { border-right: 3px solid var(--accent); padding-right: .75rem; }
        .quick-add-context { margin: .2rem 0 0; color: #596579; font-size: .8rem; line-height: 1.4; }
        .quick-add-modal label { font-weight: 600; }
        .quick-add-modal input, .quick-add-modal select { border: 1px solid var(--accent-border) !important; border-radius: 8px; }
        .quick-add-modal [data-form-error] { display: none; }
        .quick-add-modal [data-field-error] { display: block; min-height: 0; }
        .quick-add-modal [data-field-error]:empty { display: none !important; }

        @media (max-width: 768px) {
            .head-actions { width: 100%; }
            .action-bar { flex-direction: column; align-items: stretch; }
            .action-buttons-bar { flex-direction: column; }
            .grand-total-box { justify-content: space-between; }
        }
    </style>
@endsection

@section('content')
    <div class="container">

        <!-- Page Header -->
        <div class="page-head">
            <div class="page-head-title">
                <div class="page-head-icon"><i class="la la-file-invoice-dollar"></i></div>
                <div>
                    <h1>بيانات الفواتير (سند إدخال)</h1>
                    <p>إضافة فاتورة شراء جديدة وإدخال أصنافها إلى المخزون</p>
                </div>
            </div>
            <div class="head-actions">
                <a href="{{ url('/inventory/invoice') }}" class="btn-soft"><i class="la la-list"></i> عرض فواتير الشراء</a>
                <x-add-resource-button />
            </div>
        </div>

        <form action="{{ route('invoice.store') }}" method="post" class="form" novalidate
            enctype="multipart/form-data" id="kt_form">
            @csrf
            <input type="hidden" name="skip_voucher_no" value="0" id="skip_voucher_no">
            <input type="hidden" name="skip_reason" value="" id="skip_reason">

            <!-- Voucher data -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3 class="form-card-title"><i class="la la-clipboard-list"></i> بيانات السند</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-grid">

                        <div>
                            <label for="voucher_no" class="field-label">رقم السند</label>
                            <input type="number" class="@error('voucher_no') is-invalid @enderror form-control"
                                id="voucher_no" name="voucher_no" disabled
                                value="{{ old('voucher_no', $newVoucherNo) }}">
                            @error('voucher_no')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div>
                            <label for="voucher_date" class="field-label">تاريخ السند <span class="req">*</span></label>
                            <input type="date" class="form-control @error('voucher_date') is-invalid @enderror"
                                id="voucher_date" name="voucher_date"
                                value="{{ old('voucher_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                            @error('voucher_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="invoice_no" class="field-label">رقم الفاتورة <span class="req">*</span></label>
                            <input type="number" class="form-control @error('invoice_no') is-invalid @enderror"
                                id="invoice_no" name="invoice_no" value="{{ old('invoice_no') }}"
                                placeholder="أدخل رقم الفاتورة" required>
                            @error('invoice_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="supplier_item_id" class="field-label">المورد <span class="req">*</span></label>
                            <div class="field-with-add">
                                <select class="@error('supplier_item_id') is-invalid @enderror form-control selectpicker"
                                    data-live-search="true" title="أدخل اسم المورد " name="supplier_item_id"
                                    id="supplier_item_id" required>
                                    <option value="">اختر المورد</option>
                                    @foreach ($supplier_item as $supplier_items)
                                        <option value="{{ $supplier_items->id }}"
                                            {{ old('supplier_item_id') == $supplier_items->id ? 'selected' : '' }}>
                                            {{ $supplier_items->supplier_item_name }}
                                            {{ $supplier_items->supplier_item_num }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-add-inline" title="اضافة مورد جديد"
                                    aria-label="اضافة مورد جديد" data-toggle="modal"
                                    data-target="#quickAddSupplierModal">
                                    <i class="ki ki-solid-plus icon-md"></i>
                                </button>
                            </div>
                            @error('supplier_item_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="currency_id" class="field-label">معامل الصرف <span class="req">*</span></label>
                            <div class="field-with-add">
                                <select class="form-control @error('currency_id') is-invalid @enderror"
                                    name="currency_id" id="currency_id" required>
                                    <option value="">اختر معامل الصرف</option>
                                    @foreach ($currency as $currencys)
                                        <option value="{{ $currencys->id }}"
                                            {{ old('currency_id') == $currencys->id ? 'selected' : '' }}>
                                            {{ $currencys->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn-add-inline" title="اضافة معامل صرف جديد"
                                    aria-label="اضافة معامل صرف جديد" data-toggle="modal"
                                    data-target="#quickAddCurrencyModal">
                                    <i class="ki ki-solid-plus icon-md"></i>
                                </button>
                            </div>
                            @error('currency_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="span-all">
                            <label for="notes" class="field-label">ملاحظات</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                rows="3" maxlength="5000" placeholder="أي ملاحظات إضافية على السند...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3 class="form-card-title"><i class="la la-boxes"></i> الأصناف</h3>
                    <div class="card-tools">
                        <button type="button" class="btn-tool" title="اضافة صنف جديد" aria-label="اضافة صنف جديد"
                            data-toggle="modal" data-target="#quickAddItemModal">
                            <i class="ki ki-solid-plus"></i> صنف جديد
                        </button>
                        <a href="{{ url('inventory/item') }}" class="btn-tool" title="عرض الأصناف" target="_blank">
                            <i class="far fa-eye"></i> عرض الأصناف
                        </a>
                    </div>
                </div>
                <div class="form-card-body">
                    <div class="items-wrap">
                        <table class="table table-bordered" id="products-table">
                            <thead>
                                <tr>
                                    <th width="42%">الصنف</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>الإجمالي</th>
                                    <th width="6%">إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $oldProducts = old('product', ['']);
                                    $oldQuantities = old('quantity', ['']);
                                    $oldPrices = old('price', ['']);
                                @endphp
                                @foreach ($oldProducts as $index => $oldProduct)
                                    <tr class="product-row">
                                        <td>
                                            <select name="product[]"
                                                class="form-control selectpicker @error('product.' . $index) is-invalid @enderror"
                                                data-live-search="true" data-size="7" title="اختر الصنف">
                                                <option value="">اختر الصنف</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        {{ $oldProduct == $product->id ? 'selected' : '' }}>
                                                        {{ $product->item_num }}
                                                        {{ $product->item_name }}
                                                        {{ ' الرصيد: ' . $product->balance }}</option>
                                                @endforeach
                                            </select>
                                            @error('product.' . $index)
                                                <div class="text-danger mt-1 field-error">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0.1" name="quantity[]"
                                                value="{{ $oldQuantities[$index] ?? '' }}"
                                                class="form-control @error('quantity.' . $index) is-invalid @enderror">
                                            @error('quantity.' . $index)
                                                <div class="text-danger mt-1 field-error">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="price[]"
                                                value="{{ $oldPrices[$index] ?? '' }}"
                                                class="form-control @error('price.' . $index) is-invalid @enderror">
                                            @error('price.' . $index)
                                                <div class="text-danger mt-1 field-error">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control row-total" readonly tabindex="-1">
                                        </td>
                                        <td>
                                            @if ($index === 0)
                                                <button type="button" class="row-btn is-add" id="add-row" title="إضافة صف">+</button>
                                            @else
                                                <button type="button" class="row-btn is-del delete-row" title="حذف الصف"><i class="la la-trash"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @error('product')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    @error('quantity')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    @error('price')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="action-bar">
                    <div class="grand-total-box">
                        <label for="total-all">الإجمالي الكلي:</label>
                        <input type="number" step="0.01" id="total-all" class="form-control" readonly tabindex="-1" value="0.00">
                    </div>
                    <div class="action-buttons-bar">
                        {{-- زر تخطي رقم السند الحالي: يحفظ الفاتورة (سند إدخال فاضي) ثم ينتقل للتالي --}}
                        @if (auth()->user()->hasAnyRole(['admin', 'Super-User']))
                            <button type="button" onclick="submitSkipForm()" class="btn-skip">
                                <i class="la la-step-forward"></i> تخطي رقم السند
                            </button>
                        @endif
                        <button type="submit" class="btn-save"><i class="la la-save"></i> حفظ</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade quick-add-modal" id="quickAddSupplierModal" tabindex="-1" role="dialog"
        aria-labelledby="quickAddSupplierTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('supplier_item.store') }}" method="post" data-ajax-quick-add
                    data-entity="supplier">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title" id="quickAddSupplierTitle">إضافة مورد جديد</h5>
                            <p class="quick-add-context">بيانات المورد المرتبطة بسند الإدخال.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-4" role="alert" data-form-error></div>
                        <div class="form-group">
                            <label for="quick_supplier_name">اسم المورد</label>
                            <input id="quick_supplier_name" class="form-control" type="text" name="supplier_item_name"
                                maxlength="255" required>
                            <div class="invalid-feedback" data-field-error="supplier_item_name"></div>
                        </div>
                        <div class="form-group">
                            <label for="quick_supplier_address">العنوان</label>
                            <input id="quick_supplier_address" class="form-control" type="text" name="address"
                                maxlength="255" required>
                            <div class="invalid-feedback" data-field-error="address"></div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="quick_supplier_phone">رقم الهاتف</label>
                            <input id="quick_supplier_phone" class="form-control" type="tel" inputmode="numeric"
                                name="phone" required>
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

    <div class="modal fade quick-add-modal" id="quickAddCurrencyModal" tabindex="-1" role="dialog"
        aria-labelledby="quickAddCurrencyTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('currency.store') }}" method="post" data-ajax-quick-add
                    data-entity="currency">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title" id="quickAddCurrencyTitle">إضافة معامل صرف جديد</h5>
                            <p class="quick-add-context">حدد العملة وقيمة معامل الصرف للسند.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-4" role="alert" data-form-error></div>
                        <div class="form-group">
                            <label for="quick_currency_name">اسم العملة</label>
                            <input id="quick_currency_name" class="form-control" type="text" name="name"
                                maxlength="255" required>
                            <div class="invalid-feedback" data-field-error="name"></div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="quick_currency_value">قيمة معامل الصرف</label>
                            <input id="quick_currency_value" class="form-control" type="number" name="value"
                                step="any" required>
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

    <div class="modal fade quick-add-modal" id="quickAddItemModal" tabindex="-1" role="dialog"
        aria-labelledby="quickAddItemTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('item.store') }}" method="post" data-ajax-quick-add data-entity="item">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title" id="quickAddItemTitle">إضافة صنف جديد</h5>
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
                                <select id="quick_item_category" class="form-control selectpicker" name="category_id" data-live-search="true" required>
                                    <option value="">اختر العائلة</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" data-field-error="category_id"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="quick_item_unit">الوحدة</label>
                                <select id="quick_item_unit" class="form-control" name="unit_id" required>
                                    <option value="">اختر الوحدة</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                    @endforeach
                                </select>
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
                                    min="0" step="any" value="0" required>
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
@endsection

@section('scripts')
    <script>
        function submitSkipForm() {
            // التحقق من تاريخ السند قبل التخطي
            var voucherDate = document.getElementById('voucher_date').value;
            if (!voucherDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى إدخال تاريخ السند قبل التخطي',
                    confirmButtonText: 'موافق'
                });
                return;
            }

            // إزالة required من الحقول الاختيارية قبل التخطي
            document.getElementById('supplier_item_id').removeAttribute('required');
            document.getElementById('currency_id').removeAttribute('required');
            document.getElementById('invoice_no').removeAttribute('required');

            Swal.fire({
                title: 'سبب تخطي رقم السند',
                input: 'textarea',
                inputLabel: 'يرجى إدخال سبب التخطي',
                inputPlaceholder: 'اكتب السبب هنا...',
                inputAttributes: {
                    'aria-label': 'اكتب السبب هنا',
                    'maxlength': 500,
                    'rows': 4
                },
                showCancelButton: true,
                confirmButtonText: 'تأكيد التخطي',
                cancelButtonText: 'إلغاء',
                confirmButtonColor: '#f1c40f',
                cancelButtonColor: '#d33',
                customClass: {
                    input: 'text-right',
                    inputLabel: 'text-right'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return 'يرجى إدخال سبب التخطي!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var skipField = document.getElementById('skip_voucher_no');
                    var skipReasonField = document.getElementById('skip_reason');
                    if (skipField) {
                        skipField.value = '1';
                    }
                    if (skipReasonField) {
                        skipReasonField.value = result.value;
                    }
                    document.getElementById('kt_form').submit();
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            // إبراز حقول selectpicker التي عليها خطأ بعد رجوع Laravel
            $('#products-table .product-row select.is-invalid').each(function() {
                $(this).closest('td').find('.bootstrap-select > .dropdown-toggle').addClass('is-invalid');
            });

            // حساب الإجمالي لكل صف وللفاتورة
            function calculateTotalAll() {
                var total = 0;
                $("#products-table tbody tr").each(function() {
                    var quantity = parseFloat($(this).find("input[name='quantity[]']").val()) || 0;
                    var price = parseFloat($(this).find("input[name='price[]']").val()) || 0;
                    var subtotal = quantity * price;
                    $(this).find(".row-total").val(subtotal.toFixed(2));
                    total += subtotal;
                });
                $("#total-all").val(total.toFixed(2));
            }

            // السماح بحذف صف من جدول المنتجات
            $(document).on('click', '.delete-row', function() {
                $(this).closest('tr').remove();
                calculateTotalAll();
            });

            $("#add-row").click(function() {
                var firstProductSelect = $('#products-table tbody select[name="product[]"]').first();
                var newProductSelect = firstProductSelect.clone().val('');
                newProductSelect.find('option').prop('selected', false);
                newProductSelect.removeClass('is-invalid');

                var newRow = $('<tr class="product-row">');
                newRow.append($('<td>').append(newProductSelect));
                newRow.append($('<td>').append($('<input>', {
                    type: 'number',
                    step: '0.01',
                    min: '0.1',
                    name: 'quantity[]',
                    class: 'form-control'
                })));
                newRow.append($('<td>').append($('<input>', {
                    type: 'number',
                    step: '0.01',
                    min: '0',
                    name: 'price[]',
                    class: 'form-control'
                })));
                newRow.append($('<td>').append($('<input>', {
                    type: 'number',
                    step: '0.01',
                    class: 'form-control row-total',
                    readonly: true,
                    tabindex: -1
                })));
                newRow.append($('<td>').append(
                    $('<button>', {
                        type: 'button',
                        class: 'row-btn is-del delete-row',
                        title: 'حذف الصف'
                    }).append('<i class="la la-trash"></i>')
                ));
                $("#products-table tbody").append(newRow);

                newProductSelect.selectpicker();
            });

            $(document).on('input', "input[name='quantity[]'], input[name='price[]']", function() {
                calculateTotalAll();
            });

            // حساب أولي (يغطي القيم القديمة بعد رجوع Laravel)
            calculateTotalAll();
        });
    </script>

    <script>
        $(function() {
            function resetQuickAddFeedback(form) {
                var $form = $(form);
                $form.find('[data-form-error]').hide().text('');
                $form.find('[data-field-error]').text('');
                $form.find('.is-invalid').removeClass('is-invalid');
            }

            function showQuickAddError(form, message) {
                $(form).find('[data-form-error]').text(message).show();
            }

            function addOption(select, id, label) {
                var $select = $(select);
                var value = String(id);
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

            function selectNewItem(item) {
                var label = item.item_num + ' ' + item.item_name + ' الرصيد: ' + item.balance;
                var productSelects = $('#products-table tbody select[name="product[]"]');

                productSelects.each(function() {
                    var value = String(item.id);
                    if (!$(this).find('option').filter(function() {
                            return this.value === value;
                        }).length) {
                        $(this).append(new Option(label, value));
                    }
                    if ($(this).hasClass('selectpicker')) {
                        $(this).selectpicker('refresh');
                    }
                });

                var target = productSelects.filter(function() {
                    return !this.value;
                }).first();

                if (!target.length) {
                    $('#add-row').trigger('click');
                    target = $('#products-table tbody select[name="product[]"]').filter(function() {
                        return !this.value;
                    }).last();
                }

                if (target.length) {
                    target.val(String(item.id)).trigger('change');
                    if (target.hasClass('selectpicker')) {
                        target.selectpicker('refresh');
                    }
                }
            }

            $(document).on('submit', '[data-ajax-quick-add]', function(event) {
                event.preventDefault();
                var form = this;
                var $form = $(form);
                var $submit = $form.find('[type="submit"]');
                var originalLabel = $submit.data('submit-label');

                resetQuickAddFeedback(form);
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
                    if ($form.data('entity') === 'supplier') {
                        addOption('#supplier_item_id', entity.id,
                            entity.supplier_item_name + ' ' + entity.supplier_item_num);
                    } else if ($form.data('entity') === 'currency') {
                        addOption('#currency_id', entity.id, entity.name);
                    } else if ($form.data('entity') === 'item') {
                        selectNewItem(entity);
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
                    var errors = xhr.responseJSON && xhr.responseJSON.errors;
                    var assignedFieldError = false;

                    if (errors) {
                        $.each(errors, function(field, messages) {
                            var message = Array.isArray(messages) ? messages[0] : messages;
                            var $input = $form.find('[name]').filter(function() {
                                return this.name === field;
                            }).first();
                            var $feedback = $form.find('[data-field-error]').filter(function() {
                                return $(this).data('field-error') === field;
                            }).first();

                            if ($input.length && $feedback.length) {
                                $input.addClass('is-invalid');
                                $feedback.text(message);
                                assignedFieldError = true;
                            }
                        });
                    }

                    if (!assignedFieldError) {
                        var failureMessage = xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'تعذر الاتصال بالخادم. تحقق من الاتصال وحاول مرة أخرى.';
                        showQuickAddError(form, failureMessage);
                    }
                }).always(function() {
                    $submit.prop('disabled', false).text(originalLabel);
                });
            });

            $(document).on('input change', '[data-ajax-quick-add] [name]', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('[data-field-error]').text('');
            });

            // تحميل رقم الصنف تلقائياً عند اختيار العائلة في نموذج الإضافة السريعة
            $('#quick_item_category').on('change', function() {
                const categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        url: "{{ route('category.last_item', ':id') }}".replace(':id', categoryId),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            if (data && data.last_item_num !== undefined) {
                                // زيادة الرقم كـ string للحفاظ على القيمة الصحيحة
                                const lastNumStr = String(data.last_item_num);
                                const newNumStr = String(parseInt(lastNumStr) + 1);
                                $('#quick_item_num').val(newNumStr);
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

            // تهيئة selectpicker عند فتح مودال إضافة الصنف
            $('#quickAddItemModal').on('shown.bs.modal', function() {
                $(this).find('.selectpicker').selectpicker('refresh');
            });

            $('.quick-add-modal').on('hidden.bs.modal', function() {
                var form = $(this).find('[data-ajax-quick-add]')[0];
                if (form) {
                    resetQuickAddFeedback(form);
                }
            });
        });
    </script>
@endsection