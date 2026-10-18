@extends('layout.master')

@section('title')
    {{ __('اضافة المسؤوليات') }}
@endsection

@section('page_title')
    {{ __('لوحة التحكم') }}
@endsection
@section('sub_main')
    {{ __('الرئيسية') }}
@endsection
@section('sub_title')
    {{ __('اضافة المسؤوليات') }}
@endsection

@section('css')
    <style>
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

        .role-checkbox-item {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            margin: 5px;
            border-radius: 20px;
            background: #f8f9fa;
            border: 1px solid #e4e6ef;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 13px;
            user-select: none;
        }

        .role-checkbox-item:hover {
            background: #3699ff;
            color: white;
            border-color: #3699ff;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(54, 153, 255, 0.2);
        }

        .role-checkbox-item:has(input:checked) {
            background: rgba(54, 153, 255, 0.1);
            border-color: #3699ff;
            color: #3699ff;
            font-weight: 600;
        }

        .role-checkbox-item input[type="checkbox"] {
            margin-left: 8px;
            cursor: pointer;
            accent-color: #3699ff;
        }

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
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header border-0 py-5">
                <div class="card-title">
                    <h3 class="card-label">
                        <i class="la la-plus-circle mr-2"></i>
                        {{ __('اضافة المسؤوليات') }}
                    </h3>
                    <p class="mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                        قم بإنشاء مسؤولية جديدة وتحديد الأدوار المرتبطة بها
                    </p>
                </div>
                <div class="card-toolbar">
                    <a class="btn btn-light font-weight-bolder"
                        href="{{ route(config('permission_ui.route_name_prefix') . 'permissions.index') }}">
                        <i class="la la-arrow-right"></i> {{ __('المسؤوليات') }}
                    </a>
                </div>
            </div>

            <form class="form" action="{{ route(config('permission_ui.route_name_prefix') . 'permissions.store') }}"
                method="post">
                @csrf

                <div class="card-body py-5">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label">
                                <i class="la la-key"></i> {{ __('المسؤوليات') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ old('name') }}" placeholder="{{ __('أدخل اسم المسؤولية...') }}" required />
                                <div class="input-group-append">
                                    <span class="input-group-text bg-primary text-white">
                                        <i class="la la-key"></i>
                                    </span>
                                </div>
                            </div>
                            @error('name')
                                <span class="text-danger mt-2 d-block">
                                    <i class="la la-exclamation-circle"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    @if ($roles->count())
                        <div class="form-group mt-4">
                            <label class="form-label">
                                <i class="la la-shield"></i> {{ __('الأدوار') }}
                            </label>
                            <p class="text-muted small mb-3">اختر الأدوار التي ستحصل على هذه المسؤولية</p>
                            <div>
                                @foreach ($roles as $id => $name)
                                    <label class="role-checkbox-item">
                                        <input type="checkbox" name="roles[]"
                                            id="role-{{ $id }}" value="{{ $id }}"
                                            @checked(old('role-' . $id))>
                                        {{ $name }}
                                    </label>
                                @endforeach
                            </div>
                            @error('roles')
                                <span class="text-danger mt-2 d-block">
                                    <i class="la la-exclamation-circle"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-primary px-6 py-3 font-weight-bolder">
                                <i class="la la-save"></i> {{ __('Save') }}
                            </button>
                            <button type="reset" class="btn btn-secondary px-6 py-3">
                                <i class="la la-times"></i> {{ __('Cancel') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <!--begin::Page Scripts(used by this page)-->
    <script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
    <!--end::Page Scripts-->
@endsection
