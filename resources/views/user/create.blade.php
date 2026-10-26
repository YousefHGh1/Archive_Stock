@extends('layout.master')
@section('title')
    اضافة مستخدم
@endsection
@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المستخدمين
@endsection
@section('sub_title')
    صفحة الإضافة
@endsection
@section('css')
    <style>
        /* ====== Card Custom ====== */
        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            border-radius: 16px 16px 0 0;
            color: white;
            border-bottom: none;
        }

        .card-header .card-label {
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .card-header .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* ====== Form Controls ====== */
        .form-control {
            border-radius: 10px;
            border: 2px solid #e4e6ef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3699ff;
            box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.1);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 2px solid #e4e6ef;
            border-right: none;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        /* ====== Form Label ====== */
        .form-label {
            font-weight: 600;
            color: #3f4254;
            margin-bottom: 8px;
            display: block;
        }

        .form-label i {
            color: #3699ff;
            margin-left: 5px;
        }

        /* ====== Buttons ====== */
        .btn-primary {
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(54, 153, 255, 0.4);
        }

        .btn-secondary {
            border-radius: 10px;
            border: 2px solid #e4e6ef;
            background: white;
            color: #3f4254;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: #a1a5b7;
            background: #f8f9fa;
        }

        .btn-success {
            background: linear-gradient(135deg, #1bc5bd 0%, #3699ff 100%);
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(27, 197, 189, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(27, 197, 189, 0.4);
        }

        .btn-danger {
            border-radius: 10px;
            border: 2px solid #f64e60;
            background: white;
            color: #f64e60;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #f64e60;
            color: white;
        }

        .btn-light {
            border-radius: 10px;
            border: none;
            background: white;
            color: #3699ff;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card-footer {
            background: #f8f9fa;
            border-top: 1px solid #e4e6ef;
            border-radius: 0 0 16px 16px;
            padding: 20px;
        }

        /* ====== Form Group ====== */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group h6 {
            font-weight: 600;
            color: #3f4254;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header border-0 py-5">
                <div class="card-title">
                    <h3 class="card-label">
                        <i class="la la-user-plus mr-2"></i>
                        إضافة مستخدم جديد
                    </h3>
                    <p class="mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                        قم بإنشاء حساب مستخدم جديد في النظام
                    </p>
                </div>
                <div class="card-toolbar">
                    <a href="{{url('user')}}" class="btn btn-light font-weight-bolder">
                        <i class="la la-arrow-right"></i> عرض المستخدمين
                    </a>
                </div>
            </div>

            <form action="{{ route('user.store') }}" method="post" class="needs-validation" novalidate
                enctype="multipart/form-data" id="kt_form" autocomplete="off">
                @csrf
                {!! csrf_field() !!}

                <div class="card-body py-5">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="la fa-id-card"></i> الرقم الوظيفي <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="la fa-id-card"></i>
                                        </span>
                                    </div>
                                    <input class="@error('name') is-invalid @enderror form-control" id="name" type="text" name="name"
                                        :value="old('name')" required autofocus autocomplete="name" placeholder="أدخل الرقم الوظيفي" />
                                </div>
                                @error('name')
                                    <div class="text-danger mt-2">
                                        <i class="la la-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="employee_name" class="form-label">
                                    <i class="la la-user"></i> اسم الموظف <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="la la-user"></i>
                                        </span>
                                    </div>
                                    <input class="@error('employee_name') is-invalid @enderror form-control" id="employee_name" type="text" name="employee_name"
                                        :value="old('employee_name')" required autofocus autocomplete="employee_name" placeholder="أدخل اسم الموظف" />
                                </div>
                                @error('employee_name')
                                    <div class="text-danger mt-2">
                                        <i class="la la-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="la la-envelope"></i> البريد الإلكتروني <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="la la-envelope"></i>
                                        </span>
                                    </div>
                                    <input class="@error('email') is-invalid @enderror form-control" id="email" type="email" name="email"
                                        :value="old('email')" required placeholder="أدخل البريد الإلكتروني" />
                                </div>
                                @error('email')
                                    <div class="text-danger mt-2">
                                        <i class="la la-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="la la-lock"></i> كلمة المرور <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="la la-lock"></i>
                                        </span>
                                    </div>
                                    <input class="@error('password') is-invalid @enderror form-control" id="password" type="password" name="password" required
                                        autocomplete="new-password" placeholder="أدخل كلمة المرور" />
                                </div>
                                @error('password')
                                    <div class="text-danger mt-2">
                                        <i class="la la-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="section_id" class="form-label">
                                    <i class="la la-building"></i> الدائرة <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <select name="section_id" id="section_id"
                                        class="@error('section_id') is-invalid @enderror form-control selectpicker "
                                        data-size="7" tabindex="null" data-live-search="true" title="اختر الدائرة">
                                        <option value="">اختر الدائرة</option>
                                        @foreach ($section as $sections)
                                            <option value="{{ $sections->id }}">
                                                {{ $sections->name_section }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('section_id')
                                    <div class="text-danger mt-2">
                                        <i class="la la-exclamation-circle"></i> {{ $errors->first('section_id') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="sub_section_id" class="form-label">
                                    <i class="la la-sitemap"></i> القسم <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <select name="sub_section_id" id="sub_section_id"
                                        data-size="7" tabindex="null"
                                        class="@error('sub_section_id') is-invalid @enderror form-control "
                                        data-live-search="true" title="اختر القسم">
                                        <option value="">اختر القسم</option>
                                        @foreach ($subSection as $subSections)
                                            <option value="{{ $subSections->id }}">
                                                {{ $subSections->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('sub_section_id')
                                    <div class="text-danger mt-2">
                                        <i class="la la-exclamation-circle"></i> {{ $errors->first('sub_section_id') }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-success px-6 py-3 font-weight-bolder mr-2">
                                <i class="la la-save"></i> حفظ
                            </button>
                            <button type="reset" class="btn btn-danger px-6 py-3">
                                <i class="la la-times"></i> إلغاء
                            </button>
                        </div>
                        <div class="col-lg-6 text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary font-weight-bolder">
                                    <i class="la la-plus"></i> الدوائر والأقسام
                                </button>
                                <button type="button"
                                    class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left">
                                    <a href="{{ url('/section/create') }}" class="dropdown-item">
                                        <i class="la la-plus-circle text-primary"></i> إنشاء دائرة
                                    </a>
                                    <a href="{{ url('/subSection/create') }}" class="dropdown-item">
                                        <i class="la la-plus-circle text-success"></i> إنشاء قسم
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function() {
            $('select[name="section_id"]').on('change', function() {
                var section_id = $(this).val();
                if (section_id) {
                    $.ajax({
                        url: "{{ URL::to('getsub_section') }}/" + section_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="sub_section_id"]').empty();
                            $.each(data, function(id, name) {
                                // Append the correct value (ID) for sub_section_id
                                $('select[name="sub_section_id"]').append(
                                    '<option value="' + id + '">' + name +
                                    '</option>'
                                );
                            });
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });
    </script>


@endsection
