@extends('layout.master')

@section('title')
    تعديل مسؤولية
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    إدارة النظام
@endsection
@section('sub_title')
    تعديل مسؤولية
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

        .form-control {
            border: 1px solid #e4e6ef;
            border-radius: 8px;
            padding: 12px 15px;
        }

        .form-control:focus {
            border-color: #3699ff;
            box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
        }

        .btn-secondary {
            background: #e4e6ef;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            color: #3f4254;
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
                        تعديل مسؤولية
                    </h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('permissions-manage.index') }}" class="btn btn-secondary">
                        <i class="la la-arrow-right"></i> العودة
                    </a>
                </div>
            </div>
            <div class="card-body py-5">
                <form action="{{ route('permissions-manage.update', $permission->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">اسم الصلاحية</label>
                        <input type="text" class="form-control" id="name" name="name" required value="{{ $permission->name }}">
                        @error('name')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('permissions-manage.index') }}" class="btn btn-secondary mr-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">تحديث</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
