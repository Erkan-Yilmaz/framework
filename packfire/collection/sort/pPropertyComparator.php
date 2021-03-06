<?php
pload('IComparator');

/**
 * Compares two objects based on their properties
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.collection.sort
 * @since 1.0-sofia
 */
abstract class pPropertyComparator implements IComparator {
    
    /**
     * Compares two objects based on a particular component
     * @param object $value1 First value to compare
     * @param object $value2 Second value to compare
     * @param string $com Component Name
     * @return integer Return 0 if both values are equal, 1 if $value2 > $value1
     *                 and -1 if $value2 < $value1
     * @since 1.0-sofia
     */
    private function compareComponent($value1, $value2, $com){
        $time2com = $value2->$com();
        $time1com = $value1->$com();
        $ret = 0;
        if($time2com > $time1com){
            $ret = 1;
        }
        if($time2com < $time1com){
            $ret = -1;
        }       
        return $ret;
    }
    
    /**
     * Compare between two objects based on their components
     * @param object $o1 The first object to compare
     * @param object $o2 The second object to compare
     * @param array|pList $components The components to compare
     * @return integer Returns 0 if they are the same, -1 if $o1 < $o2 and 1 if
     *                 $o1 > $o2.
     * @since 1.0-sofia
     */
    protected function compareComponents($o1, $o2, $components) {
        $componentComp = 0;
        foreach($components as $comp){
            $componentComp = $this->compareComponent($o1, $o2, $comp);
            if($componentComp != 0){
                break;
            }
        }
        return $componentComp;
    }
    
}