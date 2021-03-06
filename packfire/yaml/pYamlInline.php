<?php
pload('pYamlPart');
pload('pYamlValue');
pload('pYamlInline');

/**
 * pYamlInline Description
 *
 * @author Sam Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package package
 * @since 1.0-sofia
 */
class pYamlInline {
    
    /**
     *
     * @var string
     */
    private $line;
    
    /**
     *
     * @var integer
     */
    private $length;
    
    /**
     *
     * @param string $line 
     */
    public function __construct($line){
        $this->line = $line;
        $this->length = strlen($line);
    }
    
    /**
     * Load a line and work on it.
     * @param string $line The line to work on
     * @return pYamlInline Returns the object that will work on the string
     * @since 1.0-sofia
     */
    public static function load($line){
        $inline = new self($line);
        return $inline;
    }
    
    /**
     * Parse a YAML inline value. 
     * @param integer $position (optional) The position of the line to start parsing from.
     * @param array $breakers (optional) The delimiter that determines when to stop parsing the value
     * @return mixed The value parsed.
     * @since 1.0-sofia
     */
    public function parseValue(&$position = 0, $breakers = array('{', ':','#', "\n")){
        $line = $this->line;
        $length = $this->length;
        $eov = false;
        while($position < $length && !$eov){
            switch($line[$position]){
                case '[':
                    --$position;
                    $value = $this->parseSequence($position);
                    $eov = true;
                    break;
                case '{':
                    --$position;
                    $value = $this->parseMap($position);
                    $eov = true;
                    break;
                case ' ':
                case ':':
                    // nothing to do here~
                    // fly off!
                    break;
                default:
                    $value = $this->parseScalar($position, $breakers);
                    $eov = true;
                    break;
            }
            ++$position;
        }
        return $value;
    }
    
    /**
     * Perform a inline key-value parsing
     * @param integer $position (optional) The position of the line to start parsing from.
     * @param array $breakers (optional) The delimiter that determines when to stop parsing the value
     * @return array Returns the array ($key => $value) data parsed from the line. 
     * @return 1.0-sofia
     */
    public function parseKeyValue(&$position = 0, $breakers = array('{', pYamlPart::KEY_VALUE_SEPARATOR,'#', "\n")){
        $result = array();
        $key = $this->parseScalar($position, $breakers, false);
        ++$position;
        $value = null;
        $value = $this->parseValue($position, $breakers);
        if($key){
            $result[$key] = $value;
        }else{
            $result[] = $value;
        }
        --$position;
        return $result;
    }
    
    /**
     * Parse a scalar value.
     * @param integer $position (optional) The position of the line to start parsing from.
     * @param array $breakers (optional) The delimiter that determines when to stop parsing the value
     * @param boolean $translate Whether or not to post-process the scalar value 
     * @return string Returns the parsed value.
     * @since 1.0-sofia
     */
    public function parseScalar(&$position = 0, $breakers = array('#', "\n"), $translate = true){
        $result = '';
        $line = $this->line;
        $length = $this->length;
        if($length > 0){
            if($length > 1 && in_array($line[$position], pYamlPart::quotationMarkers())){
                $result = $this->parseQuotedString($position);
            }else{
                $result = $this->parseNormalScalar($position, $breakers);
            }
        }
        $result = trim($result);
        if($translate){
            $result = pYamlValue::translateScalar($result);
        }else{
            $result = pYamlValue::stripQuote($result);
        }
        return $result;
    }
    
    /**
     * Parse a normal non-quoted scalar
     * @param integer $position (optional) The position of the line to start parsing from.
     * @param array $breakers (optional) The delimiter that determines when to stop parsing the value
     * @return Returns the parsed value
     * @since 1.0-sofia
     */
    private function parseNormalScalar(&$position = 0, $breakers = array('#', "\n")){
        $offset = $position;
        $line = $this->line;
        $length = $this->length;
        while($position < $length){
            if(in_array($line[$position], $breakers)){
                --$position;
                break;
            }
            ++$position;
        }
        return substr($line, $offset, $position - $offset + 1);
    }
    
    /**
     * Parse a quoted string
     * @param integer $position (optional) The position of the line to start parsing from.
     * @return string Returns the string parsed from the line
     * @since 1.0-sofia
     */
    private function parseQuotedString(&$position = 0){
        $line = $this->line;
        $length = $this->length;
        $offset = $position;
        $quote = $line[$position];
        $escape = false;
        ++$position;
        while($position < $length){
            if(!$escape){
                if($line[$position] == $quote){
                    break;
                }elseif($line[$position] == '\\'){
                    $escape = true;
                }
            }else{
                $escape = false;
            }
            ++$position;
        }
        return substr($line, $offset, $position - $offset + 1);
    }
    
    /**
     * Parse an inline sequence
     * @param integer $position (optional) The position of the line to start parsing from.
     * @param string $separator (optional) The item separator. Defaults to a comma.
     * @return array Returns the array sequence.
     * @since 1.0-sofia
     */
    public function parseSequence(&$position = 0, $separator = ','){
        $result = array();
        $line = $this->line;
        $length = $this->length;
        $eos = false;
        ++$position;
        while($position < $length && !$eos){
            switch($line[$position]){
                case '[': // nested sequence
                    $result[] = $this->parseSequence($position);
                    break;
                case '{': // nested map
                    $result[] = $this->parseMap($position);
                    break;
                case ']': // end of sequence
                    $eos = true;
                    break;
                case "\n":
                case ' ':
                case $separator:
                    // do nothing here
                    break;
                default:
                    $result[] = $this->parseScalar($position, array(',', ']', '#'));
                    break;
            }
            ++$position;
        }
        return $result;
    }
    
    /**
     * Parse a map
     * @param integer $position (optional) The position of the line to start parsing from.
     * @param string $separator (optional) The item separator. Defaults to a comma.
     * @return array Returns the array map.
     * @since 1.0-sofia
     */
    public function parseMap(&$position = 0, $separator = ','){
        $result = array();
        $line = trim($this->line);
        $length = strlen($line);
        $eos = false;
        ++$position;
        // {computer: food, come: home, maybe: yes}
        while($position < $length && !$eos){
            switch($line[$position]){
                case '}': // end of sequence
                    $eos = true;
                    break;
                case "\n":
                case ' ':
                case $separator:
                    continue;
                    break;
                default:
                    $data = $this->parseKeyValue($position, array(':', $separator, '}'));
                    $result = array_merge($result, $data);
                    break;
            }
            ++$position;
        }
        --$position;
        return $result;
    }
    
}