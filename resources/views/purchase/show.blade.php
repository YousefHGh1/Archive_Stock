@extends('layout.master')

@section('title')
    عرض طلب شراء
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المشتريات
@endsection
@section('sub_title')
    عرض طلب شراء
@endsection

@section('css')
    <style>
        /* ===== Modern Purchase Request Show Design ===== */
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

        .detail-hero .btn-back {
            background: #fff;
            color: #1e3a5f;
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
            color: #1e3a5f;
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

        .info-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-section h4 {
            color: #1e3a5f;
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eef0f3;
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

        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .badge-purchase_order {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-tender_committee {
            background: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

        .badge-rent_repair {
            background: #e2d9f3;
            color: #4b2e83;
            border: 1px solid #d1c4e9;
        }

        .badge-rejected {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .badge-approved_stock {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .signature-card {
            border: 1px solid #eef0f3;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background: #fff;
            transition: all 0.3s ease;
            height: 100%;
        }

        .signature-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .signature-card h5 {
            color: #1e3a5f;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .signature-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .signature-badge.signed {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .signature-badge.unsigned {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

        .action-alert {
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 20px;
            border: none;
        }

        .action-alert .btn {
            border-radius: 8px;
            font-weight: 600;
        }

        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-section h4 {
            color: #1e3a5f;
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eef0f3;
        }

        .form-section .form-control {
            border-radius: 8px;
            border: 1px solid #e4e6ef;
        }

        .form-section .form-control:focus {
            border-color: #3a7bd5;
            box-shadow: 0 0 0 0.2rem rgba(58, 123, 213, 0.1);
        }

        .form-section .btn {
            border-radius: 8px;
            font-weight: 600;
        }

        .card-border {
            border: 1px solid #E4E6EF;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Hero Banner -->
        <div class="detail-hero d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h2><i class="la la-file-text"></i> تفاصيل طلب الشراء: {{ $purchase->serial_number }}</h2>
                <p>عرض كامل لبيانات الطلب والتوقيعات والحالة</p>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('purchase-report.print', $purchase->id) }}" class="btn btn-back mr-2" title="طباعة PDF"
                    target="_blank">
                    <i class="la la-print"></i> طباعة PDF
                </a>
                <a href="{{ url('/purchase') }}" class="btn btn-back">
                    <i class="la la-arrow-right"></i> العودة
                </a>
            </div>
        </div>

        <!--begin::Card-->
        <div class="card card-custom">

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

                @if (session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif
                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif

                <div class="info-section">
                    <h4><i class="la la-info-circle"></i> بيانات الطلب</h4>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="info-item">
                                <div class="info-label">رقم المسلسل</div>
                                <div class="info-value"><strong>{{ $purchase->serial_number }}</strong></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="info-item">
                                <div class="info-label">القسم</div>
                                <div class="info-value">{{ optional($purchase->section)->name_section ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="info-item">
                                <div class="info-label">مقدم الطلب</div>
                                <div class="info-value">{{ $purchase->requester_name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="info-item">
                                <div class="info-label">الموضوع</div>
                                <div class="info-value">{{ $purchase->subject }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="info-item">
                                <div class="info-label">الوصف</div>
                                <div class="info-value">{{ $purchase->description ?? 'لا يوجد وصف' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="info-item">
                                <div class="info-label">ملاحظات</div>
                                <div class="info-value">{{ $purchase->notes ?? 'لا توجد ملاحظات' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="info-item">
                                <div class="info-label">المرفقات</div>
                                <div class="info-value">
                                    @if ($purchase->files && count(json_decode($purchase->files, true)) > 0)
                                        @foreach (json_decode($purchase->files) as $file)
                                            <a href="{{ url('uploads/' . $file) }}" target="_blank" class="file-link">
                                                <i class="la la-download"></i> {{ $file }}
                                            </a>
                                        @endforeach
                                    @else
                                        لا توجد مرفقات
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="info-item">
                                <div class="info-label">الحالة</div>
                                <div class="info-value">
                                    @php
                                        $statusLabels = [
                                            'pending' => 'قيد الانتظار',
                                            'purchase_order' => 'أمر شراء',
                                            'tender_committee' => 'لجنة العطاءات',
                                            'rent_repair' => 'استئجار + إصلاح',
                                            'rejected' => 'مرفوض',
                                            'approved_stock' => 'موافق عليه - مخازن',
                                        ];
                                        $statusClass = 'badge-' . $purchase->status;
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ $statusLabels[$purchase->status] ?? $purchase->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h4><i class="la la-pen"></i> التوقيعات</h4>
                    <div class="row">
                        <!-- توقيع الرئيس -->
                        <div class="col-lg-4 mb-3">
                            <div class="signature-card">
                                <h5><i class="la la-user-tie"></i> توقيع الرئيس</h5>
                                @if ($purchase->president_signature)
                                    <span class="signature-badge signed">
                                        <i class="la la-check-circle"></i> تم التوقيع
                                    </span>
                                @else
                                    <span class="signature-badge unsigned">
                                        <i class="la la-times-circle"></i> لم يتم التوقيع
                                    </span>
                                @endif
                                <div class="mt-3">
                                    @if (!$purchase->president_signature)
                                        <form method="POST" action="{{ url('purchase/sign-president/' . $purchase->id) }}"
                                            style="display:inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="la la-check"></i> توقيع
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST"
                                            action="{{ url('purchase/unsign-president/' . $purchase->id) }}"
                                            style="display:inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('هل تريد إلغاء التوقيع؟ سيتم رفض الطلب!')">
                                                <i class="la la-close"></i> إلغاء التوقيع
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- توقيع مقدم الطلب -->
                        <div class="col-lg-4 mb-3">
                            <div class="signature-card">
                                <h5><i class="la la-user"></i> توقيع مقدم الطلب</h5>
                                @if ($purchase->requester_signature)
                                    <span class="signature-badge signed">
                                        <i class="la la-check-circle"></i> تم التوقيع
                                    </span>
                                @else
                                    <span class="signature-badge unsigned">
                                        <i class="la la-times-circle"></i> لم يتم التوقيع
                                    </span>
                                @endif
                                <div class="mt-3">
                                    @if (!$purchase->requester_signature)
                                        <form method="POST" action="{{ url('purchase/sign-requester/' . $purchase->id) }}"
                                            style="display:inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="la la-check"></i> توقيع
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (Auth::check() && Auth::id() == $purchase->user_id)
                            <div class="signature-card">

                                <h4><i class="la la-cogs"></i> إجراءات</h4>
                                <div class="row">
                                    @if ($purchase->status == 'pending' && !$purchase->president_signature)
                                        <div class="col-lg-4 mb-3">
                                            <form method="POST" action="{{ route('purchase.withdraw', $purchase->id) }}"
                                                onsubmit="return confirm('هل تريد سحب هذا الطلب؟')">
                                                @csrf
                                                <button type="submit" class="btn btn-warning font-weight-bolder">
                                                    <i class="la la-undo"></i> سحب الطلب
                                                </button>
                                            </form>
                                        </div>
                                    @endif

                                    @if ($purchase->status == 'rejected')
                                        <div class="col-lg-4 mb-3">
                                            <a href="{{ route('purchase.reopen', $purchase->id) }}"
                                                class="btn btn-info font-weight-bolder"
                                                onclick="return confirm('هل تريد إعادة فتح هذا الطلب؟ سيتم إنشاء نسخة جديدة.')">
                                                <i class="la la-refresh"></i> إعادة فتح الطلب
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($purchase->status == 'pending' || $purchase->status == 'rejected')
                    <div class="form-section">
                        <h4><i class="la la-exchange-alt"></i> تحديث الحالة</h4>
                        <div class="alert alert-info action-alert">
                            <i class="la la-info-circle"></i> ملاحظة: إذا لم يتم توقيع الرئيس، سيتم رفض الطلب تلقائياً عند
                            محاولة تغيير الحالة.
                        </div>
                        <form method="POST" action="{{ url('purchase/update-status/' . $purchase->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label>الوصف:</label>
                                    <textarea name="description" class="form-control" rows="3">{{ $purchase->description }}</textarea>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label>ملاحظات:</label>
                                    <textarea name="notes" class="form-control" rows="3">{{ $purchase->notes }}</textarea>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label>مرفقات أمر الشراء (فاتورة الشراء):</label>
                                    <input type="file" name="files[]" multiple class="form-control" />
                                    <span class="form-text text-muted">يمكن إرفاق ملفات متعددة (jpg, jpeg, png, pdf, doc,
                                        docx, xlsx)</span>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <select name="status" class="form-control" required>
                                        <option value="">اختر الحالة</option>
                                        <option value="purchase_order">تحويل لأمر شراء</option>
                                        <option value="tender_committee">تحويل إلى لجنة العطاءات</option>
                                        <option value="rent_repair">استئجار + إصلاح</option>
                                        <option value="rejected">رفض</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <button type="submit" class="btn btn-primary font-weight-bolder">
                                        <i class="la la-save"></i> حفظ الحالة
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($purchase->status == 'purchase_order')
                    <div class="alert alert-success action-alert">
                        <i class="la la-check-circle"></i> تم تحويل الطلب إلى أمر شراء.
                        <a href="{{ url('purchase-order/create/' . $purchase->id) }}"
                            class="btn btn-primary font-weight-bolder mr-3">
                            <i class="la la-plus"></i> إنشاء أمر شراء
                        </a>
                    </div>
                @endif

                @if ($purchase->status == 'tender_committee' && $purchase->council_decision_status == null)
                    <div class="alert alert-warning action-alert">
                        <i class="la la-exclamation-triangle"></i> هذا الطلب محول إلى لجنة العطاءات. يرجى إضافة قرار المجلس
                        البلدي.
                        <a href="{{ route('purchase.council', $purchase->id) }}"
                            class="btn btn-warning font-weight-bolder mr-3">
                            <i class="la la-file"></i> إضافة قرار المجلس البلدي
                        </a>
                    </div>
                @endif

                @if ($purchase->council_decision_files && count(json_decode($purchase->council_decision_files, true)) > 0)
                    <div class="info-section">
                        <h4><i class="la la-gavel"></i> قرار المجلس البلدي</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="info-item">
                                    <div class="info-label">حالة قرار المجلس:</div>
                                    <div class="info-value">
                                        @if ($purchase->council_decision_status == 'approved_stock')
                                            <span class="badge badge-success">موافق عليه - إدخال للمخازن</span>
                                        @elseif ($purchase->council_decision_status == 'rejected')
                                            <span class="badge badge-danger">مرفوض</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="info-item">
                                    <div class="info-label">مرفقات قرار المجلس:</div>
                                    <div class="info-value">
                                        @foreach (json_decode($purchase->council_decision_files) as $file)
                                            <a href="{{ url('uploads/' . $file) }}" target="_blank" class="file-link">
                                                <i class="la la-download"></i> {{ $file }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($purchase->council_decision_status == 'approved_stock' && $purchase->stock_entry_status == null)
                    <div class="form-section">
                        <h4><i class="la la-warehouse"></i> إدخال للمخازن</h4>
                        <div class="alert alert-info action-alert">
                            <i class="la la-info-circle"></i> يرجى إضافة فاتورة الشراء وإرسال إشعار لقسم المخازن.
                        </div>
                        <form method="POST" action="{{ url('purchase/update-stock-entry/' . $purchase->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label>مرفقات فاتورة الشراء:</label>
                                    <input type="file" name="invoice_attachments[]" multiple class="form-control" />
                                    <span class="form-text text-muted">يمكن إرفاق ملفات متعددة (jpg, jpeg, png, pdf, doc,
                                        docx, xlsx)</span>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>حالة الإدخال:</label>
                                    <select name="stock_entry_status" class="form-control" required
                                        id="stock-entry-status-select">
                                        <option value="">اختر الحالة</option>
                                        <option value="completed">إدخال للمخازن</option>
                                        <option value="rejected">رفض</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <button type="submit" class="btn btn-primary font-weight-bolder">
                                        <i class="la la-save"></i> حفظ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($purchase->stock_entry_status)
                    <div class="info-section">
                        <h4><i class="la la-warehouse"></i> حالة إدخال المخازن</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="info-item">
                                    <div class="info-label">الحالة:</div>
                                    <div class="info-value">
                                        @if ($purchase->stock_entry_status == 'completed')
                                            <span class="badge badge-success">تم الإدخال للمخازن</span>
                                        @elseif ($purchase->stock_entry_status == 'rejected')
                                            <span class="badge badge-danger">مرفوض</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($purchase->invoice_attachments && count(json_decode($purchase->invoice_attachments, true)) > 0)
                                <div class="col-lg-12">
                                    <div class="info-item">
                                        <div class="info-label">مرفقات فاتورة الشراء:</div>
                                        <div class="info-value">
                                            @foreach (json_decode($purchase->invoice_attachments) as $file)
                                                <a href="{{ url('uploads/' . $file) }}" target="_blank"
                                                    class="file-link">
                                                    <i class="la la-download"></i> {{ $file }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if ($purchase->finance_transferred)
                    <div class="info-section">
                        <h4><i class="la la-money"></i> توقيع قسم المالية (استئجار + إصلاح)</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="signature-card">
                                    <h5>توقيع قسم المالية</h5>
                                    @if ($purchase->finance_signature)
                                        <span class="signature-badge signed">
                                            <i class="la la-check-circle"></i> تم التوقيع
                                        </span>
                                    @else
                                        <span class="signature-badge unsigned">
                                            <i class="la la-times-circle"></i> لم يتم التوقيع
                                        </span>
                                    @endif
                                    <div class="mt-3">
                                        @if (!$purchase->finance_signature)
                                            <form method="POST"
                                                action="{{ url('purchase/sign-finance/' . $purchase->id) }}"
                                                style="display:inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="la la-check"></i> توقيع
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST"
                                                action="{{ url('purchase/unsign-finance/' . $purchase->id) }}"
                                                style="display:inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('هل تريد إلغاء التوقيع؟')">
                                                    <i class="la la-close"></i> إلغاء التوقيع
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($purchase->finance_signature && $purchase->rent_repair_status == null)
                    <div class="form-section">
                        <h4><i class="la la-tools"></i> تحديث استئجار + إصلاح</h4>
                        <div class="alert alert-info action-alert">
                            <i class="la la-info-circle"></i> يرجى إضافة عقد الإيجار أو فاتورة التصليح.
                        </div>
                        <form method="POST" action="{{ url('purchase/update-rent-repair/' . $purchase->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label>مرفقات (عقد الإيجار أو فاتورة التصليح):</label>
                                    <input type="file" name="rent_repair_attachments[]" multiple
                                        class="form-control" />
                                    <span class="form-text text-muted">يمكن إرفاق ملفات متعددة (jpg, jpeg, png, pdf, doc,
                                        docx, xlsx)</span>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label>الحالة:</label>
                                    <select name="rent_repair_status" class="form-control" required
                                        id="rent-repair-status-select">
                                        <option value="">اختر الحالة</option>
                                        <option value="approved">موافقة</option>
                                        <option value="rejected">رفض</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3" id="rent-repair-rejection-container" style="display: none;">
                                    <label>سبب الرفض:</label>
                                    <input type="text" name="rent_repair_rejection_reason" class="form-control"
                                        placeholder="أدخل سبب الرفض" />
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <button type="submit" class="btn btn-primary font-weight-bolder">
                                        <i class="la la-save"></i> حفظ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($purchase->rent_repair_status)
                    <div class="info-section">
                        <h4><i class="la la-tools"></i> حالة استئجار + إصلاح</h4>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="info-item">
                                    <div class="info-label">الحالة:</div>
                                    <div class="info-value">
                                        @if ($purchase->rent_repair_status == 'approved')
                                            <span class="badge badge-success">موافق عليه</span>
                                        @elseif ($purchase->rent_repair_status == 'rejected')
                                            <span class="badge badge-danger">مرفوض</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($purchase->rent_repair_rejection_reason)
                                <div class="col-lg-12">
                                    <div class="info-item">
                                        <div class="info-label">سبب الرفض:</div>
                                        <div class="info-value">{{ $purchase->rent_repair_rejection_reason }}</div>
                                    </div>
                                </div>
                            @endif
                            @if ($purchase->rent_repair_attachments && count(json_decode($purchase->rent_repair_attachments, true)) > 0)
                                <div class="col-lg-12">
                                    <div class="info-item">
                                        <div class="info-label">المرفقات:</div>
                                        <div class="info-value">
                                            @foreach (json_decode($purchase->rent_repair_attachments) as $file)
                                                <a href="{{ url('uploads/' . $file) }}" target="_blank"
                                                    class="file-link">
                                                    <i class="la la-download"></i> {{ $file }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!--end::Card-->
    </div>
@endsection

@section('scripts')
    <script>
        // Toggle rent repair rejection reason based on status
        document.addEventListener('DOMContentLoaded', function() {
            const rentRepairStatusSelect = document.getElementById('rent-repair-status-select');
            if (rentRepairStatusSelect) {
                rentRepairStatusSelect.addEventListener('change', function() {
                    const rejectionContainer = document.getElementById('rent-repair-rejection-container');
                    if (this.value === 'rejected') {
                        rejectionContainer.style.display = 'block';
                    } else {
                        rejectionContainer.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endsection
