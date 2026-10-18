@extends('layout.master')

@section('title')
    عرض المسؤوليات
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    إدارة النظام
@endsection
@section('sub_title')
    عرض المسؤوليات
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

        .btn-create {
            background: white;
            color: #3699ff;
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            color: #3699ff;
        }

        .table thead th {
            background: #f8f9fa;
            color: #3f4254;
            font-weight: 700;
            font-size: 13px;
            border-bottom: 2px solid #e4e6ef;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f1f5;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: rgba(54, 153, 255, 0.03);
        }

        .permission-id {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            background: rgba(54, 153, 255, 0.1);
            color: #3699ff;
            font-weight: 700;
            font-size: 13px;
        }

        .permission-name {
            font-weight: 600;
            color: #3f4254;
            font-size: 14px;
        }

        .permission-name i {
            color: #3699ff;
            margin-left: 8px;
        }

        .permission-date {
            font-size: 13px;
            color: #a1a5b7;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border: 1px solid #e4e6ef;
            background: white;
            margin: 0 3px;
        }

        .action-btn:hover {
            transform: scale(1.1);
            background: #3699ff;
            color: white;
            border-color: #3699ff;
        }

        .action-btn.delete:hover {
            background: #f64e60;
            border-color: #f64e60;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i {
            font-size: 50px;
            color: #e4e6ef;
            margin-bottom: 15px;
        }

        .empty-state h4 {
            color: #3f4254;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header border-0 py-5">
                <div class="card-title">
                    <h3 class="card-label">
                        <i class="la la-key mr-2"></i>
                        عرض المسؤوليات
                    </h3>
                    <p class="mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                        إدارة المسؤوليات والصلاحيات في النظام
                    </p>
                </div>
                <div class="card-toolbar">
                    @can('permissions-create')
                    <a class="btn btn-create font-weight-bolder" href="{{ route('permissions-manage.create') }}">
                        <i class="la la-plus"></i> إنشاء جديد
                    </a>
                    @endcan
                </div>
            </div>
            <div class="card-body py-5">
                <div class="table-responsive">
                    <table class="table table-borderless table-vertical-center">
                        <thead>
                            <tr>
                                <th style="min-width: 80px">المعرف</th>
                                <th style="min-width: 250px">الاسم</th>
                                <th style="min-width: 150px">تاريخ الإنشاء</th>
                                <th style="min-width: 120px">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permissions as $permission)
                                <tr>
                                    <td>
                                        <span class="permission-id">{{ $permission->id }}</span>
                                    </td>
                                    <td>
                                        <span class="permission-name">
                                            <i class="la la-lock"></i> {{ $permission->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="permission-date">
                                            <i class="la la-calendar"></i> {{ $permission->created_at ? $permission->created_at->format('Y/m/d') : '-' }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        @can('permissions-update')
                                        <a href="{{ route('permissions-manage.edit', $permission->id) }}" class="action-btn" title="تعديل">
                                            <i class="la la-edit"></i>
                                        </a>
                                        @endcan
                                        @can('permissions-delete')
                                        <form action="{{ route('permissions-manage.destroy', $permission->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-btn delete" type="submit" onclick="return confirm('هل أنت متأكد من حذف هذه الصلاحية؟')" title="حذف">
                                                <i class="la la-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="la la-key"></i>
                                            <h4>لا توجد مسؤوليات مضافة</h4>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    @if ($permissions->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {!! $permissions->appends(['search' => request('search')])->withQueryString()->links('pagination::bootstrap-5') !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
