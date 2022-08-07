<?php 

namespace BlahteSoftware\BsPdf\Contracts;

use Illuminate\Contracts\Support\Renderable;

interface PdfInterface {
    /**
     * Get the Pdf instance.
     * 
     * @param \BlahteSoftware\BsPdf\Contracts\PdfCore $pdf
     * @return \BlahteSoftware\BsPdf\Contracts\PdfInterface
     */
    public static function getInstance(PdfCore $pdf = null) : PdfInterface;
    public function getPdfInstance() : PdfCore;
    public function setTemporaryFolder(string $path) : PdfInterface;
    public function setPaper(string $paper, string $orientation=null) : PdfInterface;
    public function setOrientation(string $orientation) : PdfInterface;
    public function setOption(string $name, mixed $value) : PdfInterface;
    public function setOptions(array $options) : PdfInterface;
    public function loadHTML(Array|string|Renderable $html) : PdfInterface;
    public function loadFile(string $file) : PdfInterface;
    public function loadView(string $view, array $data = array(), array $mergeData = array());
    public function output() : string;
    public function save(string $filename, bool $overwrite = false) : PdfInterface;
    public function download(string $filename = 'document.pdf');
    public function inline(string $filename = 'document.pdf');
    public function setMarginBottom(string $marginBottom) : PdfInterface;
    public function setMarginLeft(string $marginLeft = '10mm') : PdfInterface;
    public function setMarginRight(string $marginRight = '10mm') : PdfInterface;
    public function setMarginTop(string $marginTop) : PdfInterface;
    public function setMarginX(string $marginX = '10mm') : PdfInterface;
    public function setMarginY(string $marginY) : PdfInterface;
    public function setTitle(string $title) : PdfInterface;
    public function setCustomHeader(string $header) : PdfInterface;
    /**
     * --default-header
     * 
     \
        --default-header   Add a default header, with the name of the
                            page to the left, and the page number to
                            the right, this is short for:
                            --header-left='[webpage]'
                            --header-right='[page]/[toPage]' 
                            --top 2cm
                            --header-line
     \
     * 
     */
    public function addDefaultHeader() : PdfInterface;
    public function disableForms() : PdfInterface;
    public function enableForms() : PdfInterface;
    public function allowImages() : PdfInterface;
    public function disallowImages() : PdfInterface;
    public function disableJavascript() : PdfInterface;
    public function enableJavascript() : PdfInterface;
    public function setJavascriptDelay(string $delayMilliseconds = '200') : PdfInterface;
    public function enableLocalFileAccess() : PdfInterface;
    public function disableLocalFileAccess(string $path = null) : PdfInterface;
    public function enableInternalLinks() : PdfInterface;
}