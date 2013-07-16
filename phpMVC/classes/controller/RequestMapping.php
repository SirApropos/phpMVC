<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/15/13
 * Time: 1:15 AM
 */

class RequestMapping {
    /**
     * @var RequestMapping[]
     */
    private $allowed_methods;
    /**
     * @var RequestMethod
     */
    private $method;
    /**
     * @var string
     */
    private $path;

    public static $mapping = [
        "fields" => [
            "method" => [
                "type" => "RequestMethod"
            ]
        ]
    ];

    /**
     * @param RequestMapping[] $allowed_methods
     */
    public function setAllowedMethods(array $allowed_methods)
    {
        $this->allowed_methods = $allowed_methods;
    }

    /**
     * @return RequestMapping[]
     */
    public function getAllowedMethods()
    {
        return $this->allowed_methods;
    }

    /**
     * @param string RequestMethod
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return RequestMethod
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}