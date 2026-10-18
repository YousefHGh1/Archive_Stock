@extends('layout.master')

@section('title')
    السندات التي تم تخطيها
@endsection

@section('page_title')
    لوحة التحكم
@endsection

@section('sub_main')
    الفواتير
@endsection

@section('sub_title')
    السندات التي تم تخطيها (صرف)
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
                    <h3 class="card-label">السندات التي تم تخطيها (سندات صرف فارغة للمراقبة)</h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="{{ url('/inventory/invoice_export') }}" class="btn btn-primary font-weight-bolder">
                        <span class="svg-icon svg-icon-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path d="M12 22 C6.4771525 22 2 17.5228475 2 12 C2 6.4771525 6.4771525 2 12 2 C17.5228475 2 22 6.4771525 22 12 C22 17.5228475 17.5228475 22 12 22 Z M12 20 C16.418278 20 20 16.418278 20 12 C20 7.581722 16.418278 4 12 4 C7.581722 4 4 7.581722 4 12 C4 16.418278 7.581722 20 12 20 Z" fill="#000000" opacity="0.3" />
                                    <path d="M10 8 L14 8 L14 16 L10 16 L10 8 Z M8 8 L9 8 L9 16 L8 16 L8 8 Z M15 8 L16 8 L16 16 L15 16 L15 8 Z" fill="#000000" />
                                </g>
                            </svg>
                        </span>العودة لسندات الصرف</a>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table id="example" class="table dt-responsive" style="width:100%">
                    <thead class="thead_dark">
                        <tr>
                            <th width="8%">رقم السند</th>
                            <th width="12%">تاريخ السند</th>
                            <th width="12%">رقم الإيصال</th>
                            <th width="15%">القسم الفرعي</th>
                            <th width="15%">المستلم</th>
                            <th width="20%">سبب التخطي</th>
                            <th width="18%">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skippedVouchers as $voucher)
                            <tr>
                                <td>{{ $voucher->voucher_no }}</td>
                                <td>{{ $voucher->voucher_date }}</td>
                                <td>{{ $voucher->invoice_no ?? '-' }}</td>
                                <td>
                                    @if($voucher->subSection_id)
                                        {{ App\Models\SubSection::find($voucher->subSection_id)->name ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $voucher->beneficiary ?? '-' }}</td>
                                <td>{{ $voucher->skip_reason ?? '-' }}</td>

                                <td data-field="Actions" data-autohide-disabled="false" aria-label="null"
                                    class="datatable-cell">
                                    <span style="overflow: visible; position: relative; width: 110px;">
                                        <a href="{{ url('/inventory/invoice_export/' . $voucher->id . '/edit') }}"
                                            class="btn btn-sm btn-clean btn-icon" title="تعديل السند"><i
                                                class="la la-edit"></i></a>
                                        <form method="POST"
                                            action="{{ url('/inventory/invoice_export' . '/' . $voucher->id) }}"
                                            accept-charset="UTF-8" style="display:inline">
                                            {{ method_field('DELETE') }} {{ csrf_field() }}
                                            <button type="submit" class="btn btn-sm btn-clean btn-icon"
                                                title="حذف السند"
                                                onclick="return confirm('هل تريد تأكيد عملية الحذف؟؟؟')"> <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 0 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                                                </svg></button>
                                        </form>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/pages/features/miscellaneous/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/data-json.js') }}" type="text/javascript"></script>
@endsection
