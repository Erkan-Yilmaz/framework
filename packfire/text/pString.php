<?php
pload('packfire.collection.pList');

/**
 * A String object
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.text
 * @since 1.0-sofia
 */
class pString implements Countable {

    /**
     * The internal value of this string
     * @var string
     * @since 1.0-sofia
     */
    private $value = '';

    /**
     * Create a new pString object
     * @param string|pString $v The string value to initialize with
     * @since 1.0-sofia
     */
    function __construct($v = '') {
        if($v instanceof pString){
            $v = $v->value();
        }
        $this->value($v);
    }

    /**
     * Get or set the internal string value
     * @param string $v (optional) Set the new value of the string
     * @return string Returns the internal string value.
     * @since 1.0-sofia
     */
    public function value($v = null) {
        if (func_num_args() == 1) {
            $this->value = $v;
        }
        return $this->value;
    }

    /**
     * Strip whitespace from the beginning and end of a string
     * @return pString Returns the string trimmed.
     * @since 1.0-sofia
     */
    public function trim() {
        return new pString(trim($this->value()));
    }

    /**
     * Strip whitespace from the beginning of a string
     * @return pString Returns the string trimmed.
     * @since 1.0-sofia
     */
    public function trimLeft() {
        return new pString(ltrim($this->value()));
    }

    /**
     * Strip whitespace from the end of a string
     * @return pString Returns the string trimmed.
     * @since 1.0-sofia
     */
    public function trimRight() {
        return new pString(rtrim($this->value()));
    }

    /**
     * Split the string into several strings
     * @param pString|string $c The delimiter to split the string
     * @return pList Returns the list of split strings 
     * @since 1.0-sofia
     */
    public function split($c) {
        $strs = explode($c, $this->value());
        $r = new pList();
        foreach($strs as $s){
            $r->add(new pString($s));
        }
        return $r;
    }

    /**
     * Replaces occurances of $a with $b in the string
     * @param pString|array|pList $a A string, or a collection of string, to be replaced
     * @param pString|array|pList $b A string, or a collection of string, to be the replacement
     * @param integer $limit (optional) The maximum number of occurances to replace
     * @return pString Returns the resulting string
     * @since 1.0-sofia
     */
    public function replace($a, $b, $limit = null) {
        // todo pList to array
        if ($limit !== null) {
            return new pString(str_replace($a, $b, $this->value(), $limit));
        } else {
            return new pString(str_replace($a, $b, $this->value()));
        }
    }

    /**
     * Find the position of the first occurance of the string $s in the string
     * @param pString|string $s The string to search for
     * @param integer $offset (optional) The position to start searching for
     * @return integer A non-negative number indicating the position of $s in the string, or -1 if not found. 
     */
    public function indexOf($s, $offset = 0) {
        if (!($s instanceof pString)) {
            $s = new pString($s);
        }
        $result = strpos($this->value(), $s->value(), $offset);
        if ($result === false) {
            return -1;
        }
        return $result;
    }

    /**
     * Find the position of the last occurance of the string $s in the string
     * @param pString|string $s The string to search for
     * @param integer $offset (optional) The position to start searching for
     * @return integer Returns a non-negative number indicating the position of
     *                 s in the string, or -1 if not found. 
     * @since 1.0-sofia
     */
    public function lastIndexOf($s, $offset = 0) {
        if (!($s instanceof pString)) {
            $s = new pString($s);
        }
        $result = strrpos($this->value(), $s->value(), $offset);
        if ($result === false) {
            return -1;
        }
        return $result;
    }

    /**
     * Find all unique occurances of a substring in the string
     * @param pString|string $s The substring to find occurances
     * @param integer $offset (optional) The position to start searching for
     * @return pList Returns the list of index where the substring occurred.
     * @since 1.0-sofia
     */
    public function occurances($s, $offset = 0) {
        if (!($s instanceof pString)) {
            $s = new pString($s);
        }
        $occurances = new pList();
        while (($idx = $this->indexOf($s, $offset)) >= 0) {
            $occurances->add($idx);
            $offset = $idx + $s->length();
        }
        return $occurances;
    }

    /**
     * Fetch a part of the string.
     * @param integer $start The starting position of the string to fetch from
     * @param integer $length (optional) The number of characters to fetch. If not specified, the method will fetch from start to the end of the string
     * @return pString Returns the part of the string fetched.
     * @since 1.0-sofia
     */
    public function substring($start, $length = null) {
        if (func_num_args() == 2) {
            return new pString(substr($this->value(), $start, $length));
        } else {
            return new pString(substr($this->value(), $start));
        }
    }

    /**
     * Set all alphabets in the string to upper case
     * @return pString Returns the resulting string.
     * @since 1.0-sofia
     */
    public function toUpper() {
        return new pString(strtoupper($this->value()));
    }

    /**
     * Set all alphabets in the string to lower case
     * @return pString Returns the resulting string.
     * @since 1.0-sofia
     */
    public function toLower() {
        return new pString(strtolower($this->value()));
    }

    /**
     * Pad the left side of the string to the desired length
     * @param string $char The string used for padding
     * @param integer $length The maximum amount of characters for the string
     * @return pString Returns the resulting string
     * @since 1.0-sofia
     */
    public function padLeft($char, $length) {
        return new pString(str_pad($this->value(), $length, $char, STR_PAD_LEFT));
    }

    /**
     * Pad the right side of the string to the desired length
     * @param string $char The string used for padding
     * @param integer $length The maximum amount of characters for the string
     * @return pString Returns the resulting string 
     * @since 1.0-sofia
     */
    public function padRight($char, $length) {
        return new pString(str_pad($this->value(), $length, $char, STR_PAD_RIGHT));
    }

    /**
     * Pad both sides of the string to the desired length equally
     * @param string $char The string used for padding
     * @param integer $length The maximum amount of characters for the string
     * @return pString Returns the resulting string 
     * @since 1.0-sofias
     */
    public function padBoth($char, $length) {
        return new pString(str_pad($this->value(), $length, $char, STR_PAD_BOTH));
    }

    /**
     * Get the length of the string
     * @return integer Returns the length of the string
     * @since 1.0-sofias
     */
    public function length() {
        return strlen($this->value());
    }
    
    /**
     * Used by Countable for count() functions
     * @return integer
     * @internal
     * @ignore
     * @since 1.0-sofia
     */
    public function count() {
        return $this->length();
    }
    
    /**
     * For typecasting to string
     * @return string 
     * @ignore
     * @internal
     * @since 1.0-sofia
     */
    public function __toString() {
        return $this->value();
    }
    
}