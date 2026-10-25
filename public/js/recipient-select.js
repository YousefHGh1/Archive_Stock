/**
 * قائمة منسدلة بحث + إضافة جديد (Select2 tags) للجهة المستلمة / المستلم.
 * الاستخدام: أضف الكلاس recipient-select على <select> ثم حمّل هذا الملف.
 * القيمة تُحفظ كنص كما هي، وأي قيمة جديدة تظهر تلقائياً في القائمة لاحقاً.
 */
(function ($) {
    'use strict';

    $(function () {
        $('.recipient-select').each(function () {
            var $el = $(this);

            $el.select2({
                width: '100%',
                dir: 'rtl',
                tags: true,
                allowClear: true,
                placeholder: $el.data('placeholder') || 'اختر من القائمة أو اكتب جديداً',
                language: {
                    noResults: function () { return 'لا توجد نتائج'; },
                    searching: function () { return 'جاري البحث...'; }
                },
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') { return null; }

                    // لا نعرض خيار "إضافة" إذا كانت القيمة موجودة بالفعل
                    var exists = false;
                    $el.find('option').each(function () {
                        if ($.trim($(this).text()).toLowerCase() === term.toLowerCase()) {
                            exists = true;
                            return false;
                        }
                    });
                    if (exists) { return null; }

                    return { id: term, text: term, newTag: true };
                },
                templateResult: function (item) {
                    if (item.newTag) {
                        return $('<span>').text('+ إضافة: ' + item.text);
                    }
                    return item.text;
                },
                templateSelection: function (item) {
                    return item.text;
                }
            });
        });
    });
})(jQuery);
