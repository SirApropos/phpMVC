<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 7/13/13
 * Time: 3:46 PM
 */
class PageView implements View
{
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
        $path = MVCConfig::$VIEW_DIR.$this->page;
        if(!preg_match("`^.+\\.psp$`", $path)){
            $path.=".psp";
        }
        if(!file_exists($path)){
            throw new ViewResolverException("Could not locate page: ".$path);
        }
        $processor = new TagLibraryProcessor(file_get_contents($path), $this->vars, $path);
        $processor->process();
    }

    /**
     * @param HttpResponse $response
     * @return mixed
     */
    public function prepareResponse(HttpResponse $response)
    {
    }

}
