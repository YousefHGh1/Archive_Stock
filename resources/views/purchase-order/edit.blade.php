@extends('layout.master')

@section('title')
    تعديل أمر شراء
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المشتريات
@endsection
@section('sub_title')
    تعديل أمر شراء
@endsection

@section('css')
    <style>
        /* ===== Modern Purchase Order Edit Design ===== */
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
            padding: 10px 14px;
        }
        .form-section .form-control:focus {
            border-color: #0bb783;
            box-shadow: 0 0 0 0.2rem rgba(11, 183, 131, 0.1);
        }
        .form-section label {
            font-weight: 600;
            color: #0d4f4f;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }
        .form-section .form-text {
            font-size: 0.8rem;
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

        .item-row {
            background: #fff;
            border: 1px solid #eef0f3;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }
        .item-row:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
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
            <h2><i class="la la-edit"></i> تعديل أمر شراء: {{ $purchaseOrder->serial_number }}</h2>
            <p>قم بتحديث بيانات أمر الشراء والأصناف المطلوبة</p>
        </div>

        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">بيانات أمر الشراء</h3>
            </div>
            <!--begin::Form-->
            <form method="POST" action="{{ url('/purchase-order/' . $purchaseOrder->id) }}" enctype="multipart/form-data">
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

                    <div class="info-section">
                        <h4><i class="la la-file-text"></i> طلب الشراء الأصلي</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="info-item">
                                    <div class="info-label">رقم المسلسل</div>
                                    <div class="info-value"><strong>{{ optional($purchaseOrder->purchase)->serial_number ?? '-' }}</strong></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="info-item">
                                    <div class="info-label">الموضوع</div>
                                    <div class="info-value">{{ optional($purchaseOrder->purchase)->subject ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4><i class="la la-cog"></i> بيانات الأمر</h4>
                        <div class="mb-0 row">
                            <div class="mb-6 col-lg-6">
                                <label>رقم مسلسل أمر الشراء:</label>
                                <input type="text" value="{{ $purchaseOrder->serial_number }}" class="form-control"
                                    disabled />
                            </div>
                            <div class="mb-6 col-lg-6">
                                <label>الحالة:</label>
                                <select name="status" class="form-control" required id="status-select">
                                    <option value="pending" {{ $purchaseOrder->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                    <option value="approved" {{ $purchaseOrder->status == 'approved' ? 'selected' : '' }}>موافقة</option>
                                    <option value="rejected" {{ $purchaseOrder->status == 'rejected' ? 'selected' : '' }}>رفض</option>
                                </select>
                            </div>

                            <div class="mb-6 col-lg-6" id="rejection-reason-container" style="display: none;">
                                <label>سبب الرفض:</label>
                                <input type="text" name="rejection_reason" class="form-control" value="{{ $purchaseOrder->rejection_reason }}" placeholder="أدخل سبب الرفض" />
                            </div>

                            <div class="mb-6 col-lg-6" id="purchase-date-container" style="display: none;">
                                <label>تاريخ الشراء:</label>
                                <input type="date" name="purchase_date" id="purchase_date" value="{{ $purchaseOrder->purchase_date }}" class="form-control" />
                            </div>
                            <div class="mb-6 col-lg-12" id="description-container" style="display: none;">
                                <label>الوصف:</label>
                                <textarea name="description" id="description" class="form-control" rows="3" placeholder="أدخل وصف أمر الشراء">{{ $purchaseOrder->description }}</textarea>
                            </div>
                            <div class="mb-6 col-lg-12" id="notes-container" style="display: none;">
                                <label>ملاحظات:</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="أدخل ملاحظات">{{ $purchaseOrder->notes }}</textarea>
                            </div>
                            <div class="mb-6 col-lg-12" id="attachments-section" style="display: none;">
                                <label>مرفقات أمر الشراء (فاتورة الشراء):</label>
                                <input type="file" name="purchase_attachments[]" multiple class="form-control" />
                                <span class="form-text text-muted">يمكن إرفاق ملفات متعددة (jpg, jpeg, png, pdf, doc, docx,
                                    xlsx)</span>
                                @if ($purchaseOrder->purchase_attachments && count(json_decode($purchaseOrder->purchase_attachments, true)) > 0)
                                    <div class="mt-2">
                                        <strong>المرفقات الحالية:</strong>
                                        @foreach (json_decode($purchaseOrder->purchase_attachments) as $file)
                                            <a href="{{ url('uploads/' . $file) }}" target="_blank" class="file-link">
                                                <i class="la la-download"></i> {{ $file }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h4><i class="la la-list"></i> الأصناف المطلوبة</h4>
                        <div id="items-container">
                            @foreach ($purchaseOrder->items as $index => $item)
                                <div class="item-row mb-3">
                                    <div class="row">
                                        <div class="mb-6 col-lg-4">
                                            <label>الصنف:</label>
                                            <select name="items[{{ $index }}][item_id]"
                                                class="form-control item-select">
                                                <option value="">اختر الصنف</option>
                                            @foreach ($items as $i)
                                                    <option value="{{ $i->id }}" data-name="{{ $i->name }}"
                                                        data-unit="{{ optional($i->unit)->unit_name ?? '' }}"
                                                        data-category="{{ optional($i->category)->category_name ?? '' }}"
                                                        {{ $item->item_id == $i->id ? 'selected' : '' }}>
                                                        {{ $i->name }}
                                                    </option>
                                                @endforeach
                                                <option value="new" {{ is_null($item->item_id) ? 'selected' : '' }}>صنف جديد
                                                </option>
                                            </select>
                                        </div>
                                        <div class="mb-6 col-lg-4">
                                            <label>اسم الصنف:</label>
                                            <input type="text" name="items[{{ $index }}][item_name]"
                                                value="{{ $item->item_name }}" class="form-control item-name"
                                                placeholder="اسم الصنف" required />
                                        </div>
                                        <div class="mb-6 col-lg-3">
                                            <label>الوحدة:</label>
                                            <select name="items[{{ $index }}][unit_id]"
                                                class="form-control unit-select">
                                                <option value="">اختر الوحدة</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}" data-name="{{ $unit->unit_name }}"
                                                        {{ $item->unit_id == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->unit_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-6 col-lg-3" style="display: none;">
                                            <label>اسم الوحدة:</label>
                                            <input type="text" name="items[{{ $index }}][unit_name]"
                                                value="{{ $item->unit_name }}" class="form-control unit-name"
                                                placeholder="اسم الوحدة" />
                                        </div>
                                        <div class="mb-6 col-lg-3">
                                            <label>الفئة:</label>
                                            <select name="items[{{ $index }}][category_id]"
                                                class="form-control category-select">
                                                <option value="">اختر الفئة</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" data-name="{{ $category->category_name }}"
                                                        {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-6 col-lg-3" style="display: none;">
                                            <label>اسم الفئة:</label>
                                            <input type="text" name="items[{{ $index }}][category_name]"
                                                value="{{ $item->category_name }}" class="form-control category-name"
                                                placeholder="اسم الفئة" />
                                        </div>
                                        <div class="mb-6 col-lg-2">
                                            <label>الكمية:</label>
                                            <input type="number" name="items[{{ $index }}][quantity]"
                                                value="{{ $item->quantity }}" class="form-control" step="0.01"
                                                min="0" required />
                                        </div>
                                        <div class="mb-6 col-lg-8">
                                            <label>ملاحظات:</label>
                                            <input type="text" name="items[{{ $index }}][notes]"
                                                value="{{ $item->notes }}" class="form-control"
                                                placeholder="ملاحظات على الصنف" />
                                        </div>
                                        <div class="mb-6 col-lg-2">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-block remove-item">حذف</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-success" id="add-item">
                            <i class="la la-plus"></i> إضافة صنف
                        </button>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary font-weight-bolder">
                        <i class="la la-save"></i> حفظ التعديلات
                    </button>
                    <a href="{{ url('/purchase-order') }}" class="btn btn-secondary font-weight-bolder">
                        <i class="la la-close"></i> إلغاء
                    </a>
                </div>
            </form>
            <!--end::Form-->
        </div>
        <!--end::Card-->
    </div>
@endsection

@section('scripts')
    <script>
        let itemIndex = {{ $purchaseOrder->items->count() }};

        // Show/hide sections based on status
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

        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newItem = container.querySelector('.item-row').cloneNode(true);

            // Update all input names and clear values
            newItem.querySelectorAll('input, select').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, '[' + itemIndex + ']'));
                }
                if (input.tagName === 'INPUT' || input.tagName === 'TEXTAREA') {
                    input.value = '';
                } else if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            });

            container.appendChild(newItem);
            itemIndex++;
        });

        document.getElementById('items-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    e.target.closest('.item-row').remove();
                } else {
                    alert('يجب أن يكون هناك صنف واحد على الأقل');
                }
            }
        });

        // Auto-fill item name when selecting from dropdown
        document.getElementById('items-container').addEventListener('change', function(e) {
            if (e.target.classList.contains('item-select')) {
                const row = e.target.closest('.item-row');
                const selectedOption = e.target.options[e.target.selectedIndex];

                if (e.target.value !== 'new' && e.target.value !== '') {
                    row.querySelector('.item-name').value = selectedOption.dataset.name || '';

                    // Set the select values for unit and category
                    const unitSelect = row.querySelector('.unit-select');
                    const categorySelect = row.querySelector('.category-select');

                    // Find and set unit
                    for (let option of unitSelect.options) {
                        if (option.dataset.name === selectedOption.dataset.unit) {
                            unitSelect.value = option.value;
                            // Trigger change to update unit name
                            unitSelect.dispatchEvent(new Event('change'));
                            break;
                        }
                    }

                    // Find and set category
                    for (let option of categorySelect.options) {
                        if (option.dataset.name === selectedOption.dataset.category) {
                            categorySelect.value = option.value;
                            // Trigger change to update category name
                            categorySelect.dispatchEvent(new Event('change'));
                            break;
                        }
                    }
                } else if (e.target.value === 'new') {
                    row.querySelector('.item-name').value = '';
                    row.querySelector('.item-name').focus();
                }
            }

            // Auto-fill unit name when selecting unit
            if (e.target.classList.contains('unit-select')) {
                const row = e.target.closest('.item-row');
                const selectedOption = e.target.options[e.target.selectedIndex];
                row.querySelector('.unit-name').value = selectedOption.dataset.name || '';
            }

            // Auto-fill category name when selecting category
            if (e.target.classList.contains('category-select')) {
                const row = e.target.closest('.item-row');
                const selectedOption = e.target.options[e.target.selectedIndex];
                row.querySelector('.category-name').value = selectedOption.dataset.name || '';
            }
        });
    </script>
@endsection