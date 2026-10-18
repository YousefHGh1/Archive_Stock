<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="theme-color" content="#1E1E2D">
    <title>تسجيل الدخول | بلدية جباليا النزلة</title>
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #3699FF;
            --primary-dark: #187DE4;
            --dark: #181C32;
            --text: #3F4254;
            --muted: #7E8299;
            --border: #E4E6EF;
            --soft: #F3F6F9;
            --danger: #F64E60;
            --success: #1BC5BD;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body { font-family: 'Cairo', sans-serif; color: var(--text); background: #F2F4F9; -webkit-font-smoothing: antialiased; }
        a { color: var(--primary); text-decoration: none; }

        .login { min-height: 100vh; display: flex; }

        /* ===== Brand side ===== */
        .login-brand {
            position: relative;
            flex: 0 0 44%;
            max-width: 620px;
            overflow: hidden;
            color: #fff;
            background: linear-gradient(160deg, #1E1E2D 0%, #151521 55%, #12121C 100%);
            padding: 3rem 3.25rem;
            display: flex;
            flex-direction: column;
        }
        .login-brand::before, .login-brand::after { content: ""; position: absolute; border-radius: 50%; pointer-events: none; }
        .login-brand::before { width: 420px; height: 420px; background: radial-gradient(circle, rgba(54, 153, 255, .35) 0%, rgba(54, 153, 255, 0) 70%); top: -140px; left: -140px; }
        .login-brand::after { width: 360px; height: 360px; background: radial-gradient(circle, rgba(137, 80, 252, .28) 0%, rgba(137, 80, 252, 0) 70%); bottom: -120px; right: -100px; }
        .login-brand > * { position: relative; z-index: 1; }
        .brand-logo img { max-width: 230px; height: auto; }
        .brand-copy { margin: auto 0; padding: 2.5rem 0; }
        .brand-badge { display: inline-flex; align-items: center; gap: .4rem; font-size: .85rem; font-weight: 600; color: #A2A3B7; background: rgba(255, 255, 255, .06); border: 1px solid rgba(255, 255, 255, .08); padding: .3rem .8rem; border-radius: 30px; margin-bottom: 1.25rem; }
        .brand-badge::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: var(--success); box-shadow: 0 0 0 3px rgba(27, 197, 189, .25); }
        .brand-copy h1 { font-size: 2.2rem; font-weight: 800; line-height: 1.35; margin-bottom: .75rem; }
        .brand-copy h1 span { color: var(--primary); }
        .brand-copy p { color: #A2A3B7; font-size: 1.05rem; line-height: 1.8; max-width: 440px; }
        .features { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; margin-top: 2rem; max-width: 460px; }
        .features li { display: flex; align-items: center; gap: .65rem; background: rgba(255, 255, 255, .04); border: 1px solid rgba(255, 255, 255, .06); border-radius: 12px; padding: .75rem .85rem; font-weight: 600; font-size: .95rem; }
        .features svg { width: 20px; height: 20px; flex-shrink: 0; color: var(--primary); }
        .brand-foot { color: #6C7293; font-size: .88rem; }

        /* ===== Form side ===== */
        .login-main { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem 1.25rem; }
        .login-card { width: 100%; max-width: 430px; background: #fff; border: 1px solid #EBEDF3; border-radius: 20px; padding: 2.5rem 2.25rem; box-shadow: 0 24px 60px -24px rgba(24, 28, 50, .18); }
        .mobile-logo { display: none; text-align: center; margin-bottom: 1.5rem; }
        .mobile-logo img { max-width: 190px; height: auto; }
        .login-card h2 { font-size: 1.65rem; font-weight: 800; color: var(--dark); margin-bottom: .25rem; }
        .login-card .subtitle { color: var(--muted); margin-bottom: 1.75rem; }

        .alert { display: flex; gap: .6rem; align-items: flex-start; border-radius: 12px; padding: .85rem 1rem; margin-bottom: 1.25rem; font-weight: 600; font-size: .95rem; }
        .alert svg { width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; }
        .alert-danger { background: #FFF5F8; color: #D9214E; border: 1px solid #FFE2E5; }
        .alert-success { background: #E8FFF3; color: #0BB783; border: 1px solid #C9F7F5; }

        .field { margin-bottom: 1.1rem; }
        .field label { display: block; font-weight: 700; color: var(--dark); margin-bottom: .45rem; font-size: .95rem; }
        .input-wrap { position: relative; }
        .input-wrap .icon { position: absolute; top: 50%; right: 14px; transform: translateY(-50%); width: 20px; height: 20px; color: #A1A5B7; pointer-events: none; transition: color .15s ease; }
        .input-wrap input {
            width: 100%;
            height: 52px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 0 46px 0 46px;
            font-family: inherit;
            font-size: 1rem;
            color: var(--dark);
            background: var(--soft);
            transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
            outline: none;
        }
        .input-wrap input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(54, 153, 255, .14); }
        .input-wrap input:focus ~ .icon { color: var(--primary); }
        .input-wrap.has-error input { border-color: var(--danger); background: #FFF5F8; }
        .toggle-pass { position: absolute; top: 50%; left: 8px; transform: translateY(-50%); width: 36px; height: 36px; border: 0; border-radius: 9px; background: transparent; color: #A1A5B7; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
        .toggle-pass:hover { background: #EBEDF3; color: var(--text); }
        .toggle-pass svg { width: 20px; height: 20px; }
        .caps-hint { display: none; margin-top: .4rem; font-size: .85rem; color: #FFA800; font-weight: 600; }

        .row-between { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin: .25rem 0 1.5rem; flex-wrap: wrap; }
        .check { display: inline-flex; align-items: center; gap: .5rem; cursor: pointer; font-weight: 600; color: var(--text); user-select: none; }
        .check input { width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer; }
        .link-btn { border: 0; background: none; color: var(--primary); font-family: inherit; font-weight: 700; font-size: .95rem; cursor: pointer; }
        .link-btn:hover { text-decoration: underline; }
        .forgot-note { display: none; background: var(--soft); border-radius: 12px; padding: .8rem 1rem; color: var(--text); font-size: .92rem; margin: -0.75rem 0 1.25rem; line-height: 1.7; }
        .forgot-note.show { display: block; }

        .btn-submit {
            width: 100%;
            height: 54px;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, #6993FF 100%);
            color: #fff;
            font-family: inherit;
            font-size: 1.08rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            box-shadow: 0 12px 24px -10px rgba(54, 153, 255, .75);
            transition: transform .12s ease, box-shadow .15s ease, opacity .15s ease;
        }
        .btn-submit:hover { box-shadow: 0 14px 28px -10px rgba(54, 153, 255, .9); }
        .btn-submit:active { transform: translateY(1px); }
        .btn-submit[disabled] { opacity: .8; cursor: progress; }
        .btn-submit svg { width: 20px; height: 20px; }
        .spinner { width: 20px; height: 20px; border: 2.5px solid rgba(255, 255, 255, .35); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; display: none; }
        .btn-submit.loading .spinner { display: inline-block; }
        .btn-submit.loading .btn-icon { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .login-foot { margin-top: 1.5rem; color: var(--muted); font-size: .88rem; text-align: center; }

        /* ===== Responsive ===== */
        @media (max-width: 991.98px) {
            .login-brand { display: none; }
            .mobile-logo { display: block; }
            .login-main { background: linear-gradient(180deg, #1E1E2D 0, #1E1E2D 200px, #F2F4F9 200px); }
            .mobile-logo img { filter: none; }
        }
        @media (max-width: 480px) {
            .login-card { padding: 1.75rem 1.25rem; border-radius: 16px; }
            .login-card h2 { font-size: 1.4rem; }
        }
    </style>

    <script>
        // مسح التخزين المؤقت وإلغاء Service Workers عند فتح صفحة الدخول
        if ('caches' in window) {
            caches.keys().then(function(names) {
                names.forEach(function(name) { caches.delete(name); });
            });
        }
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                registrations.forEach(function(registration) { registration.unregister(); });
            });
        }
    </script>
</head>

<body>
    @php
        $loginError = $errors->first('name') ?: $errors->first('password');
        if ($loginError) {
            $loginError = str_contains($loginError, 'Too many')
                ? 'محاولات كثيرة لتسجيل الدخول. يرجى المحاولة مرة أخرى بعد قليل.'
                : (str_contains($loginError, 'credentials') ? 'اسم المستخدم أو كلمة المرور غير صحيحة.' : $loginError);
        }
        $flash = session('error') ?: session('danger') ?: session('warning');
    @endphp

    <div class="login">

        {{-- ===== جانب الهوية ===== --}}
        <aside class="login-brand">
            <div class="brand-logo">
                <img src="{{ asset('assets/media/logos/logo-light.png') }}" alt="بلدية جباليا النزلة">
            </div>

            <div class="brand-copy">
                <span class="brand-badge">النظام الإداري الموحد</span>
                <h1>نظام <span>بلدية جباليا النزلة</span><br>للأرشيف والمخازن</h1>
                <p>منصة واحدة لإدارة المراسلات والأرشيف المركزي، وحركة المخازن والمحروقات، ومتابعة التقارير بسهولة.</p>

                <ul class="features">
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="4" rx="1"/><path d="M5 8v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8"/><path d="M10 12h4"/></svg>
                        الأرشيف المركزي
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/></svg>
                        إدارة المخازن
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 22V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v17"/><path d="M2 22h13"/><path d="M14 10h2a2 2 0 0 1 2 2v4a1.5 1.5 0 0 0 3 0V8l-3-3"/><path d="M6 8h5"/></svg>
                        المحروقات
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 15l4-4 3 3 5-6"/></svg>
                        التقارير والإحصائيات
                    </li>
                </ul>
            </div>

            <div class="brand-foot">© {{ date('Y') }} بلدية جباليا النزلة — جميع الحقوق محفوظة</div>
        </aside>

        {{-- ===== نموذج الدخول ===== --}}
        <main class="login-main">
            <div class="login-card">
                <div class="mobile-logo">
                    <img src="{{ asset('assets/login/logo_login.png') }}" alt="بلدية جباليا النزلة">
                </div>

                <h2>مرحباً بعودتك 👋</h2>
                <p class="subtitle">سجّل الدخول للمتابعة إلى لوحة التحكم</p>

                @if ($loginError || $flash)
                    <div class="alert alert-danger" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                        <span>{{ $loginError ?: $flash }}</span>
                    </div>
                @elseif (session('status') || session('success'))
                    <div class="alert alert-success" role="status">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                        <span>{{ session('status') ?: session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="login_form" novalidate>
                    @csrf

                    <div class="field">
                        <label for="name">اسم المستخدم</label>
                        <div class="input-wrap {{ $errors->has('name') ? 'has-error' : '' }}">
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                autocomplete="username" placeholder="أدخل اسم المستخدم">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                        </div>
                    </div>

                    <div class="field">
                        <label for="password">كلمة المرور</label>
                        <div class="input-wrap {{ $errors->has('password') ? 'has-error' : '' }}">
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="••••••••">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                            <button type="button" class="toggle-pass" id="toggle_pass" aria-label="إظهار كلمة المرور" title="إظهار كلمة المرور">
                                <svg id="eye_on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg id="eye_off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M3 3l18 18"/><path d="M10.6 5.1A10.9 10.9 0 0 1 12 5c6.5 0 10 7 10 7a17.6 17.6 0 0 1-3.2 4.2"/><path d="M6.6 6.6C3.9 8.4 2 12 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                            </button>
                        </div>
                        <div class="caps-hint" id="caps_hint">⚠ زر الأحرف الكبيرة (Caps Lock) مفعّل</div>
                    </div>

                    <div class="row-between">
                        <label class="check">
                            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            تذكرني
                        </label>
                        <button type="button" class="link-btn" id="forgot_toggle">نسيت كلمة المرور؟</button>
                    </div>

                    <div class="forgot-note" id="forgot_note">
                        لإعادة تعيين كلمة المرور، يرجى التواصل مع مسؤول النظام.
                    </div>

                    <button type="submit" class="btn-submit" id="login_btn">
                        <span class="spinner" aria-hidden="true"></span>
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="M10 17l-5-5 5-5"/><path d="M5 12h12"/></svg>
                        <span class="btn-text">تسجيل الدخول</span>
                    </button>
                </form>
            </div>

            <div class="login-foot">بلدية جباليا النزلة · {{ date('Y') }}</div>
        </main>
    </div>

    <script>
        (function() {
            var form = document.getElementById('login_form');
            var btn = document.getElementById('login_btn');
            var pass = document.getElementById('password');
            var name = document.getElementById('name');

            // إذا كان الاسم محفوظاً، ضع المؤشر في كلمة المرور مباشرة
            if (name.value) {
                pass.focus();
            }

            // إظهار/إخفاء كلمة المرور
            document.getElementById('toggle_pass').addEventListener('click', function() {
                var show = pass.type === 'password';
                pass.type = show ? 'text' : 'password';
                document.getElementById('eye_on').style.display = show ? 'none' : '';
                document.getElementById('eye_off').style.display = show ? '' : 'none';
                this.setAttribute('aria-label', show ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور');
                pass.focus();
            });

            // تنبيه Caps Lock
            pass.addEventListener('keyup', function(e) {
                if (e.getModifierState) {
                    document.getElementById('caps_hint').style.display = e.getModifierState('CapsLock') ? 'block' : 'none';
                }
            });

            // ملاحظة نسيان كلمة المرور
            document.getElementById('forgot_toggle').addEventListener('click', function() {
                document.getElementById('forgot_note').classList.toggle('show');
            });

            // التحقق البسيط + منع الإرسال المزدوج
            form.addEventListener('submit', function(e) {
                var invalid = false;
                [name, pass].forEach(function(input) {
                    var empty = !input.value.trim();
                    input.parentElement.classList.toggle('has-error', empty);
                    invalid = invalid || empty;
                });

                if (invalid) {
                    e.preventDefault();
                    (name.value.trim() ? pass : name).focus();
                    return;
                }

                btn.disabled = true;
                btn.classList.add('loading');
                btn.querySelector('.btn-text').textContent = 'جاري تسجيل الدخول...';
            });

            // عند الرجوع للصفحة من سجل المتصفح أعد تفعيل الزر
            window.addEventListener('pageshow', function() {
                btn.disabled = false;
                btn.classList.remove('loading');
                btn.querySelector('.btn-text').textContent = 'تسجيل الدخول';
            });
        })();
    </script>
</body>

</html>
