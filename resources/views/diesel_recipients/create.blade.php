@extends('layout.master')

@section('title')
    إضافة جهة مستلمة
@endsection

@section('content')
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-label">إضافة جهة مستلمة جديدة</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('diesel_recipients.store') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name">اسم الجهة المستلمة *</label>
                        <input type="text" name="name" id="name" class="form-control" required minlength="2" maxlength="255" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">حفظ</button>
                        <a href="{{ route('diesel_recipients.index') }}" class="btn btn-secondary">رجوع</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
