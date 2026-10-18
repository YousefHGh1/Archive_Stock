أنسب حل لمشروع Archive-Stock هو تحويل الواجهة إلى تجربة تفاعلية متحركة مرتبطة بطبيعة المخازن والمحروقات، بدل الاكتفاء بإضافة تأثيرات عامة على الأزرار والقوائم.

سأجهز لك برومبت متكاملًا لتشغيله داخل Claude Code، مع المهارات (Skills) المطلوبة، بحيث يفحص المشروع أولًا ثم ينفّذ التعديلات فعليًا داخل Laravel وBlade، ويحافظ على الوظائف الحالية والصلاحيات والتقارير.

## 1. التصور المطلوب للمشروع

[Gas station cartoon scene](https://images.openai.com/static-rsc-4/9VZirESwRCymaB8K1V6f2nLV8AOeLyj9mUxyybEixQy2MotXxkl3HzDNsEM_HpfbYdTv6_8RcZeEZnKBZsCiFW_LH-ANjJPJO_Z1mzZ-9LcYc62s91Y3YMJZkKPPD5pfXdj9B84ZdOXumV-lTun8WiGww92ll_rC8a5Eq6ACI68njAo7ViHDDLNQRQakTLz0?purpose=inline)

قسم المحروقات والسولار

شاحنة صهريج كرتونية تصل إلى المحطة، تتحرك ببطء، ثم يظهر خرطوم التعبئة وتتحرك إشارة مستوى الوقود. عند اكتمال العملية تظهر رسالة نجاح.

[Inventory Management Warehouse Textures Images | Free Photos, PNG Stickers, Wallpapers & Backgrounds - rawpixel](https://images.openai.com/static-rsc-4/7VW6BIRiTAB5ipWvZ7nuXkoNwfPhUamzLa0pKsXaFvjcEBtLrpyGE6o6Yy8GPmIa9C1uEWxzDg5xsNd1QVhp5xZzMbUEV0t1Rd6xmx_lhNaRKCih_g7adG3uDTpRBGYcLl7a3wnESZaYb2tOWSjhGL5zCRx68nrCs6f7OPQW5zA?purpose=inline)

قسم المستودعات والأصناف

شخصية عامل كرتونية تحمل صندوقًا وتضعه على الرف، مع حركة خفيفة للصناديق والرفوف، ومؤشرات مرئية للأصناف والكميات.

[等距3D风格物流仓库场景，现代化单层仓库建筑，蓝色屋顶搭配浅灰色墙面，橙黄色大型货运卡车停靠在仓库旁装卸区域-1qax127ii3k2r2rj-HB-稿定 AI](https://images.openai.com/static-rsc-4/HC0dTA94ND2p6mwQxoNropaJ-Yxwu73da-xo1gt4DsZ95zG5zah64lcqt65d0gVgqGKAZov0wDEVInvUwlnB-dz_9DHFgMnSM8CRLpHQKehgkVioeBJbUjz7ecnRpEA_hZ8fLaYcBLuQRew0tU3PMSmzwKRYL6VougiGQ9XsWZc?purpose=inline)

قسم الإدخال والإخراج والفواتير

حركة صندوق عند إضافة صنف أو اعتماد فاتورة، وشاحنة تدخل أو تغادر المستودع بحسب نوع الحركة، دون التأثير على تنفيذ العمليات الفعلي.

[3d cartoon products checking concept. Merchandise inspection. Cargo examination process. Clipboard with check mark leaning against stack of goods. 3d rendering illustration.](https://images.openai.com/static-rsc-4/i7PymuyJgr7NUCNsXOC3iIlluEhcrLX_fqXnkX6-yNC4OZ7OsUpO1ziupd_qRerIUvgnND9Qd1H5kxvxvoznqyX5tBxh3md5U_NfRl4EIwsbhpY9mfxLou2WaXBWMStYIddtqdgMy6d6Gq-ksDCAG9ZEWT7QizmLkKbvqDtSk6Vjncs42YrL6Y4vmZjHw5Ms?purpose=inline)

رسائل النجاح والفشل

إشعار نجاح مع علامة صح متحركة، وإشعار خطأ واضح مع حركة تنبيه بسيطة، ورسائل تحذير عند نقص المخزون أو وجود حقول غير صحيحة.

هذه تصورات بصرية للاتجاه المقترح؛ المطلوب من Claude Code هو تطبيق الحركات على صفحات المشروع الحقيقية، وليس استبدال نظام المخازن برسومات ثابتة.

## 2. البرومبت الكامل لـ Claude Code

انسخ النص التالي كاملًا، وافتح Claude Code من مجلد المشروع:

`C:\laragon\www\Archive-Stock`

ثم أرسل له البرومبت. صممته ليتعامل مع المشروع الموجود فعليًا، لا ليعيد بناءه من الصفر.

# Mission: Transform Archive-Stock into an Animated Warehouse Management Experience

## 1. Project Context

You are working on an existing Laravel application located at:

`C:\laragon\www\Archive-Stock`

The application manages warehouse operations, including:

- Incoming inventory and purchase invoices.
- Outgoing inventory and export invoices.
- Items, quantities, stock balances, and storage locations.
- Fuel and diesel inventory and transactions.
- Suppliers, warehouses, reports, users, roles, and permissions.

The main Blade layout directory is:

`resources/views/layout`

The project already has an existing database, business logic, routes, controllers, views, authentication, and permissions.

Your mission is to enhance the existing application with a modern, polished, animated visual experience while preserving all existing business functionality.

Do not rebuild the application from scratch.

## 2. Mandatory First Step: Audit the Existing Project

Before changing any files:

1. Inspect the project structure, `composer.json`, `package.json`, Laravel version, installed frontend libraries, Blade layouts, CSS, JavaScript, routes, controllers, and relevant views.
2. Identify the existing logo, brand colors, typography, RTL support, sidebar, navbar, dashboard, and notification system.
3. Identify the existing toast, alert, modal, validation, and session flash-message implementations.
4. Identify actual incoming, outgoing, inventory, and diesel workflows.
5. Determine whether an animation library is already installed.
6. Check asset compilation, Vite or Mix configuration, and existing build commands.
7. Create a concise implementation plan based on the real codebase.

Do not assume that the application uses a particular Laravel, Bootstrap, or JavaScript version without checking.

Do not install duplicate libraries or replace existing UI frameworks unnecessarily.

## 3. Visual Design Direction

Create a modern warehouse-management interface inspired by high-quality logistics and industrial software.

Requirements:

- Use the actual logo and derive the color palette from it.
- Maintain a consistent design system across all modules.
- Support Arabic RTL and English LTR.
- Support desktop, laptop, tablet, and mobile screens.
- Use clean cards, soft shadows, subtle gradients, rounded corners, and clear typography.
- Add tasteful animations and meaningful visual feedback.
- Keep forms, tables, reports, and operational workflows professional and easy to use.
- Avoid excessive cartoon decorations in data-heavy pages.
- Use consistent iconography and appropriate empty states.
- Keep all existing page content and functionality.

## 4. Animated Visual Scenes

Implement reusable animated components rather than duplicating animation code across views.

### A. Fuel Station and Diesel Module

Create a visually appealing animated diesel station scene featuring:

- A cartoon-style fuel tanker truck.
- A small fuel station with a pump and hose.
- A fuel attendant or driver.
- A fuel-level indicator.
- Subtle truck-wheel, vehicle-arrival, hose, and fuel-flow animations.
- A filling-progress animation where appropriate.
- A completed-filling state when the relevant operation succeeds.

The animation must reflect the actual application state.

For example:

- Before submission: show a neutral idle scene.
- During a real save request: show a loading or processing state.
- After a confirmed successful save: show the completion animation.
- After a failed request: show an error state.

Never display a completed transaction before the server confirms success.

### B. Warehouse and Inventory Module

Create a warehouse scene with:

- A friendly warehouse worker character.
- Storage shelves and cardboard boxes.
- A box-placement animation.
- Small inventory movement animations.
- Subtle shelf and warehouse environmental details.
- Stock-level indicators that use actual available data when displayed as operational information.

Use appropriate animations for receiving goods, storing items, and moving inventory.

Do not suggest that stock has changed until the backend confirms the transaction.

### C. Incoming Inventory and Purchase Invoices

Create contextual visual feedback for incoming stock:

- A delivery truck arriving at the warehouse.
- Boxes moving toward warehouse shelves.
- A subtle incoming-stock indicator.
- A success animation after a confirmed purchase invoice save.

The animation must not alter invoice calculations or stock updates.

### D. Outgoing Inventory and Export Invoices

Create contextual visual feedback for outgoing stock:

- A worker preparing boxes for dispatch.
- Boxes moving toward a delivery vehicle.
- A truck leaving the warehouse after a confirmed operation.
- Clear warnings when requested quantities exceed available stock.

When validation fails, do not show the dispatch-completed animation.

### E. Dashboard

Enhance the dashboard with:

- Animated KPI cards.
- Subtle number-counting effects for real numeric values.
- Incoming and outgoing inventory visual indicators.
- A diesel or fuel summary illustration.
- Gentle entrance animations.
- Contextual empty states.
- Lightweight charts only where they provide useful information.

Avoid infinite animation loops that waste CPU or distract users.

## 5. Success, Error, Warning, and Loading Feedback

Create a consistent notification system compatible with the existing application.

### Success

- A checkmark animation.
- A short, subtle entrance transition.
- A clear Arabic success message.
- Optional automatic dismissal.
- Accessible screen-reader announcement.

### Error

- A visible error icon.
- A restrained shake or attention animation.
- A clear explanation of the failure.
- Persistent display when the user needs to read or act on the error.

### Warning

- Appropriate warning icon and styling.
- Clear stock shortage or validation explanation.
- No aggressive flashing.

### Loading

- Disable duplicate submissions where appropriate.
- Show a spinner or contextual processing indicator.
- Restore the appropriate control state after success or failure.
- Prevent accidental duplicate form submission.

Integrate with Laravel session flash messages, server-side validation errors, AJAX responses, and existing form submission mechanisms.

Do not treat an HTTP success response as a successful business operation if the response indicates failure.

Do not expose stack traces, SQL errors, secrets, or internal exception details to end users.

## 6. Animation Technology

Inspect the installed dependencies first.

Prefer lightweight CSS animations and transitions for simple UI effects.

If a JavaScript animation library is necessary, select a library compatible with the existing project and explain the reason before installing it.

Possible options:

- CSS keyframes and transitions for simple effects.
- GSAP for complex, timeline-based animations if justified.
- Lottie for externally designed vector animations if an appropriate player is already available or can be added safely.
- SVG for lightweight, scalable illustrations.

For character illustrations, prefer original or appropriately licensed SVG/vector assets. Keep illustrations local to the project whenever possible.

Avoid adding large libraries merely for a few transitions.

Respect `prefers-reduced-motion` and provide a reduced-motion experience.

## 7. Reusable Architecture

Build reusable components that match the existing Blade conventions.

Potential component structure, to be adapted after inspection:

- `resources/views/components/animations/fuel-station.blade.php`
- `resources/views/components/animations/warehouse-worker.blade.php`
- `resources/views/components/animations/delivery-truck.blade.php`
- `resources/views/components/animations/inventory-movement.blade.php`
- `resources/views/components/feedback/notification.blade.php`
- `resources/views/components/feedback/loading.blade.php`

These paths are suggestions, not mandatory replacements for existing conventions.

Separate:

- Visual components.
- Animation styles.
- Animation behavior.
- Business logic.
- Notification handling.

Avoid embedding large JavaScript blocks in individual Blade views.

Ensure that initialization works after normal Blade page loads and does not attach duplicate event handlers.

## 8. Performance Requirements

The project has previously been identified as having potentially heavy shared JavaScript and CSS assets. Verify the current situation before making changes.

Requirements:

- Avoid loading animation code on pages that do not need it.
- Reuse shared components and styles.
- Lazy-load nonessential illustrations where practical.
- Prefer SVG or optimized WebP/AVIF assets when appropriate.
- Avoid unnecessary animation of layout-triggering properties.
- Prefer `transform` and `opacity` for motion.
- Stop or pause off-screen decorative animations when appropriate.
- Avoid unnecessary DOM manipulation and repeated event listeners.
- Preserve the current asset build pipeline.
- Do not replace the existing asset system without a demonstrated need.

Do not promise performance improvements without testing them.

## 9. Accessibility and Responsive Behavior

Ensure:

- Full RTL support for Arabic.
- Correct LTR behavior for English.
- Keyboard-accessible controls.
- Accessible notification announcements.
- Adequate contrast.
- No reliance on animation or color alone to communicate success or failure.
- Reduced-motion support.
- No horizontal overflow on mobile.
- Touch-friendly buttons and form controls.
- Readable tables and reports at smaller widths.

Decorative illustrations must not interfere with keyboard navigation or screen readers.

## 10. Business Logic Protection

This is an inventory management application, not a visual demo.

Never:

- Modify inventory balances solely through frontend animations.
- Bypass Laravel validation or authorization.
- Remove existing permissions or middleware.
- Change invoice calculations without an explicitly justified requirement.
- Modify database schemas unnecessarily.
- Replace existing controllers or routes with mock functionality.
- Use fake stock quantities in operational indicators.
- Introduce dummy success responses.
- break existing Excel/PDF exports, filters, or reports.

Keep all stock changes, validation, and transaction handling on the server.

## 11. Testing and Verification

After implementation:

1. Run the existing frontend build command.
2. Run relevant Laravel tests.
3. Add tests for any newly introduced notification or integration logic where appropriate.
4. Verify successful and failed form submissions.
5. Verify insufficient-stock validation.
6. Verify that duplicate submissions are prevented where necessary.
7. Verify Arabic RTL and English LTR layouts.
8. Verify responsive behavior.
9. Verify reduced-motion behavior.
10. Confirm that existing reports, invoices, authentication, and permissions remain functional.

Do not claim that tests passed unless they were actually executed.

## 12. Execution Workflow

Work in controlled phases:

Phase 1: Audit the project and document the existing architecture.

Phase 2: Establish reusable animation and notification foundations.

Phase 3: Implement the dashboard and warehouse animations.

Phase 4: Implement diesel/fuel station animations.

Phase 5: Integrate contextual feedback into incoming and outgoing transactions.

Phase 6: Refine responsive behavior, accessibility, and performance.

Phase 7: Run tests and produce an implementation report.

Start by inspecting the repository. Then implement the changes in the existing codebase, not merely by providing examples or a theoretical plan.

Before changing dependencies, database schemas, or core business logic, explain the need and choose the least disruptive approach.

At completion, provide:

- Files created and modified.
- Dependencies added, if any.
- Implemented animations and their associated pages.
- How success, error, warning, and loading states are triggered.
- Build and test commands executed.
- Test results and unresolved issues.
- Any remaining manual steps.

The final result should feel like a polished, modern warehouse and fuel-management system with purposeful motion, not a generic dashboard covered in random animations.

## 3. ما الـ Skills المطلوبة لـ Claude Code؟

هناك فرق بين Skill ومكتبة برمجية: الـ Skill يوجه Claude Code إلى طريقة تنفيذ العمل، بينما المكتبة توفر وظائف الحركة داخل التطبيق. يدعم Claude Code مهارات مخصصة ضمن مجلد `.claude/skills/` بملف `SKILL.md`.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://platform.claude.com\&sz=32)

Claude Platform Docs

+1



هذه المهارات أنصحك بها لمشروع Laravel:

1\. UI/UX Design Skill

أساسية

تصميم واجهات حديثة، نظام ألوان مستخرج من الشعار، بطاقات وإشعارات وتناسق التصميم بين جميع الصفحات.

2\. Motion Design & Animation Skill

أساسية

تصميم حركات الشاحنات والعامل والصناديق ومضخات الوقود، وضبط التوقيت والانتقالات والحركة بحسب حالة العملية.

3\. Laravel Blade Frontend Skill

أساسية

دمج الرسومات والحركات في ملفات Blade والمكونات المشتركة، مع المحافظة على المسارات والصلاحيات ومنطق المخزون.

4\. UI Feedback & Form Validation Skill

إشعارات النجاح والفشل والتحذير والتحميل، وربطها باستجابات Laravel الحقيقية والتحقق من صحة المدخلات.

5\. Frontend Performance & Accessibility Skill

تحسين تحميل الأصول، تقليل الحركات غير الضرورية، ودعم الهواتف وRTL وميزة تقليل الحركة.

هذه أسماء مقترحة لمهارات مخصصة، وليست ادعاءً بأن جميعها متوفرة مسبقًا. يمكنك أن تطلب من Claude Code إنشاءها داخل المشروع. وتوصي وثائق Anthropic بأن تركز كل Skill على سير عمل محدد ومتكرر.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://platform.claude.com\&sz=32)

Claude Platform Docs

+1



## 4. المكتبات التي أنصح باستخدامها

| المكتبة أو التقنية           | الاستخدام                                         | الأولوية              |
| ---------------------------- | ------------------------------------------------- | --------------------- |
| CSS Keyframes + SVG          | حركة الصناديق والشاحنات والشخصيات البسيطة         | أساسية                |
| GSAP                         | الحركات المركبة وتسلسل وصول الشاحنة وتعبئة الوقود | عند الحاجة            |
| Lottie                       | تشغيل رسوم متجهة جاهزة لشخصيات العمال والشاحنات   | اختياري               |
| Toast/Alert الموجود بالمشروع | رسائل النجاح والفشل والتحذير                      | إعادة استخدام الموجود |

روابط التوثيق الرسمية:

- [GSAP Animation](https://gsap.com/docs/v3/GSAP/)
- [GSAP وتقليل الحركة](https://gsap.com/docs/v3/GSAP/gsap.matchMedia/)
- [Claude Code Skills](https://code.claude.com/docs/en/skills)

من المهم احترام إعداد `prefers-reduced-motion`، حتى يستطيع المستخدم تقليل الحركات التي قد تسبب له الانزعاج.&#x20;

[image](https://www.google.com/s2/favicons?domain=https://gsap-docs.netlify.app\&sz=32)

Docs & Learning

+1



توصيتي: ابدأ بـ CSS وSVG، وأضف GSAP فقط للحركات المركبة. لا تثبّت جميع المكتبات دفعة واحدة؛ اجعل Claude Code يفحص `package.json` وملفات الواجهة أولًا، ثم يقرر ما يحتاجه فعلًا.

## 5. إنشاء Skill مخصصة للمشروع

أضف المجلد التالي داخل جذر المشروع:

```
Archive-Stock/
└── .claude/
    └── skills/
        └── warehouse-motion/
            └── SKILL.md
```

ثم ضع المحتوى التالي في ملف `SKILL.md`:

---

# Archive-Stock Warehouse Motion Skill

## Project Rules

- Inspect the existing Laravel and frontend architecture before implementation.
- Preserve existing business logic, permissions, routes, database schemas, and inventory calculations.
- Reuse installed libraries and shared Blade components wherever possible.
- Keep animations separate from backend business logic.

## Animation Design

- Create reusable SVG/CSS-based warehouse and fuel illustrations.
- Use subtle, professional motion rather than excessive decoration.
- Implement truck arrival, box placement, inventory movement, and fuel-filling scenes where relevant.
- Use GSAP only when a multi-step timeline cannot be implemented efficiently with CSS.
- Keep decorative illustrations independent of form calculations and stock mutations.

## Transactional Feedback

- Show success animations only after confirmed server-side success.
- Show validation and stock errors without implying that the operation completed.
- Handle loading states, repeated clicks, and AJAX failures correctly.
- Preserve Laravel validation messages and session flash messages.

## Accessibility and Performance

- Support Arabic RTL and English LTR.
- Respect prefers-reduced-motion.
- Prefer transform and opacity for simple animation.
- Avoid loading animation assets on unrelated pages.
- Keep animations lightweight and mobile-friendly.

## Completion Checklist

- Verify the actual pages where each animation is used.
- Verify success and error states.
- Run the existing asset build and relevant tests.
- Report changed files, dependencies, test results, and remaining issues.

بعد حفظ الملف، افتح Claude Code من مجلد المشروع واطلب منه تنفيذ البرومبت السابق. يمكنك أيضًا استدعاء المهارة باسم `/warehouse-motion` إذا ظهرت ضمن المهارات المكتشفة في إصدار Claude Code لديك.

## 6. كيف تعرف أن التنفيذ اكتمل بشكل صحيح؟

- الشاحنة ومشهد محطة السولار يعملان على الصفحة المناسبة.
- العامل يضع الصناديق على الرفوف بحركة سلسة.
- حركات الإدخال والإخراج مرتبطة بنوع العملية الفعلية.
- إشعارات النجاح لا تظهر قبل نجاح الحفظ.
- أخطاء المخزون والتحقق تظهر بوضوح دون حركات نجاح مضللة.
- الواجهة تعمل على الهاتف والكمبيوتر وباتجاهي RTL وLTR.
- الحركات لا تعطل الجداول أو الفواتير أو التقارير أو الصلاحيات.
- حجم الأصول ووقت تحميل الصفحات لا يتدهوران دون مبرر.
- توجد اختبارات أو خطوات تحقق موثقة للعمليات الأساسية.

بهذا الأسلوب ستحصل على واجهة متحركة متكاملة مرتبطة بنظام Archive-Stock الفعلي، وليس مجرد صفحة تجريبية جميلة.