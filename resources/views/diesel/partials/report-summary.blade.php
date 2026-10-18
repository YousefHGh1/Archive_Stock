{{--
    ملخص الكميات الشهرية والمتبقي لكل نوع محروقات
    المتغيرات: $dieselData, $typesfuel, $remainingByType, $totalRemaining, $fmt, $movementLabel
--}}
@php
    $monthNames = [1 => 'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
    $monthsMax = max(1, (float) collect($dieselData)->max());
    $currentMonth = (int) date('n');
@endphp

<div class="fr-card">
    <div class="fr-card-header">
        <h3 class="fr-card-title"><i class="la la-chart-bar"></i> كميات {{ $movementLabel }} الشهرية لسنة {{ date('Y') }}</h3>
    </div>
    <div class="fr-card-body">
        <div class="fr-months">
            @for ($i = 1; $i <= 12; $i++)
                @php $monthTotal = (float) ($dieselData[$i] ?? 0); @endphp
                <div class="fr-month {{ $i === $currentMonth ? 'is-current' : '' }} {{ $monthTotal == 0 ? 'is-empty' : '' }}">
                    <div class="fr-month-name">
                        <span>{{ $monthNames[$i] }}</span>
                        <span>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="fr-month-value">{{ $fmt($monthTotal) }} <small class="text-muted">لتر</small></div>
                    <div class="fr-bar"><span style="width: {{ round($monthTotal / $monthsMax * 100, 1) }}%"></span></div>
                </div>
            @endfor
        </div>
    </div>
</div>

<div class="fr-card">
    <div class="fr-card-header">
        <h3 class="fr-card-title"><i class="la la-balance-scale"></i> المتبقي حسب نوع المحروقات</h3>
    </div>
    <div class="fr-card-body">
        <div class="fr-types">
            @foreach ($typesfuel as $type)
                @php $remaining = $remainingByType[$type->id] ?? 0; @endphp
                <div class="fr-type">
                    <div class="fr-type-name">المتبقي من {{ $type->name }}</div>
                    <div class="fr-type-value {{ $remaining < 0 ? 'is-negative' : '' }}">{{ $fmt($remaining) }} <small class="text-muted">لتر</small></div>
                </div>
            @endforeach
            <div class="fr-type is-total">
                <div class="fr-type-name">إجمالي المتبقي</div>
                <div class="fr-type-value">{{ $fmt($totalRemaining) }} <small>لتر</small></div>
            </div>
        </div>
    </div>
</div>
