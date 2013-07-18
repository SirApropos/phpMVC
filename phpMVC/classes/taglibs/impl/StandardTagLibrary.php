<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/16/13
 * Time: 9:53 PM
 */

class StandardTagLibrary extends TagLibrary {
    public static $mapping = [
        "methods" => [
            "foreach" => "for_each"
        ]
    ];
    public static function for_each(TagLibraryEvent $event, $attributes){
        for($i = 0; $i < 10; $i++){
            $event->setVar("key", $i);
            $event->process();
        }
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return "http://www.caiyern.net/psp/taglibs/core";
    }
}
new StandardTagLibrary();