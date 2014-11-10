<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/17/13
 * Time: 10:34 PM
 */

class GrantedAuthority {
	/**
	 * @var array
	 */
	private $roles = [];

	public function GrantedAuthority(){
	}

	/**
	 * @param array $roles
	 */
	public function setRoles($roles)
	{
		$this->roles = $roles;
	}

	/**
	 * @return array
	 */
	public function getRoles()
	{
		return $this->roles;
	}
	public function addRole($role){
		array_push($this->roles, $role);
	}

	public function hasRole($role){
		return in_array($role, $this->getRoles());
	}

}