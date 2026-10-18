    @extends('layout.master')

    @section('title')
        تعديل فاتورة شراء
    @endsection

    @section('page_title')
        لوحة التحكم
    @endsection

    @section('sub_main')
        الفواتير
    @endsection

    @section('sub_title')
        تعديل فاتورة شراء
    @endsection

    @section('css')
        <style>
            input {
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
                                <h3 class="card-label"> تعديل بيانات الفواتير <i class="mr-2"></i></h3>
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
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form action="{{ route('invoice.update', ['invoice' => $invoice->id]) }}" method="post"
                            class="form needs-validation " novalidate enctype="multipart/form-data" id="kt_form">
                            @csrf
                            {!! csrf_field() !!}
                            @method('PUT')

                            <div class="card-body">
                                <div class="p-3 form-group row">
                                    <label for="voucher_no" class="col-lg-2 col-form-label ">
                                        <h6> رقم السند:</h6>
                                    </label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('voucher_no') is-invalid @enderror" id="voucher_no" name="voucher_no"
                                                value="{{ old('voucher_no', $invoice->voucher_no) }}"
                                                @canany(['admin', 'stock']) {{ '' }} @else disabled @endcanany>
                                            @error('voucher_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-1"></div>

                                    <label for="voucher_date" class="col-lg-2 col-form-label ">
                                        <h6> تاريخ السند:</h6>
                                    </label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="date" class="form-control @error('voucher_date') is-invalid @enderror" id="voucher_date" name="voucher_date"
                                                value="{{ old('voucher_date', $invoice->voucher_date) }}"
                                                @canany(['admin', 'stock']) {{ '' }} @else disabled @endcanany
                                                max="{{ date('Y-m-d') }}">
                                            @error('voucher_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                            <input type="text" class="form-control @error('invoice_no') is-invalid @enderror" id="invoice_no" name="invoice_no"
                                                value="{{ old('invoice_no', $invoice->invoice_no) }}"
                                                @canany(['admin', 'stock']) {{ '' }} @else disabled @endcanany>
                                            @error('invoice_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-1"></div>


                                    <label for="supplier_item_id" class="col-lg-2 col-form-label ">
                                        <h6> المورد:</h6>
                                    </label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <select class="form-control selectpicker @error('supplier_item_id') is-invalid @enderror" data-size="7" tabindex="null"
                                                data-live-search="true" ر name="supplier_item_id" id="supplier_item_id"
                                                @canany(['admin', 'stock']) {{ '' }} @else disabled @endcanany>
                                                <option value="0" disabled selected>الموردين</option>
                                                @foreach ($supplier_item as $supplier_items)
                                                    <option value="{{ $supplier_items->id }}"
                                                        {{ old('supplier_item_id', $invoice->supplier_item_id) == $supplier_items->id ? 'selected' : '' }}>
                                                        {{ $supplier_items->supplier_item_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('supplier_item_id')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 form-group row">
                                    <label for="currency_id" class="col-lg-2 col-form-label ">
                                        <h6> معامل الصرف:</h6>
                                    </label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <select class="form-control @error('currency_id') is-invalid @enderror" name="currency_id" id="currency_id"
                                                @canany(['admin', 'stock']) {{ '' }} @else disabled @endcanany
                                                required>
                                                <option value="">اختر معامل الصرف</option>
                                                @foreach ($currency as $currencys)
                                                    <option value="{{ $currencys->id }}"
                                                        {{ old('currency_id', $invoice->currency_id) == $currencys->id ? 'selected' : '' }}>
                                                        {{ $currencys->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('currency_id')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-bordered" id="products-table">
                                    <thead>
                                        <tr>
                                            <th width="50%">الصنف
                                                <a href="{{ url('inventory/item/create') }}" title="اضافة صنف جديد"
                                                    target="_blank">
                                                    <i class="p-3 ki ki-solid-plus icon-md"></i>
                                                </a>
                                                <a href="{{ url('inventory/item') }}" title="عرض الأصناف" target="_blank">
                                                    <i class="p-3 far fa-eye icon-md"></i>
                                                </a>
                                            </th>
                                            <th>الكمية</th>
                                            <th>السعر</th>
                                            <th width="5%">جديد</th>
                                            @canany(['admin', 'Super-User'])
                                                <th width="5%">حذف</th>
                                            @endcanany
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $rowProducts = old('product', $invoice->invoiceproduct->pluck('item_id')->toArray());
                                            $rowQuantities = old('quantity', $invoice->invoiceproduct->pluck('quantity')->toArray());
                                            $rowPrices = old('price', $invoice->invoiceproduct->pluck('price')->toArray());
                                            if (empty($rowProducts)) {
                                                $rowProducts = [''];
                                                $rowQuantities = [''];
                                                $rowPrices = [''];
                                            }
                                        @endphp
                                        @foreach ($rowProducts as $index => $rowProduct)
                                            <tr class="product-row">
                                                <td>
                                                    <select name="product[]"
                                                        class="form-control selectpicker @error('product.' . $index) is-invalid @enderror"
                                                        data-live-search="true" title="اختر الصنف"
                                                        @if (auth()->user()->hasRole('Super-User')) {{ '' }} @else disabled @endif>
                                                        <option value="" selected>اختر المنتج</option>

                                                        @foreach ($products as $itemOption)
                                                            <option value="{{ $itemOption->id }}"
                                                                {{ $rowProduct == $itemOption->id ? 'selected' : '' }}>
                                                                {{ $itemOption->item_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('product.' . $index)
                                                        <div class="text-danger mt-1 field-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td><input type="number" step="0.01" min="0.1" name="quantity[]"
                                                        value="{{ $rowQuantities[$index] ?? '' }}"
                                                        class="form-control @error('quantity.' . $index) is-invalid @enderror"
                                                        @if (auth()->user()->hasRole('Super-User')) {{ '' }} @else disabled @endif>
                                                    @error('quantity.' . $index)
                                                        <div class="text-danger mt-1 field-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td><input type="number" step="0.01" min="0" name="price[]"
                                                        value="{{ $rowPrices[$index] ?? '' }}"
                                                        class="form-control @error('price.' . $index) is-invalid @enderror"
                                                        @if (auth()->user()->hasRole('Super-User')) {{ '' }} @else disabled @endif>
                                                    @error('price.' . $index)
                                                        <div class="text-danger mt-1 field-error">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                @if(auth()->user()->hasRole('Super-User'))
                                                    <td><button type="button" class="btn btn-danger delete-row">حذف</button></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3"></td>
                                            <td>
                                                @if(auth()->user()->hasRole('Super-User'))
                                                    <button type="button" class="btn btn-primary" id="add-row">+</button>
                                                @endif
                                            </td>
                                            @if(auth()->user()->hasRole('Super-User'))
                                                <td></td>
                                            @endif
                                        </tr>

                                    </tbody>
                                </table>

                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-10">
                                        @if(auth()->user()->hasRole('Super-User'))
                                            <button type="submit" class="ml-5 btn btn-success">حفظ</button>
                                        @endif

                                        <x-add-resource-button />

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
    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {
                // إبراز حقول selectpicker التي عليها خطأ بعد رجوع Laravel
                $('#products-table .product-row select.is-invalid').each(function() {
                    $(this).closest('td').find('.bootstrap-select > .dropdown-toggle').addClass('is-invalid');
                });

                // السماح بحذف صف من جدول المنتجات
                $(document).on('click', '.delete-row', function() {
                    $(this).closest('tr').remove();
                });

                @if(auth()->user()->hasRole('Super-User'))
                    // إضافة زر "delete-row" إلى كل صف في جدول المنتجات
                    $("#products-table tbody tr.product-row").each(function() {
                        if ($(this).find('.delete-row').length == 0) {
                            var deleteBtnHtml =
                                '<td><button type="button" class="btn btn-danger delete-row">حذف</button></td>';
                            $(this).append(deleteBtnHtml);
                        }
                    });
                @endif

                // تحويل الزر "add-row" لإضافة الصف إلى جدول المنتجات
                $("#add-row").click(function() {
                    var html = "<tr class='product-row'>";
                    html +=
                        "<td><select name='product[]' title='اختر الصنف' class='form-control selectpicker' data-live-search='true'><option value=''>اختر الصنف</option>";
                    @foreach ($products as $productOption)
                        html +=
                            "<option value='{{ $productOption->id }}'>{{ $productOption->item_num }} {{ $productOption->item_name }}</option>";
                    @endforeach
                    html += "</select></td>";
                    html += "<td><input type='number' step='0.01' min='0.1' name='quantity[]' class='form-control'></td>";
                    html += "<td><input type='number' step='0.01' min='0' name='price[]' class='form-control'></td>";
                    @if(auth()->user()->hasRole('Super-User'))
                        html += "<td><button type='button' class='btn btn-danger delete-row'>حذف</button></td>";
                    @endif
                    html += "</tr>";
                    $("#products-table tbody").append(html);
                    $("#products-table tbody .selectpicker").selectpicker('refresh');
                });
            });
        </script>
    @endsection
