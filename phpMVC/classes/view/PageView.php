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
     * @param string $page
     */
    function __construct($page){
        $this->page = $page;
    }

    /**
     * @return void
     */
    public function render()
    {
        $path = Config::$VIEW_DIR.$this->page;
        if(!preg_match("`^.+\\.psp$`", $path)){
            $path.=".psp";
        }
        if(!file_exists($path)){
            throw new ViewResolverException("Could not locate page: ".$path);
        }
        $contents = file_get_contents($path);
        echo $contents;
    }
}
