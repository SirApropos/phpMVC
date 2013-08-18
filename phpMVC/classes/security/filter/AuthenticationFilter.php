<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/17/13
 * Time: 10:32 PM
 */

interface AuthenticationFilter {
    /**
     * @param HttpRequest $request
     * @param GrantedAuthority $authority
     * @return bool stopFiltering
     */
    public function doFilter(HttpRequest $request, GrantedAuthority $authority);
}