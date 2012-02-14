<?php

/**
 * pUrl class
 * defines a URL object
 *
 */
class pUrl {

    /**
     * Scheme / Protocol of the URL e.g. http, https, ftp
     * @var string
     */
    private $scheme;

    /**
     * Host of the URL e.g. www.php.net, ftp.example.com
     * @var string
     */
    private $host;

    /**
     * Port number in the URL e.g. 8080, 2084
     * @var integer
     */
    private $port;

    /**
     * Username to login via URL
     * @var string
     */
    private $user;

    /**
     * Password of the authentication in URL
     * @var string
     */
    private $pass;

    /**
     * Requesting path of the URL e.g. /example/path/to/file.html
     * @var string
     */
    private $path;

    /**
     * GET parameters
     * @var pMap
     */
    private $params = array();

    /**
     * Fragment that appears after the hex (#)
     * @var string
     */
    private $fragment;

    /**
     * Create a new URL based on a URL string
     * @param string $url (optional) Create the URL object based on a URL string
     */
    public function __construct($url = false){
        if(func_num_args() == 1){
            $parts = parse_url($url);
            foreach($parts as $k => $v){
                $this->$k = $v;
            }
            if(isset($this->query)){
                parse_str($this->query, $this->params);
            }
            unset($this->query);
        }
        $this->params = new pMap($this->params);
    }

    /**
     * Get or set the fragment of the URL (i.e. string after the #)
     * @param string $f (optional) If specified, the function will set the new value.
     * @return string
     */
    public function fragment($f = null){
        if(func_num_args() == 1){
            $this->fragment = $f;
        }
        return $this->fragment;
    }

    /**
     * Get or set the host of the URL (e.g. www.google.com)
     * @param string $h (optional) If specified, the function will set the new value.
     * @return string
     */
    public function host($h = null){
        if(func_num_args() == 1){
            $this->host = $h;
        }
        return $this->host;
    }

    /**
     * Get or set the password in the URL
     * @param string $p (optional) If specified, the function will set the new value.
     * @return string
     */
    public function pass($p = null){
        if(func_num_args() == 1){
            $this->pass = $p;
        }
        return $this->pass;
    }

    /**
     * Get or set the port number of the URL (e.g. 8080)
     * @param string $p (optional) If specified, the function will set the new value.
     * @return string
     */
    public function port($p = null){
        if(func_num_args() == 1){
            $this->port = $p;
        }
        return $this->port;
    }

    /**
     * Get or set the path of the URL (e.g. /home/path/to)
     * @param string $p (optional) If specified, the function will set the new value.
     * @return string
     */
    public function path($p = null){
        if(func_num_args() == 1){
            $this->path = $p;
        }
        return $this->path;
    }

    /**
     * Get or set the scheme of the URL (e.g. http, https, ftp)
     * @param string $s (optional) If specified, the function will set the new value.
     * @return string
     */
    public function scheme($s = null){
        if(func_num_args() == 1){
            $this->scheme = $s;
        }
        return $this->scheme;
    }

    /**
     * Get or set the username of the URL
     * @param string $u (optional) If specified, the function will set the new value.
     * @return string
     */
    public function user($u = null){
        if(func_num_args() == 1){
            $this->user = $u;
        }
        return $this->user;
    }

    /**
     * Get or set the GET parameters of the URL
     * @param pMap $p (optional) If specified, the function will set the new value.
     * @return pMap
     */
    public function params($p = null){
        if(func_num_args() == 1){
            $this->params = $p;
        }
        if(is_array($this->params)){
            $this->params = new pMap($this->params);
        }
        return $this->params;
    }

    /**
     * Returns the URL object as a URL string
     * @return string
     */
    public function __toString(){
        $s = $this->scheme() . '://';
        if($this->user())
        {
            $s .= $this->user();
            if($this->pass()){
                $s .= ':' . $this->pass();
            }
            $s .= '@';
        }
        $s .= $this->host();
        if($this->port() != null){
            $s .= ':'.$this->port();
        }
        $s .= $this->path();
        $query = http_build_query($this->params());
        if($query){
            $s .= '?' . $query;
        }
        if($this->fragment()){
            $s .= '#' . $this->fragment();
        }
        return $s;
    }

    /**
     * URL encode a value so that it can be used safely in URL
     * @param mixed $var
     * @return string
     * @static
     */
    public static function encode($var){
        return urlencode($var);
    }

    /**
     * URL decode a value that was previously URL encoded
     * @param string $var
     * @return mixed
     * @static
     */
    public static function decode($var){
        return urldecode($var);
    }

    /**
     * Combine a base URL with a relative URL
     * @param string|pUrl $baseUrl The base URL
     * @param string $relativeUrl The relative URL to navigate based on the Base URL
     * @return pUrl The combined URL
     * @static
     */
    public static function combine($baseUrl, $relativeUrl){
        $path = null;
        if($baseUrl == ''){
            $path = $relativeUrl;
        }else{
            if(!($baseUrl instanceof self)){
                $baseUrl = new self($baseUrl);
            }
            $path = pPath::combine($baseUrl->path(), $relativeUrl);
        }
        $path = str_replace(array('\\','\\\\','//'), '/', $path);
        if($baseUrl instanceof self && $baseUrl->scheme()){
            $baseUrl->path($path);
        }else{
            $baseUrl = $path;
        }
        return $baseUrl;
    }
    
}
