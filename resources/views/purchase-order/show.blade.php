@extends('layout.master')

@section('title')
    عرض أمر شراء
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المشتريات
@endsection
@section('sub_title')
    عرض أمر شراء
@endsection

@section('css')
    <style>
        /* ===== Modern Purchase Order Show Design ===== */
        .detail-hero {
            background: linear-gradient(135deg, #0d4f4f 0%, #0a6b6b 50%, #0bb783 100%);
            border-radius: 12px;
            padding: 24px 30px;
            margin-bottom: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(13, 79, 79, 0.25);
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

        .detail-hero::after {
            content: '';
            position: absolute;
            bottom: -60%;
            right: 10%;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
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
            color: #0d4f4f;
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
            color: #0d4f4f;
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
            color: #0d4f4f;
        }

        .info-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-section h4 {
            color: #0d4f4f;
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

        .badge-approved {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-rejected {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
            color: #0d4f4f;
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
            color: #0d4f4f;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            margin: 3px 0;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .file-link:hover {
            background: #0d4f4f;
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
            color: #0d4f4f;
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
            border-color: #0bb783;
            box-shadow: 0 0 0 0.2rem rgba(11, 183, 131, 0.1);
        }

        .form-section .btn {
            border-radius: 8px;
            font-weight: 600;
        }

        /* Items table */
        .items-table {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #eef0f3;
        }

        .items-table thead th {
            background: #0d4f4f;
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
        }

        .items-table tbody td {
            padding: 10px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f2f5;
        }

        .items-table tbody tr:hover {
            background: #f0faf7;
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
                <h2><i class="la la-file-text"></i> تفاصيل أمر الشراء: {{ $purchaseOrder->serial_number }}</h2>
                <p>عرض كامل لبيانات الأمر والتوقيعات والحالة</p>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('purchase-report.print-order', $purchaseOrder->id) }}" class="btn btn-back mr-2"
                    title="طباعة PDF" target="_blank">
                    <i class="la la-print"></i> طباعة PDF
                </a>
                <a href="{{ url('/purchase-order') }}" class="btn btn-back">
                    <i class="la la-arrow-right"></i> العودة
                </a>
            </div>
        </div>

        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">معلومات أمر الشراء</h3>
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

                @if (session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif
                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif

                <div class="info-section">
                    <h4><i class="la la-info-circle"></i> بيانات الأمر</h4>
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="info-item">
                                <div class="info-label">رقم مسلسل أمر الشراء</div>
                                <div class="info-value"><strong>{{ $purchaseOrder->serial_number }}</strong></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="info-item">
                                <div class="info-label">طلب الشراء الأصلي</div>
                                <div class="info-value">{{ optional($purchaseOrder->purchase)->serial_number ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="info-item">
                                <div class="info-label">التاريخ</div>
                                <div class="info-value">{{ $purchaseOrder->purchase_date ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="info-item">
                                <div class="info-label">مقدم الطلب</div>
                                <div class="info-value">{{ optional($purchaseOrder->user)->employee_name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="info-item">
                                <div class="info-label">الحالة</div>
                                <div class="info-value">
                                    @php
                                        $statusLabels = [
                                            'pending' => 'قيد الانتظار',
                                            'approved' => 'موافق عليه',
                                            'rejected' => 'مرفوض',
                                        ];
                                        $statusClass = 'badge-' . $purchaseOrder->status;
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ $statusLabels[$purchaseOrder->status] ?? $purchaseOrder->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if ($purchaseOrder->rejection_reason)
                            <div class="col-lg-12">
                                <div class="info-item">
                                    <div class="info-label">سبب الرفض</div>
                                    <div class="info-value">{{ $purchaseOrder->rejection_reason }}</div>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-12">
                            <div class="info-item">
                                <div class="info-label">الوصف</div>
                                <div class="info-value">{{ $purchaseOrder->description ?? 'لا يوجد وصف' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="info-item">
                                <div class="info-label">ملاحظات</div>
                                <div class="info-value">{{ $purchaseOrder->notes ?? 'لا توجد ملاحظات' }}</div>
                            </div>
                        </div>

                        @if ($purchaseOrder->status == 'approved')
                            <div class="col-lg-12">
                                <div class="info-item">
                                    <div class="info-label">مرفقات أمر الشراء (فاتورة الشراء)</div>
                                    <div class="info-value">
                                        @if ($purchaseOrder->purchase_attachments && count(json_decode($purchaseOrder->purchase_attachments, true)) > 0)
                                            @foreach (json_decode($purchaseOrder->purchase_attachments) as $file)
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
                        @endif
                    </div>
                </div>

                <div class="info-section">
                    <h4><i class="la la-pen"></i> التوقيعات</h4>
                    <div class="row">
                        <!-- توقيع قسم المشتريات -->
                        <div class="col-lg-4 mb-3">
                            <div class="signature-card">
                                <h5><i class="la la-shopping-cart"></i> توقيع قسم المشتريات</h5>
                                @if ($purchaseOrder->purchases_signature)
                                    <span class="signature-badge signed">
                                        <i class="la la-check-circle"></i> تم التوقيع
                                    </span>
                                @else
                                    <span class="signature-badge unsigned">
                                        <i class="la la-times-circle"></i> لم يتم التوقيع
                                    </span>
                                @endif
                                <div class="mt-3">
                                    @if (!$purchaseOrder->purchases_signature)
                                        <form method="POST"
                                            action="{{ url('purchase-order/sign-purchases/' . $purchaseOrder->id) }}"
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

                        <!-- توقيع قسم المالية -->
                        <div class="col-lg-4 mb-3">
                            <div class="signature-card">
                                <h5><i class="la la-money"></i> توقيع قسم المالية</h5>
                                @if ($purchaseOrder->finance_signature)
                                    <span class="signature-badge signed">
                                        <i class="la la-check-circle"></i> تم التوقيع
                                    </span>
                                @else
                                    <span class="signature-badge unsigned">
                                        <i class="la la-times-circle"></i> لم يتم التوقيع
                                    </span>
                                @endif
                                <div class="mt-3">
                                    @if (!$purchaseOrder->finance_signature)
                                        <form method="POST"
                                            action="{{ url('purchase-order/sign-finance/' . $purchaseOrder->id) }}"
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

                        <!-- توقيع مقدم الطلب -->
                        <div class="col-lg-4 mb-3">
                            <div class="signature-card">
                                <h5><i class="la la-user"></i> توقيع مقدم الطلب</h5>
                                @if ($purchaseOrder->requester_signature)
                                    <span class="signature-badge signed">
                                        <i class="la la-check-circle"></i> تم التوقيع
                                    </span>
                                @else
                                    <span class="signature-badge unsigned">
                                        <i class="la la-times-circle"></i> لم يتم التوقيع
                                    </span>
                                @endif
                                <div class="mt-3">
                                    @if (!$purchaseOrder->requester_signature)
                                        <form method="POST"
                                            action="{{ url('purchase-order/sign-requester/' . $purchaseOrder->id) }}"
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
                    </div>
                </div>

                @if ($purchaseOrder->status == 'pending')
                    <div class="form-section">
                        <h4><i class="la la-exchange-alt"></i> تحديث الحالة</h4>
                        <form method="POST" action="{{ url('purchase-order/update-status/' . $purchaseOrder->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label>الحالة:</label>
                                    <select name="status" class="form-control" required id="status-select">
                                        <option value="">اختر الحالة</option>
                                        <option value="approved">موافقة</option>
                                        <option value="rejected">رفض</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3" id="rejection-reason-container" style="display: none;">
                                    <label>سبب الرفض:</label>
                                    <input type="text" name="rejection_reason" class="form-control"
                                        placeholder="أدخل سبب الرفض" />
                                </div>
                                <div class="col-lg-6 mb-3" id="purchase-date-container" style="display: none;">
                                    <label>تاريخ الشراء:</label>
                                    <input type="date" name="purchase_date" id="purchase_date"
                                        class="form-control" />
                                </div>
                                <div class="col-lg-12 mb-3" id="description-container" style="display: none;">
                                    <label>الوصف:</label>
                                    <textarea name="description" id="description" class="form-control" rows="3"
                                        placeholder="أدخل وصف أمر الشراء"></textarea>
                                </div>
                                <div class="col-lg-12 mb-3" id="notes-container" style="display: none;">
                                    <label>ملاحظات:</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="أدخل ملاحظات"></textarea>
                                </div>
                                <div class="col-lg-12 mb-3" id="attachments-section" style="display: none;">
                                    <label>مرفقات أمر الشراء (فاتورة الشراء):</label>
                                    <input type="file" name="purchase_attachments[]" multiple class="form-control" />
                                    <span class="form-text text-muted">يمكن إرفاق ملفات متعددة (jpg, jpeg, png, pdf, doc,
                                        docx, xlsx)</span>
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

                <div class="info-section">
                    <h4><i class="la la-list"></i> الأصناف المطلوبة</h4>
                    <table class="table table-bordered items-table">
                        <thead>
                            <tr>
                                <th>الصنف</th>
                                <th>الوحدة</th>
                                <th>الفئة</th>
                                <th>الكمية</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseOrder->items as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->unit_name ?? '-' }}</td>
                                    <td>{{ $item->category_name ?? '-' }}</td>
                                    <td><strong>{{ $item->quantity }}</strong></td>
                                    <td>{{ $item->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
@endsection

@section('scripts')
    <script>
        function toggleSections() {
            const status = document.querySelector('select[name="status"]').value;

            // Show/hide rejection reason
            const rejectionContainer = document.getElementById('rejection-reason-container');
            if (status === 'rejected') {
                rejectionContainer.style.display = 'block';
            } else {
                rejectionContainer.style.display = 'none';
            }

            // Show/hide purchase date, description, notes, and attachments when approved
            const purchaseDateContainer = document.getElementById('purchase-date-container');
            const descriptionContainer = document.getElementById('description-container');
            const notesContainer = document.getElementById('notes-container');
            const attachmentsSection = document.getElementById('attachments-section');

            if (status === 'approved') {
                purchaseDateContainer.style.display = 'block';
                descriptionContainer.style.display = 'block';
                notesContainer.style.display = 'block';
                attachmentsSection.style.display = 'block';
            } else {
                purchaseDateContainer.style.display = 'none';
                descriptionContainer.style.display = 'none';
                notesContainer.style.display = 'none';
                attachmentsSection.style.display = 'none';
            }
        }

        // Add status change listener
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.querySelector('select[name="status"]');
            if (statusSelect) {
                statusSelect.addEventListener('change', toggleSections);
                // Initial check
                toggleSections();
            }
        });
    </script>
@endsection
