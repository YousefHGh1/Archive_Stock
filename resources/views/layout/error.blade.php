@php
    $alertTypes = [
        'success' => ['class' => 'success', 'title' => 'تمت العملية بنجاح', 'icon' => 'M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.5-1.5z'],
        'error'   => ['class' => 'danger',  'title' => 'حدث خطأ',            'icon' => 'M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z'],
        'danger'  => ['class' => 'danger',  'title' => 'تنبيه خطير',         'icon' => 'M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z'],
        'warning' => ['class' => 'warning', 'title' => 'تحذير',              'icon' => 'M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z'],
        'info'    => ['class' => 'info',    'title' => 'معلومة',             'icon' => 'M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z'],
    ];
@endphp

@once
    <style>
        .app-alerts { direction: rtl; text-align: right; margin-bottom: 1rem; }
        .app-alert {
            position: relative; display: flex; align-items: flex-start; gap: 12px;
            padding: 14px 16px; margin-bottom: 10px;
            border: 0; border-right: 5px solid; border-radius: 10px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
            animation: appAlertIn .35s ease-out;
        }
        .app-alert .app-alert-icon { flex: 0 0 auto; width: 26px; height: 26px; margin-top: 2px; }
        .app-alert .app-alert-icon svg { width: 100%; height: 100%; fill: currentColor; }
        .app-alert .app-alert-body { flex: 1 1 auto; line-height: 1.7; }
        .app-alert .app-alert-title { display: block; font-weight: 700; margin-bottom: 2px; }
        .app-alert .app-alert-text { display: block; font-size: .95rem; }
        .app-alert ul { margin: 6px 0 0; padding: 0 18px 0 0; }
        .app-alert li { margin-bottom: 2px; }
        .app-alert .close {
            position: static; flex: 0 0 auto; padding: 0; margin: 0;
            font-size: 1.6rem; line-height: 1; opacity: .55; color: inherit;
            background: none; border: 0; cursor: pointer; text-shadow: none;
        }
        .app-alert .close:hover { opacity: 1; }
        .app-alert-success { background: #ecfdf3; border-color: #16a34a; color: #166534; }
        .app-alert-danger  { background: #fef2f2; border-color: #dc2626; color: #991b1b; }
        .app-alert-warning { background: #fffbeb; border-color: #f59e0b; color: #92400e; }
        .app-alert-info    { background: #eff6ff; border-color: #2563eb; color: #1e40af; }
        .app-alert.is-hiding { opacity: 0; transform: translateY(-6px); transition: all .3s ease; }
        @keyframes appAlertIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: none; } }
    </style>
@endonce

<div class="app-alerts">
    @foreach ($alertTypes as $key => $cfg)
        @if (Session::has($key))
            @php $messages = (array) Session::get($key); @endphp
            @foreach ($messages as $message)
                <div class="app-alert app-alert-{{ $cfg['class'] }} alert alert-{{ $cfg['class'] }} alert-block"
                     role="alert" data-autohide="{{ in_array($key, ['success', 'info']) ? 6000 : 0 }}">
                    <span class="app-alert-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $cfg['icon'] }}"/></svg>
                    </span>
                    <div class="app-alert-body">
                        <span class="app-alert-title">{{ $cfg['title'] }}</span>
                        <span class="app-alert-text">{{ $message }}</span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">&times;</button>
                </div>
            @endforeach
        @endif
    @endforeach

    @if ($errors->any())
        <div class="app-alert app-alert-danger alert alert-danger" role="alert" data-autohide="0">
            <span class="app-alert-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </span>
            <div class="app-alert-body">
                <span class="app-alert-title">تحقق من الأخطاء الموجودة:</span>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">&times;</button>
        </div>
    @endif
</div>

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function hideAlert(el) {
                el.classList.add('is-hiding');
                setTimeout(function () { el.remove(); }, 300);
            }
            document.querySelectorAll('.app-alert').forEach(function (el) {
                var delay = parseInt(el.dataset.autohide || '0', 10);
                if (delay > 0) setTimeout(function () { if (el.isConnected) hideAlert(el); }, delay);
                var btn = el.querySelector('.close');
                if (btn) btn.addEventListener('click', function (e) {
                    e.stopImmediatePropagation();
                    hideAlert(el);
                });
            });
        });
    </script>
@endonce