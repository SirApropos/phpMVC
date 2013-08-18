<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/17/13
 * Time: 11:51 PM
 */

class SecurityMapping {
    /**
     * @var array
     */
    private $allowed_roles;

    /**
     * @var array
     */
    private $denied_roles;

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


}