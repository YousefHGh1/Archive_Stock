/**
 * App Theme — سلوكيات مشتركة لكل الصفحات
 * 1) تمييز الصفحة الحالية في السايدبار وفتح القوائم الأب
 * 2) البحث السريع عن الصفحات من الهيدر
 */
(function () {
    var aside = document.getElementById('kt_aside');

    function normalize(path) {
        return (path || '/').replace(/\/+$/, '') || '/';
    }

    function menuLinks() {
        if (!aside) {
            return [];
        }

        return Array.prototype.filter.call(aside.querySelectorAll('a.menu-link[href]'), function (link) {
            var href = link.getAttribute('href') || '';
            return href && href !== '#' && href.indexOf('javascript:') !== 0;
        });
    }

    /* ===== 1) الصفحة الحالية ===== */
    function highlightActive() {
        var current = normalize(window.location.pathname);
        var best = null;
        var bestScore = 0;

        menuLinks().forEach(function (link) {
            var url;
            try {
                url = new URL(link.href, window.location.origin);
            } catch (e) {
                return;
            }

            if (url.origin !== window.location.origin) {
                return;
            }

            var path = normalize(url.pathname);
            var score = 0;

            if (path === current) {
                score = 10000 + path.length;
            } else if (path !== '/' && current.indexOf(path + '/') === 0) {
                // مثال: /diesel/5/edit يطابق رابط /diesel
                score = path.length;
            }

            if (score > bestScore) {
                bestScore = score;
                best = link;
            }
        });

        if (!best) {
            return;
        }

        var item = best.closest('li.menu-item');
        if (item) {
            item.classList.add('menu-item-active');
        }

        // فتح كل القوائم الأب حتى يظهر الرابط
        var parent = item ? item.parentElement : null;
        while (parent && parent !== aside) {
            if (parent.matches && parent.matches('li.menu-item-submenu')) {
                parent.classList.add('menu-item-open', 'menu-item-here');
            }
            parent = parent.parentElement;
        }

        // تمرير السايدبار حتى يظهر الرابط الحالي
        setTimeout(function () {
            var wrapper = document.getElementById('kt_aside_menu');
            if (wrapper && best.getBoundingClientRect) {
                var linkTop = best.getBoundingClientRect().top;
                var wrapperTop = wrapper.getBoundingClientRect().top;
                if (linkTop - wrapperTop > wrapper.clientHeight - 80) {
                    wrapper.scrollTop += linkTop - wrapperTop - wrapper.clientHeight / 2;
                }
            }
        }, 300);
    }

    /* ===== 2) البحث السريع ===== */
    function buildIndex() {
        var seen = {};
        var index = [];

        menuLinks().forEach(function (link) {
            if (link.closest('li.menu-item-parent')) {
                return;
            }

            var textEl = link.querySelector('.menu-text');
            var title = (textEl ? textEl.textContent : link.textContent).replace(/\s+/g, ' ').trim();
            if (!title || seen[link.href]) {
                return;
            }
            seen[link.href] = true;

            // مسار القائمة (الأب > الابن)
            var trail = [];
            var node = link.closest('li.menu-item');
            node = node ? node.parentElement : null;
            while (node && node !== aside) {
                if (node.matches && node.matches('li.menu-item-submenu')) {
                    var toggleText = node.querySelector(':scope > .menu-link .menu-text');
                    if (toggleText) {
                        trail.unshift(toggleText.textContent.replace(/\s+/g, ' ').trim());
                    }
                }
                node = node.parentElement;
            }

            index.push({
                title: title,
                trail: trail.join(' › '),
                href: link.href,
                haystack: (trail.join(' ') + ' ' + title).toLowerCase()
            });
        });

        return index;
    }

    function escapeHtml(text) {
        return String(text).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function initQuickSearch() {
        var input = document.getElementById('app_quick_search_input');
        var results = document.getElementById('app_quick_search_results');
        if (!input || !results) {
            return;
        }

        var index = null;

        function render() {
            index = index || buildIndex();
            var terms = input.value.toLowerCase().trim().split(/\s+/).filter(Boolean);

            if (!terms.length) {
                results.innerHTML = '<div class="py-4 text-center text-muted">اكتب اسم الصفحة للبحث بين ' + index.length + ' صفحة</div>';
                return;
            }

            var matches = index.filter(function (entry) {
                return terms.every(function (term) {
                    return entry.haystack.indexOf(term) !== -1;
                });
            }).slice(0, 12);

            if (!matches.length) {
                results.innerHTML = '<div class="py-4 text-center text-muted"><i class="la la-search-minus" style="font-size:2rem;display:block;opacity:.5"></i>لا توجد نتائج</div>';
                return;
            }

            results.innerHTML = matches.map(function (entry, i) {
                return '<a class="app-qs-item' + (i === 0 ? ' is-active' : '') + '" href="' + escapeHtml(entry.href) + '">' +
                    '<span class="app-qs-icon"><i class="la la-angle-left"></i></span>' +
                    '<span class="app-qs-text"><b>' + escapeHtml(entry.title) + '</b>' +
                    (entry.trail ? '<small>' + escapeHtml(entry.trail) + '</small>' : '') + '</span></a>';
            }).join('');
        }

        input.addEventListener('input', render);

        input.addEventListener('keydown', function (event) {
            var items = results.querySelectorAll('.app-qs-item');
            if (!items.length) {
                return;
            }

            var active = results.querySelector('.app-qs-item.is-active');
            var idx = Array.prototype.indexOf.call(items, active);

            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                idx = event.key === 'ArrowDown' ? Math.min(items.length - 1, idx + 1) : Math.max(0, idx - 1);
                items.forEach(function (el) { el.classList.remove('is-active'); });
                items[idx].classList.add('is-active');
                items[idx].scrollIntoView({ block: 'nearest' });
            } else if (event.key === 'Enter' && active) {
                event.preventDefault();
                window.location.href = active.href;
            }
        });

        // التركيز على الحقل عند فتح القائمة
        if (window.jQuery) {
            window.jQuery('#kt_quick_search_toggle').on('shown.bs.dropdown', function () {
                render();
                setTimeout(function () { input.focus(); }, 50);
            });
        }

        // اختصار لوحة المفاتيح: Ctrl + K
        document.addEventListener('keydown', function (event) {
            if ((event.ctrlKey || event.metaKey) && (event.key === 'k' || event.key === 'K')) {
                event.preventDefault();
                if (window.jQuery) {
                    window.jQuery('#kt_quick_search_toggle > [data-toggle="dropdown"]').dropdown('toggle');
                }
            }
        });

        // منع إغلاق القائمة عند النقر داخلها
        results.parentElement.addEventListener('click', function (event) {
            if (!event.target.closest('a')) {
                event.stopPropagation();
            }
        });
    }

    function init() {
        highlightActive();
        initQuickSearch();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
