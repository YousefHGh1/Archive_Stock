@extends('layout.master')

@section('title')
    تعديل فاتورة صرف
@endsection

@section('page_title')
    لوحة التحكم
@endsection

@section('sub_main')
    الفواتير
@endsection

@section('sub_title')
    تعديل فاتورة صرف
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

                            <a href="{{ url('/inventory/invoice_export') }}" class="btn btn-info font-weight-bolder">
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
                                </span>عرض فاتورة صرف</a>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form action="{{ route('invoice_export.update', $invoiceExport->id) }}" method="post"
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
                                            value="{{ old('voucher_no', $invoiceExport->voucher_no) }}"
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
                                            value="{{ old('voucher_date', $invoiceExport->voucher_date) }}"
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
                                        <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                            value="{{ $invoiceExport->invoice_no }}"
                                            @canany(['admin', 'stock']) {{ '' }} @else disabled @endcanany>
                                    </div>
                                </div>

                                <div class="col-lg-1"></div>


                                <label for="subSection_id" class="col-lg-2 col-form-label ">
                                    <h6> الأقسام:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <select class="form-control selectpicker" data-size="7" tabindex="null"
                                            data-live-search="true" title="أدخل اسم القسم" name="subSection_id"
                                            id="subSection_id"  selected
                                            @canany(['admin', 'stock']) {{ '' }} @else disabled @endcanany>
                                            @foreach ($subSection as $subSections)
                                                <option value="{{ $subSections->id }}" {{ $invoiceExport->subSection_id == $subSections->id ? 'selected' : '' }}>
                                                    {{ $subSections->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @canany(['admin', 'stock'])
                                <a href="{{ url('inventory/sub_section/create') }}" title="اضافة قسم جديد">
                                    <i class="p-3 ki ki-solid-plus icon-md"></i>
                                </a>
                                @endcanany
                            </div>

                            <div class="p-3 form-group row">
                                <label for="notes" class="col-lg-2 col-form-label">
                                    <h6>ملاحظات:</h6>
                                </label>
                                <div class="col-lg-9">
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                        rows="3" maxlength="5000">{{ old('notes', $invoiceExport->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <table class="table table-bordered" id="products-table">
                                <thead>
                                    <tr>
                                        <th width="50%">الصنف</th>
                                        <th>الكمية</th>
                                        {{-- <th>السعر</th> --}}
                                        <th width="5%">جديد</th>
                                        @canany(['admin', 'stock', 'Super-User'])
                                        <th width="5%">حذف</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $rowProducts = old('product', $invoiceExport->InvoiceExport_product->pluck('item_id')->toArray());
                                        $rowQuantities = old('quantity', $invoiceExport->InvoiceExport_product->pluck('quantity')->toArray());
                                        if (empty($rowProducts)) {
                                            $rowProducts = [''];
                                            $rowQuantities = [''];
                                        }
                                    @endphp
                                    @foreach ($rowProducts as $index => $rowProduct)
                                        <tr class="product-row">
                                            <td>
                          <select name="product[]"
                                                    class="form-control selectpicker @error('product.' . $index) is-invalid @enderror"
                                                    @canany(['admin', 'stock', 'Super-User']) @else disabled @endcanany>
                                                    <option value=""  selected>اختر المنتج</option>

                                                    @foreach ($products as $itemOption)
                                                        <option value="{{ $itemOption->id }}"
                                                            {{ $rowProduct == $itemOption->id ? 'selected' : '' }}>
                                                            {{ $itemOption->item_num }} {{ $itemOption->item_name }} {{ 'الرصيد:'. $itemOption->balance }}
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
                                                    @canany(['admin', 'stock', 'Super-User']) @else disabled @endcanany>
                                                @error('quantity.' . $index)
                                                    <div class="text-danger mt-1 field-error">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td></td>
                                            {{-- <td><input type="number" name="price[]"
                                                    value="{{ $InvoiceExport_product->price }}" class="form-control"></td> --}}
                                            @canany(['admin', 'stock', 'Super-User'])
                                            <td><button type="button" class="btn btn-danger delete-row">حذف</button></td>
                                            @endcanany
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2"></td>
                                        <td>
                                            @canany(['admin', 'stock', 'Super-User'])
                                                <button type="button" class="btn btn-primary" id="add-row">+</button>
                                            @endcanany
                                        </td>
                                        @canany(['admin', 'stock', 'Super-User'])
                                            <td></td>
                                        @endcanany
                                    </tr>

                                </tbody>
                            </table>

                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    @canany(['admin', 'stock', 'Super-User'])
                                    <button type="submit" class="ml-5 btn btn-success">حفظ</button>
                                    @endcanany

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

        // تحويل الزر "add-row" لإضافة الصف إلى جدول المنتجات
        $("#add-row").click(function() {
            var html = "<tr class='product-row'>";
            html +=
                "<td><select name='product[]' title='اختر الصنف' class='form-control selectpicker' data-live-search='true'><option value=''>اختر الصنف</option>";
            @foreach ($products as $productOption)
                html +=
                    "<option value='{{ $productOption->id }}'>{{ $productOption->item_num }} {{ $productOption->item_name }} {{ $productOption->balance }}</option>";
            @endforeach
            html += "</select></td>";
            html += "<td><input type='number' step='0.01' min='0.1' name='quantity[]' class='form-control'></td>";
            html += "<td></td>";
            @canany(['admin', 'stock', 'Super-User'])
                html += "<td><button type='button' class='btn btn-danger delete-row'>حذف</button></td>";
            @endcanany
            html += "</tr>";
            $("#products-table tbody").append(html);
            $("#products-table tbody .selectpicker").selectpicker('refresh');
        });
    });
</script>
@endsection
