@extends('layout.master')

@section('title')
    إضافة الوارد
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    الوارد
@endsection
@section('sub_title')
    صفحة الإضافة
@endsection

@section('css')
@endsection

@section('content')
    <!-- Main content -->

    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label"> بيانات المحروقات الوارد <i class="mr-2"></i></h3>
                        </div>
                        <div class="card-toolbar">

                            <a href="{{ route('diesel.index') }}" class="btn btn-info font-weight-bolder">
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
                                </span>عرض وارد المحروقات</a>
                            <x-add-resource-button />

                        </div>
                    </div>
                    <!--begin::Form-->
                    <form action="{{ route('diesel.store') }}" method="post" class="form needs-validation " novalidate
                        enctype="multipart/form-data" id="kt_form">
                        @csrf
                        {!! csrf_field() !!}
                        <div class="card-body" style="margin:20px;">
                            <div class="pb-2 form-group row">
                                <label for="exampleSelectd" class="col-lg-2 col-form-label text-lg-right">
                                    <h6>اسم المورد:</h6>
                                </label>
                                <div class="col-8">
                                    <div class="dropdown bootstrap-select form-control dropup">
                                        <select class="form-control selectpicker @error('supplier_id') is-invalid @enderror"
                                            name="supplier_id" id="supplier_id" data-size="7" data-live-search="true"
                                            title="...">
                                            @foreach ($supplier as $suppliers)
                                                <option value="{{ $suppliers->id }}"
                                                    {{ old('supplier_id') == $suppliers->id ? 'selected' : '' }}>
                                                    {{ $suppliers->name_supplier }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    @error('supplier_id')
                                        <span class="text-sm text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="pb-2 form-group row">
                                <label for="quantity" class="col-lg-2 col-form-label text-lg-right">
                                    <h6>كمية التوريد:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="quantity" id="quantity" type="number" value="{{ old('quantity') }}"
                                            class="@error('quantity') is-invalid @enderror form-control" min="0"
                                            placeholder="ادخل كمية التوريد">
                                    </div>
                                    @error('quantity')
                                        <span class="text-sm text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <label for="type" class="col-lg-2 col-form-label text-lg-right">
                                    <h6>جهة التوريد:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="type" id="type" type="text" value="{{ old('type') }}"
                                            class="@error('type') is-invalid @enderror form-control"
                                            placeholder="ادخل جهة التوريد">
                                    </div>
                                    @error('type')
                                        <span class="text-sm text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="pb-2 form-group row">
                                <label for="price" class="col-lg-2 col-form-label text-lg-right">
                                    <h6>سعر اللتر:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="price" id="price" type="number" step="0.01" value="{{ old('price') }}"
                                            class="@error('price') is-invalid @enderror form-control" min="0"
                                            placeholder="اختياري — ادخل سعر اللتر">
                                    </div>
                                    @error('price')
                                        <span class="text-sm text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="pb-2 form-group row">
                                <label for="invoice_num" class="col-lg-2 col-form-label text-lg-right">
                                    <h6>رقم الفاتورة:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="invoice_num" type="number" min="0"
                                            value="{{ old('invoice_num') }}"
                                            class="@error('invoice_num') is-invalid @enderror form-control" id="invoice_num"
                                            placeholder="ادخل رقم الفاتورة" />
                                    </div>
                                    @error('invoice_num')
                                        <span class="text-sm text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <label for="voucher" class="col-lg-2 col-form-label text-lg-right">
                                    <h6>سند التوريد:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="voucher" type="text" value="{{ old('voucher') }}"
                                            class="@error('voucher') is-invalid @enderror form-control" id="voucher"
                                            placeholder="ادخل سند التوريد" />
                                    </div>
                                    @error('voucher')
                                        <span class="text-sm text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date" class="col-lg-2 col-form-label text-lg-right">
                                    <h6>تاريخ التوريد:</h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="date" type="date"
                                            class="@error('date') is-invalid @enderror form-control" id="date"
                                            placeholder="ادخل تاريخ التوريد" value="{{ date('Y-m-d') }}" />
                                    </div>
                                    @error('date')
                                        <span class="text-sm text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <label for="typesfuel_id" class="col-lg-2 col-form-label text-lg-right">
                                    <h6>نوع المحروقات:</h6>
                                </label>

                                <div class="col-3">
                                    <div class="dropdown bootstrap-select form-control dropup">
                                        <select
                                            class="form-control selectpicker @error('typesfuel_id') is-invalid @enderror"
                                            name="typesfuel_id" id="typesfuel_id" data-size="7" data-live-search="true"
                                            title="...">
                                            @foreach ($typesfuel as $fuelType)
                                                <option value="{{ $fuelType->id }}"
                                                    {{ old('typesfuel_id') == $fuelType->id ? 'selected' : '' }}>
                                                    {{ $fuelType->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    @error('typesfuel_id')
                                        <span class="text-sm text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <button type="submit" class="mr-2 btn btn-success">حفظ</button>
                                    <button type="reset" class="btn btn-danger">إلغاء</button>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary font-weight-bolder">
                                            الموردين
                                        </button>
                                        <button type="button"
                                            class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                            <ul class="nav nav-hover flex-column">

                                                <li class="nav-item">
                                                    <a href="#" class="nav-link" data-toggle="modal" data-target="#quickAddSupplierModal">
                                                        <i class="nav-icon flaticon2-add-1"></i> إضافة مورد</a>
                                                </li>

                                                <li class="nav-item">
                                                    <a href="{{ route('supplier.index') }}" class="nav-link">
                                                        <i class="nav-icon flaticon-eye"></i>عرض الموردين
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary font-weight-bolder">
                                            أنواع المحروقات
                                        </button>
                                        <button type="button"
                                            class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                            <ul class="nav nav-hover flex-column">
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link" data-toggle="modal" data-target="#quickAddTypesFuelModal">
                                                        <i class="nav-icon flaticon2-add-1"></i> إضافة نوع محروقات
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('TypesFuel.index') }}" class="nav-link">
                                                        <i class="nav-icon flaticon-eye"></i>عرض أنواع المحروقات
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
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
    <!-- /.content -->
@endsection

<!-- Quick-add modals -->

<!-- Supplier quick-add modal -->
<div class="modal fade quick-add-modal" id="quickAddSupplierModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title quick-add-heading">إضافة مورد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="quick-add-context">سيتم اختيار المورد تلقائياً بعد الإضافة.</div>
            </div>
            <div class="modal-body">
                <form action="{{ route('supplier.store') }}" method="POST" data-ajax-quick-add data-entity="supplier">
                    @csrf
                    <div data-form-error class="alert alert-danger"></div>

                    <div class="form-group">
                        <label>اسم المورد</label>
                        <input type="text" name="name_supplier" class="form-control" />
                        <div data-field-error="name_supplier" class="text-danger"></div>
                    </div>

                    <input type="hidden" name="num_supplier" value="">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" data-submit-label="حفظ">حفظ</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- TypesFuel quick-add modal -->
<div class="modal fade quick-add-modal" id="quickAddTypesFuelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title quick-add-heading">إضافة نوع محروقات</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="quick-add-context">سيتم اختيار النوع تلقائياً بعد الإضافة.</div>
            </div>
            <div class="modal-body">
                <form action="{{ route('TypesFuel.store') }}" method="POST" data-ajax-quick-add data-entity="typesfuel">
                    @csrf
                    <div data-form-error class="alert alert-danger"></div>

                    <div class="form-group">
                        <label>اسم النوع</label>
                        <input type="text" name="name" class="form-control" />
                        <div data-field-error="name" class="text-danger"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" data-submit-label="حفظ">حفظ</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        $(function() {
            function resetFeedback(form) {
                var $form = $(form);
                $form.find('[data-form-error]').hide().text('');
                $form.find('[data-field-error]').text('');
                $form.find('.is-invalid').removeClass('is-invalid');
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

            $(document).on('submit', '[data-ajax-quick-add]', function(event) {
                event.preventDefault();
                var form = this;
                var $form = $(form);
                var $submit = $form.find('[type="submit"]');
                var label = $submit.data('submit-label') || $submit.text();

                resetFeedback(form);
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
                    var entityType = $form.data('entity');
                    if (entityType === 'supplier') {
                        addOption('#supplier_id', entity.id, entity.name_supplier || entity.name || entity.label || entity);
                    } else if (entityType === 'typesfuel') {
                        addOption('#typesfuel_id', entity.id, entity.name || entity.label || entity);
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
                    var assignedError = false;
                    if (errors) {
                        $.each(errors, function(field, messages) {
                            var $input = $form.find('[name]').filter(function() {
                                return this.name === field;
                            }).first();
                            var $feedback = $form.find('[data-field-error]').filter(function() {
                                return $(this).data('field-error') === field || $(this).attr('data-field-error') === field;
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

            $(document).on('input change', '[data-ajax-quick-add] [name]', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('[data-field-error]').text('');
            });

            $('.quick-add-modal').on('hidden.bs.modal', function() {
                var form = $(this).find('[data-ajax-quick-add]')[0];
                if (form) {
                    resetFeedback(form);
                }
            });
        });
    </script>
@endsection
