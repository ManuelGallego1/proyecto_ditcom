<?php

return [
    'font_dir' => storage_path('fonts/'), // Cambia esto si es necesario
    'font_cache' => storage_path('fonts/'),
    'temp_dir' => storage_path('temp/'),
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
    'enable_html5_parser' => true, // Cambiado a true para mejor compatibilidad con CSS
];
