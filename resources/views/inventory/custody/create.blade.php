@extends('layout.master')

@section('title')
    اضافة العهد
@endsection

@section('page_title')
    لوحة التحكم
@endsection

@section('sub_main')
    العهد
@endsection

@section('sub_title')
    اضافة العهد
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
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label"> بيانات العهد <i class="mr-2"></i></h3>
                        </div>
                        <div class="card-toolbar">

                            <a href="{{ url('/inventory/custody') }}" class="btn btn-info font-weight-bolder">
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
                                </span>عرض العهد </a>
                                <x-add-resource-button />
                            </div>
                    </div>
                    <!--begin::Form-->
                    <form action="{{ route('custody.store') }}" method="post" class="form needs-validation " novalidate
                        enctype="multipart/form-data" id="kt_form">
                        @csrf
                        {!! csrf_field() !!}
                        <div class="card-body">

                            {{-- 1 --}}
                            <div class="p-3 form-group row">
                                <label for="section_id" class="col-lg-2 col-form-label ">
                                    <h6><strong>الدائرة<span class="mr-1 tx-danger">*</span></strong></h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">

                                        <select name="section_id" id="section_id"
                                            class="@error('section_id') is-invalid @enderror form-control selectpicker "
                                            data-size="7" tabindex="null" data-live-search="true" title="أدخل اسم الدائرة">
                                            <option value="">اختر الدائرة</option>
                                            @foreach ($section as $sections)
                                                <option value="{{ $sections->id }}">
                                                    {{ $sections->name_section }}
                                                </option>
                                            @endforeach
                                            @if ($errors->has('section_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('section_id') }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <label for="sub_section_id " class="col-lg-2 col-form-label ">
                                    <h6><strong>القسم <span class="mr-1 tx-danger">*</span></strong></h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <select name="sub_section_id" id="sub_section_id"
                                            onclick="console.log($(this).val())" onchange="console.log('change is firing')"
                                            data-size="7" tabindex="null"
                                            class="@error('sub_section_id') is-invalid @enderror form-control "
                                            data-live-search="true" title="أدخل اسم القسم ">
                                            <option value="">اختر القسم </option>
                                            @foreach ($subSection as $subSections)
                                                <option value="{{ $subSections->id }}">
                                                    {{ $subSections->name }}
                                                </option>
                                            @endforeach
                                            @if ($errors->has('sub_section_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('sub_section_id') }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- 2 --}}
                            <div class="p-3 form-group row">
                                <label for="user_id" class="col-lg-2 col-form-label">
                                    <h6><strong>الموظف:</strong></h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">

                                        <select name="user_id" id="user_id" data-size="7" tabindex="null"
                                            class="@error('user_id') is-invalid @enderror form-control "
                                            data-live-search="true" title="أدخل اسم القسم">


                                            {{-- @foreach ($user as $users)
                                                <option value="{{ $users->id }}">
                                                    {{ $users->employee_name }}
                                                </option>
                                            @endforeach --}}

                                            @if ($errors->has('user_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('user_id') }}</strong>
                                                </span>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <label for="custody_num" class="col-lg-2 col-form-label ">
                                    <h6><strong>رقم العهدة:</strong></h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="custody_num" type="number" min="0" class="form-control"
                                            id="custody_num" />
                                    </div>
                                </div>


                            </div>

                            <div class="p-3 form-group row">
                                <label for="date" class="col-lg-2 col-form-label ">
                                    <h6><strong> التاريخ:</strong></h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="date" id="date" type="date" class="form-control"
                                            value="{{ date('Y-m-d') }}" placeholder="ادخل التاريخ">
                                    </div>
                                </div>
                            </div>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50%">الصنف
                                            <button type="button" class="btn btn-link p-0" title="اضافة صنف جديد"
                                                data-toggle="modal" data-target="#quickAddCustodyItemModal"
                                                aria-label="إضافة صنف عهدة جديد">
                                                <i class="p-3 ki ki-solid-plus icon-md"></i>
                                            </button>
                                            <a href="{{ url('inventory/custody_Item') }}" title="عرض الأصناف"
                                                target="_blank">
                                                <i class="p-3 far fa-eye icon-md"></i>
                                            </a>
                                        </th>
                                        <th>الكمية</th>
                                        <th width="5%">جديد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control selectpicker" data-size="7" tabindex="null"
                                                title="أدخل الصنف" data-live-search="true" name="product[]"
                                                id="product[]">
                                                <option value="">اختر الصنف</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="quantity[]" class="form-control"></td>
                                        <td> <button type="button" class="btn btn-primary" id="add-row">+</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <button type="submit" class="mr-2 btn btn-success">حفظ</button>
                                    <button type="reset" class="mr-2 btn btn-danger">إلغاء</button>

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

    <div class="modal fade quick-add-modal" id="quickAddCustodyItemModal" tabindex="-1" role="dialog"
        aria-labelledby="quickAddCustodyItemTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('custody_Item.store') }}" method="post" data-ajax-quick-add
                    data-entity="custody-item">
                    @csrf
                    <div class="modal-header">
                        <div class="quick-add-heading">
                            <h5 class="modal-title" id="quickAddCustodyItemTitle">إضافة صنف عهدة جديد</h5>
                            <p class="quick-add-context">أضف الصنف مباشرةً إلى قائمة العهد.</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-4" role="alert" data-form-error></div>
                        <div class="form-group mb-0">
                            <label for="quick_custody_item_name">اسم الصنف</label>
                            <input id="quick_custody_item_name" class="form-control" type="text" name="name"
                                minlength="2" required>
                            <div class="invalid-feedback" data-field-error="name"></div>
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
    </script>

    <script>
        $(document).ready(function() {
            // السماح بحذف صف من جدول المنتجات
            $(document).on('click', '.delete-row', function() {
                $(this).closest('tr').remove();
                calculateTotalAll();
            });

            // تحويل الزر "add-row" لإضافة الصف إلى جدول المنتجات
            $("#add-row").click(function() {
                var html = "<tr>";
                html +=
                    "<td><select name='product[]' title='أدخل الصنف' class='form-control selectpicker' data-size='7' data-live-search='true'><option value=''>اختر الصنف</option>";
                @foreach ($products as $product)
                    html +=
                        "<option value='{{ $product->id }}'>{{ $product->name }} </option>";
                @endforeach
                html += "</select></td>";
                html +=
                    "<td><input type='number' name='quantity[]' class='form-control'  onchange='calculateTotal(this); calculateTotalAll();'></td>";

                html += "<td><button type='button' class='btn btn-danger delete-row'>حذف</button></td>";
                html += "</tr>";
                $("table tbody").append(html);
                var $newSelect = $("table tbody tr:last select[name='product[]']");
                $newSelect.selectpicker();
                $newSelect.selectpicker('refresh');
            });

            // حساب الإجمالي الكلي
            function calculateTotalAll() {
                var total = 0;
                $("table tbody tr").each(function() {
                    var quantity = parseFloat($(this).find("input[name='quantity[]']").val());
                    var price = parseFloat($(this).find("input[name='price[]']").val());
                    var subtotal = quantity * price;
                    $(this).find("input[name='Total']").val(subtotal.toFixed(2));
                    total += subtotal;
                });
                $("#total-all").val(total.toFixed(2));
            }

        });

        function calculateTotal(element) {
            var tr = $(element).closest('tr');
            var quantity = parseFloat(tr.find("input[name='quantity[]']").val());
            var price = parseFloat(tr.find("input[name='price[]']").val());
            var total = quantity * price;
            tr.find("input[name='Total[]']").val(total);
        }
    </script>

    <script>
        $(document).ready(function() {
            $('select[name="sub_section_id"]').on('change', function() {
                var sub_section_id = $(this).val();
                if (sub_section_id) {
                    $.ajax({
                        url: "{{ URL::to('getUsers') }}/" + sub_section_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="user_id"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="user_id"]').append(
                                    '<option value="' +
                                    value + '">' + value + '</option>');
                            });
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
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

            function addCustodyItemToSelects(item) {
                var value = String(item.id);
                var $selects = $('#kt_form select[name="product[]"]');

                if (!$selects.filter(function() {
                        return !this.value;
                    }).length) {
                    $('#add-row').trigger('click');
                    $selects = $('#kt_form select[name="product[]"]');
                }

                $selects.each(function() {
                    if (!$(this).find('option').filter(function() {
                            return this.value === value;
                        }).length) {
                        $(this).append(new Option(item.name, value));
                    }
                    if ($(this).hasClass('selectpicker')) {
                        $(this).selectpicker('refresh');
                    }
                });

                var $target = $selects.filter(function() {
                    return !this.value;
                }).first();
                if ($target.length) {
                    $target.val(value).trigger('change');
                    if ($target.hasClass('selectpicker')) {
                        $target.selectpicker('refresh');
                    }
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
                }).done(function(item) {
                    addCustodyItemToSelects(item);
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
                        var message = xhr.responseJSON && xhr.responseJSON.message;
                        $form.find('[data-form-error]').text(message ||
                            'تعذر الاتصال بالخادم. تحقق من الاتصال وحاول مرة أخرى.').show();
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
