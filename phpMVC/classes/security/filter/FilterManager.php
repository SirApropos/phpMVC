<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/17/13
 * Time: 10:56 PM
 */

class FilterManager {
    /**
     * @var AuthenticationFilter[]
     */
    private $filters = array();

    public function FilterManager(){
    }

    /**
     * @param AuthenticationFilter $filter
     */
    public function addFilter(AuthenticationFilter $filter){
        array_push($this->filters, $filter);
    }

    /**
     * @return array|AuthenticationFilter[]
     */
    public function getFilters(){
        return $this->filters;
    }

    /**
     * @param HttpRequest $request
     * @return GrantedAuthority;
     */
    public function doFilter(HttpRequest $request){
        $authority = new GrantedAuthority();
        foreach($this->filters as $filter){
            if($filter->doFilter($request, $authority)){
                break;
            }
        }
        return $authority;
    }
}