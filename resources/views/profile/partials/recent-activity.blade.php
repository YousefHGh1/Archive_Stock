<div class="card card-custom gutter-b profile-form-card">
    <div class="border-0 card-header">
        <div class="card-title">
            <h3 class="card-label font-weight-bolder text-dark">آخر النشاطات</h3>
        </div>
    </div>
    <div class="card-body pt-2">
        @if ($activities->isEmpty())
            <div class="text-center py-10">
                <i class="flaticon2-clock-1 font-size-h1 text-muted"></i>
                <p class="text-muted font-weight-bold mt-4 mb-0">لا توجد نشاطات بعد</p>
            </div>
        @else
            <div class="timeline timeline-5 mt-3">
                <div class="timeline-items">
                    @foreach ($activities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-media">
                                <i class="flaticon2-hourglass-1 text-primary"></i>
                            </div>
                            <div class="timeline-content" style="padding-right: 55px">
                                <div class="mb-1">{{ $activity->description }}</div>
                                <span class="text-muted font-weight-bold font-size-sm">
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
