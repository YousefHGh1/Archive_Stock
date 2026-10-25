@extends('layout.master')

@section('title', 'اضافة الأصناف')

@section('page_title', 'لوحة التحكم')

@section('sub_main', 'الأصناف')

@section('sub_title', 'اضافة الأصناف')

@section('css')
    <style>
        input,
        .btn.dropdown-toggle.btn-light.bs-placeholder,
        .btn.btn-light:hover:not(.btn-text):not(:disabled):not(.disabled),
        .btn.btn-light:focus:not(.btn-text),
        .btn.btn-light.focus:not(.btn-text) {
            border: 1px solid #d5c1ff !important;
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
            border: 1px solid #d5c1ff !important;
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
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">بيانات الأصناف</h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('item.index') }}" class="btn btn-info font-weight-bolder">
                                <span class="svg-icon svg-icon-md">
                                    <svg><!-- your SVG here --></svg>
                                </span>
                                عرض الأصناف
                            </a>
                            <x-add-resource-button />
                        </div>
                    </div>

                    <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" id="kt_form"
                        class="form needs-validation">
                        @csrf
                        <div class="card-body">

                            {{-- Category and Unit --}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label"><strong>العائلة:</strong></label>
                                <div class="col-lg-3">
                                    <select name="category_id" id="category_id" class="form-control selectpicker"
                                        data-live-search="true" title="اختر العائلة">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->category_name }} - {{ $category->category_num }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="mt-1 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="button" class="btn btn-link p-0" title="إضافة عائلة"
                                    data-toggle="modal" data-target="#quickAddCategoryModal"
                                    aria-label="إضافة عائلة">
                                    <i class="p-3 ki ki-solid-plus icon-md"></i>
                                </button>

                                <label class="col-lg-2 col-form-label"><strong>الوحدة:</strong></label>
                                <div class="col-lg-3">
                                    <select name="unit_id" id="unit_id" class="form-control selectpicker"
                                        data-live-search="true" title="اختر الوحدة">
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->unit_name }} - {{ $unit->unit_num }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <div class="mt-1 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="button" class="btn btn-link p-0" title="إضافة وحدة"
                                    data-toggle="modal" data-target="#quickAddUnitModal"
                                    aria-label="إضافة وحدة">
                                    <i class="p-3 ki ki-solid-plus icon-md"></i>
                                </button>
                            </div>

                            {{-- Item Name and Number --}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label"><strong>اسم الصنف:</strong></label>
                                <div class="col-lg-3">
                                    <input type="text" name="item_name"
                                        class="form-control @error('item_name') is-invalid @enderror"
                                        placeholder="ادخل اسم الصنف" value="{{ old('item_name') }}">
                                    @error('item_name')
                                        <div class="mt-1 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <label class="col-lg-2 col-form-label"><strong>رقم الصنف:</strong></label>
                                <div class="col-lg-3">
                                    <input type="number" name="item_num" id="item_num"  class="form-control @error('item_num') is-invalid @enderror"
                                        placeholder="ادخل رقم الصنف" value="{{ old('item_num') }}" readonly>
                                    @error('item_num')
                                        <div class="mt-1 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Open Balance and Low Limit --}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label"><strong>الرصيد الافتتاحي:</strong></label>
                                <div class="col-lg-3">
                                    <input type="number" name="open_balance"
                                        class="form-control @error('open_balance') is-invalid @enderror"
                                        placeholder="ادخل الرصيد" value="{{ old('open_balance') }}">
                                    @error('open_balance')
                                        <div class="mt-1 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <label class="col-lg-2 col-form-label"><strong>الحد الأدنى:</strong></label>
                                <div class="col-lg-3">
                                    <input type="number" name="low_limit"
                                        class="form-control @error('low_limit') is-invalid @enderror"
                                        placeholder="ادخل الحد الأدنى" value="{{ old('low_limit') }}">
                                    @error('low_limit')
                                        <div class="mt-1 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Hidden Balance --}}
                            <input type="hidden" name="balance" id="balance" value="0">
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-2">
                                    <button type="submit" class="mr-2 btn btn-success">حفظ</button>
                                    <button type="reset" class="btn btn-danger">إلغاء</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade quick-add-modal" id="quickAddCategoryModal" tabindex="-1" role="dialog"
        aria-labelledby="quickAddCategoryTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('category.store') }}" method="post" data-ajax-quick-add
                    data-entity="category">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title" id="quickAddCategoryTitle">إضافة عائلة جديدة</h5>
                            <p class="quick-add-context">سيتم اختيار العائلة الجديدة تلقائياً في نموذج الصنف.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-4" role="alert" data-form-error></div>
                        <div class="form-group">
                            <label for="quick_category_name">اسم العائلة</label>
                            <input id="quick_category_name" class="form-control" type="text" name="category_name"
                                minlength="2" maxlength="255" required>
                            <div class="invalid-feedback" data-field-error="category_name"></div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="quick_category_num">رقم العائلة</label>
                            <input id="quick_category_num" class="form-control" type="number" name="category_num"
                                step="1" required>
                            <div class="invalid-feedback" data-field-error="category_num"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary" data-submit-label="حفظ العائلة">حفظ العائلة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade quick-add-modal" id="quickAddUnitModal" tabindex="-1" role="dialog"
        aria-labelledby="quickAddUnitTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('unit.store') }}" method="post" data-ajax-quick-add data-entity="unit">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title" id="quickAddUnitTitle">إضافة وحدة جديدة</h5>
                            <p class="quick-add-context">سيتم اختيار الوحدة الجديدة تلقائياً في نموذج الصنف.</p>
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
                        <button type="button" class="btn btn-light" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary" data-submit-label="حفظ الوحدة">حفظ الوحدة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#category_id').on('change', function() {
                const categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        url: "{{ route('category.last_item', ':id') }}".replace(':id', categoryId),
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            if (data && data.last_item_num !== undefined) {
                                // زيادة الرقم بمقدار 1 على الرقم الأخير
                                const newItemNum = parseInt(data.last_item_num) + 1;
                                $('#item_num').val(newItemNum);
                            } else {
                                // إذا لم يكن هناك صنف سابق، تعيين الرقم الافتراضي بناءً على رقم العائلة
                                $('#item_num').val(categoryId + '1');
                            }
                        },
                        error: function() {
                            $('#item_num').val('');
                        }
                    });
                }
            });
        });
    </script>

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
                var label = $submit.data('submit-label');

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
                    if ($form.data('entity') === 'category') {
                        addOption('#category_id', entity.id,
                            entity.category_name + ' - ' + entity.category_num);
                    } else if ($form.data('entity') === 'unit') {
                        addOption('#unit_id', entity.id, entity.unit_name + ' - ' + entity.unit_num);
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
