<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/15/13
 * Time: 1:22 AM
 */

class ControllerMapping implements Mapping {
    /**
     * @var RequestMapping[]
     */
    private $mappings = [];

    /**
     * @param RequestMapping $mapping
     */
    public function addMapping(RequestMapping $mapping){
        array_push($this->mappings, $mapping);
    }

    /**
     * @return RequestMapping[]
     */
    public function getMappings(){
        return $this->mappings;
    }

    /**
     * @param $obj
     * @return mixed
     */
    function bind($obj)
    {
        foreach($obj as $mapping){
            $this->addMapping(MappingUtils::bindObject($mapping, "RequestMapping"));
        }
    }
}