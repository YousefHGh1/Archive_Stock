@extends('layout.master')

@section('title')
    عرض المستخدمين
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    المستخدمين
@endsection
@section('sub_title')
    عرض المستخدمين
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

        /* ====== Stats Cards ====== */
        .stats-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e4e6ef;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3699ff, #bc82f6);
        }

        .stats-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-3px);
        }

        .stats-card .stats-icon {
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

        .stats-card .stats-icon.purple {
            background: rgba(188, 130, 246, 0.1);
            color: #bc82f6;
        }

        .stats-card .stats-icon.green {
            background: rgba(27, 197, 189, 0.1);
            color: #1bc5bd;
        }

        .stats-card .stats-icon.orange {
            background: rgba(255, 168, 0, 0.1);
            color: #ffa800;
        }

        .stats-number {
            font-size: 28px;
            font-weight: 700;
            color: #3f4254;
            line-height: 1.2;
        }

        .stats-label {
            font-size: 13px;
            color: #a1a5b7;
            font-weight: 500;
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

        /* ====== Table ====== */
        .table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            color: #3f4254;
            font-weight: 700;
            font-size: 13px;
            border-bottom: 2px solid #e4e6ef;
            padding: 18px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 18px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f1f5;
            color: #3f4254;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(54, 153, 255, 0.03);
            transform: scale(1.01);
        }

        /* ====== User Avatar Badge ====== */
        .user-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            color: white;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.3);
        }

        /* ====== Action Buttons ====== */
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
            color: #6c757d;
        }

        .action-btn:hover {
            transform: translateY(-3px) scale(1.1);
            background: #3699ff;
            color: white;
            border-color: #3699ff;
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.3);
        }

        .action-btn.delete:hover {
            background: #f64e60;
            border-color: #f64e60;
            box-shadow: 0 5px 15px rgba(246, 78, 96, 0.3);
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

        /* ====== Status Badge ====== */
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: rgba(27, 197, 189, 0.1);
            color: #1bc5bd;
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
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header border-0 py-5">
                <div class="card-title">
                    <h3 class="card-label">
                        <i class="la la-users mr-2"></i>
                        عرض المستخدمين
                    </h3>
                    <p class="mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                        إدارة المستخدمين والموظفين في النظام
                    </p>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex align-items-center">
                        <div class="search-box mr-4">
                            <input type="text" class="form-control" placeholder="بحث عن مستخدم..." id="userSearch">
                            <i class="la la-search"></i>
                        </div>
                        @can('users-create')
                        <a href="{{ url('/user/create') }}" class="btn btn-create font-weight-bolder">
                            <i class="la la-plus"></i> إنشاء مستخدم جديد
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body py-5">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <div class="stats-icon">
                                <i class="la la-users"></i>
                            </div>
                            <div class="stats-number">{{ $users->total() ?? $users->count() }}</div>
                            <div class="stats-label">إجمالي المستخدمين</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <div class="stats-icon purple">
                                <i class="la la-building"></i>
                            </div>
                            <div class="stats-number">{{ $sectionCount ?? count($section ?? []) }}</div>
                            <div class="stats-label">الدوائر</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <div class="stats-icon green">
                                <i class="la la-sitemap"></i>
                            </div>
                            <div class="stats-number">{{ $subSectionCount ?? count($subSection ?? []) }}</div>
                            <div class="stats-label">الأقسام</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <div class="stats-icon orange">
                                <i class="la la-shield"></i>
                            </div>
                            <div class="stats-number">{{ \Spatie\Permission\Models\Role::count() }}</div>
                            <div class="stats-label">الأدوار</div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="8%">الرقم الوظيفي</th>
                                <th width="25%">اسم الموظف</th>
                                <th width="20%">الدائرة</th>
                                <th width="20%">القسم</th>
                                <th width="12%">الحالة</th>
                                <th width="15%">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr data-user-name="{{ $user->employee_name }} {{ $user->name }}">
                                    <td>
                                        <div class="user-badge">
                                            {{ mb_substr($user->name, 0, 2) }}
                                        </div>
                                        <div class="mt-2 font-weight-bold">{{ $user->name }}</div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $user->employee_name }}</div>
                                        <div class="text-muted small">{{ $user->email ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-primary font-weight-bold px-3 py-2">
                                            {{ $user->Section->name_section ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-info font-weight-bold px-3 py-2">
                                            {{ $user->subSection->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge">
                                            <i class="la la-check-circle"></i> نشط
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            @can('users-update')
                                            <a href="{{ url('/user/' . $user->id . '/edit') }}"
                                                class="action-btn mr-2" title="تعديل">
                                                <i class="la la-edit"></i>
                                            </a>
                                            @endcan

                                            @can('users-delete')
                                            <form action="{{ url('/user/' . $user->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn delete" title="حذف"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                                    <i class="la la-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="la la-users"></i>
                                            <h4>لا يوجد مستخدمين</h4>
                                            <p class="text-muted">قم بإنشاء أول مستخدم للبدء</p>
                                            @can('users-create')
                                            <a href="{{ url('/user/create') }}" class="btn btn-primary font-weight-bolder mt-3">
                                                <i class="la la-plus"></i> إنشاء مستخدم جديد
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {!! $users->appends(['search' => request('search')])->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Live search functionality
        document.getElementById('userSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');

            tableRows.forEach(row => {
                const userName = row.dataset.userName;
                if (userName && userName.toLowerCase().includes(searchTerm)) {
                    row.style.display = '';
                } else if (userName) {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endsection
