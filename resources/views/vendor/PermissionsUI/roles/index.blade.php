@extends('layout.master')

@section('title')
    {{ __('عرض الأدوار') }}
@endsection

@section('page_title')
    {{ __('لوحة التحكم') }}
@endsection
@section('sub_main')
    {{ __('الرئيسية') }}
@endsection
@section('sub_title')
    {{ __('عرض الأدوار') }}
@endsection

@section('css')
    <style>
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            min-height: 100vh;
        }

        .card-custom {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: white;
            margin: 30px 0;
        }

        .card-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px 35px;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 5%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .card-header .card-label {
            color: white;
            font-weight: 700;
            font-size: 1.3rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }

        .card-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            margin-top: 8px;
            position: relative;
            z-index: 1;
        }

        .card-header i {
            color: rgba(255, 255, 255, 0.8);
            margin-right: 10px;
        }

        .btn-create {
            background: white;
            color: #f5576c;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 1;
        }

        .btn-create:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            padding: 35px;
        }

        .role-card {
            background: white;
            border: none;
            border-radius: 20px;
            padding: 30px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #f093fb 0%, #f5576c 50%, #f5576c 100%);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .role-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(240, 147, 251, 0.25);
        }

        .role-card:hover::before {
            transform: scaleX(1);
        }

        .role-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .role-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            margin-left: 18px;
            box-shadow: 0 8px 20px rgba(240, 147, 251, 0.4);
            position: relative;
            z-index: 1;
        }

        .role-icon::after {
            content: '';
            position: absolute;
            top: -5px;
            right: -5px;
            width: 15px;
            height: 15px;
            background: #f5576c;
            border: 3px solid white;
            border-radius: 50%;
        }

        .role-info {
            flex: 1;
        }

        .role-id {
            display: inline-block;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
            box-shadow: 0 4px 10px rgba(240, 147, 251, 0.3);
        }

        .role-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 19px;
        }

        .role-permissions {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px dashed #e8e8e8;
        }

        .role-permissions-label {
            font-size: 12px;
            color: #95a5a6;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .permission-badge {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            background: linear-gradient(135deg, #fff5f5 0%, #ffcdd2 100%);
            color: #495057;
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
            position: relative;
            overflow: hidden;
        }

        .permission-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(240, 147, 251, 0.2), transparent);
            transition: left 0.3s ease;
        }

        .permission-badge:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 20px rgba(240, 147, 251, 0.4);
            border-color: transparent;
        }

        .permission-badge:hover::before {
            left: 100%;
        }

        .role-meta {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px dashed #e8e8e8;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .role-date {
            font-size: 14px;
            color: #7f8c8d;
        }

        .role-date i {
            color: #f093fb;
            margin-left: 8px;
        }

        .role-actions {
            display: flex;
        }

        .action-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: 2px solid #e0e0e0;
            background: white;
            margin-left: 10px;
            color: #6c757d;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(240, 147, 251, 0.2);
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-color: #f093fb;
            transform: translateY(-5px) rotate(10deg);
            box-shadow: 0 8px 20px rgba(240, 147, 251, 0.4);
        }

        .action-btn:hover::before {
            width: 100%;
            height: 100%;
        }

        .action-btn.delete:hover {
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
            border-color: #f5576c;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 80px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 25px;
        }

        .empty-state h4 {
            color: #2c3e50;
            font-weight: 700;
            font-size: 20px;
        }

        .empty-state p {
            color: #7f8c8d;
            margin-top: 15px;
            font-size: 15px;
        }

        @media (max-width: 1200px) {
            .roles-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .roles-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .roles-grid {
                grid-template-columns: 1fr;
            }
        }

        .pagination-wrapper {
            padding: 25px 35px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin: 0;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-link {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            color: #2c3e50;
            font-weight: 700;
            padding: 12px 18px;
            transition: all 0.3s ease;
            background: white;
            min-width: 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .pagination .page-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .pagination .page-link span {
            position: relative;
            z-index: 1;
        }

        .pagination .page-link:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-color: #f093fb;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(240, 147, 251, 0.4);
        }

        .pagination .page-link:hover::before {
            opacity: 1;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-color: #f093fb;
            box-shadow: 0 8px 20px rgba(240, 147, 251, 0.4);
        }

        .pagination .page-item.disabled .page-link {
            color: #bdc3c7;
            border-color: #e0e0e0;
            background: #f8f9fa;
            cursor: not-allowed;
        }

        .pagination .page-item.disabled .page-link:hover {
            background: #f8f9fa;
            color: #bdc3c7;
            border-color: #e0e0e0;
            transform: none;
            box-shadow: none;
        }

        .pagination .page-item.disabled .page-link::before {
            opacity: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">
                        <i class="la la-shield mr-2"></i>
                        {{ __('عرض الأدوار') }}
                    </h3>
                    <p class="mt-2 mb-0">
                        إدارة الأدوار والصلاحيات في النظام
                    </p>
                </div>
                <div class="card-toolbar">
                    <a class="btn btn-create font-weight-bolder"
                        href="{{ route(config('permission_ui.route_name_prefix') . 'roles.create') }}">
                        <i class="la la-plus"></i> {{ __('انشاء جديد') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="roles-grid">
                    @forelse($roles as $role)
                        <div class="role-card">
                            <div class="role-card-header">
                                <div class="role-icon">
                                    <i class="la la-shield"></i>
                                </div>
                                <div class="role-info">
                                    <span class="role-id">#{{ $role->id }}</span>
                                    <div class="role-name">{{ $role->name }}</div>
                                </div>
                            </div>
                            <div class="role-permissions">
                                <div class="role-permissions-label">الصلاحيات ({{ $role->permissions->count() }})</div>
                                @foreach ($role->permissions->take(5) as $permission)
                                    <span class="permission-badge">{{ $permission->name }}</span>
                                @endforeach
                                @if ($role->permissions->count() > 5)
                                    <span class="permission-badge">+{{ $role->permissions->count() - 5 }} آخر</span>
                                @endif
                            </div>
                            <div class="role-meta">
                                <span class="role-date">
                                    <i class="la la-calendar"></i> {{ $role->created_at ? $role->created_at->format('Y/m/d') : '-' }}
                                </span>
                                <div class="role-actions">
                                    <a href="{{ route(config('permission_ui.route_name_prefix') . 'roles.edit', $role) }}"
                                        class="action-btn" title="{{ __('تعديل') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <form
                                        action="{{ route(config('permission_ui.route_name_prefix') . 'roles.destroy', $role) }}"
                                        method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-btn delete" type="submit"
                                            onclick="return confirm('{{ __('PermissionsUI::permissions.global.confirm_action') }}')"
                                            title="{{ __('حذف') }}">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="la la-shield"></i>
                            <h4>{{ __('no_records') }}</h4>
                            <p>لا يوجد أدوار حالياً</p>
                        </div>
                    @endforelse
                </div>
                @if ($roles->links())
                    <div class="pagination-wrapper mt-4 px-5 pb-4">
                        {!! $roles->appends(['search' => request('search')])->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!--begin::Page Scripts(used by this page)-->
    <script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
    <!--end::Page Scripts-->
@endsection
