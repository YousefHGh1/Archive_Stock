@extends('layout.master')

@section('title')
    عرض الأصناف
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    الأصناف
@endsection
@section('sub_title')
    صفحة العرض
@endsection
@section('css')
    <link href="{{ asset('assets/css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.foundation.min.css') }}">


    <style>
        .dataTables_wrapper .dataTable th,
        .dataTables_wrapper .dataTable td {
            padding-right: 10px !important;
            margin: 10px !important;
        }

        .dataTables_wrapper .dataTable tfoot th,
        .dataTables_wrapper .dataTable thead th {
            padding-right: 0px;
            padding-left: 0px;
            text-align: right;
        }

        div.dt-buttons {
            top: 50%;
            left: 10%;
            width: 200px;
            margin-left: -100px;
            margin-top: -20px;
            text-align: center;
            padding: 1rem 0
        }

        div.dt-buttons .dt-button {
            margin: -2px !important;
        }

        .dataTables_wrapper .dataTable thead th {
            background-color: #E4E6EF;
        }

        .card-body {
            padding: 10px 12px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">

        <!--begin::Card-->
        <div class="card card-custom">
            <div class="flex-wrap pb-0 border-0 card-header">
                <div class="card-title">
                    <h3 class="card-label">عرض الأصناف </h3>
                </div>
                <div class="pb-5 card-toolbar">
                                        <!--begin::Dropdown-->
                    <div class="mr-2 dropdown dropdown-inline">
                        <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="svg-icon svg-icon-md">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                            fill="#000000" opacity="0.3" />
                                        <path
                                            d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                            fill="#000000" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>تقارير</button>
                        <!--begin::Dropdown Menu-->
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            <!--begin::Navigation-->
                            <ul class="py-2 navi flex-column navi-hover">
                                <li class="pb-2 navi-header font-weight-bolder text-uppercase font-size-sm text-primary">
                                    اختر:</li>
                                <li class="navi-item">
                                    <a href="{{ route('transactions_report') }}" class="navi-link">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Communication/Clipboard-list.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path
                                                        d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z"
                                                        fill="#000000" opacity="0.3" />
                                                    <path
                                                        d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z"
                                                        fill="#000000" />
                                                    <rect fill="#000000" opacity="0.3" x="10" y="9" width="7"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="7" y="9" width="2"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="7" y="13" width="2"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="10" y="13" width="7"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="7" y="17" width="2"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="10" y="17" width="7"
                                                        height="2" rx="1" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <span class="navi-text"> حركة الأصناف</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="{{ route('inventory.totalreport') }}" class="navi-link">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Communication/Clipboard-list.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path
                                                        d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z"
                                                        fill="#000000" opacity="0.3" />
                                                    <path
                                                        d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z"
                                                        fill="#000000" />
                                                    <rect fill="#000000" opacity="0.3" x="10" y="9" width="7"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="7" y="9" width="2"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="7" y="13" width="2"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="10" y="13" width="7"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="7" y="17" width="2"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="10" y="17" width="7"
                                                        height="2" rx="1" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <span class="navi-text">المجاميع</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="{{ route('balance') }}" class="navi-link">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Communication/Clipboard-list.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path
                                                        d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z"
                                                        fill="#000000" opacity="0.3" />
                                                    <path
                                                        d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z"
                                                        fill="#000000" />
                                                    <rect fill="#000000" opacity="0.3" x="10" y="9" width="7"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="7" y="9" width="2"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="7" y="13" width="2"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="10" y="13" width="7"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="7" y="17" width="2"
                                                        height="2" rx="1" />
                                                    <rect fill="#000000" opacity="0.3" x="10" y="17" width="7"
                                                        height="2" rx="1" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <span class="navi-text">أرصدة الأصناف</span>
                                    </a>
                                </li>
                            </ul>
                            <!--end::Navigation-->
                        </div>
                        <!--end::Dropdown Menu-->
                    </div>
                    <!--end::Dropdown-->
                    <!--begin::Button-->
                    @can('items-create')
                    <button type="button" class="btn btn-success font-weight-bolder" data-toggle="modal" data-target="#itemModal">
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
                        </span>إنشاء صنف جديد</button>
                    @endcan

                    <x-add-resource-button />
                    <!--end::Button-->

                </div>

            </div>
            <div class="accordion accordion-light " id="accordionExample5">
                <div class="card">
                    <div class="p-5 card-header" style="cursor: auto" id="headingOne5">
                        <div class="card-title">
                            <div data-toggle="collapse" data-target="#collapseOne5"
                                class="btn btn-primary advance_search font-weight-bolder "><i class="flaticon-search"></i>
                                بحــــــث متــقـــــــــدم</div>
                        </div>
                    </div>
                    <div id="collapseOne5" class="pl-5 collapse" data-parent="#accordionExample5">

                        <div class="mb-6 col-lg-12">
                            <form action="{{ route('item.index') }}" method="GET" class="pl-5 form-group">
                                @csrf
                                <div class="form-group row">
                                    <!-- Category Search -->
                                    <div class="pl-5 pr-5 col-3">
                                        <select name="category_name" class="form-control" title="اختر العائلة...">
                                            <option value="">{{ 'اسم العائلة  -------' }}</option>
                                            @foreach ($category as $categories)
                                                <option value="{{ $categories->category_name }}"
                                                    {{ request('category_name') == $categories->category_name ? 'selected' : '' }}>
                                                    {{ $categories->category_name }} {{ $categories->category_num }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Category Search -->
                                    <div class="pl-5 pr-5 col-2">
                                        <select name="category_num" class="form-control" title="اختر العائلة...">
                                            <option value="">{{ 'رقم العائلة  -------' }}</option>
                                            @foreach ($category as $categories)
                                                <option value="{{ $categories->category_num }}"
                                                    {{ request('category_num') == $categories->category_num ? 'selected' : '' }}>
                                                    {{ $categories->category_num }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Item Name Search -->
                                    <div class="pl-5 pr-5 col-2">
                                        <input type="text" name="item_name" class="form-control"
                                            placeholder="بحث عن الصنف..." value="{{ request('item_name') }}">
                                    </div>

                                    <!-- Item Number Search -->
                                    <div class="pl-5 pr-5 col-2">
                                        <input type="text" name="item_num" class="form-control"
                                            placeholder="بحث عن رقم الصنف..." value="{{ request('item_num') }}">
                                    </div>

                                    <!-- Search Button -->
                                    <div class="mt-auto mb-auto col-lg-1">
                                        <input type="submit" class="btn btn-success btn-success--icon" value="بحث" />
                                    </div>

                                    <!-- Clear Button -->
                                    <div class="mt-auto mb-auto col-lg-2">
                                        <a href="{{ route('item.index') }}" class="btn btn-danger btn-danger--icon">تنظيف
                                            البحث</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-0 card-body">
                <!--begin: Datatable-->

                <table id="example" class="table dt-responsive" style="width:100%">
                    <thead class="thead_dark">
                        <tr>

                            <th>{{ 'رقم الصنف' }} </th>
                            <th>{{ 'اسم الصنف' }}</th>
                            <th>{{ 'العائلة' }} </th>
                            <th>{{ 'الوحدة' }} </th>
                            <th>{{ 'الرصيد الإفتتاحي' }}</th>
                            <th>{{ 'الحد الأدني' }}</th>
                            <th>{{ 'رصيد الصنف' }}</th>
                            <th width="13%">{{ 'الاجراءات' }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($item as $items)
                            <tr>

                                <td>{{ $items->item_num }}</td>
                                <td>{{ $items->item_name }}</td>
                                <td>{{ $items->category->category_name }}</td>
                                <td>{{ $items->unit->unit_name }}</td>
                                <td>{{ $items->open_balance }}</td>
                                <td>{{ $items->low_limit }}</td>
                                {{-- <td>{{ $items->balance }}</td> --}}
                                <td>{{ $items->balance }}</td>

                                <td data-field="Actions" data-autohide-disabled="false" aria-label="null"
                                    class="datatable-cell">
                                    <span style="overflow: visible; position: relative; width: 110px;">

                                        {{-- <a href="#" onclick="confirmDelete('{{ $items->id }}', this)"
                                        class="btn btn-sm btn-clean btn-icon" title="Delete"><i
                                            class="la la-trash"></i></a> --}}
                                        {{-- <a href="{{ url('/inventory/item/' . $items->id) }}" title="View items"><button
                                                class="btn btn-sm btn-clean btn-icon"> <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                    <path
                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                                </svg></button>
                                        </a> --}}

                                        <a href="{{ route('transactions', ['itemId' => $items->id]) }}" title="View items">
                                            <button class="btn btn-sm btn-clean btn-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                    <path
                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                                </svg></button></a>


                                        @can('items-delete')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon"
                                            title="Delete item"
                                            onclick="deleteItem({{ $items->id }})"> <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                </svg></button>
                                        @endcan

                                        @can('items-update')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon"
                                            title="Edit details"
                                            onclick="editItem({{ $items->id }})"><i
                                                class="la la-edit"></i></button>
                                        @endcan

                                    </span>
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
                {{-- {{ $item->appends(['search' => request('search')])->links() }} --}}

                {!! $item->appends(['search' => request('search')])->withQueryString()->links('pagination::bootstrap-5') !!}
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/features/miscellaneous/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/data-json_item.js') }}" type="text/javascript"></script>

    <!-- Item Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalTitle">إضافة صنف جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="itemForm">
                        @csrf
                        <input type="hidden" name="item_id" id="item_id" value="">

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label"><strong>العائلة:</strong></label>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <select name="category_id" id="modal_category_id" class="form-control selectpicker"
                                        data-live-search="true" title="اختر العائلة" required>
                                        @foreach ($category as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->category_name }} - {{ $cat->category_num }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary"
                                            title="إضافة عائلة جديدة"
                                            onclick="openCategoryModal()">
                                            <i class="ki ki-solid-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label"><strong>الوحدة:</strong></label>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <select name="unit_id" id="modal_unit_id" class="form-control selectpicker"
                                        data-live-search="true" title="اختر الوحدة" required>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->unit_name }} - {{ $unit->unit_num }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary"
                                            title="إضافة وحدة جديدة"
                                            onclick="openUnitModal()">
                                            <i class="ki ki-solid-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label"><strong>اسم الصنف:</strong></label>
                            <div class="col-lg-8">
                                <input type="text" name="item_name" id="modal_item_name"
                                    class="form-control" placeholder="ادخل اسم الصنف" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label"><strong>رقم الصنف:</strong></label>
                            <div class="col-lg-8">
                                <input type="number" name="item_num" id="modal_item_num"
                                    class="form-control" placeholder="ادخل رقم الصنف" required readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label"><strong>الرصيد الافتتاحي:</strong></label>
                            <div class="col-lg-8">
                                <input type="number" name="open_balance" id="modal_open_balance"
                                    class="form-control" placeholder="ادخل الرصيد" required min="0">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label"><strong>الحد الأدنى:</strong></label>
                            <div class="col-lg-8">
                                <input type="number" name="low_limit" id="modal_low_limit"
                                    class="form-control" placeholder="ادخل الحد الأدنى" required min="0">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="saveItem()">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة عائلة جديدة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm">
                        @csrf
                        <div class="form-group">
                            <label>اسم العائلة</label>
                            <input type="text" name="category_name" id="category_name"
                                class="form-control" required minlength="2" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label>رقم العائلة</label>
                            <input type="number" name="category_num" id="category_num"
                                class="form-control" required step="1">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="saveCategory()">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Unit Modal -->
    <div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة وحدة جديدة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="unitForm">
                        @csrf
                        <div class="form-group">
                            <label>اسم الوحدة</label>
                            <input type="text" name="unit_name" id="unit_name"
                                class="form-control" required minlength="2" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label>رقم الوحدة</label>
                            <input type="number" name="unit_num" id="unit_num"
                                class="form-control" required step="1">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="saveUnit()">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // إضافة صنف جديد
        $('#itemModal').on('show.bs.modal', function (e) {
            if (!$(e.relatedTarget).data('edit-mode')) {
                resetForm();
                $('#itemModalTitle').text('إضافة صنف جديد');
            }
        });

        // تحديث رقم الصنف عند اختيار العائلة
        $('#modal_category_id').on('change', function() {
            const categoryId = $(this).val();
            if (categoryId) {
                $.ajax({
                    url: "{{ route('category.last_item', ':id') }}".replace(':id', categoryId),
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data && data.last_item_num !== undefined) {
                            const newItemNum = parseInt(data.last_item_num) + 1;
                            $('#modal_item_num').val(newItemNum);
                        } else {
                            $('#modal_item_num').val(categoryId + '1');
                        }
                    },
                    error: function() {
                        $('#modal_item_num').val('');
                    }
                });
            }
        });

        // إعادة تعيين النموذج
        function resetForm() {
            $('#itemForm')[0].reset();
            $('#item_id').val('');
            $('#modal_category_id').selectpicker('refresh');
            $('#modal_unit_id').selectpicker('refresh');
        }

        // فتح Modal إضافة عائلة
        function openCategoryModal() {
            $('#categoryForm')[0].reset();
            $('#categoryModal').modal('show');
        }

        // فتح Modal إضافة وحدة
        function openUnitModal() {
            $('#unitForm')[0].reset();
            $('#unitModal').modal('show');
        }

        // حفظ عائلة جديدة
        function saveCategory() {
            const formData = {
                category_name: $('#category_name').val(),
                category_num: $('#category_num').val(),
            };

            if (!formData.category_name || !formData.category_num) {
                Swal.fire('تنبيه', 'يرجى ملء جميع الحقول المطلوبة', 'warning');
                return;
            }

            $.ajax({
                url: "{{ route('category.store') }}",
                type: "POST",
                data: formData,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success || data.id) {
                        Swal.fire('نجاح', 'تمت إضافة العائلة بنجاح', 'success');
                        $('#categoryModal').modal('hide');

                        // إضافة العائلة الجديدة للقائمة المنسدلة
                        const newOption = new Option(
                            data.category_name + ' - ' + data.category_num,
                            data.id
                        );
                        $('#modal_category_id').append(newOption);
                        $('#modal_category_id').val(data.id);
                        $('#modal_category_id').selectpicker('refresh');

                        // تحديث رقم الصنف تلقائياً
                        $('#modal_category_id').trigger('change');
                    } else {
                        Swal.fire('خطأ', 'حدث خطأ أثناء الحفظ', 'error');
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessage = '';
                        for (const field in errors) {
                            errorMessage += errors[field][0] + '\n';
                        }
                        Swal.fire('خطأ', errorMessage, 'error');
                    } else {
                        Swal.fire('خطأ', 'حدث خطأ أثناء الحفظ', 'error');
                    }
                }
            });
        }

        // حفظ وحدة جديدة
        function saveUnit() {
            const formData = {
                unit_name: $('#unit_name').val(),
                unit_num: $('#unit_num').val(),
            };

            if (!formData.unit_name || !formData.unit_num) {
                Swal.fire('تنبيه', 'يرجى ملء جميع الحقول المطلوبة', 'warning');
                return;
            }

            $.ajax({
                url: "{{ route('unit.store') }}",
                type: "POST",
                data: formData,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success || data.id) {
                        Swal.fire('نجاح', 'تمت إضافة الوحدة بنجاح', 'success');
                        $('#unitModal').modal('hide');

                        // إضافة الوحدة الجديدة للقائمة المنسدلة
                        const newOption = new Option(
                            data.unit_name + ' - ' + data.unit_num,
                            data.id
                        );
                        $('#modal_unit_id').append(newOption);
                        $('#modal_unit_id').val(data.id);
                        $('#modal_unit_id').selectpicker('refresh');
                    } else {
                        Swal.fire('خطأ', 'حدث خطأ أثناء الحفظ', 'error');
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessage = '';
                        for (const field in errors) {
                            errorMessage += errors[field][0] + '\n';
                        }
                        Swal.fire('خطأ', errorMessage, 'error');
                    } else {
                        Swal.fire('خطأ', 'حدث خطأ أثناء الحفظ', 'error');
                    }
                }
            });
        }

        // تحميل بيانات الصنف للتعديل
        function editItem(id) {
            $.ajax({
                url: "{{ route('item.show', ':id') }}".replace(':id', id),
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        $('#itemModalTitle').text('تعديل الصنف');
                        $('#item_id').val(data.item.id);
                        $('#modal_category_id').val(data.item.category_id);
                        $('#modal_unit_id').val(data.item.unit_id);
                        $('#modal_item_name').val(data.item.item_name);
                        $('#modal_item_num').val(data.item.item_num);
                        $('#modal_open_balance').val(data.item.open_balance);
                        $('#modal_low_limit').val(data.item.low_limit);

                        $('#modal_category_id').selectpicker('refresh');
                        $('#modal_unit_id').selectpicker('refresh');

                        $('#itemModal').modal('show');
                    } else {
                        Swal.fire('خطأ', data.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('خطأ', 'حدث خطأ أثناء تحميل البيانات', 'error');
                }
            });
        }

        // حفظ الصنف (إضافة أو تعديل)
        function saveItem() {
            const itemId = $('#item_id').val();
            const url = itemId ? "{{ route('item.update', ':id') }}".replace(':id', itemId) : "{{ route('item.store') }}";
            const method = itemId ? 'PUT' : 'POST';

            const formData = {
                category_id: $('#modal_category_id').val(),
                unit_id: $('#modal_unit_id').val(),
                item_name: $('#modal_item_name').val(),
                item_num: $('#modal_item_num').val(),
                open_balance: $('#modal_open_balance').val(),
                low_limit: $('#modal_low_limit').val(),
            };

            // التحقق من الحقول المطلوبة
            if (!formData.category_id || !formData.unit_id || !formData.item_name || !formData.item_num || !formData.open_balance || !formData.low_limit) {
                Swal.fire('تنبيه', 'يرجى ملء جميع الحقول المطلوبة', 'warning');
                return;
            }

            $.ajax({
                url: url,
                type: method,
                data: formData,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        Swal.fire('نجاح', data.message, 'success');
                        $('#itemModal').modal('hide');
                        location.reload(); // إعادة تحميل الصفحة لعرض البيانات المحدثة
                    } else {
                        Swal.fire('خطأ', data.message, 'error');
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessage = '';
                        for (const field in errors) {
                            errorMessage += errors[field][0] + '\n';
                        }
                        Swal.fire('خطأ', errorMessage, 'error');
                    } else {
                        Swal.fire('خطأ', 'حدث خطأ أثناء الحفظ', 'error');
                    }
                }
            });
        }

        // حذف الصنف
        function deleteItem(id) {
            Swal.fire({
                title: 'تأكيد الحذف',
                text: 'هل تريد حذف هذا الصنف؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('item.destroy', ':id') }}".replace(':id', id),
                        type: "DELETE",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            if (data.success) {
                                Swal.fire('تم الحذف', data.message, 'success');
                                location.reload();
                            } else {
                                Swal.fire('خطأ', data.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('خطأ', 'حدث خطأ أثناء الحذف', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
