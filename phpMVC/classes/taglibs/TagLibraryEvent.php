<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/16/13
 * Time: 9:33 PM
 */

class TagLibraryEvent {
	/**
	 * @var string
	 */
	private $xml;

	/**
	 * @var array
	 */
	private $vars;

	/**
	 * @var TagLibraryProcessor
	 */
	private $processor;

	/**
	 * @var TagLibraryMethodInvoker
	 */
	private static $invoker;

	/**
	 * @var string
	 */
	private $tagName;

	/**
	 * @param TagLibraryProcessor $processor
	 * @param string $xml
	 * @param array $vars
	 */
	function __construct(TagLibraryProcessor $processor, $xml, array $vars)
	{
		$this->processor = $processor;
		$this->vars = $vars;
		$this->xml = $xml;
		if(!isset(self::$invoker)){
			self::$invoker = IOCContainer::getInstance()->resolve("TagLibraryMethodInvoker");
		}
	}

	/**
	 * @param array $vars
	 */
	private function setVars($vars)
	{
		$this->vars = $vars;
	}

	/**
	 * @return array
	 */
	public function getVars()
	{
		return $this->vars;
	}

	/**
	 * @return string
	 */
	public function getXml()
	{
		return $this->xml;
	}

	/**
	 * @throws HtmlElementMismatchException
	 */
	public function process(){
		$str = $this->xml;
		$match = [];
		while(preg_match("`<([a-zA-Z0-9_]+)\:([a-zA-Z0-9_]+)[^<>]*?>`", $str, $match, PREG_OFFSET_CAPTURE)){
			$prefix = $match[1][0];
			$taglib = $this->processor->getTagLibrary($prefix);
			if(!is_null($taglib)){
				$openTag = $match[0][0];
				$method = $match[2][0];
				$contents = "";
				$this->printWithReplacers(substr($str,0,$match[0][1]));
				$str = substr($str, $match[0][1]+strlen($openTag));
				if(preg_match('`^<.+/>$`',$openTag)){
					//Self-closing tag.
				}else{
					$offset = 0;
					$count = 1;
					$child = [];
					while($count != 0){
						preg_match("`</?".$prefix.":".$method."[^<]*>`", $str, $child, PREG_OFFSET_CAPTURE, $offset);
						if(sizeof($child) > 0){
							if(preg_match("`^</`", $child[0][0])){
								$count--;
							}else{
								$count++;
							}
							$offset = $child[0][1]+1;
						}else{
							throw new HtmlElementMismatchException("Could not find closing tag for: $prefix:$method.");
						}
					}
					$contents = substr($str, 0, $offset-1);
					$str = substr($str, $offset-1+strlen($child[0][0]));
				}
				$event = new TagLibraryEvent($this->processor, $contents, $this->vars);
				$attributes = [];
				$matches = [];
				preg_match_all('`([a-zA-Z0-9]+)\s*=\s*\"([^"]+)"`', $openTag, $matches, PREG_SET_ORDER);
				$this->addAttributesToArr($attributes, $matches);
				//This could be done much more cleanly with a good regex pattern.
				//TODO: Figure out that pattern. and reduce this redundancy.
				$matches = [];
				preg_match_all("`([a-zA-Z0-9]+)\s*=\s*\'([^']+)'`", $openTag, $matches, PREG_SET_ORDER);
				$this->addAttributesToArr($attributes, $matches);
				self::$invoker->invoke($taglib, $method, $attributes, $event);
			}else{
				throw new NoSuchTagLibraryException("No Tag Library loaded with prefix: ".$prefix);
			}
		}
		$this->printWithReplacers($str);
	}
	private function addAttributesToArr(&$attributes, &$matches){
		foreach($matches as $match){
			$attributes[$match[1]] = $match[2];
		}
	}

	private function printWithReplacers($str){
		$matches = [];
		preg_match_all("`\\$\{([a-zA-Z_0-9]+(\\.\\$?[a-zA-Z_0-9]+(\(\))?)*)\}`", $str, $matches, PREG_SET_ORDER);
		foreach($matches as $match){
			$key = $match[1];
			$value = $this->getVar($key);
			$str = str_replace('${'.$key.'}',$value, $str);
		}
		echo $str;
	}

	public function setVar($key, $value){
		$this->vars[$key] = $value;
	}

	public function unsetVar($key){
		if(isset($this->vars[$key])){
			unset($this->vars[$key]);
		}
	}

	public function getVar($key){
		$key = preg_replace("`\\$\{(.+)\}`","$1", $key);
		$var = null;
		$match = array();
		if(isset($this->vars[$key])){
			$var = $this->vars[$key];
		}else if(preg_match("/^([a-zA-Z_0-9]+)((\\.\\$?[a-zA-Z_0-9]+(\(\))?)+)$/",$key, $match)){
			if(isset($this->vars[$match[1]])){
				$var = eval('return $this->vars[$match[1]]'.str_replace(".","->", $match[2]).";");
			}
		}
		return $var;
	}

	/**
	 * @param string $tagName
	 */
	public function setTagName($tagName)
	{
		$this->tagName = $tagName;
	}

	/**
	 * @return string
	 */
	public function getTagName()
	{
		return $this->tagName;
	}

	/**
	 * @return \TagLibraryProcessor
	 */
	public function getProcessor()
	{
		return $this->processor;
	}
}