<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 9/2/13
 * Time: 7:23 PM
 */

class Timer {

	/**
	 * @var Timer[]
	 */
	private static $timers = array();

	/**
	 * @var Timer[]
	 */
	private static $names = array();

	/**
	 * @var float
	 */
	private $start;

	/**
	 * @var float
	 */
	private $stop;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $category;

	/**
	 * @param string $name
	 * @param string $category
	 */
	private function __construct($name, $category){
		$this->name = $name;
		$this->category = $category;
	}

	/**
	 * @param string $name
	 * @param string $category
	 * @return Timer
	 */
	public static function create($name, $category="undefined"){
		$timer = new Timer($name, $category);
		self::$timers[] = &$timer;
		self::$names[$name] = &$timer;
		$timer->start();
		return $timer;
	}

	/**
	 * @param $name
	 * @return Timer
	 */
	public static function getTimer($name){
		return isset(self::$names[$name]) ? self::$names[$name] : null;
	}

	public function start(){
		$this->start = $this->_getCurrentTime();
	}

	public function stop(){
		$this->stop = $this->_getCurrentTime();
	}

	public static function getTimers(){
		return self::$timers;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getCategory()
	{
		return $this->category;
	}

	public function getTime(){
		return (isset($this->stop) ? $this->stop : $this->_getCurrentTime()) - $this->start;
	}

	private function _getCurrentTime(){
		return time() + microtime();
	}
}