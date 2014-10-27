<?php

/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 8/17/13
 * Time: 10:53 PM
 */
class SimpleAuthenticationFilter implements AuthenticationFilter {
	public static $mapping = [
		"fields" => [
			"container" =>[
				"autowired" => true,
				"type" => "IOCContainer"
			]
		]
	];

	/**
	 * @var IOCContainer
	 */
	private $container;

	/**
	 * @param HttpRequest $request
	 * @param GrantedAuthority $authority
	 * @return bool stopFiltering
	 */
	public function doFilter(HttpRequest $request, GrantedAuthority $authority) {
		if (session_status() != PHP_SESSION_ACTIVE) {
			session_start();
		}
		if (isset($_SESSION['user']) || $this->doLogin()) {
			$user = MappingUtils::bindObject($_SESSION['user'], "User");
			$this->container->register($user);
//			$authority->addRole("USER");
		}
	}

	/**
	 * @return bool
	 */
	private function doLogin() {
		$result = false;
		if(isset($_COOKIE['member_id'])){
			/**
			 * @var DataSource $ds
			 */
			$ds = $this->container->resolve("DataSource");
			if(!$ds->isConnected()){
				throw new DBException("Not connected to database.");
			}
			try {
				$_SESSION['user'] = $ds->fetchAssoc("SELECT a.username, a.id FROM site.accounts a WHERE a.id = :user_id " .
					"AND a.password = :pass_hash", ["user_id" => $_COOKIE['member_id'], "pass_hash" => $_COOKIE["pass_hash"]]);
				$result = true;
			}catch(NoResultException $ex){}
		}
		return $result;
	}
}