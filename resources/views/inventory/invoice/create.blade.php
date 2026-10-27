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
        input {
            border: 1px solid #d5c1ff !important;
        }

        .btn.btn-light:hover:not(.btn-text):not(:disabled):not(.disabled),
        .btn.btn-light:focus:not(.btn-text),
        .btn.btn-light.focus:not(.btn-text) {
            border: 1px solid #d5c1ff !important;

        }

        .btn.dropdown-toggle.btn-light.bs-placeholder {
            border: 1px solid #d5c1ff !important;

        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback,
        .field-error {
            display: block !important;
            width: 100%;
            margin-top: 5px;
            font-size: 12px;
        }

        .product-row .is-invalid {
            border: 1px solid #dc3545 !important;
        }

        .bootstrap-select.is-invalid > .dropdown-toggle,
        .bootstrap-select .dropdown-toggle.is-invalid {
            border-color: #dc3545 !important;
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

        .quick-add-modal input,
        .quick-add-modal select {
            border: 1px solid #d5c1ff !important;
        }

        .quick-add-modal [data-form-error] {
            display: none;
        }

        .quick-add-modal [data-field-error] {
            display: block;
            min-height: 0;
        }

        .quick-add-modal [data-field-error]:empty {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label"> بيانات الفواتير (سند إدخال) <i class="mr-2"></i></h3>
                        </div>
                        <div class="card-toolbar">

                            <a href="{{ url('/inventory/invoice') }}" class="btn btn-info font-weight-bolder">
                                <span class="svg-icon svg-icon-md">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <circle fill="#000000" cx="9" cy="13" r="6" />
                                            <path
                                                d="M8.8012943,7.00241933 C9.83837773,3.20768121 11.7781343,4 14,4 C17.3137083,4 20,6.6862913 20,10 C20,12.2218437 18.7923188,14.1616223 16.9973803,13.1987037 C16.9991904,13.1326638 17,13.0664274 17,13 C17,10.381722 13.418278,7 9,7 C8.93337236,7 8.86733422,7.00080962 8.8012943,7.00241933 Z"
                                                fill="#000000" opacity="0.3" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>عرض فاتورة شراء</a>
                            <x-add-resource-button />
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form action="{{ route('invoice.store') }}" method="post" class="form" novalidate
                        enctype="multipart/form-data" id="kt_form">
                        @csrf
                        {!! csrf_field() !!}
                        <input type="hidden" name="skip_voucher_no" value="0" id="skip_voucher_no">
                        <input type="hidden" name="skip_reason" value="" id="skip_reason">

                        <div class="card-body">
                            <div class="p-3 form-group row">
                                <label for="voucher_no" class="col-lg-2 col-form-label ">
                                    <h6> رقم السند:</h6>
                                </label>
                                {{-- <div class="col-lg-3">
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="decrement()">-</button>
                                        <input type="number" class="@error('voucher_no') is-invalid @enderror form-control"
                                            id="voucher_no" name="voucher_no" value="1">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="increment()">+</button>
                                        @error('voucher_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number" class="@error('voucher_no') is-invalid @enderror form-control"
                                            id="voucher_no" name="voucher_no" disabled
                                            value="{{ old('voucher_no', $newVoucherNo) }}">
                                        @error('voucher_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-1"></div>

                                <label for="voucher_date" class="col-lg-2 col-form-label ">
                                    <h6> تاريخ السند:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="date"
                                            class="form-control @error('voucher_date') is-invalid @enderror"
                                            id="voucher_date" name="voucher_date"
                                            value="{{ old('voucher_date', date('Y-m-d')) }}"
                                            max="{{ date('Y-m-d') }}">
                                        @error('voucher_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>


                            </div>

                            <div class="p-3 form-group row">

                                <label for="invoice_no" class="col-lg-2 col-form-label ">
                                    <h6> رقم الفاتورة:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="number"
                                            class="form-control @error('invoice_no') is-invalid @enderror"
                                            id="invoice_no" name="invoice_no"
                                            value="{{ old('invoice_no') }}"
                                            placeholder="أدخل رقم الفاتورة" required>
                                        @error('invoice_no')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-1"></div>


                                <label for="supplier_item_id" class="col-lg-2 col-form-label ">
                                    <h6> المورد:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <select
                                            class="@error('supplier_item_id') is-invalid @enderror form-control selectpicker"
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
                                        @error('supplier_item_id')
                                            <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <button type="button" class="btn btn-link p-0" title="اضافة مورد جديد"
                                    aria-label="اضافة مورد جديد" data-toggle="modal"
                                    data-target="#quickAddSupplierModal">
                                    <i class="p-3 ki ki-solid-plus icon-md"></i>
                                </button>

                            </div>


                            <div class="p-3 form-group row">

                                <label for="currency_id" class="col-lg-2 col-form-label ">
                                    <h6> معامل الصرف:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
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
                                        @error('currency_id')
                                            <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <button type="button" class="btn btn-link p-0" title="اضافة معامل صرف جديد"
                                    aria-label="اضافة معامل صرف جديد" data-toggle="modal"
                                    data-target="#quickAddCurrencyModal">
                                    <i class="p-3 ki ki-solid-plus icon-md"></i>
                                </button>
                            </div>

                            <div class="p-3 form-group row">
                                <label for="notes" class="col-lg-2 col-form-label">
                                    <h6>ملاحظات:</h6>
                                </label>
                                <div class="col-lg-9">
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                        rows="3" maxlength="5000">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <table class="table table-bordered" id="products-table">
                                <thead>
                                    <tr>
                                        <th width="50%">الصنف
                                            <button type="button" class="btn btn-link p-0" title="اضافة صنف جديد"
                                                aria-label="اضافة صنف جديد" data-toggle="modal"
                                                data-target="#quickAddItemModal">
                                                <i class="p-3 ki ki-solid-plus icon-md"></i>
                                            </button>
                                            <a href="{{ url('inventory/item') }}" title="عرض الأصناف" target="_blank">
                                                <i class="p-3 far fa-eye icon-md"></i>
                                            </a>
                                        </th>
                                        <th>الكمية</th>
                                        <th>السعر</th>
                                        <th width="5%">جديد</th>
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
                                                    <div class="text-danger mt-1 field-error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0.1" name="quantity[]"
                                                    value="{{ $oldQuantities[$index] ?? '' }}"
                                                    class="form-control @error('quantity.' . $index) is-invalid @enderror">
                                                @error('quantity.' . $index)
                                                    <div class="text-danger mt-1 field-error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" min="0" name="price[]"
                                                    value="{{ $oldPrices[$index] ?? '' }}"
                                                    class="form-control @error('price.' . $index) is-invalid @enderror">
                                                @error('price.' . $index)
                                                    <div class="text-danger mt-1 field-error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                @if ($index === 0)
                                                    <button type="button" class="btn btn-primary" id="add-row">+</button>
                                                @else
                                                    <button type="button" class="btn btn-danger delete-row">حذف</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    {{-- زر تخطي رقم السند الحالي: يحفظ الفاتورة (سند إدخال فاضي) ثم ينتقل للتالي --}}
                                    {{-- @if (auth()->user()->hasRole('admin')) --}}
                                    @if (auth()->user()->hasAnyRole(['admin', 'Super-User']))
                                        <button type="button" onclick="submitSkipForm()"
                                            class="ml-5 btn btn-warning">تخطي رقم السند</button>
                                    @endif
                                    <button type="submit" class="ml-3 btn btn-success">حفظ</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card-->
            </div>
        </div>
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
        function myFunction() {
            var quantity = parseFloat(document.getElementById("quantity").value);
            var price = parseFloat(document.getElementById("price").value);
            var total = quantity * price;

            document.getElementById("Total").value = total;
        }

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

            // السماح بحذف صف من جدول المنتجات
            $(document).on('click', '.delete-row', function() {
                $(this).closest('tr').remove();
                calculateTotalAll();
            });
            //     // html += "<td> <input type='number' class='form-control' name='Total[]' readonly></td>";

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
                newRow.append($('<td>').append(
                    $('<button>', {
                        type: 'button',
                        class: 'btn btn-danger delete-row',
                        text: 'حذف'
                    })
                ));
                $("#products-table tbody").append(newRow);

                newProductSelect.selectpicker();
            });

            $(document).on('input', "input[name='quantity[]'], input[name='price[]']", function() {
                calculateTotal(this);
                calculateTotalAll();
            });


            // حساب الإجمالي الكلي
            function calculateTotalAll() {
                var total = 0;
                $("#products-table tbody tr").each(function() {
                    var quantity = parseFloat($(this).find("input[name='quantity[]']").val()) || 0;
                    var price = parseFloat($(this).find("input[name='price[]']").val()) || 0;
                    var subtotal = quantity * price;
                    $(this).find("input[name='Total']").val(subtotal.toFixed(2));
                    $(this).find("input[name='Total[]']").val(subtotal.toFixed(2));
                    total += subtotal;
                });
                $("#total-all").val(total.toFixed(2));
            }

        });

        function calculateTotal(element) {
            var tr = $(element).closest('tr');
            var quantity = parseFloat(tr.find("input[name='quantity[]']").val()) || 0;
            var price = parseFloat(tr.find("input[name='price[]']").val()) || 0;
            var total = quantity * price;
            tr.find("input[name='Total[]']").val(total.toFixed(2));
            tr.find("input[name='Total']").val(total.toFixed(2));
        }
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

    {{-- <script>
    function increment() {
        let input = document.getElementById("voucher_no");
        input.value = parseInt(input.value) + 1;
    }

    function decrement() {
        let input = document.getElementById("voucher_no");
        if (input.value > 1) { // Prevents negative numbers
            input.value = parseInt(input.value) - 1;
        }
    }
</script> --}}
@endsection
