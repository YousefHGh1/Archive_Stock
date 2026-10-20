@extends('layout.master')

@section('title')
    {{ __('عرض المستخدمين') }}
@endsection

@section('page_title')
    {{ __('لوحة التحكم') }}
@endsection
@section('sub_main')
    {{ __('الرئيسية') }}
@endsection
@section('sub_title')
    {{ __('عرض المستخدمين') }}
@endsection

@section('css')
    <style>
        body {
            background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 35px;
            position: relative;
            overflow: hidden;
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

        .users-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            padding: 35px;
        }

        .user-card {
            background: white;
            border: none;
            border-radius: 20px;
            padding: 25px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .user-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .user-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }

        .user-card:hover::before {
            transform: scaleX(1);
        }

        .user-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .user-avatar {
            width: 65px;
            height: 65px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin-left: 18px;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            position: relative;
            z-index: 1;
        }

        .user-avatar::after {
            content: '';
            position: absolute;
            top: -5px;
            right: -5px;
            width: 15px;
            height: 15px;
            background: #48bb78;
            border: 3px solid white;
            border-radius: 50%;
        }

        .user-info {
            flex: 1;
        }

        .user-id {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        .user-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .user-email {
            font-size: 14px;
            color: #7f8c8d;
            display: flex;
            align-items: center;
        }

        .user-email i {
            color: #667eea;
            margin-left: 8px;
            font-size: 14px;
        }

        .user-roles {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #e8e8e8;
            max-height: 120px;
            overflow-y: auto;
            position: relative;
            padding-bottom: 20px;
        }

        .user-roles::-webkit-scrollbar {
            width: 6px;
        }

        .user-roles::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .user-roles::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        .user-roles.scrollable::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(to bottom, transparent, white);
            pointer-events: none;
        }

        .user-roles-label {
            font-size: 12px;
            color: #95a5a6;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .role-badge {
            display: inline-block;
            padding: 6px 14px;
            margin: 4px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #495057;
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
            position: relative;
            overflow: hidden;
        }

        .role-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.2), transparent);
            transition: left 0.3s ease;
        }

        .role-badge:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            border-color: transparent;
        }

        .role-badge:hover::before {
            left: 100%;
        }

        .user-actions {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .action-btn {
            width: 45px;
            height: 45px;
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
            background: rgba(102, 126, 234, 0.2);
            transform: translate(-50%, -50%);
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            transform: translateY(-5px) rotate(10deg);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .action-btn:hover::before {
            width: 100%;
            height: 100%;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            .users-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .users-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .users-grid {
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 0;
        }

        .pagination .page-link span {
            position: relative;
            z-index: 1;
        }

        .pagination .page-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .pagination .page-link:hover::before {
            opacity: 1;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
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
                        <i class="la la-users mr-2"></i>
                        {{ __('عرض المستخدمين') }}
                    </h3>
                    <p class="mt-2 mb-0">
                        إدارة المستخدمين والأدوار المرتبطة بهم
                    </p>
                </div>
            </div>
            <div class="card-body">
                <div class="users-grid">
                    @forelse($users as $user)
                        <div class="user-card">
                            <div class="user-card-header">
                                <div class="user-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
                                <div class="user-info">
                                    <span class="user-id">#{{ $user->id }}</span>
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">
                                        <i class="la la-envelope"></i> {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                            <div class="user-roles {{ $user->roles->count() > 4 ? 'scrollable' : '' }}">
                                <div class="user-roles-label">الأدوار ({{ $user->roles->count() }})</div>
                                @foreach ($user->roles as $role)
                                    <span class="role-badge">{{ $role->name }}</span>
                                @endforeach
                            </div>
                            <div class="user-actions">
                                <a href="{{ route(config('permission_ui.route_name_prefix') . 'users.edit', $user) }}"
                                    class="action-btn" title="{{ __('تعديل') }}">
                                    <i class="la la-edit"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="la la-users"></i>
                            <h4>{{ __('no_records') }}</h4>
                            <p>لا يوجد مستخدمين حالياً</p>
                        </div>
                    @endforelse
                </div>
                @if ($users->links())
                    <div class="pagination-wrapper mt-4 px-5 pb-4">
                        {!! $users->appends(['search' => request('search')])->withQueryString()->links('pagination::bootstrap-5') !!}
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
