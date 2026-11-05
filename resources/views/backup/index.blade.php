@extends('layout.master')

@section('title')
    النسخ الاحتياطي لقاعدة البيانات
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    إدارة النظام
@endsection
@section('sub_title')
    النسخ الاحتياطي
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

        .btn-backup {
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-backup:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(54, 153, 255, 0.4);
            color: white;
        }

        .table-custom thead th {
            background: linear-gradient(135deg, #3699ff 0%, #bc82f6 100%);
            color: white;
            border: none;
            font-weight: 600;
        }

        .table-custom tbody tr:hover {
            background-color: #f8f9ff;
        }

        .action-btn {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-download {
            background-color: #1bc5bd;
            color: white;
            border: none;
        }

        .btn-download:hover {
            background-color: #159a96;
            color: white;
        }

        .btn-delete {
            background-color: #f64e60;
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background-color: #d83345;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card card-custom">
                    <div class="card-header card-header-stretch">
                        <div class="card-title">
                            <h3 class="card-label">النسخ الاحتياطي لقاعدة البيانات</h3>
                        </div>
                        <div class="card-toolbar">
                            <form action="{{ route('backup.create') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-backup">
                                    <i class="flaticon2-download me-2"></i>
                                    إنشاء نسخة احتياطية جديدة
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-custom table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الملف</th>
                                        <th>الحجم</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($backups as $index => $backup)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $backup['name'] }}</td>
                                            <td>{{ $backup['size'] }}</td>
                                            <td>{{ $backup['date'] }}</td>
                                            <td>
                                                <a href="{{ $backup['download_link'] }}" class="btn btn-download action-btn me-2">
                                                    <i class="flaticon2-download me-1"></i>
                                                    تحميل
                                                </a>
                                                <form action="{{ $backup['delete_link'] }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-delete action-btn" onclick="return confirm('هل أنت متأكد من حذف هذه النسخة الاحتياطية؟')">
                                                        <i class="flaticon2-trash me-1"></i>
                                                        حذف
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="flaticon-database text-primary" style="font-size: 3rem;"></i>
                                                    <p class="mt-3">لا توجد نسخ احتياطية حالياً</p>
                                                    <p class="small">اضغط على "إنشاء نسخة احتياطية جديدة" لبدء العملية</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
