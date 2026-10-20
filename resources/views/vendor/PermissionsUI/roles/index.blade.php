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
        .card-custom {
            border: none;
            border-radius: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: #ffffff;
            border-bottom: 2px solid #f0f0f0;
            color: #2c3e50;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header .card-label {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-header p {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .btn-create {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
            padding: 25px;
        }

        .role-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.2);
            border-color: #f093fb;
        }

        .role-card:hover::before {
            transform: scaleX(1);
        }

        .role-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .role-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            margin-left: 15px;
            box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3);
        }

        .role-info {
            flex: 1;
        }

        .role-id {
            display: inline-block;
            background: #f8f9fa;
            color: #6c757d;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .role-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 17px;
        }

        .role-permissions {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #e0e0e0;
        }

        .role-permissions-label {
            font-size: 12px;
            color: #95a5a6;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .permission-badge {
            display: inline-block;
            padding: 5px 12px;
            margin: 4px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            background: #f8f9fa;
            color: #495057;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .permission-badge:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3);
            border-color: transparent;
        }

        .role-meta {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .role-date {
            font-size: 13px;
            color: #7f8c8d;
        }

        .role-date i {
            color: #f093fb;
            margin-left: 5px;
        }

        .role-actions {
            display: flex;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: 2px solid #e0e0e0;
            background: white;
            margin-left: 8px;
            color: #6c757d;
        }

        .action-btn:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-color: #f093fb;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3);
        }

        .action-btn.delete:hover {
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
            border-color: #f5576c;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 70px;
            color: #e0e0e0;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #2c3e50;
            font-weight: 600;
            font-size: 18px;
        }

        .empty-state p {
            color: #7f8c8d;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .roles-grid {
                grid-template-columns: 1fr;
            }
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
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!--begin::Page Scripts(used by this page)-->
    <script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
    <!--end::Page Scripts-->
@endsection
