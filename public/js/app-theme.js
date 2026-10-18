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

    /* Keep each visible sibling submenu group in an accordion state. */
    function initSidebarAccordion() {
        if (!aside) {
            return;
        }

        var activeItem = aside.querySelector('.menu-item-active');
        var activeAncestors = [];
        var node = activeItem;

        while (node && node !== aside) {
            if (node.matches && node.matches('li.menu-item-submenu')) {
                activeAncestors.push(node);
                node.classList.add('app-sidebar-branch-current');
            }
            node = node.parentElement;
        }

        var submenus = Array.prototype.slice.call(
            aside.querySelectorAll('li.menu-item-submenu')
        );

        function setOpen(item, isOpen) {
            item.classList.toggle('menu-item-open', isOpen);
            item.classList.toggle('menu-item-here', isOpen);
            item.classList.toggle('open', isOpen);
            item.classList.toggle('app-sidebar-branch-open', isOpen);

            if (!isOpen) {
                item.classList.remove('menu-item-hover');
            }

            var submenu = item.querySelector(':scope > .menu-submenu');
            if (submenu) {
                submenu.style.display = isOpen ? 'block' : 'none';
            }
        }

        function siblingSubmenus(item) {
            if (!item.parentElement) {
                return [];
            }

            return Array.prototype.filter.call(item.parentElement.children, function (sibling) {
                return sibling !== item && sibling.matches('li.menu-item-submenu') &&
                    !sibling.hidden;
            });
        }

        function isOpen(item) {
            var submenu = item.querySelector(':scope > .menu-submenu');
            return item.classList.contains('menu-item-open') ||
                item.classList.contains('menu-item-hover') ||
                item.classList.contains('open') ||
                (submenu && submenu.style.display === 'block');
        }

        function closeSiblingsFor(target) {
            var toggle = target && target.closest ? target.closest('a.menu-link.menu-toggle') : null;
            var item = toggle && toggle.closest('li.menu-item-submenu');
            if (!item || !aside.contains(item) || !isOpen(item)) {
                return;
            }

            siblingSubmenus(item).forEach(function (sibling) {
                setOpen(sibling, false);
            });
        }

        submenus.forEach(function (item) {
            setOpen(item, activeAncestors.indexOf(item) !== -1);
        });

        aside.addEventListener('mouseover', function (event) {
            window.setTimeout(function () {
                closeSiblingsFor(event.target);
            }, 0);
        });
        aside.addEventListener('click', function (event) {
            window.setTimeout(function () {
                closeSiblingsFor(event.target);
            }, 0);
        });
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

    /* ===== البحث داخل القائمة الجانبية ===== */
    function initSidebarSearch() {
        var input = document.getElementById('app_sidebar_search_input');
        var clearButton = document.getElementById('app_sidebar_search_clear');
        var status = document.getElementById('app_sidebar_search_status');
        var emptyState = aside && aside.querySelector('.app-sidebar-search-empty');
        if (!aside || !input || !clearButton || !status || !emptyState) {
            return;
        }

        var menuItems = Array.prototype.slice.call(aside.querySelectorAll('.menu-nav li.menu-item'));
        var sections = Array.prototype.slice.call(aside.querySelectorAll('.menu-nav .menu-section'));
        var visibility = menuItems.concat(sections).map(function (element) {
            return { element: element, hidden: element.hidden };
        });

        function menuTrail(item) {
            var labels = [];
            var node = item.parentElement;

            while (node && node !== aside) {
                if (node.matches && node.matches('li.menu-item-submenu')) {
                    var parentLabel = node.querySelector(':scope > .menu-link .menu-text');
                    if (parentLabel) {
                        labels.unshift(parentLabel.textContent.replace(/\s+/g, ' ').trim());
                    }
                }
                node = node.parentElement;
            }

            var topItem = item;
            while (topItem.parentElement && !topItem.parentElement.matches('.menu-nav')) {
                var parentItem = topItem.parentElement.closest('li.menu-item');
                if (!parentItem || parentItem === topItem) {
                    break;
                }
                topItem = parentItem;
            }

            var sibling = topItem.previousElementSibling;
            while (sibling && !sibling.matches('.menu-section')) {
                sibling = sibling.previousElementSibling;
            }
            if (sibling) {
                var sectionLabel = sibling.querySelector('.menu-text');
                if (sectionLabel) {
                    labels.unshift(sectionLabel.textContent.replace(/\s+/g, ' ').trim());
                }
            }

            return { labels: labels, section: sibling };
        }

        var entries = menuLinks().reduce(function (result, link) {
            var href = link.getAttribute('href') || '';
            var item = link.closest('li.menu-item');
            if (!item || item.classList.contains('menu-item-parent') || href === '#' ||
                /^javascript:/i.test(href)) {
                return result;
            }

            var text = link.querySelector('.menu-text');
            var title = (text ? text.textContent : link.textContent).replace(/\s+/g, ' ').trim();
            if (!title) {
                return result;
            }

            var trail = menuTrail(item);
            result.push({
                item: item,
                title: title,
                section: trail.section,
                haystack: (trail.labels.join(' ') + ' ' + title).toLowerCase()
            });
            return result;
        }, []);

        function restoreVisibility() {
            visibility.forEach(function (entry) {
                entry.element.hidden = entry.hidden;
            });
        }

        function filterMenu() {
            var terms = input.value.toLowerCase().replace(/\s+/g, ' ').trim().split(' ').filter(Boolean);
            clearButton.hidden = !terms.length;
            emptyState.hidden = !terms.length;

            if (!terms.length) {
                aside.classList.remove('app-sidebar-search-active');
                restoreVisibility();
                status.textContent = '';
                return;
            }

            aside.classList.add('app-sidebar-search-active');
            menuItems.forEach(function (item) { item.hidden = true; });
            sections.forEach(function (section) { section.hidden = true; });

            var matches = entries.filter(function (entry) {
                return terms.every(function (term) {
                    return entry.haystack.indexOf(term) !== -1;
                });
            });

            matches.forEach(function (entry) {
                var item = entry.item;
                while (item && item !== aside) {
                    if (item.matches && item.matches('li.menu-item')) {
                        item.hidden = false;
                    }
                    item = item.parentElement;
                }
                if (entry.section) {
                    entry.section.hidden = false;
                }
            });

            emptyState.hidden = matches.length > 0;
            status.textContent = matches.length ?
                'عدد النتائج: ' + matches.length :
                'لا توجد نتائج تطابق البحث';
        }

        input.addEventListener('input', filterMenu);
        clearButton.addEventListener('click', function () {
            input.value = '';
            filterMenu();
            input.focus();
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && input.value) {
                event.preventDefault();
                event.stopPropagation();
                input.value = '';
                filterMenu();
                input.focus();
            }
        });
    }

    function init() {
        highlightActive();
        initSidebarAccordion();
        initSidebarSearch();
        initQuickSearch();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
