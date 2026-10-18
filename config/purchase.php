<?php

return [
    // SLA thresholds in days for each status
    'sla_thresholds' => [
        'pending' => 2,
        'purchase_order' => 5,
        'tender_committee' => 10,
        'rent_repair' => 3,
    ],

    // Responsible roles for each status (for SLA alerts)
    'responsible_roles' => [
        'pending' => 'purchase',
        'purchase_order' => 'purchase',
        'tender_committee' => 'tender_committee',
        'rent_repair' => 'finance',
    ],

    // Status labels in Arabic
    'status_labels' => [
        'pending' => 'قيد الانتظار',
        'purchase_order' => 'أمر شراء',
        'tender_committee' => 'لجنة العطاءات',
        'rent_repair' => 'استئجار + إصلاح',
        'rejected' => 'مرفوض',
        'approved_stock' => 'موافق عليه - مخازن',
        'withdrawn' => 'مسحوب',
    ],

    // Notification settings
    'notifications' => [
        'enable_database' => true,
        'enable_mail' => true,
    ],
];
