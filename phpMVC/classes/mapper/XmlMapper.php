<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 11:22 PM
 */

class XmlMapper implements Mapper {

    /**
     * @param string $contentType
     * @return bool
     */
    public function canRead($contentType)
    {
        return $contentType == ContentType::TEXT_HTML || $contentType == ContentType::TEXT_XML;
    }

    /**
     * @param string $contentType
     * @return bool
     */
    public function canWrite($contentType)
    {
        return canRead($contentType);
    }

    /**
     * @param object $obj
     * @return string
     */
    public function write($obj)
    {
        return XmlUtils::toXml($obj);
    }

    /**
     * @param $str
     * @param ReflectionClass $clazz
     * @return mixed
     */
    public function read($str, ReflectionClass $clazz)
    {
        return XmlUtils::bindXml($str, $clazz);
    }
}