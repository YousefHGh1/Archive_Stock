@extends('layout.master')

@section('title')
    عرض فاتورة صرف
@endsection

@section('page_title')
    لوحة التحكم
@endsection

@section('sub_main')
    الفواتير
@endsection

@section('sub_title')
    عرض فاتورة صرف
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

        /* إصلاح z-index للمودل المستلم */
        #quickAddBeneficiaryModal {
            z-index: 1100 !important;
        }

        #invoiceExportModal {
            z-index: 1050 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">

        <!--begin::Card-->
        <div class="card card-custom">
            <div class="flex-wrap pb-0 border-0 card-header">
                <div class="card-title">
                    <h3 class="card-label">عرض سندات الصرف </h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <button type="button" class="btn btn-success font-weight-bolder" data-toggle="modal" data-target="#invoiceExportModal">
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
                        </span>إنشاء سند صرف جديد</button>

                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('invoice_export.skipped-vouchers') }}" class="btn btn-warning font-weight-bolder">
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
                        <div class="card-header" id="headingOne5">
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
                                    <form action="{{ url('inventory/invoice_export/searchdate') }}" method="POST"
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
                            <th width="6%">رقم الفاتورة</th>
                            <th width="7%">تاريخ الفاتورة</th>
                            <th width="6%">رقم الإيصال</th>
                            <th width="11%">اسم القسم</th>
                            <th width="11%">اسم المستلم</th>
                            <th width="12%">ملاحظات</th>
                            <th width="35%">الأصناف</th>
                            <th width="13%">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice_exports as $invoice_export)
                            <tr>
                                <td>{{ $invoice_export->voucher_no }}</td>
                                <td>{{ $invoice_export->voucher_date }}</td>
                                <td>{{ $invoice_export->invoice_no }}</td>
                                <td>{{ $invoice_export->name }}</td>
                                <td>{{ $invoice_export->beneficiary }}</td>
                                <td>{{ $invoice_export->notes ?? '-' }}</td>
                                <td>{!! $invoice_export->products !!}</td>

                                <td data-field="Actions" data-autohide-disabled="false" aria-label="null"
                                    class="datatable-cell">
                                    <span style="overflow: visible; position: relative; width: 110px;">

                                        <a href="{{ url('/inventory/invoice_export/' . $invoice_export->id) }}"
                                            title="View invoice_exports"><button class="btn btn-sm btn-clean btn-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                    <path
                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                                </svg></button>
                                        </a>
                                        <form method="POST"
                                            action="{{ url('/inventory/invoice_export' . '/' . $invoice_export->id) }}"
                                            accept-charset="UTF-8" style="display:inline">
                                            {{ method_field('DELETE') }} {{ csrf_field() }}
                                            <button type="submit" class="btn btn-sm btn-clean btn-icon"
                                                title="Delete invoice_export"
                                                onclick="deleteInvoiceExport({{ $invoice_export->id }})"> <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                </svg></button>
                                        </form>
                                        @canany(['invoice_exports-update', 'admin', 'stock', 'Super-User'])
                                            <button type="button" class="btn btn-sm btn-clean btn-icon"
                                                title="Edit details"
                                                onclick="editInvoiceExport({{ $invoice_export->id }})"><i
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

    <!-- Quick Add Beneficiary Modal -->
    <div class="modal fade quick-add-modal" id="quickAddBeneficiaryModal" tabindex="-1" role="dialog"
        aria-labelledby="quickAddBeneficiaryTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('beneficiary.store') }}" method="post" data-ajax-quick-add data-entity="beneficiary">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title" id="quickAddBeneficiaryTitle">إضافة مستلم جديد</h5>
                            <p class="quick-add-context">سيتم اختيار المستلم الجديد تلقائياً.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-4" role="alert" data-form-error></div>
                        <div class="form-group mb-0">
                            <label for="quick_beneficiary_name">اسم المستلم</label>
                            <input id="quick_beneficiary_name" class="form-control" type="text" name="name"
                                minlength="2" maxlength="255" required>
                            <div class="invalid-feedback" data-field-error="name"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary" data-submit-label="حفظ المستلم">حفظ المستلم</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/recipient-select.js') }}"></script>

    <!-- Invoice Export Modal -->
    <div class="modal fade" id="invoiceExportModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceExportModalTitle">إضافة فاتورة صرف</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="invoiceExportForm">
                        @csrf
                        <input type="hidden" name="invoice_export_id" id="invoice_export_id" value="">

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

                            <label class="col-lg-2 col-form-label">القسم:</label>
                            <div class="col-lg-3">
                                <select class="form-control selectpicker" id="modal_subSection_id" data-live-search="true" required>
                                    <option value="">اختر القسم</option>
                                    @foreach ($subSection as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">المستلم:</label>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <select class="form-control selectpicker" id="modal_beneficiary" name="beneficiary" required
                                        data-live-search="true" title="اختر المستلم">
                                        <option value="">اختر المستلم</option>
                                        @foreach ($beneficiaries as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary"
                                            title="إضافة مستلم جديد"
                                            data-toggle="modal" data-target="#quickAddBeneficiaryModal">
                                            <i class="ki ki-solid-plus"></i>
                                        </button>
                                    </div>
                                </div>
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
                                            <button type="button" class="btn btn-link p-0" onclick="openItemModal()">
                                                <i class="p-3 ki ki-solid-plus icon-md"></i>
                                            </button>
                                        </th>
                                        <th>الكمية</th>
                                        <th width="5%">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="product-row">
                                        <td>
                                            <select class="form-control selectpicker product-select" data-live-search="true" title="اختر الصنف">
                                                <option value="">اختر الصنف</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0.1" class="form-control quantity-input">
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
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="saveInvoiceExport()">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadProducts() {
            $.ajax({
                url: "{{ route('item.index') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('.product-select').each(function() {
                        const currentValue = $(this).val();
                        $(this).empty().append('<option value="">اختر الصنف</option>');
                        data.data.forEach(function(item) {
                            $(this).append('<option value="' + item.id + '">' + item.item_num + ' ' + item.item_name + ' (الرصيد: ' + item.balance + ')</option>');
                        });
                        $(this).val(currentValue);
                        $(this).selectpicker('refresh');
                    });
                }
            });
        }

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
                if ($form.data('entity') === 'beneficiary') {
                    addOption('#modal_beneficiary', entity.id, entity.name);
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
        function loadProducts() {
            $.ajax({
                url: "{{ route('item.index') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('.product-select').each(function() {
                        const currentValue = $(this).val();
                        $(this).empty().append('<option value="">اختر الصنف</option>');
                        data.data.forEach(function(item) {
                            $(this).append('<option value="' + item.id + '">' + item.item_num + ' ' + item.item_name + ' (الرصيد: ' + item.balance + ')</option>');
                        });
                        $(this).val(currentValue);
                        $(this).selectpicker('refresh');
                    });
                }
            });
        }

        function addProductRow(itemId = null, quantity = '') {
            const newRow = `
                <tr class="product-row">
                    <td>
                        <select class="form-control selectpicker product-select" data-live-search="true" title="اختر الصنف">
                            <option value="">اختر الصنف</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0.1" class="form-control quantity-input" value="${quantity}">
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
            $('.product-select').selectpicker('refresh');

            if (itemId) {
                $('.product-select').last().val(itemId);
                $('.product-select').last().selectpicker('refresh');
            }
        }

        $(document).on('click', '.add-row', function(e) {
            e.preventDefault();
            addProductRow();
        });

        $(document).on('click', '.delete-row', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });

        function editInvoiceExport(id) {
            $.ajax({
                url: "{{ route('invoice_export.show', ':id') }}".replace(':id', id),
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        $('#invoiceExportModalTitle').text('تعديل فاتورة صرف');
                        $('#invoice_export_id').val(data.invoice_export.id);
                        $('#modal_voucher_no').val(data.invoice_export.voucher_no);
                        $('#modal_voucher_date').val(data.invoice_export.voucher_date);
                        $('#modal_invoice_no').val(data.invoice_export.invoice_no);
                        $('#modal_subSection_id').val(data.invoice_export.subSection_id);
                        $('#modal_beneficiary').val(data.invoice_export.beneficiary);
                        $('#modal_notes').val(data.invoice_export.notes);

                        $('#modal_subSection_id').selectpicker('refresh');

                        $('#modal-products-table tbody').empty();
                        data.invoice_export.products.forEach(function(product) {
                            addProductRow(product.item_id, product.quantity);
                        });

                        loadProducts();
                        $('#invoiceExportModal').modal('show');
                    } else {
                        Swal.fire('خطأ', data.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('خطأ', 'حدث خطأ أثناء تحميل البيانات', 'error');
                }
            });
        }

        function saveInvoiceExport() {
            const invoiceExportId = $('#invoice_export_id').val();
            const url = invoiceExportId ? "{{ route('invoice_export.update', ':id') }}".replace(':id', invoiceExportId) : "{{ route('invoice_export.store') }}";

            const products = [];
            const quantities = [];

            $('#modal-products-table tbody tr').each(function() {
                const productId = $(this).find('.product-select').val();
                const quantity = $(this).find('.quantity-input').val();

                if (productId && quantity) {
                    products.push(productId);
                    quantities.push(quantity);
                }
            });

            if (products.length === 0) {
                Swal.fire('تنبيه', 'يجب إضافة صنف واحد على الأقل', 'warning');
                return;
            }

            const formData = {
                voucher_date: $('#modal_voucher_date').val(),
                invoice_no: $('#modal_invoice_no').val(),
                subSection_id: $('#modal_subSection_id').val(),
                beneficiary: $('#modal_beneficiary').val(),
                notes: $('#modal_notes').val(),
                product: products,
                quantity: quantities,
            };

            if (invoiceExportId) {
                formData._method = 'PUT';
                formData.voucher_no = $('#modal_voucher_no').val();
            }

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        Swal.fire('نجاح', data.message, 'success');
                        $('#invoiceExportModal').modal('hide');
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

        function deleteInvoiceExport(id) {
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
                        url: "{{ route('invoice_export.destroy', ':id') }}".replace(':id', id),
                        type: "POST",
                        data: { _method: 'DELETE' },
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
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

        $('#invoiceExportModal').on('show.bs.modal', function(e) {
            if (!$(e.relatedTarget).data('edit-mode')) {
                $('#invoiceExportForm')[0].reset();
                $('#invoice_export_id').val('');
                $('#invoiceExportModalTitle').text('إضافة فاتورة صرف');
                $('#modal-products-table tbody').empty();
                addProductRow();
            }
            loadProducts();
        });
    </script>
@endsection
