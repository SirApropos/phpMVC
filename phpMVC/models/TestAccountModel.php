<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 9/2/13
 * Time: 4:54 PM
 */

class TestAccountModel {
	private $username;
	private $id;

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return mixed
	 */
	public function getUsername()
	{
		return $this->username;
	}

}