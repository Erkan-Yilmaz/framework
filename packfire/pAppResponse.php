<?php
pload('IAppResponse');

/**
 * Application response
 * Decorator pattern
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class pAppResponse implements IAppResponse {
    
    /**
     * The internal response
     * @var IAppResponse
     * @since 1.0-sofia 
     */
    protected $response;
    
    /**
     * Create a new pAppResponse
     * @param IAppResponse $response The internal response
     * @since 1.0-sofia
     */
    public function __construct($response){
        $this->response = $response->response();
    }
    
    /**
     * Get the internal response
     * @return IAppResponse Returns the internal response
     * @since 1.0-sofia
     */
    public function response(){
        return $this->response;
    }
    
}