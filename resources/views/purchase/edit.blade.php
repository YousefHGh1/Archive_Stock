@extends('layout.master')

@section('title')
    تعديل طلب شراء
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المشتريات
@endsection
@section('sub_title')
    تعديل طلب شراء
@endsection

@section('css')
    <style>
        /* ===== Modern Purchase Edit Design ===== */
        .detail-hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8e 50%, #3a7bd5 100%);
            border-radius: 12px;
            padding: 24px 30px;
            margin-bottom: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(30, 58, 95, 0.25);
        }

        .detail-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .detail-hero h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
            position: relative;
            z-index: 1;
        }

        .detail-hero p {
            opacity: 0.85;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }

        .card-custom {
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: none;
        }

        .card-custom .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #eef0f3;
            border-radius: 12px 12px 0 0 !important;
            padding: 18px 24px;
        }

        .card-custom .card-header .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e3a5f;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }

        .form-section .form-control {
            border-radius: 8px;
            border: 1px solid #e4e6ef;
            padding: 10px 14px;
        }

        .form-section .form-control:focus {
            border-color: #3a7bd5;
            box-shadow: 0 0 0 0.2rem rgba(58, 123, 213, 0.1);
        }

        .form-section label {
            font-weight: 600;
            color: #1e3a5f;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .form-section .form-text {
            font-size: 0.8rem;
        }

        .file-link {
            display: inline-block;
            background: #eef2f7;
            color: #1e3a5f;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            margin: 3px 0;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .file-link:hover {
            background: #1e3a5f;
            color: #fff;
        }

        .card-footer {
            background: #f8f9fa;
            border-top: 1px solid #eef0f3;
            border-radius: 0 0 12px 12px !important;
            padding: 16px 24px;
        }

        .card-footer .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 24px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Hero Banner -->
        <div class="detail-hero">
            <h2><i class="la la-edit"></i> تعديل طلب شراء: {{ $purchase->serial_number }}</h2>
            <p>قم بتحديث بيانات طلب الشراء</p>
        </div>

        <!--begin::Card-->
        <div class="card card-custom">

            <!--begin::Form-->
            <form method="POST" action="{{ url('/purchase/' . $purchase->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-section">
                        <div class="mb-0 row">
                            <div class="mb-1 col-lg-2">
                                <label>رقم المسلسل:</label>
                                <input type="text" value="{{ $purchase->serial_number }}" class="form-control"
                                    disabled />
                            </div>
                            <div class="mb-1 col-lg-4">
                                <label>مقدم الطلب:</label>
                                <input type="text" name="requester_name" class="form-control"
                                    value="{{ $purchase->requester_name }}" />
                            </div>
                            <div class="mb-1 col-lg-6">
                                <label>الموضوع:</label>
                                <input type="text" name="subject" class="form-control" value="{{ $purchase->subject }}"
                                    required />
                            </div>
                            <div class="mb-1 col-lg-4">
                                <label>القسم:</label>
                                <select name="section_id" class="form-control" required>
                                    <option value="">اختر القسم</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"
                                            {{ $purchase->section_id == $section->id ? 'selected' : '' }}>
                                            {{ $section->name_section }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-1 col-lg-4">
                                <label>المرفقات الحالية:</label>
                                <p class="form-control-plaintext">
                                    @if ($purchase->files && count(json_decode($purchase->files, true)) > 0)
                                        @foreach (json_decode($purchase->files) as $file)
                                            <a href="{{ url('uploads/' . $file) }}" target="_blank" class="file-link">
                                                <i class="la la-paperclip"></i> {{ $file }}
                                            </a>
                                        @endforeach
                                    @else
                                        لا توجد مرفقات
                                    @endif
                                </p>
                            </div>
                            <div class="mb-1 col-lg-4">
                                <label>استبدال المرفقات:</label>
                                <input type="file" name="files[]" multiple class="form-control" />
                                <span class="form-text text-muted">سيتم استبدال جميع المرفقات الحالية</span>
                            </div>
                            <div class="mb-6 col-lg-6">
                                <label>الوصف:</label>
                                <textarea name="description" class="form-control" rows="3">{{ $purchase->description }}</textarea>
                            </div>
                            <div class="mb-6 col-lg-6">
                                <label>ملاحظات:</label>
                                <textarea name="notes" class="form-control" rows="3">{{ $purchase->notes }}</textarea>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary font-weight-bolder">
                        <i class="la la-save"></i> حفظ التعديلات
                    </button>
                    <a href="{{ url('/purchase') }}" class="btn btn-secondary font-weight-bolder">
                        <i class="la la-close"></i> إلغاء
                    </a>
                </div>
            </form>
            <!--end::Form-->
        </div>
        <!--end::Card-->
    </div>
@endsection
