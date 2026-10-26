@extends('layout.master')

@section('title')
    {{ __('عرض المسؤوليات') }}
@endsection

@section('page_title')
    {{ __('لوحة التحكم') }}
@endsection
@section('sub_main')
    {{ __('الرئيسية') }}
@endsection
@section('sub_title')
    {{ __('عرض المسؤوليات') }}
@endsection

@section('css')
    <style>
        /* ====== Card Custom ====== */
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

        .card-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            margin-top: 8px;
        }

        .card-header i {
            color: rgba(255, 255, 255, 0.8);
            margin-right: 10px;
        }

        /* ====== Create Button ====== */
        .btn-create {
            background: white;
            color: #3699ff;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        /* ====== Permissions Grid ====== */
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            padding: 35px;
        }

        .permission-card {
            background: white;
            border: none;
            border-radius: 16px;
            padding: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e4e6ef;
        }

        .permission-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3699ff, #bc82f6);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .permission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(54, 153, 255, 0.15);
            border-color: #3699ff;
        }

        .permission-card:hover::before {
            transform: scaleX(1);
        }

        .permission-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .permission-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            margin-left: 15px;
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.3);
        }

        .permission-info {
            flex: 1;
        }

        .permission-id {
            display: inline-block;
            background: rgba(54, 153, 255, 0.1);
            color: #3699ff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .permission-name {
            font-weight: 700;
            color: #3f4254;
            font-size: 16px;
        }

        .permission-meta {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e4e6ef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .permission-date {
            font-size: 13px;
            color: #a1a5b7;
        }

        .permission-date i {
            color: #3699ff;
            margin-left: 8px;
        }

        .permission-actions {
            display: flex;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: 2px solid #e4e6ef;
            background: white;
            margin-left: 10px;
            color: #6c757d;
        }

        .action-btn:hover {
            background: #3699ff;
            color: white;
            border-color: #3699ff;
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.3);
        }

        .action-btn.delete:hover {
            background: #f64e60;
            border-color: #f64e60;
            box-shadow: 0 5px 15px rgba(246, 78, 96, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 60px;
            color: #e4e6ef;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #3f4254;
            font-weight: 600;
        }

        .empty-state p {
            color: #a1a5b7;
            margin-top: 10px;
        }

        @media (max-width: 1200px) {
            .permissions-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .permissions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .permissions-grid {
                grid-template-columns: 1fr;
            }
        }

        .pagination-wrapper {
            padding: 25px 35px;
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
                        {{ __('عرض المسؤوليات') }}
                    </h3>
                    <p class="mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                        إدارة المسؤوليات والصلاحيات في النظام
                    </p>
                </div>
                <div class="card-toolbar">
                    <a class="btn btn-create font-weight-bolder"
                        href="{{ route(config('permission_ui.route_name_prefix') . 'permissions.create') }}">
                        <i class="la la-plus"></i> {{ __('انشاء جديد') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="permissions-grid">
                    @forelse($permissions as $permission)
                        <div class="permission-card">
                            <div class="permission-card-header">
                                <div class="permission-icon">
                                    <i class="la la-lock"></i>
                                </div>
                                <div class="permission-info">
                                    <span class="permission-id">#{{ $permission->id }}</span>
                                    <div class="permission-name">{{ $permission->name }}</div>
                                </div>
                            </div>
                            <div class="permission-meta">
                                <span class="permission-date">
                                    <i class="la la-calendar"></i> {{ $permission->created_at ? $permission->created_at->format('Y/m/d') : '-' }}
                                </span>
                                <div class="permission-actions">
                                    <a href="{{ route(config('permission_ui.route_name_prefix') . 'permissions.edit', $permission) }}"
                                        class="action-btn" title="{{ __('تعديل') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <form
                                        action="{{ route(config('permission_ui.route_name_prefix') . 'permissions.destroy', $permission) }}"
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
                            <i class="la la-key"></i>
                            <h4>{{ __('no_records') }}</h4>
                            <p>لا يوجد مسؤوليات حالياً</p>
                        </div>
                    @endforelse
                </div>
                @if ($permissions->links())
                    <div class="pagination-wrapper mt-4 px-5 pb-4">
                        {!! $permissions->appends(['search' => request('search')])->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
@endsection