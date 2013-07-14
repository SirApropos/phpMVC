<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 11:19 PM
 */

interface Mapper {
    /**
     * @param string $contentType
     * @return bool
     */
    public function canRead($contentType);

    /**
     * @param string $contentType
     * @return bool
     */
    public function canWrite($contentType);

    /**
     * @param object $obj
     * @return string
     */
    public function write($obj);

    /**
     * @param string $str
     * @return object
     */
    public function read($str);
}