(function () {
    'use strict';

    var body = document.body;
    var isReducedMotion = window.matchMedia &&
        window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var isPrintView = window.matchMedia && window.matchMedia('print').matches;
    var feedback = document.getElementById('transaction-feedback');
    var closeTimer = null;
    var previousFocus = null;
    var progress = null;
    var knownKinds = ['fuel-in', 'fuel-out', 'stock-in', 'stock-out', 'create', 'update', 'delete', 'generic'];

    if (!body || isPrintView || body.dataset.motionExcluded === 'true') {
        return;
    }

    function modalElements() {
        if (!feedback) {
            return null;
        }

        return {
            dialog: feedback.querySelector('.transaction-feedback__dialog'),
            title: feedback.querySelector('[data-feedback-title]'),
            eyebrow: feedback.querySelector('[data-feedback-eyebrow]'),
            text: feedback.querySelector('[data-feedback-text]')
        };
    }

    function normalizedKind(kind, message) {
        if (knownKinds.indexOf(kind) !== -1 && kind !== 'generic') {
            return kind;
        }

        var text = String(message || '');
        var path = window.location.pathname.toLowerCase();

        if (/حذف|الغاء|إلغاء/.test(text)) {
            return 'delete';
        }
        if (/تعديل|تحديث/.test(text)) {
            return 'update';
        }
        if (/محروقات|سولار|ديزل/.test(text)) {
            if (/صادر|صرف/.test(text) || path.indexOf('/dieselexport') === 0) {
                return 'fuel-out';
            }
            if (/وارد/.test(text) || path.indexOf('/diesel') === 0) {
                return 'fuel-in';
            }
        }
        if (/invoice_export|سند الصرف|صرف الأصناف/.test(text) || path.indexOf('/inventory/invoice_export') === 0) {
            return 'stock-out';
        }
        if (/invoice|فاتورة وارد|فاتورة الشراء/.test(text) || path.indexOf('/inventory/invoice') === 0) {
            return 'stock-in';
        }
        if (/إضافة|اضافة|إنشاء|انشاء|حفظ|تسجيل|إرسال|ارسال/.test(text)) {
            return 'create';
        }

        return 'generic';
    }

    function titleFor(kind) {
        var titles = {
            'fuel-in': 'تم تسجيل وارد المحروقات بنجاح',
            'fuel-out': 'تم تسجيل صادر المحروقات بنجاح',
            'stock-in': 'تم تسجيل الوارد بنجاح',
            'stock-out': 'تم تسجيل الصرف بنجاح',
            create: 'تمت الإضافة بنجاح',
            update: 'تم التحديث بنجاح',
            delete: 'تم الحذف بنجاح',
            generic: 'تمت العملية بنجاح'
        };

        return titles[kind] || titles.generic;
    }

    function closeFeedback(result) {
        if (!feedback || feedback.hidden) {
            return;
        }

        feedback.classList.remove('is-visible');
        feedback.hidden = true;
        body.classList.remove('transaction-feedback-open');
        window.clearTimeout(closeTimer);

        if (previousFocus && typeof previousFocus.focus === 'function') {
            previousFocus.focus();
        }

        if (feedback.resolve) {
            var resolve = feedback.resolve;
            feedback.resolve = null;
            resolve(result || { isDismissed: true, dismiss: 'close' });
        }
    }

    function showFeedback(options) {
        options = options || {};
        if (!feedback) {
            return Promise.resolve({ isDismissed: true, dismiss: 'unavailable' });
        }

        var message = typeof options.message === 'string' ? options.message : '';
        var kind = normalizedKind(options.type, message);
        var elements = modalElements();
        if (!elements) {
            return Promise.resolve({ isDismissed: true, dismiss: 'unavailable' });
        }

        if (!feedback.hidden) {
            closeFeedback({ isDismissed: true, dismiss: 'replaced' });
        }

        previousFocus = document.activeElement;
        feedback.dataset.kind = kind;
        elements.title.textContent = options.title || titleFor(kind);
        elements.eyebrow.textContent = options.eyebrow || (kind.indexOf('fuel-') === 0
            ? 'حركة المحروقات'
            : kind.indexOf('stock-') === 0 ? 'حركة المخازن' : 'تمت العملية');
        elements.text.textContent = message || titleFor(kind);
        feedback.hidden = false;
        feedback.classList.add('is-visible');
        body.classList.add('transaction-feedback-open');

        window.clearTimeout(closeTimer);
        closeTimer = window.setTimeout(function () {
            closeFeedback({ isDismissed: true, dismiss: 'timer' });
        }, 7000);

        window.requestAnimationFrame(function () {
            var closeButton = feedback.querySelector('[data-feedback-close]');
            if (closeButton) {
                closeButton.focus();
            }
        });

        return new Promise(function (resolve) {
            feedback.resolve = resolve;
        });
    }

    function showError(error) {
        var data = error && error.response ? error.response.data : error;
        var message = data && typeof data === 'object' ? data.message : data;
        if (typeof message !== 'string' || !message.trim()) {
            message = 'تعذر إتمام العملية. يرجى المحاولة مرة أخرى.';
        }

        if (window.Swal && typeof window.Swal.fire === 'function') {
            return window.Swal.fire({
                icon: 'error',
                title: 'تعذر إتمام العملية',
                text: message
            });
        }

        window.alert(message);
        return Promise.resolve({ isConfirmed: true });
    }

    window.ArchiveStockFeedback = {
        show: function (response, overrides) {
            if (!response || response.success !== true || typeof response.message !== 'string') {
                return Promise.resolve({ isDismissed: true, dismiss: 'not-successful' });
            }

            return showFeedback(Object.assign({}, response, overrides || {}));
        },
        error: showError
    };

    function installSweetAlertAdapter() {
        var swal = window.Swal;
        if (!swal || typeof swal.fire !== 'function' || swal.fire.__archiveStockAdapter) {
            return;
        }

        var originalFire = swal.fire;
        var adaptedFire = function () {
            var options = arguments[0];
            var icon = typeof options === 'object' && options !== null
                ? options.icon || options.type
                : arguments[2];

            if (icon !== 'success') {
                return originalFire.apply(swal, arguments);
            }

            var title = typeof options === 'object' && options !== null
                ? (options.titleText || options.title || '')
                : (options || '');
            var text = typeof options === 'object' && options !== null
                ? (options.text || options.html || '')
                : (arguments[1] || '');
            var message = [title, text].filter(Boolean).join(' — ');

            return showFeedback({
                message: message || 'تمت العملية بنجاح',
                type: normalizedKind('', message)
            });
        };

        adaptedFire.__archiveStockAdapter = true;
        adaptedFire.__archiveStockOriginal = originalFire;
        swal.fire = adaptedFire;
    }

    function animateDashboardCounters() {
        if (isReducedMotion) {
            return;
        }

        Array.prototype.forEach.call(document.querySelectorAll('.db-kpi-value'), function (element) {
            if (element.dataset.counterAnimated === 'true') {
                return;
            }

            var valueNode = Array.prototype.find.call(element.childNodes, function (node) {
                return node.nodeType === 3 && /[0-9٠-٩۰-۹]/.test(node.nodeValue);
            });
            if (!valueNode) {
                return;
            }

            var original = valueNode.nodeValue;
            var normalized = original.replace(/[٠-٩]/g, function (digit) {
                return String('٠١٢٣٤٥٦٧٨٩'.indexOf(digit));
            }).replace(/[۰-۹]/g, function (digit) {
                return String('۰۱۲۳۴۵۶۷۸۹'.indexOf(digit));
            });
            var match = normalized.match(/-?[\d][\d,٬،]*(?:\.\d+)?/);
            if (!match) {
                return;
            }

            var target = Number(match[0].replace(/[,٬،]/g, ''));
            if (!Number.isFinite(target) || Math.abs(target) > 1000000000) {
                return;
            }

            var prefix = original.slice(0, match.index);
            var suffix = original.slice(match.index + match[0].length);
            var started = null;
            var duration = 550;
            element.dataset.counterAnimated = 'true';

            function frame(timestamp) {
                if (started === null) {
                    started = timestamp;
                }

                var progress = Math.min(1, (timestamp - started) / duration);
                var eased = 1 - Math.pow(1 - progress, 3);
                var current = target * eased;
                var digits = Math.abs(target % 1) > 0 ? 1 : 0;
                valueNode.nodeValue = prefix + current.toLocaleString('en-US', {
                    maximumFractionDigits: digits,
                    minimumFractionDigits: 0
                }) + suffix;

                if (progress < 1) {
                    window.requestAnimationFrame(frame);
                } else {
                    valueNode.nodeValue = original;
                }
            }

            window.requestAnimationFrame(frame);
        });
    }

    function initializeReveals() {
        if (isReducedMotion) {
            return;
        }

        var content = document.getElementById('kt_content');
        if (!content) {
            return;
        }

        var selectors = [
            '.card:not(.db-fade)',
            '.db-panel',
            '.db-dept',
            '.db-kpi',
            '.db-tile',
            '#kt_content form'
        ];
        var elements = [];

        selectors.forEach(function (selector) {
            Array.prototype.forEach.call(content.querySelectorAll(selector), function (element) {
                if (elements.length < 32 && elements.indexOf(element) === -1) {
                    elements.push(element);
                }
            });
        });

        Array.prototype.forEach.call(content.querySelectorAll('.table tbody'), function (tbody) {
            Array.prototype.slice.call(tbody.rows, 0, 8).forEach(function (row) {
                if (elements.length < 40) {
                    elements.push(row);
                }
            });
        });

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries, activeObserver) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('transaction-motion-reveal');
                        activeObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08 });

            elements.forEach(function (element, index) {
                element.style.setProperty('--transaction-motion-delay', Math.min(index % 5, 4) * 35 + 'ms');
                observer.observe(element);
            });
        } else {
            elements.forEach(function (element, index) {
                element.style.setProperty('--transaction-motion-delay', Math.min(index % 5, 4) * 35 + 'ms');
                element.classList.add('transaction-motion-reveal');
            });
        }
    }

    function initializeHoverEffects() {
        if (isReducedMotion) {
            return;
        }

        Array.prototype.forEach.call(document.querySelectorAll(
            '#kt_content .db-dept, #kt_content .db-tile, #kt_content .db-kpi, #kt_content .btn, #kt_aside .menu-item'
        ), function (element) {
            element.classList.add('transaction-motion-hover');
        });
    }

    function initializeProgress() {
        progress = document.createElement('div');
        progress.className = 'transaction-page-progress';
        progress.setAttribute('aria-hidden', 'true');
        document.body.appendChild(progress);

        document.addEventListener('click', function (event) {
            var link = event.target.closest && event.target.closest('a[href]');
            if (!link || event.defaultPrevented || event.button !== 0 ||
                event.metaKey || event.ctrlKey || event.shiftKey || event.altKey ||
                link.hasAttribute('download') || link.target && link.target !== '_self') {
                return;
            }

            var url;
            try {
                url = new URL(link.href, window.location.href);
            } catch (error) {
                return;
            }

            if (url.origin !== window.location.origin || url.pathname === window.location.pathname && url.search === window.location.search ||
                url.hash && url.pathname === window.location.pathname && url.search === window.location.search) {
                return;
            }

            window.setTimeout(function () {
                if (!event.defaultPrevented && progress) {
                    progress.classList.add('is-active');
                    window.setTimeout(function () {
                        progress.classList.remove('is-active');
                    }, 8000);
                }
            }, 0);
        }, true);

        document.addEventListener('submit', function (event) {
            var form = event.target;
            if (!form || form.matches('[data-ajax], [data-remote]')) {
                return;
            }

            var submitter = event.submitter || form.querySelector('button[type="submit"], input[type="submit"]');
            if (!submitter) {
                return;
            }

            window.setTimeout(function () {
                if (event.defaultPrevented) {
                    return;
                }

                submitter.classList.add('transaction-motion-loading');
                submitter.setAttribute('aria-busy', 'true');

                window.setTimeout(function () {
                    submitter.classList.remove('transaction-motion-loading');
                    submitter.removeAttribute('aria-busy');
                }, 15000);
            }, 0);
        }, true);

        window.addEventListener('pagehide', function () {
            if (progress) {
                progress.classList.remove('is-active');
            }
        });
    }

    if (feedback) {
        feedback.addEventListener('click', function (event) {
            if (event.target.closest('[data-feedback-confirm]')) {
                closeFeedback({ isConfirmed: true, isDismissed: false });
                return;
            }

            if (event.target.closest('[data-feedback-close]')) {
                closeFeedback({ isDismissed: true, dismiss: 'close' });
            }
        });

        document.addEventListener('keydown', function (event) {
            if (!feedback || feedback.hidden) {
                return;
            }

            if (event.key === 'Escape') {
                closeFeedback({ isDismissed: true, dismiss: 'escape' });
            } else if (event.key === 'Tab') {
                var focusable = feedback.querySelectorAll('button:not([disabled]), [href], input:not([disabled]), [tabindex="0"]');
                if (!focusable.length) {
                    event.preventDefault();
                    return;
                }

                var first = focusable[0];
                var last = focusable[focusable.length - 1];
                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }
        });
    }

    installSweetAlertAdapter();
    initializeReveals();
    initializeHoverEffects();
    initializeProgress();
    animateDashboardCounters();

    if (feedback && feedback.dataset.feedbackMessage) {
        showFeedback({
            message: feedback.dataset.feedbackMessage,
            type: feedback.dataset.feedbackType
        });
    }
})();
