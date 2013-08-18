<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/17/13
 * Time: 10:53 PM
 */

class SimpleAuthenticationFilter implements AuthenticationFilter{
    /**
     * @param HttpRequest $request
     * @param GrantedAuthority $authority
     * @return bool stopFiltering
     */
    public function doFilter(HttpRequest $request, GrantedAuthority $authority)
    {
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
        if(isset($_SESSION['phpMVC_roles']) && is_array($_SESSION['phpMVC_roles'])){
              $authority->setRoles($_SESSION['phpMVC_roles']);
        }
    }
}