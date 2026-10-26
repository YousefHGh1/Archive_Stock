@extends('layout.master')

@section('title')
    تعديل الصادر
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    الصادر
@endsection
@section('sub_title')
    صفحة التعديل
@endsection
@section('css')
    <style>
        .quick-add-modal .modal-header {
            align-items: center;
        }

        .quick-add-modal .modal-title {
            margin: 0;
        }

        .quick-add-modal .modal-body {
            color: #5e6775;
        }

        .quick-add-modal .quick-add-context {
            margin-bottom: 1rem;
            color: #5e6775;
            line-height: 1.6;
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

        /* إصلاح z-index للمودل الجهة المستلمة */
        #quickAddDieselRecipientModal {
            z-index: 1100 !important;
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
                            <h3 class="card-label"> بيانات المحروقات الصادر <i class="mr-2"></i></h3>
                        </div>
                        <div class="card-toolbar">

                            <a href="https://master/CoreArchive/public/dieselexport" class="btn btn-info font-weight-bolder">
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
                                </span>عرض صادر المحروقات</a>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form action="{{ route('dieselexport.update', $dieselexport->id) }}" method="post"
                        class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {!! csrf_field() !!}
                        <div class="card-body" style="margin:20px;">
                            <div class="pb-5 form-group row">
                                <label for="exampleSelectd" class="col-lg-2 col-form-label text-lg-right"> القسم:</label>
                                <div class="col-3">
                                    <div class="dropdown bootstrap-select form-control dropup">
                                        <select class="form-control selectpicker" name="section_id" required
                                            id="section_id">
                                            <option value="option_select" disabled selected></option>
                                            @foreach ($section as $sections)
                                                <option value="{{ $sections->id }}"
                                                    {{ $dieselexport->section_id == $sections->id ? 'selected' : '' }}>
                                                    {{ $sections->name_section }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                                <label for="exampleSelectd" class="col-lg-2 col-form-label text-lg-right">القسم
                                    الفرعي:</label>
                                <div class="col-3">
                                    <div class="dropdown bootstrap-select form-control dropup">
                                        <select class="form-control selectpicker" name="sub_section_id" required
                                            id="sub_section_id">
                                            <option value="option_select" disabled selected></option>
                                            @foreach ($subSection as $subSections)
                                                <option value="{{ $subSections->id }}"
                                                    {{ $dieselexport->sub_section_id == $subSections->id ? 'selected' : '' }}>
                                                    {{ $subSections->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="pb-5 form-group row">
                                <label for="num_section" class="col-lg-2 col-form-label text-lg-right">الجهة المستلمة:</label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <select name="num_section" id="num_section" class="form-control selectpicker"
                                            data-live-search="true" title="اختر الجهة المستلمة">
                                            <option value="">اختر الجهة المستلمة</option>
                                            @foreach ($recipients as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('num_section', $dieselexport->num_section) == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-primary"
                                                title="إضافة جهة جديدة"
                                                data-toggle="modal" data-target="#quickAddDieselRecipientModal">
                                                <i class="ki ki-solid-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <label for="num_note" class="col-lg-2 col-form-label text-lg-right">رقم الدفتر:</label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="num_note" id="num_note" type="number" class="form-control"
                                            value="{{ $dieselexport->num_note }}">
                                    </div>
                                </div>
                            </div>

                            <div class="pb-5 form-group row">
                                <label for="quantity" class="col-lg-2 col-form-label text-lg-right">كمية الصادر:</label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="quantity" id="quantity" type="number" class="form-control"
                                            min="0" value="{{ $dieselexport->quantity }}">
                                    </div>
                                </div>
                                <label for="voucher" class="col-lg-2 col-form-label text-lg-right">رقم الإيصال:</label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="voucher" type="number" min="0"
                                            class="@error('voucher') is-invalid @enderror form-control"
                                            id="voucher" value="{{ old('voucher', $dieselexport->voucher) }}" />
                                    </div>
                                    @error('voucher')
                                        <div class="d-block invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date" class="col-lg-2 col-form-label text-lg-right">تاريخ التصدير:</label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="date" type="date" class="form-control" id="date"
                                            value="{{ $dieselexport->date }}" />
                                    </div>
                                </div>

                                <label for="exampleSelectd" class="col-lg-2 col-form-label text-lg-right">نوع
                                    المحروقات</label>
                                <div class="col-3">
                                    <div class="dropdown bootstrap-select form-control dropup">
                                        <select class="form-control selectpicker" name="typesfuel_id" required
                                            id="typesfuel_id">
                                            @foreach ($typesfuel as $typesfuels)
                                                <option value="{{ $typesfuels->id }}"
                                                    {{ $dieselexport->typesfuel_id == $typesfuels->id ? 'selected' : '' }}>
                                                    {{ $typesfuels->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="notes" class="col-lg-2 col-form-label text-lg-right">ملاحظات:</label>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <textarea name="notes" id="notes" rows="3"
                                            class="form-control" maxlength="5000"
                                            placeholder="أدخل الملاحظات">{{ old('notes', $dieselexport->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <button type="submit" class="mr-2 btn btn-success">حفظ</button>
                                    <button type="reset" class="btn btn-danger">إلغاء</button>
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

<!-- Quick-add modals -->

<!-- DieselRecipient quick-add modal -->
<div class="modal fade quick-add-modal" id="quickAddDieselRecipientModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title quick-add-heading">إضافة جهة مستلمة جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="quick-add-context">سيتم اختيار الجهة تلقائياً بعد الإضافة.</p>
                <form action="{{ route('diesel_recipient.store') }}" method="POST" data-ajax-quick-add data-entity="diesel_recipient">
                    @csrf
                    <div data-form-error class="alert alert-danger"></div>

                    <div class="form-group">
                        <label>اسم الجهة المستلمة</label>
                        <input type="text" name="name" class="form-control" required minlength="2" maxlength="255" />
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

@section('css')
    <style>
        .quick-add-modal .modal-header {
            align-items: center;
        }

        .quick-add-modal .modal-title {
            margin: 0;
        }

        .quick-add-modal .modal-body {
            color: #5e6775;
        }

        .quick-add-modal .quick-add-context {
            margin-bottom: 1rem;
            color: #5e6775;
            line-height: 1.6;
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

        /* إصلاح z-index للمودل الجهة المستلمة */
        #quickAddDieselRecipientModal {
            z-index: 1100 !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
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

        function resetFeedback(form) {
            const $form = $(form);
            $form.find('[data-form-error]').hide().text('');
            $form.find('[data-field-error]').text('');
            $form.find('.is-invalid').removeClass('is-invalid');
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
                if (entityType === 'diesel_recipient') {
                    addOption('#num_section', entity.id, entity.name);
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
    </script>
@endsection
