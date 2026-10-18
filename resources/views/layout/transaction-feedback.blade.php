@php
    $message = Session::get('success');
    if (!$message) {
        $infoMessage = Session::get('info');
        if (is_string($infoMessage) && str_contains($infoMessage, 'تعديل') && str_contains($infoMessage, 'بنجاح')) {
            $message = $infoMessage;
        }
    }
@endphp

<div
    id="transaction-feedback"
    class="transaction-feedback"
    data-feedback-message="{{ $message ?? '' }}"
    data-feedback-type="{{ Session::get('success_type', 'generic') }}"
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
        </div>
        <p class="transaction-feedback__eyebrow" data-feedback-eyebrow>تمت العملية</p>
        <h2 id="transaction-feedback-title" class="transaction-feedback__title" data-feedback-title>تمت العملية بنجاح</h2>
        <p id="transaction-feedback-message" class="transaction-feedback__message" data-feedback-text>{{ $message ?? '' }}</p>
        <button type="button" class="btn btn-primary transaction-feedback__confirm" data-feedback-confirm>حسنًا</button>
    </section>
</div>
