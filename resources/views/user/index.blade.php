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
    </style>
@endsection

@section('content')
    <div class="container">

        <!--begin::Card-->
        <div class="card card-custom">
            <div class="flex-wrap pt-6 pb-0 border-0 card-header">
                <div class="card-title">
                    <h3 class="card-label">عرض المستخدمين </h3>
                </div>
                <div class="card-toolbar">

                    <!--begin::Button-->
                    @can('users-create')
                    <a href="{{ url('/user/create') }}" class="btn btn-success font-weight-bolder">
                        <span class="svg-icon svg-icon-md">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <circle fill="#000000" cx="9" cy="15" r="6" />
                                    <path
                                        d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                        fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>إنشاء مستخدم جديد</a>
                    @endcan
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-striped" style="width:100%">
                    <thead class="thead_dark">
                        <tr>
                            <th width="10%">{{ ' الرقم الوظيفي' }}</th>
                            <th width="25%">{{ 'اسم الموظف  ' }}</th>
                            <th width="25%">{{ 'القسم الرئيسي  ' }}</th>
                            <th width="20%">{{ ' القسم الفرعي' }}</th>

                            <th width="10%">{{ 'الاجراءات ' }} </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td> {{ $user->name }}</td>
                                <td> {{ $user->employee_name }}</td>
                                <td> {{ $user->Section->name_section }}</td>
                                <td>{{ $user->subSection->name }}</td>


                                <td data-field="Actions" data-autohide-disabled="false" aria-label="null"
                                    class="datatable-cell">
                                    <span style="overflow: visible; position: relative; width: 110px;">

                                        @can('users-update')
                                        <a href="{{ url('/user/' . $user->id . '/edit') }}"
                                            class="btn btn-sm btn-clean btn-icon" title="Edit details"><i
                                                class="la la-edit"></i></a>
                                        @endcan

                                        @can('users-delete')
                                        <form action="{{ url('/user/' . $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-clean btn-icon" title="Delete">
                                                <i class="la la-trash"></i>
                                            </button>
                                        </form>
                                        @endcan

                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if ($users->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {!! $users->appends(['search' => request('search')])->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                @endif
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
@endsection
