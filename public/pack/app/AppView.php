<?php
pload('packfire.view.pView');
pload('AppTemplate');
pload('AppTheme');

/**
 * The generic application view class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.app
 * @since 1.0-sofia
 */
abstract class AppView extends pView {
    
    protected function template($template) {
        if(is_string($template)){
            $template = AppTemplate::load($template);
        }
        return parent::template($template);
    }

    protected function theme($theme) {
        if(is_string($theme)){
            $theme = AppTheme::load($theme);
        }
        return parent::theme($theme);
    }
    
}