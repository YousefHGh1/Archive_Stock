{{-- Load jQuery first --}}
<script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>

<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->

<!--begin::Global Theme Bundle (used by all pages)-->
<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>
<!--end::Global Theme Bundle-->

<!--begin::Page Vendors (used by this page)-->
<script src="{{asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
<!--end::Page Vendors-->

<!--begin::Page Scripts (used by this page)-->
<script src="{{asset('assets/js/pages/widgets.js')}}"></script>
<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
<script src="{{asset('assets/js/pages/features/miscellaneous/toastr.js')}}"></script>
<script src="{{asset('assets/js/pages/features/miscellaneous/sweetalert2.js')}}"></script>
<script src="{{asset('assets/js/pages/crud/ktdatatable/base/html-table.js')}}"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/mobile-aside.js') }}"></script>
<script src="{{ asset('js/app-theme.js') }}?v={{ @filemtime(public_path('js/app-theme.js')) }}"></script>
<script src="{{asset('assets/js/pages/crud/file-upload/image-input.js')}}"></script>
<!--end::Page Scripts-->

@yield('scripts')
<script>
    console.log('=== PWA Debug Info ===');
    console.log('Service Worker support:', 'serviceWorker' in navigator);
    console.log('HTTPS:', window.location.protocol === 'https:');
    console.log('Manifest check:', document.querySelector('link[rel="manifest"]'));

    // متغيرات للتحكم في تثبيت PWA
    let deferredPrompt;
    const installButton = document.createElement('button');
    installButton.textContent = 'تثبيت التطبيق';
    installButton.style.cssText = `
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 15px 25px;
        border-radius: 30px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        z-index: 9999;
        display: none;
        transition: all 0.3s ease;
    `;
    installButton.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-3px)';
        this.style.boxShadow = '0 6px 20px rgba(102, 126, 234, 0.6)';
    });
    installButton.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 4px 15px rgba(102, 126, 234, 0.4)';
    });
    document.body.appendChild(installButton);

    // الاستماع لحدث beforeinstallprompt
    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('🎯 beforeinstallprompt event fired!');
        e.preventDefault();
        deferredPrompt = e;
        installButton.style.display = 'block';

        if (typeof toastr !== 'undefined') {
            toastr.info('يمكنك تثبيت التطبيق على جوالك!', 'تثبيت التطبيق');
        }
    });

    // عند الضغط على زر التثبيت
    installButton.addEventListener('click', async () => {
        console.log('Install button clicked');
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log('User choice:', outcome);
            if (outcome === 'accepted') {
                if (typeof toastr !== 'undefined') {
                    toastr.success('جاري تثبيت التطبيق...', 'تم');
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.info('تم إلغاء التثبيت', 'إلغاء');
                }
            }
            deferredPrompt = null;
            installButton.style.display = 'none';
        }
    });

    // إخفاء الزر بعد التثبيت
    window.addEventListener('appinstalled', () => {
        console.log('🎉 App installed!');
        installButton.style.display = 'none';
        if (typeof toastr !== 'undefined') {
            toastr.success('تم تثبيت التطبيق بنجاح!', 'ممتاز');
        }
    });

    // إلغاء تسجيل Service Workers لتفادي مشاكل التخزين المؤقت
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(function(registrations) {
            registrations.forEach(function(registration) {
                registration.unregister();
                console.log('🗑️ Service Worker unregistered:', registration.scope);
            });
        });
    }

    // كشف حالة الاتصال بالإنترنت
    window.addEventListener('online', function() {
        console.log('🌐 Connection restored');
        if (typeof toastr !== 'undefined') {
            toastr.success('تم استعادة الاتصال بالإنترنت', 'متصل');
        }
    });

    window.addEventListener('offline', function() {
        console.log('⚠️ Connection lost');
        if (typeof toastr !== 'undefined') {
            toastr.warning('أنت غير متصل بالإنترنت', 'غير متصل');
        }
    });

    // عرض معلومات PWA بعد 3 ثواني
    setTimeout(() => {
        console.log('=== PWA Status Check ===');
        console.log('Install button visible:', installButton.style.display === 'block');
        console.log('URL:', window.location.href);
        console.log('Protocol:', window.location.protocol);
    }, 3000);
</script>