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
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            padding: 25px;
        }

        .permission-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .permission-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .permission-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(79, 172, 254, 0.2);
            border-color: #4facfe;
        }

        .permission-card:hover::before {
            transform: scaleX(1);
        }

        .permission-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .permission-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            margin-left: 12px;
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
        }

        .permission-info {
            flex: 1;
        }

        .permission-id {
            display: inline-block;
            background: #f8f9fa;
            color: #6c757d;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .permission-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 15px;
        }

        .permission-meta {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .permission-date {
            font-size: 13px;
            color: #7f8c8d;
        }

        .permission-date i {
            color: #4facfe;
            margin-left: 5px;
        }

        .permission-actions {
            display: flex;
        }

        .action-btn {
            width: 36px;
            height: 36px;
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
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border-color: #4facfe;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);
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
            .permissions-grid {
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
                        <i class="la la-key mr-2"></i>
                        {{ __('عرض المسؤوليات') }}
                    </h3>
                    <p class="mt-2 mb-0">
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
                    <div class="d-flex justify-content-center mt-4 px-5 pb-4">
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