<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 12:25 AM
 */
class ControllerMethod
{
	/**
	 * @var Controller
	 */
	private $controller;

	/**
	 * @var ReflectionClass
	 */
	private $clazz;

	/**
	 * @var RequestMapping
	 */
	private $mapping;

	private $path;

	/**
	 * @param \Controller $controller
	 */
	public function setController($controller)
	{
		$this->controller = $controller;
		$this->clazz = new ReflectionClass(get_class($controller));
	}

	/**
	 * @return \Controller
	 */
	public function getController()
	{
		return $this->controller;
	}

	/**
	 * @param \RequestMapping $mapping
	 */
	public function setMapping($mapping)
	{
		$this->mapping = $mapping;
	}

	/**
	 * @return \RequestMapping
	 */
	public function getMapping()
	{
		return $this->mapping;
	}

	/**
	 * @return ReflectionMethod
	 * @throws InvocationException
	 */
	public function getMethod(){
		$methodName = $this->mapping->getMethod()->getName();
		if($this->clazz->hasMethod($methodName)){
			return $this->clazz->getMethod($methodName);
		}else{
			throw new InvocationException("No such method: ".$methodName." in controller ".$this->clazz->getName());
		}
	}

	/**
	 * @return mixed
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @param mixed $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}


}
