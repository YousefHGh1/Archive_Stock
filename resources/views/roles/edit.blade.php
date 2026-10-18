@extends('layout.master')

@section('title')
    تعديل الدور
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    إدارة النظام
@endsection
@section('sub_title')
    تعديل الدور: {{ $role->name }}
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

        /* ====== Form Controls ====== */
        .form-control {
            border-radius: 10px;
            border: 2px solid #e4e6ef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3699ff;
            box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.1);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 2px solid #e4e6ef;
            border-right: none;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        /* ====== Role Info Card ====== */
        .role-info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e4e6ef;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .role-info-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border-color: #3699ff;
        }

        .role-info-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-left: 15px;
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.3);
            flex-shrink: 0;
        }

        .role-info-name {
            font-size: 18px;
            font-weight: 700;
            color: #3f4254;
            margin-bottom: 5px;
        }

        .role-info-meta {
            font-size: 13px;
            color: #a1a5b7;
        }

        /* ====== Permission Groups ====== */
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

        .select-all-group {
            margin-bottom: 0;
            font-weight: 600;
            padding: 8px 15px;
            background: rgba(54, 153, 255, 0.1);
            border-radius: 8px;
            display: inline-block;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .select-all-group:hover {
            background: rgba(54, 153, 255, 0.2);
            border-color: #3699ff;
        }

        .select-all-group input[type="checkbox"] {
            margin-left: 8px;
            cursor: pointer;
        }

        /* ====== Permission Items ====== */
        .permission-item {
            padding: 8px 15px;
            margin: 5px;
            border-radius: 20px;
            background: #f8f9fa;
            border: 1px solid #e4e6ef;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 13px;
            user-select: none;
        }

        .permission-item:hover {
            background: #3699ff;
            color: white;
            border-color: #3699ff;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(54, 153, 255, 0.2);
        }

        .permission-item:has(input:checked) {
            background: rgba(54, 153, 255, 0.1);
            border-color: #3699ff;
            color: #3699ff;
            font-weight: 600;
        }

        .permission-item input[type="checkbox"] {
            margin-left: 8px;
            cursor: pointer;
            accent-color: #3699ff;
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

        /* ====== Label ====== */
        .form-label {
            font-weight: 600;
            color: #3f4254;
            margin-bottom: 8px;
            display: block;
        }

        .form-label i {
            color: #3699ff;
            margin-left: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header border-0 py-5">
                <div class="card-title">
                    <h3 class="card-label">
                        <i class="la la-edit mr-2"></i>
                        تعديل الدور
                    </h3>
                    <p class="mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                        قم بتعديل بيانات الدور والصلاحيات الممنوحة له
                    </p>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('roles.index') }}" class="btn btn-light font-weight-bolder">
                        <i class="la la-arrow-right"></i> عودة
                    </a>
                </div>
            </div>
            <div class="card-body py-5">
                <div class="role-info-card">
                    <div class="role-info-icon">
                        <i class="la la-shield"></i>
                    </div>
                    <div>
                        <div class="role-info-name">{{ $role->name }}</div>
                        <div class="role-info-meta">
                            <i class="la la-key"></i> {{ $role->permissions->count() }} صلاحية
                            @if($role->users && $role->users->count() > 0)
                                <span class="mx-2">|</span>
                                <i class="la la-user"></i> {{ $role->users->count() }} مستخدم
                            @endif
                        </div>
                    </div>
                </div>

                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4">
                        <label for="name" class="form-label">
                            <i class="la la-shield"></i> اسم الدور <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="la la-shield"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $role->name) }}" required>
                        </div>
                        @error('name')
                            <div class="text-danger mt-2">
                                <i class="la la-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="la la-lock"></i> الصلاحيات
                        </label>
                        <p class="text-muted small mb-3">
                            اختر الصلاحيات التي تريد منحها لهذا الدور. ويمكن البحث السريع داخل القائمة.
                        </p>

                        <div class="form-group mb-4">
                            <div class="search-box">
                                <input type="text" class="form-control" id="permissionSearch"
                                    placeholder="بحث سريع في الصلاحيات...">
                                <i class="la la-search"></i>
                            </div>
                        </div>

                        @foreach ($groupedPermissions as $group => $permissions)
                            <div class="permission-group">
                                <div class="permission-group-header">
                                    <h5 class="mb-0">
                                        <i class="la la-folder-open"></i> {{ $group }}
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <span class="group-count-badge mr-3">{{ $permissions->count() }} صلاحية</span>
                                        <label class="select-all-group mb-0">
                                            <input type="checkbox" class="select-all-group-checkbox"
                                                data-group="{{ $group }}">
                                            تحديد الكل
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach ($permissions as $permission)
                                        <label class="permission-item">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                class="group-{{ $group }} permission-checkbox"
                                                @if (in_array($permission->id, $rolePermissions)) checked @endif>
                                            {{ $permission->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group mt-4 pt-4 border-top">
                        <button type="submit" class="btn btn-primary px-6 py-3 font-weight-bolder">
                            <i class="la la-save"></i> تحديث الدور
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary px-6 py-3">
                            <i class="la la-times"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Select all permissions in a group
        document.querySelectorAll('.select-all-group-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const group = this.dataset.group;
                const groupCheckboxes = document.querySelectorAll('.group-' + group);
                groupCheckboxes.forEach(cb => cb.checked = this.checked);
            });
        });

        // Update select all checkbox when individual checkboxes change
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const group = this.classList[0].replace('group-', '');
                const groupCheckboxes = document.querySelectorAll('.group-' + group);
                const selectAllCheckbox = document.querySelector('.select-all-group-checkbox[data-group="' +
                    group + '"]');
                const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });

        // Initialize select all checkboxes on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.select-all-group-checkbox').forEach(checkbox => {
                const group = checkbox.dataset.group;
                const groupCheckboxes = document.querySelectorAll('.group-' + group);
                const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
                checkbox.checked = allChecked;
            });
        });

        document.getElementById('permissionSearch').addEventListener('input', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.permission-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(term) ? '' : 'none';
            });
        });
    </script>
@endsection
