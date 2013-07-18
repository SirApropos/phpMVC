<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/16/13
 * Time: 9:53 PM
 */

class StandardTagLibrary extends TagLibrary {
    public static $mapping = [
        "methods" => [
            "foreach" => "_foreach",
            "for" => "_for",
            "include" => "_include"
        ]
    ];
    public static function _foreach(TagLibraryEvent $event, $attr){
        self::requireAttributes(['in','as'], $attr, $event);
        $arr = $event->getVar($attr['in']);
        if(is_object($arr)){
            $arr = get_object_vars($arr);
        }
        $as = $attr['as'];
        $keyName = isset($attr['key']) ? $attr['key'] : null;
        if(is_array($arr)){
            foreach($arr as $key => $value){
                $event->setVar($as, $value);
                if($keyName){
                    $event->setVar($keyName, $key);
                }
                $event->process();
            }
        }else{
            throw new InvalidArgumentException($attr['in']."is not an array or object.");
        }
    }

    public static function _for(TagLibraryEvent $event, $attr){
        self::requireAttributes(["var", "start", "end"], $attr, $event);
        $var = $attr['var'];
        $start = is_numeric($attr['start']) ? $attr['start'] : $event->getVar($attr['start']);
        $end = is_numeric($attr['end']) ? $attr['end'] : $event->getVar($attr['end']);
        if(!is_numeric($start)){
            throw new IllegalArgumentException("Key specified in 'start' is not valid.");
        }
        if(!is_numeric($end)){
            throw new IllegalArgumentException("Key specified in 'end' is not valid.");
        }
        $i = $start;
        $inc = $start < $end ? 1 : -1;
        $end += $inc; //Adjust to make it inclusive.
        while($i != $end){
            $event->setVar($var, $i);
            $event->process();
            $i+=$inc;
        }
    }

    public static function _include(TagLibraryEvent $event, array $attr){
        self::requireAttributes(["path"], $attr, $event);
        $path = $event->getProcessor()->getPath()."\\".$attr['path'];
        if(file_exists($path)){
            $processor = new TagLibraryProcessor(file_get_contents($path), $event->getVars(), $path);
            $processor->process();
        }else{
            throw new IllegalArgumentException("No such file: ".$path);
        }
    }
    /**
     * @return string
     */
    public function getNamespace()
    {
        return "http://www.caiyern.net/psp/taglibs/core";
    }

    private static function requireAttributes(array $required, array &$attr, TagLibraryEvent $event){
        foreach($required as $key){
            if(!isset($attr[$key])){
                throw new InvalidArgumentException($key." must be specified when using StandardTagLibrary::".$event->getTagName().".");
            }
        }
    }
}
new StandardTagLibrary();