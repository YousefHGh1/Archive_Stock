@extends('layout.master')

@section('title')
    تفاصيل العملية
@endsection

@section('page_title')
    لوحة التحكم
@endsection
@section('sub_main')
    عمليات المركبات
@endsection
@section('sub_title')
    صفحة العرض
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label"> تفاصيل عملية المركبة <i class="mr-2"></i></h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('vehicle-operations.index') }}" class="btn btn-info font-weight-bolder">
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
                                </span>عرض عمليات المركبات</a>
                        </div>
                    </div>

                    <div class="card-body" style="margin:20px;">
                        <div class="pb-5 form-group row">
                            <label class="col-lg-2 col-form-label text-lg-right">نوع العملية:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="{{ $operation->type_label }}" disabled>
                            </div>
                        </div>

                        <div class="pb-5 form-group row">
                            <label class="col-lg-2 col-form-label text-lg-right">المركبة:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="{{ $operation->vehicle->plate_number ?? '—' }} ({{ $operation->vehicle->vehicle_type ?? '—' }})" disabled>
                            </div>
                        </div>

                        <div class="pb-5 form-group row">
                            <label class="col-lg-2 col-form-label text-lg-right">السائق:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="{{ $operation->driver->name ?? '—' }}" disabled>
                            </div>
                        </div>

                        @if($operation->diesel_export_id)
                            <div class="pb-5 form-group row">
                                <label class="col-lg-2 col-form-label text-lg-right">سند الصادر:</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" value="{{ $operation->dieselExport->date ?? '—' }} - رقم الإيصال: {{ $operation->dieselExport->voucher ?? '—' }} - الكمية: {{ $operation->dieselExport->quantity ?? '—' }} لتر" disabled>
                                </div>
                            </div>
                        @endif

                        @if($operation->invoice_export_id && $operation->invoiceExport)
                            <div class="pb-5 form-group row">
                                <label class="col-lg-2 col-form-label text-lg-right">سند الصرف من المخازن:</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" value="رقم {{ $operation->invoiceExport->voucher_no ?? '—' }} - التاريخ: {{ $operation->invoiceExport->voucher_date ?? '—' }}" disabled>
                                </div>
                            </div>

                            @if($operation->invoiceExport->InvoiceExport_product && $operation->invoiceExport->InvoiceExport_product->count() > 0)
                                <div class="pb-5 form-group row">
                                    <label class="col-lg-2 col-form-label text-lg-right">تفاصيل الأصناف:</label>
                                    <div class="col-lg-10">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>رقم الصنف</th>
                                                        <th>اسم الصنف</th>
                                                        <th>العدد/الكمية</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($operation->invoiceExport->InvoiceExport_product as $product)
                                                        <tr>
                                                            <td>{{ $product->item->item_num ?? '—' }}</td>
                                                            <td>{{ $product->item->item_name ?? '—' }}</td>
                                                            <td>{{ $product->quantity ?? '—' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="pb-5 form-group row">
                            <label class="col-lg-2 col-form-label text-lg-right">اللترات:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="{{ $operation->liters ?? '—' }}" disabled>
                            </div>
                        </div>

                        <div class="pb-5 form-group row">
                            <label class="col-lg-2 col-form-label text-lg-right">التكلفة:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="{{ $operation->maintenance_cost ?? '—' }}" disabled>
                            </div>
                        </div>

                        <div class="pb-5 form-group row">
                            <label class="col-lg-2 col-form-label text-lg-right">تاريخ العملية:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="{{ $operation->operation_date->format('Y-m-d') }}" disabled>
                            </div>
                        </div>

                        <div class="pb-5 form-group row">
                            <label class="col-lg-2 col-form-label text-lg-right">أنشأها:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="{{ $operation->creator->name ?? '—' }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label text-lg-right">ملاحظات:</label>
                            <div class="col-lg-8">
                                <textarea rows="3" class="form-control" disabled>{{ $operation->notes ?? '—' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card-->

            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
