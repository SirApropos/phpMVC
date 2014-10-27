<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:46 PM
 */
class PageView implements View
{
	private static $extensions = [".psp", ".php", ".xhtml", ".html"];

	/**
	 * @var string
	 */
	private $page;

	/**
	 * @var array
	 */
	private $vars;

	/**
	 * @param $page
	 * @param array $vars
	 */
	function __construct($page, $vars=[]){
		$this->page = $page;
		$this->vars = $vars;
	}

	/**
	 * @throws ViewResolverException
	 * @return mixed
	 */
	public function render()
	{
		$path = MVCConfig::getInstance()->getViewDir().$this->page;
		if(!file_exists($path)){
			foreach($this->getDefaultExtensions() as $extension){
				if(file_exists($path.$extension)){
					$path .= $extension;
					break;
				}
			}
		}
		if(!file_exists($path)){
			throw new ViewResolverException("Could not locate page: ".$path);
		}
		if(!preg_match("`^.+\\.psp$`", $path)) {
			$processor = new TagLibraryProcessor(file_get_contents($path), $this->vars, $path);
			$processor->process();
		}else{
			echo file_get_contents($path);
		}
	}

	/**
	 * @param HttpResponse $response
	 * @return mixed
	 */
	public function prepareResponse(HttpResponse $response)
	{
	}

	protected function getDefaultExtensions(){

	}
}
