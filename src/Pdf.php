<?php 

namespace BlahteSoftware\BsPdf;

use BlahteSoftware\BsPdf\Contracts\PdfCore;
use BlahteSoftware\BsPdf\Contracts\PdfInterface;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Contracts\Support\Renderable;

class Pdf implements PdfInterface {
    /**
     * @var \BlahteSoftware\BsPdf\Contracts\PdfInterface
     */
    protected static $instance;

    /**
     * @var \BlahteSoftware\BsPdf\Contracts\PdfCore
     */
    protected $pdf;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param \BlahteSoftware\BsPdf\Contracts\PdfCore $pdf
     */
    private function __construct(PdfCore $pdf) {
       $this->pdf = $pdf;
    }

    /**
     * @inheritdoc
     */
    public static function getInstance(PdfCore $pdf = null) : PdfInterface {
        if( is_null($pdf) ) {
            if( is_null(static::$instance) ) {
                if( function_exists('app') && method_exists(app(), 'make') && method_exists(app(), 'bound') ) {
                    if(app()->bound(PdfCore::class)) {
                        return static::$instance = new static(app()->make(PdfCore::class));
                    }
                }
                throw new Exception("Pdf Object Not Found.");
            }
            if( !static::$instance instanceof PdfInterface ) {
                throw  new Exception("Invalid PDF object..");
            }
            return static::$instance;
        }
        if( is_null(static::$instance) ) {
            static::$instance = new static($pdf);
        }
        return static::$instance;
    }

    public function getPdfInstance() : PdfCore
    {
        return $this->pdf;
    }

    /**
     * Set temporary folder
     *
     * @param  string $path
     */
     public function setTemporaryFolder(string $path) : PdfInterface {
        $this->pdf->setTemporaryFolder($path);
        return $this;
     }

    /**
     * Set the paper size (default A4)
     *
     * @param  string $paper
     * @param  string $orientation
     * @return $this
     */
    public function setPaper(string $paper, string $orientation=null) : PdfInterface {
        $this->pdf->setOption('page-size', $paper);
        if($orientation) {
            $this->pdf->setOption('orientation', $orientation);
        }
        return $this;
    }

    /**
     * Set the orientation (default portrait)
     *
     * @param  string $orientation
     * @return $this
     */
    public function setOrientation(string $orientation) : PdfInterface {
        $this->pdf->setOption('orientation', $orientation);
        return $this;
    }

    /**
     * @param  string $name
     * @param  mixed $value
     * @return $this
     */
    public function setOption(string $name, mixed $value) : PdfInterface {
        if ($value instanceof Renderable) {
            $value = $value->render();
        }
        $this->pdf->setOption($name, $value);
        return $this;
    }

    /**
     * @param  array $options
     * @return $this
     */
    public function setOptions(array $options) : PdfInterface {
        $this->pdf->setOptions($options);
        return $this;
    }

    /**
     * Load a HTML string
     *
     * @param  Array|string|Renderable $html
     * @return $this
     */
    public function loadHTML(Array|string|Renderable $html) : PdfInterface {
        if ($html instanceof Renderable) {
            $html = $html->render();
        }
        $this->html = $html;
        $this->file = null;
        return $this;
    }

    /**
     * Load a HTML file
     *
     * @param  string $file
     * @return $this
     */
    public function loadFile(string $file) : PdfInterface {
        $this->html = null;
        $this->file = $file;
        return $this;
    }

    /**
     * Load a View and convert to HTML
     *
     * @param  string $view
     * @param  array $data
     * @param  array $mergeData
     * @return $this
     */
    public function loadView(string $view, array $data = array(), array $mergeData = array()) {
	    $view = View::make($view, $data, $mergeData);
	    return $this->loadHTML($view);
    }

    /**
	 * Output the PDF as a string.
	 *
	 * @return string The rendered PDF as string
	 * @throws \InvalidArgumentException
	 */
	public function output() : string {
		if ($this->html) {
			return $this->pdf->getOutputFromHtml($this->html, $this->options);
		}

		if ($this->file) {
			return $this->pdf->getOutput($this->file, $this->options);
		}

		throw new \InvalidArgumentException('PDF Generator requires a html or file in order to produce output.');
    }

    /**
     * Save the PDF to a file
     *
     * @param string $filename
     * @param bool $overwrite
     * @return $this
     */
    public function save(string $filename, bool $overwrite = false) : PdfInterface {
        if ($this->html) {
            $this->pdf->generateFromHtml($this->html, $filename, $this->options, $overwrite);
        } elseif ($this->file) {
            $this->pdf->generate($this->file, $filename, $this->options, $overwrite);
        }

        return $this;
    }

    /**
     * Make the PDF downloadable by the user
     *
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function download(string $filename = 'document.pdf') {
        return new Response($this->output(), 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'attachment; filename="'.$filename.'"'
        ));
    }

    /**
     * Return a response with the PDF to show in the browser
     *
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function inline(string $filename = 'document.pdf') {
        return new Response($this->output(), 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ));
    }

    /**
     * Call PdfCore instance.
     *
     * Also shortcut's
     * ->html => loadHtml
     * ->view => loadView
     * ->file => loadFile
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments) {
        $method = 'load' . ucfirst($name);
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $arguments);
        }

        return call_user_func_array (array($this->pdf, $name), $arguments);
    }

    public function setMarginBottom(string $marginBottom) : PdfInterface {
        return $this->setOption('margin-bottom', $marginBottom);
    }
    public function setMarginLeft(string $marginLeft = '10mm') : PdfInterface {
        return $this->setOption('margin-left', $marginLeft);
    }
    public function setMarginRight(string $marginRight = '10mm') : PdfInterface {
        return $this->setOption('margin-right', $marginRight);
    }
    public function setMarginTop(string $marginTop) : PdfInterface {
        return $this->setOption('margin-top', $marginTop);
    }
    public function setMarginX(string $marginX = '10mm') : PdfInterface {
        return $this->setMarginLeft($marginX)->setMarginRight($marginX);

    }
    public function setMarginY(string $marginY) : PdfInterface {
        return $this->setMarginBottom($marginY)->setMarginTop($marginY);
    }
    public function setTitle(string $title) : PdfInterface {
        return $this->setOption('title', $title);
    }
    public function setCustomHeader(string $header) : PdfInterface {
        return $this->setOption('custom-header', $header);
    }
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
    public function addDefaultHeader() : PdfInterface {
        return $this->setOption('default-header', true);
    }
    public function disableForms() : PdfInterface {
        return $this->setOption('disable-forms', true);
    }
    public function enableForms() : PdfInterface {
        return $this->setOption('enable-forms', true);
    }
    public function allowImages() : PdfInterface {
        return $this->setOption('images', true);
    }
    public function disallowImages() : PdfInterface {
        return $this->setOption('no-images', true);
    }
    public function disableJavascript() : PdfInterface {
        return $this->setOption('disable-javascript', true);
    }
    public function enableJavascript() : PdfInterface {
        return $this->setOption('enable-javascript', true);
    }
    public function setJavascriptDelay(string $delayMilliseconds = '200') : PdfInterface {
        return $this->setOption('javascript-delay', $delayMilliseconds);
    }
    public function enableLocalFileAccess() : PdfInterface {
        return $this->setOption('enable-local-file-access', true);
    }
    public function disableLocalFileAccess(string $path = null) : PdfInterface {
        if(!is_null($path)) {
            return $this->setOption('disable-local-file-access', true)->setOption('allow', $path);
        }
        return $this->setOption('disable-local-file-access', true);
    }
    public function enableInternalLinks() : PdfInterface {
        return $this->setOption('enable-internal-links', true);
    }
}
