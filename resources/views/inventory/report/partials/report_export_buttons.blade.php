<div class="card-toolbar">
    <a class="btn btn-light-success mr-2" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">تصدير Excel</a>
    <a class="btn btn-light-danger" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">تصدير PDF</a>
</div>
