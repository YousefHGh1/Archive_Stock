@extends('layout.master')

@section('title')
    اضافة الجهات الموردة
@endsection

@section('page_title')
    لوحة التحكم
@endsection

@section('sub_main')
الجهات الموردة
@endsection

@section('sub_title')
    اضافة وارد
@endsection

@section('css')
    <style>
        .fuel-entry-form .card-body {
            padding: 1.75rem 2rem;
        }

        .fuel-entry-form .fuel-form-section {
            counter-increment: fuel-stage;
            padding: 1.35rem 1.5rem 1.15rem;
            margin-bottom: 1.25rem;
            background: #f8fafc;
            border: 1px solid #e5eaf0;
            border-right: 3px solid #3699ff;
            border-radius: .25rem;
        }

        .fuel-entry-form .fuel-form-section:last-child {
            margin-bottom: 0;
        }

        .fuel-entry-form .card-body {
            counter-reset: fuel-stage;
        }

        .fuel-entry-form .fuel-section-heading {
            display: flex;
            align-items: center;
            padding-bottom: .8rem;
            margin: 0 0 1.25rem;
            color: #344256;
            font-size: 1.08rem;
            font-weight: 700;
            border-bottom: 1px solid #e4eaf1;
        }

        .fuel-entry-form .fuel-section-heading::before {
            display: inline-flex;
            flex: 0 0 1.65rem;
            align-items: center;
            justify-content: center;
            width: 1.65rem;
            height: 1.65rem;
            margin-left: .7rem;
            content: counter(fuel-stage, decimal-leading-zero);
            background: #3699ff;
            border-radius: .25rem;
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            line-height: 1;
        }

        .fuel-entry-form .form-group {
            margin-bottom: 1rem;
        }

        .fuel-entry-form .form-group > label {
            display: block;
            padding: 0 0 .4rem;
            color: #596779;
            font-weight: 500;
        }

        .fuel-entry-form .form-group > label h6 {
            margin: 0;
            font-size: inherit;
            font-weight: inherit;
        }

        .fuel-entry-form .form-control,
        .fuel-entry-form .bootstrap-select > .dropdown-toggle {
            min-height: 42px;
            border-color: #d9e0e8;
        }

        .fuel-entry-form .card-footer {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .65rem;
            padding: 1rem 2rem;
        }

        .fuel-entry-form .card-footer > .row {
            width: 100%;
            margin: 0;
        }

        .fuel-entry-form .card-footer > .row > div:first-child {
            display: none;
        }

        .fuel-entry-form .card-footer > .row > div:last-child {
            width: 100%;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .55rem;
        }

        @media (max-width: 767.98px) {
            .fuel-entry-form .card-body {
                padding: 1rem;
            }

            .fuel-entry-form .fuel-form-section {
                padding: 1rem;
            }

            .fuel-entry-form .card-footer {
                padding: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact fuel-entry-form" dir="rtl">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label"> بيانات الجهات الموردة <i class="mr-2"></i></h3>
                        </div>
                        <div class="card-toolbar">

                            <a href="{{ route('import.index') }}" class="btn btn-info font-weight-bolder">
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
                                </span>عرض جهات التوريد</a>
                            <x-add-resource-button />

                        </div>
                    </div>
                    <!--begin::Form-->
                    <form action="{{ route('import.store') }}" method="post" class="form needs-validation " novalidate
                        enctype="multipart/form-data" id="kt_form">
                        @csrf
                        {!! csrf_field() !!}
                        <div class="card-body">
                            <section class="fuel-form-section" aria-labelledby="import-details-heading">
                                <h4 id="import-details-heading" class="fuel-section-heading">بيانات الجهة الموردة</h4>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="import_name">الجهة الموردة</label>
                                        <input name="import_name" id="import_name" type="text"
                                            class="form-control" placeholder="ادخل الجهة الموردة">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="import_num">رقم الجهة الموردة</label>
                                        <input name="import_num" type="number" min="0" class="form-control"
                                            id="import_num" placeholder="ادخل رقم الجهة الموردة" />
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-lg-2"></div>
                                <div class="col-lg-10">
                                    <button type="submit" class="mr-2 btn btn-success">حفظ</button>
                                    <button type="reset" class="btn btn-danger">إلغاء</button>
                                </div>
                            </div>
                        </div>

                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card-->

            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
