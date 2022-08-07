<?php

namespace BlahteSoftware\BsPdf\Facades;

use BlahteSoftware\BsPdf\Contracts\PdfInterface;
use Illuminate\Support\Facades\Facade;

class BsPdf extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { 
        return PdfInterface::class; 
    }
}