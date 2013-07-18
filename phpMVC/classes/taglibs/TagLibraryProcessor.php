<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/16/13
 * Time: 9:33 PM
 */

class TagLibraryProcessor {

    /**
     * @var array
     */
    private $vars;

    /**
     * @var SimpleXMLElement[]
     */
    private $imports;

    /**
     * @var TagLibrary[]
     */
    private $taglibs = [];

    /**
     * @var string
     */
    private $xml;

    /**
     * @param string $xml
     * @param array $vars
     */
    function __construct($xml, array $vars)
    {
        libxml_use_internal_errors();
        $matches = [];
        preg_match_all("`<%@ *import([^>]+)>`", $xml, $matches, PREG_SET_ORDER);
        $str = preg_replace("`<%@ *import[^>]+>`","",$xml);
        $this->imports = [];
        foreach($matches as $match){
            $this->imports[] = simplexml_load_string("<import ".$match[1]." />");
        }
        $this->vars = $vars;
        $this->xml = $str;
    }

    function process(array $vars){
        $manager = IOCContainer::getInstance()->resolve("TagLibraryManager");
        /**
         * @var TagLibraryManager $manager
         */
        foreach($this->imports as $import){
            if(!isset($import['prefix']) || !isset($import['schema'])){
                throw new IllegalArgumentException("All imports must specify both prefix and schema.");
            }
            $this->taglibs[(string)$import['prefix']] = $manager->resolve((string)$import['schema']);
        }
        $event = new TagLibraryEvent($this, $this->xml, $vars);
        $event->process();
    }

    /**
     * @param $prefix
     * @return null|TagLibrary
     */
    function getTagLibrary($prefix){
        return isset($this->taglibs[$prefix]) ? $this->taglibs[$prefix] : null;
    }
}