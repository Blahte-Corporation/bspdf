<?php

$COMPOSER_JSON_PATH = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'composer.json';
$isWkhtmltopdfBinaryProvidedByComposer = false;
if(file_exists($COMPOSER_JSON_PATH)) {
    $obj = json_decode(file_get_contents($COMPOSER_JSON_PATH), false);
    $isWkhtmltopdfBinaryProvidedByComposer = property_exists($obj, 'require') && property_exists($obj->require, 'h4cc/wkhtmltopdf-amd64');
}

return [

    /*
    |--------------------------------------------------------------------------
    | PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timout:
    |
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */

    'pdf' => [
        'enabled' => true,
        'binary'  => $isWkhtmltopdfBinaryProvidedByComposer
            ? dirname(__FILE__, 2) . "/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64"
            : env('WKHTML_PDF_BINARY', '/usr/local/bin/wkhtmltopdf'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

    'image' => [
        'enabled' => true,
        'binary'  => $isWkhtmltopdfBinaryProvidedByComposer
            ? dirname(__FILE__, 2) . "/vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64"
            : env('WKHTML_IMG_BINARY', '/usr/local/bin/wkhtmltoimage'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
