@php
    $action = $action ?? '#';
    $showItem = $showItem ?? false;
    $method = $method ?? 'get';
@endphp
<div class="card-body1">
    <div class="mb-6 col-lg-12">
        <form action="{{ $action }}" method="{{ $method }}" class="pr-5 form-group">
            @csrf
            <div class="form-group row align-items-center">
                @if ($showItem)
                    <div class="pl-5 pr-5 col-lg-4">
                        <label class="m-2">الصنف:</label>
                        <select name="item_id" class="form-control">
                            <option value="">الكل</option>
                            @foreach ($items ?? [] as $item)
                                <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_num }} - {{ $item->item_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="pl-5 pr-5 col-lg-4">
                    <label class="m-2">المورد:</label>
                    <select name="supplier_id" class="form-control">
                        <option value="">الكل</option>
                        @foreach ($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->supplier_item_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pl-5 pr-5 col-lg-4">
                    <label class="m-2">الفترة:</label>
                    <div class="input-group">
                        <input name="start_date" type="date" class="form-control" value="{{ request('start_date') }}" />
                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                        <input name="end_date" type="date" class="form-control" value="{{ request('end_date') }}" />
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success btn-success--icon mr-2">
                        <i class="flaticon-search"></i> بحث
                    </button>
                    <a href="{{ $action }}" class="btn btn-danger btn-danger--icon">تنظيف البحث</a>
                </div>
            </div>
        </form>
    </div>
</div>