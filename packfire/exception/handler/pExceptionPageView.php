<?php
pload('packfire.view.pView');
pload('packfire.template.pHtmlTemplate');

/**
 * pExceptionPageView Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception.handler
 * @since 1.0-sofia
 */
class pExceptionPageView extends pView {
    
    /**
     * The exception
     * @var pException
     * @since 1.0-sofia
     */
    private $exception;
    
    /**
     * Create a new Exception Page view
     * @param Exception $exception The exception
     * @since 1.0-sofia
     */
    public function __construct($exception){
        parent::__construct();
        $this->exception = $exception;
    }
    
    /**
     * Create the page
     * @since 1.0-sofia 
     */
    protected function create() {
        $this->template(new pHtmlTemplate(file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'error.html')));
        $this->define('title', 'Error ' . $this->exception->getCode());
        $this->define('file',  $this->exception->getFile());
        $this->define('line',  $this->exception->getLine());
        $this->define('message',  $this->exception->getMessage());
        $this->define('stack', $this->exception->getTraceAsString());
    }
    
}