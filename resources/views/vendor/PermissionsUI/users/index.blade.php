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

        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            padding: 25px;
        }

        .user-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .user-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
            border-color: #667eea;
        }

        .user-card:hover::before {
            transform: scaleX(1);
        }

        .user-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .user-avatar {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin-left: 15px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .user-info {
            flex: 1;
        }

        .user-id {
            display: inline-block;
            background: #f8f9fa;
            color: #6c757d;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .user-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 3px;
        }

        .user-email {
            font-size: 13px;
            color: #7f8c8d;
            display: flex;
            align-items: center;
        }

        .user-email i {
            color: #667eea;
            margin-left: 5px;
            font-size: 12px;
        }

        .user-roles {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #e0e0e0;
        }

        .user-roles-label {
            font-size: 12px;
            color: #95a5a6;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .role-badge {
            display: inline-block;
            padding: 6px 14px;
            margin: 4px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #2c3e50;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .role-badge:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            border-color: transparent;
        }

        .user-actions {
            margin-top: 15px;
            display: flex;
            justify-content: flex-end;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
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
            .users-grid {
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
                            <div class="user-roles">
                                <div class="user-roles-label">الأدوار</div>
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
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!--begin::Page Scripts(used by this page)-->
    <script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
    <!--end::Page Scripts-->
@endsection
