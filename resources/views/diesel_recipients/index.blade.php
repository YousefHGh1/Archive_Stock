@extends('layout.master')

@section('title')
    الجهات المستلمة
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    الجهات المستلمة
@endsection
@section('sub_title')
    إدارة الجهات المستلمة
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">إدارة الجهات المستلمة</h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('diesel_recipients.create') }}" class="btn btn-success font-weight-bolder">
                        <span class="svg-icon svg-icon-md">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <circle fill="#000000" cx="9" cy="15" r="6" />
                                    <path
                                        d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                        fill="#000000" opacity="0.3" />
                                </svg>
                            </span>
                        إضافة جهة مستلمة
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم الجهة المستلمة</th>
                                <th>الحالة</th>
                                <th>تاريخ الإضافة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recipients as $recipient)
                                <tr>
                                    <td>{{ $recipient->id }}</td>
                                    <td>{{ $recipient->name }}</td>
                                    <td>
                                        @if ($recipient->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>{{ $recipient->created_at ? $recipient->created_at->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('diesel_recipients.edit', $recipient->id) }}" class="btn btn-sm btn-primary">
                                            تعديل
                                        </a>
                                        @if ($recipient->is_active)
                                            <form action="{{ route('diesel_recipients.destroy', $recipient->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الجهة المستلمة؟')">
                                                    حذف
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $recipients->links() }}
            </div>
        </div>
    </div>
@endsection
