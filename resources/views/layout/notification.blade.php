@php
    $authUser = Auth::user();
    $unreadNotifications = $authUser ? $authUser->unreadNotifications : collect();

    // كل تبويب يعرض إشعارات القسم الخاص به فقط
    $notificationGroups = [
        'archiveNot' => ['label' => 'الأرشيف', 'icon' => 'la-archive'],
        'computerNot' => ['label' => 'الحاسوب', 'icon' => 'la-desktop'],
        'jibayaNot' => ['label' => 'الجباية', 'icon' => 'la-coins'],
        'censorshipNot' => ['label' => 'الرقابة', 'icon' => 'la-search-plus'],
        'legalNot' => ['label' => 'الشؤون', 'icon' => 'la-balance-scale'],
    ];

    $groupedNotifications = collect($notificationGroups)->map(function ($group, $prefix) use ($unreadNotifications) {
        return $unreadNotifications->filter(function ($notification) use ($prefix) {
            return isset($notification->data[$prefix . '_id']) || isset($notification->data[$prefix . '_create']);
        });
    });

    $userName = $authUser->employee_name ?? '';
    $userInitial = $userName !== '' ? mb_strtoupper(mb_substr(trim($userName), 0, 1)) : '؟';
@endphp

<div id="kt_header" class="header header-fixed">
    <div class="container-fluid d-flex align-items-center justify-content-between">

        {{-- ===== عنوان الصفحة + مسار التنقل ===== --}}
        <div class="d-flex align-items-center" style="min-width: 0">
            <button type="button" class="mobile-aside-toggle" id="mobile_aside_toggle" aria-controls="kt_aside"
                aria-expanded="false" aria-label="فتح القائمة">
                <span class="mobile-aside-toggle-icon" aria-hidden="true">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>

            <div class="app-header-title" id="kt_subheader">
                <h1>{{ trim($__env->yieldContent('title')) ?: trim($__env->yieldContent('page_title')) }}</h1>
                <ul class="app-breadcrumb">
                    <li><a href="{{ url('dashboard') }}"><i class="la la-home"></i> @yield('page_title', 'لوحة التحكم')</a></li>
                    @hasSection('sub_main')
                        <li>@yield('sub_main')</li>
                    @endif
                    @hasSection('sub_title')
                        <li>@yield('sub_title')</li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- ===== Topbar ===== --}}
        <div class="topbar d-flex align-items-center">

            {{-- البحث السريع في صفحات النظام --}}
            <div class="dropdown" id="kt_quick_search_toggle">
                <div class="app-icon-btn" data-toggle="dropdown" data-offset="10px,0px" title="بحث سريع">
                    <span class="svg-icon svg-icon-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
                            </g>
                        </svg>
                    </span>
                </div>
                <div class="p-0 m-0 dropdown-menu dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                    <div class="p-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="la la-search"></i></span>
                            </div>
                            <input type="text" class="form-control" id="app_quick_search_input"
                                placeholder="ابحث عن صفحة... مثال: المحروقات" autocomplete="off" />
                        </div>
                        <div class="mt-3 app-quick-search-results" id="app_quick_search_results"></div>
                    </div>
                </div>
            </div>

            {{-- الإشعارات --}}
            <div class="dropdown">
                <div class="app-icon-btn" data-toggle="dropdown" data-offset="10px,0px" title="الإشعارات">
                    <span class="svg-icon svg-icon-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <path d="M17,12 L18.5,12 C19.3284271,12 20,12.6715729 20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 C4,12.6715729 4.67157288,12 5.5,12 L7,12 L7.5582739,6.97553494 C7.80974924,4.71225688 9.72279394,3 12,3 C14.2772061,3 16.1902508,4.71225688 16.4417261,6.97553494 L17,12 Z" fill="#000000" />
                                <rect fill="#000000" opacity="0.3" x="10" y="16" width="4" height="4" rx="2" />
                            </g>
                        </svg>
                    </span>
                    @if ($unreadNotifications->count())
                        <span class="app-badge">{{ $unreadNotifications->count() > 99 ? '99+' : $unreadNotifications->count() }}</span>
                    @endif
                </div>

                <div class="p-0 m-0 dropdown-menu dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                    <div class="app-notif-head">
                        <h4>
                            <i class="la la-bell"></i> الإشعارات
                            <span class="label label-inline font-weight-bold" style="background: rgba(255,255,255,.2); color: #fff">{{ $unreadNotifications->count() }} جديد</span>
                        </h4>
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($notificationGroups as $prefix => $group)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active show' : '' }}" data-toggle="tab"
                                        href="#topbar_notifications_{{ $prefix }}">
                                        {{ $group['label'] }}
                                        @if ($groupedNotifications[$prefix]->count())
                                            <small>({{ $groupedNotifications[$prefix]->count() }})</small>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="tab-content">
                        @foreach ($notificationGroups as $prefix => $group)
                            <div class="px-5 py-3 tab-pane {{ $loop->first ? 'active show' : '' }}"
                                id="topbar_notifications_{{ $prefix }}" role="tabpanel">
                                <div class="scroll" data-scroll="true" data-height="300" data-mobile-height="220">
                                    @forelse ($groupedNotifications[$prefix] as $notification)
                                        @php
                                            $data = $notification->data;
                                            $link = isset($data[$prefix . '_id']) ? route($prefix . '.show', $data[$prefix . '_id']) : null;
                                        @endphp
                                        <div class="app-notif-item">
                                            <div class="symbol symbol-40 symbol-light-primary flex-shrink-0">
                                                <span class="symbol-label"><i class="la {{ $group['icon'] }} text-primary icon-xl"></i></span>
                                            </div>
                                            <div class="d-flex flex-column" style="min-width: 0">
                                                @if ($link)
                                                    <a href="{{ $link }}" class="text-dark text-hover-primary font-weight-bold">{{ $data[$prefix . '_create'] ?? 'إشعار جديد' }}</a>
                                                @else
                                                    <span class="text-dark font-weight-bold">{{ $data[$prefix . '_create'] ?? 'إشعار جديد' }}</span>
                                                @endif
                                                @if (!empty($data[$prefix . '_file']))
                                                    <span class="text-muted">{{ $data[$prefix . '_file'] }}</span>
                                                @endif
                                                @if (!empty($data[$prefix . '_description']))
                                                    <span class="text-muted font-size-sm">{{ $data[$prefix . '_description'] }}</span>
                                                @endif
                                                <span class="text-muted font-size-xs mt-1"><i class="la la-clock"></i> {{ optional($notification->created_at)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="app-notif-empty">
                                            <i class="la la-bell-slash"></i>
                                            لا توجد إشعارات جديدة في {{ $group['label'] }}
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- المستخدم --}}
            <div class="app-user" id="kt_quick_user_toggle">
                <div class="app-user-meta">
                    <small>مرحباً،</small>
                    <b>{{ $userName }}</b>
                </div>
                <span class="app-avatar">{{ $userInitial }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ===== لوحة المستخدم ===== --}}
<div id="kt_quick_user" class="p-8 offcanvas offcanvas-right">
    <div class="pb-5 offcanvas-header d-flex align-items-center justify-content-between">
        <h3 class="m-0 font-weight-bold">حسابي</h3>
        <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
            <i class="ki ki-close icon-xs text-muted"></i>
        </a>
    </div>

    <div class="offcanvas-content">
        <div class="app-user-card">
            <span class="app-avatar app-avatar-lg">{{ $userInitial }}</span>
            <div style="min-width: 0">
                <b>{{ $userName }}</b>
                @if ($authUser && $authUser->subSection)
                    <small>{{ $authUser->subSection->name }}</small>
                @endif
                <small>{{ $authUser->email ?? '' }}</small>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ url('/profile') }}" class="app-user-link">
                <span class="app-user-link-icon bg-light-warning"><i class="la la-user-edit text-warning"></i></span>
                <span>
                    <b>الحساب الشخصي</b>
                    <small>تعديل البيانات الشخصية</small>
                </span>
            </a>
        </div>

        <div class="separator separator-dashed my-6"></div>

        <a href="javascript:void(0)" onclick="clearCacheAndLogoutNotification()"
            class="btn btn-light-danger btn-block font-weight-bolder">
            <i class="la la-sign-out-alt"></i> {{ __('تسجيل الخروج') }}
        </a>
    </div>
</div>

<script>
    function clearCacheAndLogoutNotification() {
        var logoutUrl = "{{ route('logout.get') }}";

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                registrations.forEach(function(registration) {
                    registration.unregister();
                });
            });
        }

        if ('caches' in window) {
            caches.keys().then(function(cacheNames) {
                return Promise.all(cacheNames.map(function(cacheName) {
                    return caches.delete(cacheName);
                }));
            }).then(function() {
                window.location.href = logoutUrl;
            }).catch(function() {
                window.location.href = logoutUrl;
            });
        } else {
            window.location.href = logoutUrl;
        }
    }
</script>
