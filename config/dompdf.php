<?php

return [
    'show_warnings' => false,
    
    // 🚀 THE MAGIC BYPASS: Tell DOMPDF exactly where the public folder is
    'public_path' => '/home/orbitate/public_html',

    'orientation' => 'portrait',
    'defines' => [
        // 🚀 DOUBLE PROTECTION: Force it to save temporary fonts in the writable storage folder
        'font_dir' => storage_path('fonts'),
        'font_cache' => storage_path('fonts'),
        'temp_dir' => sys_get_temp_dir(),
        'chroot' => base_path(),
        'enable_font_subsetting' => false,
        'pdf_backend' => 'CPDF',
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        'default_font' => 'serif',
        'dpi' => 96,
        'enable_php' => false,
        'enable_javascript' => true,
        'enable_remote' => true,
        'font_height_ratio' => 1.1,
        'enable_html5_parser' => true,
    ],
];