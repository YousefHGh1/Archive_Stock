@php
    $message = Session::get('success');
    $successType = Session::get('success_type', 'generic');
    $messageType = 'success';

    if (!$message) {
        $infoMessage = Session::get('info');
        if (is_string($infoMessage) && str_contains($infoMessage, 'تعديل') && str_contains($infoMessage, 'بنجاح')) {
            $message = $infoMessage;
            $successType = 'update';
        }
    }

    // Check for warning message
    if (!$message) {
        $warningMessage = Session::get('warning');
        if ($warningMessage) {
            $message = $warningMessage;
            $messageType = 'warning';
            $successType = 'warning';
        }
    }

    // Check for error message
    if (!$message) {
        $errorMessage = Session::get('error');
        if ($errorMessage) {
            $message = $errorMessage;
            $messageType = 'error';
            $successType = 'error';
        }
    }

    $kind = $successType;
    if (!in_array($kind, ['fuel-in', 'fuel-out', 'stock-in', 'stock-out', 'create', 'update', 'delete', 'warning', 'error'])) {
        $kind = 'generic';
    }
@endphp

<style>
    .transaction-feedback { position: fixed; inset: 0; z-index: 11000; display: grid; place-items: center; padding: 1rem; direction: rtl; }
    .transaction-feedback[hidden] { display: none !important; }
    .transaction-feedback__backdrop { position: absolute; inset: 0; background: rgba(24, 28, 50, .58); backdrop-filter: blur(3px); animation: transactionBackdropIn .18s ease-out both; }
    .transaction-feedback__dialog { position: relative; width: min(100%, 430px); max-height: min(90vh, 700px); overflow-y: auto; padding: 2.25rem 2rem 1.75rem; border: 1px solid rgba(255, 255, 255, .72); border-radius: 22px; background: #fff; box-shadow: 0 24px 80px rgba(24, 28, 50, .28); color: #3F4254; text-align: center; animation: transactionDialogIn .24s cubic-bezier(.2, .8, .2, 1) both; }
    .transaction-feedback__close { position: absolute; inset-block-start: .75rem; inset-inline-start: .75rem; display: grid; width: 38px; height: 38px; place-items: center; border: 0; border-radius: 50%; background: #F3F6F9; color: #7E8299; font-size: 1.75rem; line-height: 1; cursor: pointer; }
    .transaction-feedback__close:hover { background: #E1F0FF; color: #3699FF; }
    .transaction-feedback__visual { position: relative; display: grid; min-height: 150px; place-items: center; margin-bottom: 1rem; color: #3699FF; }
    .transaction-feedback__icon-wrapper { display: grid; place-items: center; width: 116px; height: 116px; }
    .transaction-feedback__fuel, .transaction-feedback__stock, .transaction-feedback__check, .transaction-feedback__warning, .transaction-feedback__error { display: none !important; width: 116px; height: 116px; animation: transactionIconIn .4s cubic-bezier(.2, .8, .2, 1) both; }
    .transaction-feedback__check { width: 80px; height: 80px; filter: drop-shadow(0 8px 14px rgba(27, 197, 189, .18)); }
    .transaction-feedback[data-kind="fuel-in"] .transaction-feedback__fuel, .transaction-feedback[data-kind="fuel-out"] .transaction-feedback__fuel, .transaction-feedback[data-kind="stock-in"] .transaction-feedback__stock, .transaction-feedback[data-kind="stock-out"] .transaction-feedback__stock, .transaction-feedback[data-kind="create"] .transaction-feedback__check, .transaction-feedback[data-kind="update"] .transaction-feedback__check, .transaction-feedback[data-kind="delete"] .transaction-feedback__check, .transaction-feedback[data-kind="generic"] .transaction-feedback__check, .transaction-feedback[data-kind="warning"] .transaction-feedback__warning, .transaction-feedback[data-kind="error"] .transaction-feedback__error { display: block !important; }
    .transaction-feedback[data-kind="fuel-in"] .transaction-feedback__liquid { transform-box: fill-box; transform-origin: center bottom; animation: transactionFuelIn .65s ease-out both; }
    .transaction-feedback[data-kind="fuel-out"] .transaction-feedback__liquid { transform-box: fill-box; transform-origin: center bottom; animation: transactionFuelOut .65s ease-out both; }
    .transaction-feedback[data-kind="fuel-in"] .transaction-feedback__wave, .transaction-feedback[data-kind="fuel-out"] .transaction-feedback__wave { animation: transactionWave 1.2s ease-in-out infinite; }
    .transaction-feedback[data-kind="stock-in"] .transaction-feedback__stock { animation: transactionStockIn .5s ease-out both; }
    .transaction-feedback[data-kind="stock-out"] .transaction-feedback__stock { animation: transactionStockOut .5s ease-out both; }
    @keyframes transactionIconIn { from { opacity: 0; transform: scale(0.6) rotate(-8deg); } to { opacity: 1; transform: scale(1) rotate(0); } }
    @keyframes transactionBackdropIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes transactionDialogIn { from { opacity: 0; transform: translateY(12px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    @keyframes transactionFuelIn { from { transform: scaleY(.55); } to { transform: scaleY(1); } }
    @keyframes transactionFuelOut { from { transform: scaleY(1); } to { transform: scaleY(.7); } }
    @keyframes transactionWave { from { transform: translateX(-2px); } to { transform: translateX(2px); } }
    @keyframes transactionStockIn { from { opacity: .45; transform: translateX(14px) scale(.92); } to { opacity: 1; transform: translateX(0) scale(1); } }
    @keyframes transactionStockOut { from { opacity: 1; transform: translateX(0) scale(1); } to { opacity: .65; transform: translateX(-10px) scale(.96); } }
</style>

<div
    id="transaction-feedback"
    class="transaction-feedback"
    data-kind="{{ $kind }}"
    data-feedback-message="{{ $message ?? '' }}"
    data-feedback-type="{{ $successType }}"
    hidden
>
    <div class="transaction-feedback__backdrop" data-feedback-close></div>
    <section
        class="transaction-feedback__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="transaction-feedback-title"
        aria-describedby="transaction-feedback-message"
        tabindex="-1"
    >
        <button class="transaction-feedback__close" type="button" aria-label="إغلاق" data-feedback-close>
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="transaction-feedback__visual" data-feedback-visual aria-hidden="true">
            <div class="transaction-feedback__icon-wrapper">
                <svg class="transaction-feedback__fuel" viewBox="0 0 120 120" fill="none">
                    <path d="M35 18h42v84H35z" fill="#F3F6F9" stroke="currentColor" stroke-width="5" />
                    <path d="M77 32h12l10 12v44a8 8 0 0 1-8 8h-5" stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M44 58h24v35H44z" fill="#FFA800" class="transaction-feedback__liquid" />
                    <path d="M44 58c7-5 16 5 24 0" stroke="#fff" stroke-width="3" class="transaction-feedback__wave" />
                    <path d="M46 27h20" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                </svg>
                <svg class="transaction-feedback__stock" viewBox="0 0 120 120" fill="none">
                    <path d="M18 48 60 25l42 23-42 23-42-23Z" fill="#E1F0FF" stroke="currentColor" stroke-width="4" stroke-linejoin="round" />
                    <path d="M18 48v45l42 23V71L18 48Z" fill="#3699FF" fill-opacity=".18" stroke="currentColor" stroke-width="4" stroke-linejoin="round" />
                    <path d="M102 48v45l-42 23V71l42-23Z" fill="#3699FF" fill-opacity=".3" stroke="currentColor" stroke-width="4" stroke-linejoin="round" />
                    <path d="M48 38 90 61M60 71v35" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                </svg>
                <svg class="transaction-feedback__check" viewBox="0 0 64 64" fill="none">
                    <circle cx="32" cy="32" r="29" fill="#C9F7F5" />
                    <path d="m18 33 9 9 19-21" stroke="#1BC5BD" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <svg class="transaction-feedback__warning" viewBox="0 0 64 64" fill="none">
                    <circle cx="32" cy="32" r="29" fill="#FFF4E5" />
                    <path d="M32 18v14m0 8v4" stroke="#FFA800" stroke-width="6" stroke-linecap="round" />
                </svg>
                <svg class="transaction-feedback__error" viewBox="0 0 64 64" fill="none">
                    <circle cx="32" cy="32" r="29" fill="#FFE5E5" />
                    <path d="M23 23l18 18m0-18L23 41" stroke="#F64E60" stroke-width="6" stroke-linecap="round" />
                </svg>
            </div>
        </div>
        <p class="transaction-feedback__eyebrow" data-feedback-eyebrow>{{ $messageType === 'warning' ? 'تحذير' : ($messageType === 'error' ? 'خطأ' : 'تمت العملية') }}</p>
        <h2 id="transaction-feedback-title" class="transaction-feedback__title" data-feedback-title>{{ $messageType === 'warning' ? 'تنبيه' : ($messageType === 'error' ? 'فشلت العملية' : 'تمت العملية بنجاح') }}</h2>
        <p id="transaction-feedback-message" class="transaction-feedback__message" data-feedback-text>{{ $message ?? '' }}</p>
        <button type="button" class="btn {{ $messageType === 'warning' ? 'btn-warning' : ($messageType === 'error' ? 'btn-danger' : 'btn-primary') }} transaction-feedback__confirm" data-feedback-confirm>حسنًا</button>
    </section>
</div>

<script>
    document.querySelectorAll('.app-alert, .alert, .swal2-container, [class*="toast"]').forEach(function(el){el.style.display='none';});
</script>
