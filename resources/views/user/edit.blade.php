@extends('layout.master')

@section('title')
    تعديل الموظف
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
الموظفين
@endsection
@section('sub_title')
    صفحة التعديل
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

        .form-control:read-only {
            background: #f8f9fa;
            border-color: #e4e6ef;
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

        /* ====== User Info Card ====== */
        .user-info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e4e6ef;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .user-info-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border-color: #3699ff;
        }

        .user-info-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-left: 15px;
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.3);
            flex-shrink: 0;
        }

        .user-info-name {
            font-size: 18px;
            font-weight: 700;
            color: #3f4254;
            margin-bottom: 5px;
        }

        .user-info-meta {
            font-size: 13px;
            color: #a1a5b7;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header border-0 py-5">
                <div class="card-title">
                    <h3 class="card-label">
                        <i class="la la-edit mr-2"></i>
                        تعديل بيانات الموظف
                    </h3>
                    <p class="mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                        قم بتعديل بيانات الموظف
                    </p>
                </div>
                <div class="card-toolbar">
                    <a href="{{url('user')}}" class="btn btn-light font-weight-bolder">
                        <i class="la la-arrow-right"></i> عرض المستخدمين
                    </a>
                </div>
            </div>

            <!-- User Info Card -->
            <div class="card-body py-5">
                <div class="user-info-card">
                    <div class="user-info-icon">
                        <i class="la la-user"></i>
                    </div>
                    <div>
                        <div class="user-info-name">{{ $user->employee_name }}</div>
                        <div class="user-info-meta">
                            <i class="la fa-id-card"></i> الرقم الوظيفي: {{ $user->name }}
                            <span class="mx-2">|</span>
                            <i class="la la-envelope"></i> {{ $user->email ?? '-' }}
                        </div>
                    </div>
                </div>

                <form action="{{ route('user.update', $user->id) }}" method="post" class="needs-validation" novalidate
                    enctype="multipart/form-data">
                    @csrf
                    {!! csrf_field() !!}
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="la fa-id-card"></i> الرقم الوظيفي
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="la fa-id-card"></i>
                                        </span>
                                    </div>
                                    <input name="name" id="name" type="text" class="form-control"
                                        value="{{ $user->name }}" readonly>
                                </div>
                                <small class="text-muted">لا يمكن تعديل الرقم الوظيفي</small>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="employee_name" class="form-label">
                                    <i class="la la-user"></i> اسم الموظف
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="la la-user"></i>
                                        </span>
                                    </div>
                                    <input name="employee_name" id="employee_name" type="text"
                                        class="form-control" value="{{ $user->employee_name }}" placeholder="أدخل اسم الموظف">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="section_id" class="form-label">
                                    <i class="la la-building"></i> الدائرة
                                </label>
                                <div class="input-group">
                                    <select class="form-control selectpicker" data-size="7" tabindex="null"
                                    data-live-search="true" title="اختر الدائرة" name="section_id" id="section_id">
                                    <option value="">اختر الدائرة</option>
                                    @foreach ($section as $sections)
                                        <option value="{{ $sections->id }}" {{ $user->section_id == $sections->id ? 'selected' : '' }}>
                                            {{ $sections->name_section }}
                                        </option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="sub_section_id" class="form-label">
                                    <i class="la la-sitemap"></i> القسم
                                </label>
                                <div class="input-group">
                                    <select class="form-control selectpicker" data-size="7" tabindex="null"
                                    data-live-search="true" title="اختر القسم" name="sub_section_id" id="sub_section_id">
                                    <option value="">اختر القسم</option>
                                    @foreach ($subSection as $subSections)
                                        <option value="{{ $subSections->id }}" {{ $user->sub_section_id == $subSections->id ? 'selected' : '' }}>
                                            {{ $subSections->name }}
                                        </option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-success px-6 py-3 font-weight-bolder mr-2">
                                <i class="la la-save"></i> تعديل
                            </button>
                            <button type="reset" class="btn btn-danger px-6 py-3">
                                <i class="la la-times"></i> إلغاء
                            </button>
                        </div>
                        <div class="col-lg-6 text-right">
                            <a href="{{url('user')}}" class="btn btn-light px-6 py-3">
                                <i class="la la-arrow-right"></i> عودة للقائمة
                            </a>
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
                                $('select[name="sub_section_id"]').append(
                                    '<option value="' + id + '">' + name + '</option>'
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

@section('scripts')
@endsection
