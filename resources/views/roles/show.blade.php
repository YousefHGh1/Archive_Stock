@extends('layout.master')

@section('title')
    تفاصيل الدور
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    إدارة النظام
@endsection
@section('sub_title')
    تفاصيل الدور: {{ $role->name }}
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

        /* ====== Role Hero Card ====== */
        .role-hero {
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            border-radius: 16px;
            padding: 30px;
            color: white;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .role-hero::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .role-hero::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -30px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .role-hero-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .role-hero-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }

        .role-hero-meta {
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        /* ====== Info Cards ====== */
        .info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e4e6ef;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3699ff, #bc82f6);
        }

        .info-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-3px);
        }

        .info-card .info-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 10px;
            background: rgba(54, 153, 255, 0.1);
            color: #3699ff;
        }

        .info-card .info-icon.purple {
            background: rgba(188, 130, 246, 0.1);
            color: #bc82f6;
        }

        .info-card .info-icon.green {
            background: rgba(27, 197, 189, 0.1);
            color: #1bc5bd;
        }

        .info-card .info-icon.orange {
            background: rgba(255, 168, 0, 0.1);
            color: #ffa800;
        }

        .info-card .info-label {
            font-size: 13px;
            color: #a1a5b7;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .info-card .info-value {
            font-size: 18px;
            font-weight: 700;
            color: #3f4254;
        }

        /* ====== Permission Badges ====== */
        .permission-badge {
            display: inline-block;
            padding: 6px 14px;
            margin: 4px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            background: linear-gradient(135deg, #e4e6ef 0%, #dfe3e8 100%);
            color: #3f4254;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .permission-badge:hover {
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            color: white;
            transform: scale(1.05);
            border-color: #3699ff;
        }

        .permission-group {
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 14px;
            border: 1px solid #e4e6ef;
            transition: all 0.3s ease;
        }

        .permission-group:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border-color: #3699ff;
        }

        .permission-group-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e4e6ef;
        }

        .permission-group h5 {
            margin-bottom: 0;
            color: #3f4254;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .permission-group h5 i {
            margin-left: 10px;
            color: #3699ff;
            font-size: 20px;
        }

        .group-count-badge {
            background: rgba(54, 153, 255, 0.1);
            color: #3699ff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ====== Users List ====== */
        .users-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .users-list li {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 8px;
            background: #f8f9fa;
            border: 1px solid #e4e6ef;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .users-list li:hover {
            background: white;
            border-color: #3699ff;
            box-shadow: 0 3px 10px rgba(54, 153, 255, 0.1);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            margin-left: 12px;
            flex-shrink: 0;
        }

        .user-name {
            font-weight: 600;
            color: #3f4254;
            font-size: 14px;
        }

        .user-email {
            font-size: 12px;
            color: #a1a5b7;
        }

        /* ====== Buttons ====== */
        .btn-primary {
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(54, 153, 255, 0.4);
        }

        .btn-secondary {
            border-radius: 10px;
            border: 2px solid #e4e6ef;
            background: white;
            color: #3f4254;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: #a1a5b7;
            background: #f8f9fa;
        }

        /* ====== Section Title ====== */
        .section-title {
            font-weight: 700;
            color: #3f4254;
            font-size: 16px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            color: #3699ff;
            margin-left: 8px;
            font-size: 20px;
        }

        /* ====== Empty State ====== */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: #f8f9fa;
            border-radius: 14px;
            border: 2px dashed #e4e6ef;
        }

        .empty-state i {
            font-size: 40px;
            color: #a1a5b7;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #a1a5b7;
            margin-bottom: 0;
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
                        تفاصيل الدور
                    </h3>
                    <p class="mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                        عرض تفاصيل الدور والصلاحيات والمستخدمين المرتبطين به
                    </p>
                </div>
                <div class="card-toolbar">
                    @can('roles-update')
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-light font-weight-bolder mr-2">
                        <i class="la la-edit"></i> تعديل
                    </a>
                    @endcan
                    <a href="{{ route('roles.index') }}" class="btn btn-light font-weight-bolder">
                        <i class="la la-arrow-right"></i> عودة
                    </a>
                </div>
            </div>
            <div class="card-body py-5">
                <!-- Role Hero -->
                <div class="role-hero">
                    <div class="role-hero-icon">
                        @if($role->name === 'admin')
                            <i class="la la-shield"></i>
                        @elseif($role->name === 'Super-User')
                            <i class="la la-star"></i>
                        @elseif(str_contains($role->name, 'manager'))
                            <i class="la la-user-tie"></i>
                        @else
                            <i class="la la-users"></i>
                        @endif
                    </div>
                    <div class="role-hero-name">{{ $role->name }}</div>
                    <div class="role-hero-meta">
                        <i class="la la-calendar"></i> تم الإنشاء: {{ $role->created_at ? $role->created_at->format('Y/m/d') : '-' }}
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="info-card text-center">
                            <div class="info-icon">
                                <i class="la la-shield"></i>
                            </div>
                            <div class="info-label">اسم الدور</div>
                            <div class="info-value">{{ $role->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card text-center">
                            <div class="info-icon purple">
                                <i class="la la-lock"></i>
                            </div>
                            <div class="info-label">Guard</div>
                            <div class="info-value">{{ $role->guard_name }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card text-center">
                            <div class="info-icon green">
                                <i class="la la-key"></i>
                            </div>
                            <div class="info-label">عدد الصلاحيات</div>
                            <div class="info-value">{{ $role->permissions->count() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="mb-4">
                    <h4 class="section-title">
                        <i class="la la-lock"></i> الصلاحيات الممنوحة
                    </h4>
                    @if($role->permissions->count() > 0)
                        <?php
                        $groupedPermissions = $role->permissions->groupBy(function ($permission) {
                            if (str_contains($permission->name, '-')) {
                                return explode('-', $permission->name)[0];
                            }
                            return $permission->name;
                        });
                        ?>

                        @foreach($groupedPermissions as $group => $permissions)
                        <div class="permission-group">
                            <div class="permission-group-header">
                                <h5 class="mb-0">
                                    <i class="la la-folder-open"></i> {{ $group }}
                                </h5>
                                <span class="group-count-badge">{{ $permissions->count() }} صلاحية</span>
                            </div>
                            <div>
                                @foreach($permissions as $permission)
                                <span class="permission-badge">{{ $permission->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="la la-lock"></i>
                            <p>لا توجد صلاحيات ممنوحة لهذا الدور</p>
                        </div>
                    @endif
                </div>

                <!-- Users -->
                <div>
                    <h4 class="section-title">
                        <i class="la la-users"></i> المستخدمون الذين لديهم هذا الدور
                    </h4>
                    @if($role->users && $role->users->count() > 0)
                        <ul class="users-list">
                            @foreach($role->users as $user)
                            <li>
                                <div class="user-avatar">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">
                                        <i class="la la-envelope"></i> {{ $user->email }}
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-state">
                            <i class="la la-user"></i>
                            <p>لا يوجد مستخدمون لديهم هذا الدور</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Add any additional JavaScript if needed
    </script>
@endsection
