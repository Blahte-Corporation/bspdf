<?php

namespace BlahteSoftware\BsPdf\ServiceProviders;

use BlahteSoftware\BsPdf\Contracts\PdfCore as ContractsPdfCore;
use BlahteSoftware\BsPdf\Contracts\PdfInterface;
use BlahteSoftware\BsPdf\Pdf;
use BlahteSoftware\BsPdf\PdfCore;
use Illuminate\Support\ServiceProvider;

class BsPdfServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $configPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bspdf.php';
        $this->mergeConfigFrom($configPath, 'bspdf');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bspdf.php';
        $this->publishes([$configPath => config_path('bspdf.php')], 'config');

        if( $this->app['config']->get('bspdf.pdf.enabled') ) {
            
            $this->app->bind(ContractsPdfCore::class, function($app) {
                $binary = $app['config']->get('bspdf.pdf.binary', '/usr/local/bin/wkhtmltopdf');
                $options = $app['config']->get('bspdf.pdf.options', array());
                $env = $app['config']->get('bspdf.pdf.env', array());
                $timeout = $app['config']->get('bspdf.pdf.timeout', false);
                $pdfCore = new PdfCore($binary, $options, $env);
                if(false !== $timeout) { $pdfCore->setTimeout($timeout); }
                return $pdfCore;
            });

            $this->app->bind(PdfInterface::class, function($app) {
                return Pdf::getInstance($app->make(ContractsPdfCore::class));
            });

        }
    }

    public function provides() {
        return array(ContractsPdfCore::class, PdfInterface::class);
    }
}
