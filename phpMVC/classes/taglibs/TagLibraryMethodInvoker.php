<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/17/13
 * Time: 9:53 PM
 */

class TagLibraryMethodInvoker {
    /**
     * @param TagLibrary $taglib
     * @param $method
     * @param array $attributes
     * @param TagLibraryEvent $event
     * @throws NoSuchTagLibraryException
     */
    public function invoke(TagLibrary $taglib, $method, array $attributes, TagLibraryEvent $event){
        $mapping = $taglib->getMapping()->getMappings();
        $method = isset($mapping[$method]) ? $mapping[$method] : $method;
        $event->setTagName($method);
        $clazz = new ReflectionClass(get_class($taglib));
        if($clazz->hasMethod($method)){
            $clazz->getMethod($method)->invokeArgs($clazz, [$event, $attributes]);
        }else{
            throw new NoSuchTagLibraryException("Tag Library ".$clazz->getName()." does not contain tag: ".$method);
        }
    }
}