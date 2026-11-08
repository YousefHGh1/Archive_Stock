@extends('layout.master')

@section('title')
    اضافة العائلات
@endsection

@section('page_title')
    لوحة التحكم
@endsection

@section('sub_main')
    العائلات
@endsection

@section('sub_title')
    اضافة العائلات
@endsection

@section('css')
    <style>
        input {
            border: 1px solid #d5c1ff !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label"> بيانات العائلات <i class="mr-2"></i></h3>
                        </div>
                        <div class="card-toolbar">

                            <a href="{{ url('/inventory/category') }}" class="btn btn-info font-weight-bolder">
                                <span class="svg-icon svg-icon-md">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <circle fill="#000000" cx="9" cy="13" r="6" />
                                            <path
                                                d="M8.8012943,7.00241933 C9.83837773,3.20768121 11.7781343,4 14,4 C17.3137083,4 20,6.6862913 20,10 C20,12.2218437 18.7923188,14.1616223 16.9973803,13.1987037 C16.9991904,13.1326638 17,13.0664274 17,13 C17,10.381722 13.418278,7 9,7 C8.93337236,7 8.86733422,7.00080962 8.8012943,7.00241933 Z"
                                                fill="#000000" opacity="0.3" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>عرض العائلات </a>

                            <x-add-resource-button />

                        </div>
                    </div>

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ session('warning') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!--begin::Form-->
                    <form action="{{ route('category.store') }}" method="post" class="form needs-validation " novalidate
                        enctype="multipart/form-data" id="kt_form">
                        @csrf
                        {!! csrf_field() !!}
                        <div class="card-body">

                            <div class="p-3 form-group row">
                                <label for="category_name" class="col-lg-2 col-form-label ">
                                    <h6><strong>اسم العائلة:</strong></h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="category_name" id="category_name" type="text" class="form-control @error('category_name') is-invalid @enderror"
                                            placeholder="ادخل اسم العائلة" value="{{ old('category_name') }}">
                                    </div>
                                    @error('category_name')
                                        <div class="mt-1 text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <label for="category_num" class="col-lg-2 col-form-label ">
                                    <h6><strong>رقم العائلة:</strong></h6>
                                </label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input name="category_num" type="number" min="0" class="form-control @error('category_num') is-invalid @enderror"
                                            id="category_num" value="{{ old('category_num') }}" />
                                    </div>
                                    @error('category_num')
                                        <div class="mt-1 text-danger">{{ $message }}</div>
                                    @enderror
                                    <div id="max_num_hint" class="mt-1 alert alert-info" style="display: none; padding: 0.5rem 1rem; font-size: 0.9rem;"></div>
                                </div>
                            </div>


                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <button type="submit" class="mr-2 btn btn-success">حفظ</button>
                                    <button type="reset" class="mr-2 btn btn-danger">إلغاء</button>

                                </div>
                            </div>

                        </div>
                </div>


                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded');

            const categoryNumInput = document.getElementById('category_num');
            const maxNumHint = document.getElementById('max_num_hint');

            console.log('Input element:', categoryNumInput);
            console.log('Hint element:', maxNumHint);

            if (categoryNumInput && maxNumHint) {
                categoryNumInput.addEventListener('input', function() {
                    const value = this.value;
                    console.log('Input value:', value);

                    if (value && value.length > 0) {
                        const prefix = value.charAt(0);
                        console.log('Prefix:', prefix);

                        const url = "{{ route('category.max_num') }}?prefix=" + prefix;
                        console.log('Fetching:', url);

                        fetch(url)
                            .then(response => {
                                console.log('Response status:', response.status);
                                return response.json();
                            })
                            .then(data => {
                                console.log('Data received:', data);
                                if (data.max_num) {
                                    maxNumHint.innerHTML = '<strong>أكبر رقم موجود يبدأ بـ ' + prefix + ':</strong> ' + data.max_num + '<br><strong>الرقم المقترح:</strong> ' + data.next_num;
                                    maxNumHint.style.display = 'block';
                                } else {
                                    maxNumHint.innerHTML = 'لا يوجد أرقام تبدأ بـ ' + prefix + '<br><strong>الرقم المقترح:</strong> ' + data.next_num;
                                    maxNumHint.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                maxNumHint.style.display = 'none';
                            });
                    } else {
                        maxNumHint.style.display = 'none';
                    }
                });
            } else {
                console.error('Elements not found');
            }
        });
    </script>
@endsection
