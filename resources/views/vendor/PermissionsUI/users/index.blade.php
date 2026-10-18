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

        .user-id {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            background: rgba(27, 197, 189, 0.1);
            color: #1bc5bd;
            font-weight: 700;
            font-size: 13px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            margin-left: 10px;
        }

        .user-name {
            font-weight: 600;
            color: #3f4254;
            font-size: 14px;
        }

        .user-email {
            font-size: 13px;
            color: #a1a5b7;
        }

        .user-email i {
            color: #3699ff;
            margin-left: 5px;
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
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .role-badge:hover {
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            color: white;
            transform: scale(1.05);
            border-color: #3699ff;
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
                        <i class="la la-users mr-2"></i>
                        {{ __('عرض المستخدمين') }}
                    </h3>
                    <p class="mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                        إدارة المستخدمين والأدوار المرتبطة بهم
                    </p>
                </div>
            </div>
            <div class="card-body py-5">
                <div class="table-responsive">
                    <table class="table table-borderless table-vertical-center">
                        <thead>
                            <tr>
                                <th style="min-width: 80px">{{ __('id') }}</th>
                                <th style="min-width: 250px">{{ __('name') }}</th>
                                <th style="min-width: 250px">{{ __('Email') }}</th>
                                <th style="min-width: 250px">{{ __('roles') }}</th>
                                <th style="min-width: 120px">{{ __('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <span class="user-id">{{ $user->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
                                            <span class="user-name">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="user-email">
                                            <i class="la la-envelope"></i> {{ $user->email }}
                                        </span>
                                    </td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="role-badge">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route(config('permission_ui.route_name_prefix') . 'users.edit', $user) }}"
                                            class="action-btn" title="{{ __('تعديل') }}">
                                            <i class="la la-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="la la-users"></i>
                                            <h4>{{ __('no_records') }}</h4>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($users->links())
                        <div class="pagination-wrapper">
                            {!! $users->appends(['search' => request('search')])->withQueryString()->links('pagination::bootstrap-5') !!}
                        </div>
                    @endif
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
