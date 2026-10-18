@extends('layout.master')

@section('title')
    إدارة الأدوار والصلاحيات
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    إدارة النظام
@endsection
@section('sub_title')
    الأدوار والصلاحيات
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

        /* ====== Search Box ====== */
        .search-box {
            position: relative;
        }

        .search-box input {
            padding-left: 40px;
            border-radius: 25px;
            border: 2px solid #e4e6ef;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            border-color: #3699ff;
            box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.1);
            background: white;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a1a5b7;
        }

        /* ====== Role Cards ====== */
        .role-card {
            margin-bottom: 20px;
            border: 1px solid #e4e6ef;
            border-radius: 16px;
            padding: 25px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3699ff, #bc82f6);
        }

        .role-card:hover {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            transform: translateY(-5px);
            border-color: #3699ff;
        }

        .role-card.admin::before {
            background: linear-gradient(90deg, #f64e60, #ffa800);
        }

        .role-card.super-user::before {
            background: linear-gradient(90deg, #bc82f6, #3699ff);
        }

        .role-card.manager::before {
            background: linear-gradient(90deg, #1bc5bd, #3699ff);
        }

        /* ====== Role Icon ====== */
        .role-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.3);
        }

        .role-icon.admin {
            background: linear-gradient(135deg, #f64e60 0%, #ffa800 100%);
            box-shadow: 0 5px 15px rgba(246, 78, 96, 0.3);
        }

        .role-icon.super-user {
            background: linear-gradient(135deg, #bc82f6 0%, #3699ff 100%);
            box-shadow: 0 5px 15px rgba(188, 130, 246, 0.3);
        }

        .role-icon.manager {
            background: linear-gradient(135deg, #1bc5bd 0%, #3699ff 100%);
            box-shadow: 0 5px 15px rgba(27, 197, 189, 0.3);
        }

        /* ====== Stats Badge ====== */
        .stats-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: rgba(54, 153, 255, 0.1);
            color: #3699ff;
        }

        .stats-badge i {
            margin-left: 6px;
        }

        .stats-badge.users {
            background: rgba(27, 197, 189, 0.1);
            color: #1bc5bd;
        }

        /* ====== Permission Badges ====== */
        .permission-badge {
            display: inline-block;
            padding: 5px 12px;
            margin: 3px;
            border-radius: 20px;
            font-size: 11px;
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

        .permission-badge.more {
            background: rgba(54, 153, 255, 0.1);
            color: #3699ff;
            font-weight: 600;
        }

        /* ====== Action Buttons ====== */
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
        }

        .action-btn:hover {
            transform: scale(1.1);
            background: #3699ff;
            color: white;
            border-color: #3699ff;
        }

        .dropdown-menu {
            border-radius: 12px;
            border: 1px solid #e4e6ef;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 8px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(246, 78, 96, 0.1);
        }

        /* ====== View Details Button ====== */
        .btn-view-details {
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
            border: 2px solid #3699ff;
            color: #3699ff;
            background: transparent;
        }

        .btn-view-details:hover {
            background: #3699ff;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.3);
        }

        /* ====== Create Button ====== */
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

        /* ====== Empty State ====== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
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

        /* ====== Animation ====== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .role-item {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header border-0 py-5">
                <div class="card-title">
                    <h3 class="card-label">
                        <i class="la la-shield mr-2"></i>
                        إدارة الأدوار والصلاحيات
                    </h3>
                    <p class="mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                        قم بإدارة الأدوار والصلاحيات للتحكم في وصول المستخدمين
                    </p>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex align-items-center">
                        <div class="search-box mr-4">
                            <input type="text" class="form-control" placeholder="بحث عن دور..." id="roleSearch">
                            <i class="la la-search"></i>
                        </div>
                        @can('roles-create')
                        <a href="{{ route('roles.create') }}" class="btn btn-create font-weight-bolder">
                            <i class="la la-plus"></i> إنشاء دور جديد
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body py-5">
                <div class="row" id="rolesContainer">
                    @forelse($roles as $role)
                    @php
                        $cardClass = '';
                        $iconClass = '';
                        if ($role->name === 'admin') {
                            $cardClass = 'admin';
                            $iconClass = 'admin';
                        } elseif ($role->name === 'Super-User') {
                            $cardClass = 'super-user';
                            $iconClass = 'super-user';
                        } elseif (str_contains($role->name, 'manager')) {
                            $cardClass = 'manager';
                            $iconClass = 'manager';
                        }
                    @endphp
                    <div class="col-md-6 col-lg-4 role-item" data-role-name="{{ $role->name }}" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        <div class="role-card {{ $cardClass }}">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <div class="role-icon {{ $iconClass }}">
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
                                    <h4 class="mb-2 font-weight-bold text-dark">{{ $role->name }}</h4>
                                    <div class="d-flex flex-wrap">
                                        <span class="stats-badge mr-2 mb-1">
                                            <i class="la la-key"></i>
                                            {{ $role->permissions->count() }} صلاحية
                                        </span>
                                        @if($role->users && $role->users->count() > 0)
                                        <span class="stats-badge users mb-1">
                                            <i class="la la-user"></i>
                                            {{ $role->users->count() }} مستخدم
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle action-btn" type="button" data-toggle="dropdown">
                                        <i class="la la-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        @can('roles-update')
                                        <a href="{{ route('roles.edit', $role->id) }}" class="dropdown-item">
                                            <i class="la la-edit text-primary"></i> تعديل
                                        </a>
                                        @endcan
                                        @can('roles-delete')
                                        @if(!in_array($role->name, ['admin', 'Super-User']))
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                                <i class="la la-trash"></i> حذف
                                            </button>
                                        </form>
                                        @endif
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 flex-grow-1">
                                <small class="text-muted font-weight-bold d-block mb-2">
                                    <i class="la la-lock mr-1"></i> الصلاحيات الرئيسية:
                                </small>
                                <div>
                                    @if($role->permissions->count() > 0)
                                        @foreach($role->permissions->take(8) as $permission)
                                            <span class="permission-badge">{{ $permission->name }}</span>
                                        @endforeach
                                        @if($role->permissions->count() > 8)
                                            <span class="permission-badge more">+{{ $role->permissions->count() - 8 }} أخرى</span>
                                        @endif
                                    @else
                                        <span class="text-muted">
                                            <i class="la la-info-circle"></i> لا توجد صلاحيات
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <a href="{{ route('roles.show', $role->id) }}" class="btn btn-view-details">
                                    <i class="la la-eye"></i> عرض التفاصيل
                                </a>
                                <span class="text-muted small">
                                    <i class="la la-calendar"></i> {{ $role->created_at ? $role->created_at->format('Y/m/d') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="la la-shield"></i>
                            <h4>لا توجد أدوار مضافة</h4>
                            <p class="text-muted">قم بإنشاء أول دور للبدء في إدارة الصلاحيات</p>
                            @can('roles-create')
                            <a href="{{ route('roles.create') }}" class="btn btn-primary font-weight-bolder mt-3">
                                <i class="la la-plus"></i> إنشاء دور جديد
                            </a>
                            @endcan
                        </div>
                    </div>
                    @endforelse
                </div>
                
                @if ($roles->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {!! $roles->appends(['search' => request('search')])->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Live search functionality
        document.getElementById('roleSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const roleCards = document.querySelectorAll('.role-item');

            roleCards.forEach(card => {
                const roleName = card.dataset.roleName.toLowerCase();
                if (roleName.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Add animation to cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.role-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
@endsection
