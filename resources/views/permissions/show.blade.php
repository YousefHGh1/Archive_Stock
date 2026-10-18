@extends('layout.master')

@section('title')
    عرض تفاصيل المسؤولية
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    إدارة النظام
@endsection
@section('sub_title')
    عرض تفاصيل المسؤولية
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

        .detail-item {
            padding: 20px;
            border-bottom: 1px solid #f1f1f5;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #a1a5b7;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .detail-value {
            font-weight: 600;
            color: #3f4254;
            font-size: 16px;
        }

        .role-badge {
            display: inline-block;
            padding: 5px 12px;
            margin: 3px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            background: rgba(54, 153, 255, 0.1);
            color: #3699ff;
            border: 1px solid transparent;
        }

        .btn-secondary {
            background: #e4e6ef;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            color: #3f4254;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header border-0 py-5">
                <div class="card-title">
                    <h3 class="card-label">
                        <i class="la la-eye mr-2"></i>
                        عرض تفاصيل المسؤولية
                    </h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('permissions-manage.index') }}" class="btn btn-secondary">
                        <i class="la la-arrow-right"></i> العودة
                    </a>
                </div>
            </div>
            <div class="card-body py-5">
                <div class="detail-item">
                    <div class="detail-label">المعرف</div>
                    <div class="detail-value">{{ $permission->id }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">اسم الصلاحية</div>
                    <div class="detail-value">{{ $permission->name }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">الحارس</div>
                    <div class="detail-value">{{ $permission->guard_name }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">تاريخ الإنشاء</div>
                    <div class="detail-value">{{ $permission->created_at ? $permission->created_at->format('Y/m/d H:i:s') : '-' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">آخر تحديث</div>
                    <div class="detail-value">{{ $permission->updated_at ? $permission->updated_at->format('Y/m/d H:i:s') : '-' }}</div>
                </div>

                @if($permission->roles && $permission->roles->count() > 0)
                <div class="detail-item">
                    <div class="detail-label">الأدوار المرتبطة</div>
                    <div class="detail-value">
                        @foreach($permission->roles as $role)
                            <span class="role-badge">{{ $role->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-end mt-4">
                    @can('permissions-update')
                    <a href="{{ route('permissions-manage.edit', $permission->id) }}" class="btn btn-primary mr-2">
                        <i class="la la-edit"></i> تعديل
                    </a>
                    @endcan
                    <a href="{{ route('permissions-manage.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </div>
        </div>
    </div>
@endsection
