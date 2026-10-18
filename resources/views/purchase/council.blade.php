@extends('layout.master')

@section('title')
    قرار لجنة العطاءات
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المشتريات
@endsection
@section('sub_title')
    قرار لجنة العطاءات
@endsection

@section('css')
    <style>
        /* ===== Modern Council Decision Design ===== */
        .detail-hero {
            background: linear-gradient(135deg, #6f42c1 0%, #8e5cd9 50%, #a78bfa 100%);
            border-radius: 12px;
            padding: 24px 30px;
            margin-bottom: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(111, 66, 193, 0.25);
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
        .detail-hero .btn-back {
            background: #fff;
            color: #6f42c1;
            font-weight: 700;
            border-radius: 8px;
            padding: 8px 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        .detail-hero .btn-back:hover {
            transform: translateY(-2px);
            color: #6f42c1;
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
            color: #6f42c1;
        }

        .info-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 14px;
        }
        .info-item .info-label {
            font-size: 0.8rem;
            color: #7e8299;
            font-weight: 600;
            margin-bottom: 2px;
        }
        .info-item .info-value {
            font-size: 0.95rem;
            color: #181c32;
            font-weight: 500;
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
            border-color: #6f42c1;
            box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.1);
        }
        .form-section label {
            font-weight: 600;
            color: #6f42c1;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }
        .form-section .form-text {
            font-size: 0.8rem;
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
        <div class="detail-hero d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h2><i class="la la-gavel"></i> قرار المجلس البلدي للطلب: {{ $purchase->serial_number }}</h2>
                <p>إضافة قرار المجلس البلدي (موافقة أو رفض) مع المرفقات</p>
            </div>
            <a href="{{ url('/purchase/' . $purchase->id) }}" class="btn btn-back">
                <i class="la la-arrow-right"></i> العودة
            </a>
        </div>

        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">قرار لجنة العطاءات</h3>
            </div>
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

                <div class="alert alert-info">
                    <i class="la la-info-circle"></i> هذا الطلب محول إلى لجنة العطاءات. يرجى إضافة قرار المجلس البلدي (موافقة أو رفض) مع المرفقات.
                </div>

                <div class="info-section">
                    <div class="row mb-6">
                        <div class="mb-3 col-lg-6">
                            <div class="info-item">
                                <div class="info-label">رقم المسلسل</div>
                                <div class="info-value"><strong>{{ $purchase->serial_number }}</strong></div>
                            </div>
                        </div>
                        <div class="mb-3 col-lg-6">
                            <div class="info-item">
                                <div class="info-label">الموضوع</div>
                                <div class="info-value">{{ $purchase->subject }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('purchase.store-council', $purchase->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-section">
                        <div class="mb-0 row">
                            <div class="mb-6 col-lg-12">
                                <label>قرار المجلس البلدي:</label>
                                <select name="council_decision_status" class="form-control" required>
                                    <option value="">اختر القرار</option>
                                    <option value="approved_stock">موافقة شراء وإدخال للمخازن</option>
                                    <option value="rejected">رفض</option>
                                </select>
                            </div>
                            <div class="mb-6 col-lg-12">
                                <label>مرفقات قرار المجلس البلدي:</label>
                                <input type="file" name="council_decision_files[]" multiple class="form-control" />
                                <span class="form-text text-muted">يمكن إرفاق ملفات متعددة (jpg, jpeg, png, pdf, doc, docx, xlsx)</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary font-weight-bolder">
                            <i class="la la-save"></i> حفظ القرار
                        </button>
                        <a href="{{ url('/purchase/' . $purchase->id) }}" class="btn btn-secondary font-weight-bolder">
                            <i class="la la-close"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <!--end::Card-->
    </div>
@endsection