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

	/**
	 * @var boolean
	 */
	private $https;

    /**
     * @var SecurityMapping
     */
    private $security;

	private $allowed_roles = [];

	private $denied_roles = [];

	/**
	 * @var FilterMapping
	 */
	private $filters;

    public static $mapping = [
        "fields" => [
            "method" => [
                "type" => "RequestMethod"
            ],
            "security" =>[
                "type" => "SecurityMapping"
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

    /**
     * @param array $allowed_roles
     */
    public function setAllowedRoles($allowed_roles)
    {
        $this->allowed_roles = $allowed_roles;
    }

    /**
     * @return array
     */
    public function getAllowedRoles()
    {
        return $this->allowed_roles;
    }

    /**
     * @param array $denied_roles
     */
    public function setDeniedRoles($denied_roles)
    {
        $this->denied_roles = $denied_roles;
    }

    /**
     * @return array
     */
    public function getDeniedRoles()
    {
        return $this->denied_roles;
    }

    /**
     * @param \SecurityMapping $security
     */
    public function setSecurity($security)
    {
        $this->security = $security;
    }

    /**
     * @return \SecurityMapping
     */
    public function getSecurity()
    {
        return $this->security;
    }

	/**
	 * @return boolean
	 */
	public function isHttps() {
		return $this->https;
	}

	/**
	 * @param boolean $https
	 */
	public function setHttps($https) {
		$this->https = $https;
	}

	/**
	 * @return FilterMapping
	 */
	public function getFilters() {
		return $this->filters;
	}

	/**
	 * @param FilterMapping $filters
	 */
	public function setFilters($filters) {
		$this->filters = $filters;
	}
}