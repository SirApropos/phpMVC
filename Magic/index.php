<?php
//**********************************************************************//
// Caiyern's Site Engine v2.0                                           //
// Copyright 2009 Nate Eckardt (Nate@Caiyern.net)                       //
//                                                                      //
// This file is part of Caiyern's Site Engine.                          //
//                                                                      //
// This script is free software: you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by //
// the Free Software Foundation, either version 3 of the License, or    //
// (at your option) any later version as long as this header remains    //
// intact in its entirety.                                              //
//**********************************************************************// 
	header('Content-Type: text/html; charset=utf-8');
	include("./classes/timer.php");
	$timer = new Timer;
	
	include("./classes/config.php");
	include("./classes/mysql.php");
	include("./classes/user.php");
	
	function __autoload($name){
		$path = "./classes/";
		if(!function_exists('find_classpath')){
			function find_classpath($path, $name){
				$result = false;
				$handle = opendir($path);
				while(($file = readdir($handle)) && !$result){
					if(!in_array($file, array(".",".."))){
						if(is_dir($path.$file)){
							$result = find_classpath($path.$file."/", $name);
						}else{
							if(strtolower($file) == $name.".php"){
								$result = $path.$file;
							}
						}
					}
				}
				return $result;
			}
		}
		$result = find_classpath($path, strtolower($name));
		if($result){
			require_once($result);
		}else{
			trigger_error("Could not find specified class: ".$name,E_USER_ERROR);
		}
	}
	include("./modules/error_handler.php");

	session_start(); //Session start must come after class declarations, or else objects will not be accessible.
	//Object creation
	$sql = Mysql::create(Config::mysql_server, Config::mysql_user, Config::mysql_pass, Config::mysql_db);
	
	include("./modules/logintester.php");

	//Base URLs
	define('UNSECURE', "http://".$_SERVER['SERVER_NAME']);
	define('SECURE', "https://".$_SERVER['SERVER_NAME']);
	define('BASE_URL',"/Magic/");

	
	if(isset($_GET['act']) && $_GET['act'] == "req" && $user->magic->id > 0){
		include("./request_modules/index.php");
		die();
	}

	
	include("./classes/messager.php");
	$messager = new Messager($sql, $user->magic->id, strtolower($_GET['page']));
	
	include("./classes/TaskManager.php");
	$taskmgr = new TaskManager($sql);
	$taskmgr->load_tasks();
	$taskmgr->delay_task("UpdatePrices");
	$taskmgr->run_tasks();
	
	if($user->magic->id > 0){
		$modules = array(
			"index" => array(
				"path" => "./modules/default.php",
				"title" => "Magic: the Gathering",
				"https" => false),
			"boosters" => array(
				"path" => "./modules/boosters.php", 
				"title" => "Booster Pack Manager",
				"https" => false,
				"scripts" => array(
					"./scripts/cards.js",	
					"./scripts/boosters.js",
					)
				),
			"store" => array(
				"path" => "./modules/store.php",
				"title" => "Store",
				"https" => false,
				"scripts" => array(
					"./scripts/cards.js",
					),
				"style" => array("./style/decks.css"),
				),
			"decks" => array(
				"path" => "./modules/view_decks.php",
				"title" => "Deck Manager",
				"https" => false,
				"scripts" =>array(
					"./scripts/cards.js",
					"./scripts/decks.js",
					),
				"style" => array("./style/decks.css"),
				),
			"deckbuilder" => array(
				"path" => "./modules/deckbuilder.php",
				"title" => "Deck editor",
				"https" => false,
				"lightweight" => true,
				"scripts" =>array(
					"./scripts/deckbuilder.js"
					),
				"style" => array("./style/decks.css"),
				),
			"trade_cards" => array(
				"path" => "./modules/trade_cards.php",
				"title" => "Trade Cards",
				"https" => false,
				"lightweight" => true,
				"scripts" =>array(
					"./scripts/cards.js",
					),
				"style" => array("./style/decks.css"),
				),
			"add_booster" => array(
				"path" => "./modules/add_booster.php",
				"title" => "Give Boosters",
				"https" => false),
			"global" => array(
				"scripts" => array("./scripts/jquery.js","./scripts/global.js"),
				"style" => array("./style/global.css"),
				),
			"manage_games" => array(
				"path" => "./modules/manage_games.php",
				"title" => "Manage Games",
				"https" => false,
				"scripts" =>array(
					"./scripts/manage_games.js",
					),
				),
			"trade" => array(
				"path" => "./modules/trade.php",
				"title" => "Trade Cards",
				"https" => false,
				"scripts" =>array(
					"./scripts/trade.js",
					),
				"style" => array(
					"./style/trade.css"
					)
				)			
			);
			$modules['play_game'] = array(
				"path" => "./modules/playfield.php",
				"title" => "Play Game",
				"https" => false,
				"lightweight" => true,
				"scripts" =>array(
					"./scripts/playfield.js",
					"./scripts/playfield/playfield.js",
					"./scripts/playfield/card_actions.js",
					"./scripts/playfield/zone_actions.js",
					),
				"style" => array("./style/playfield.css"),
				);
		if($user->magic->id==1){
			$modules['add_set'] = array(
				"path" => "./modules/add_set.php",
				"title" => "Set Manager",
				"https" => false
			);
			$modules['add_user'] = array(
				"path" => "./modules/add_user.php",
				"title" => "User Manager",
				"https" => false
			);
			$modules['dump_boosters'] = array(
				"path" => "./modules/dump_booster.php",
				"title" => "Add boosters to store",
				"https" => false
			);
			$modules['add_set_ids'] = array(
				"path" => "./modules/add_set_ids.php",
				"title" => "Add set ids",
				"https" => false
			);
			$modules['generate_potential'] = array(
				"path" => "./modules/generate_potential.php",
				"title" => "Generate Potential",
				"https" => false
			);
			}
	}else{		
		$modules = array(
			"index" => array(
				"path" => "./modules/default.php",
				"title" => "Magic: the Gathering",
				"https" => false));
	}
	if(isset($_GET['page']) && isset($modules[strtolower($_GET['page'])]['path']) && $user->loggedin){
		$page = $modules[strtolower($_GET['page'])];
	}else{
		$page = $modules["index"];
	}
	if(isset($page['scripts']) && is_array($page['scripts'])){
		$page['scripts'] = array_merge($modules['global']['scripts'],$page['scripts']);
	}else{
		$page['scripts'] = $modules['global']['scripts'];
	}
	if(isset($page['style']) && is_array($page['style'])){
		if(!$_GET['page'] != "play_game"){
			$page['style'] = array_merge($modules['global']['style'],$page['style']);
		}
	}else{
		$page['style'] = $modules['global']['style'];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $page['title']." &ndash; http://www.caiyern.net/";?></title>
		<?php
			if(is_array($page['style'])){
				foreach($page['style'] as $url){
					echo '<link rel="stylesheet" href="'.$url.'" type="text/css" />'."\r\n";
				}		
			}
			if(is_array($page['scripts'])){
				foreach($page['scripts'] as $url){
					echo '<script type="text/javascript" src="'.$url.'"></script>'."\r\n";
				}
			}
			if(sizeof($_GET) > 0){
		?>
		<script type="text/javascript">
<?php
			foreach($_GET as $var => $value){
				if(is_array($value)){
					$value = 'new Array("'.implode('","',$value).'")';
				}else{
					$value = "'".$value."'";
				}
				echo "vars['".$var."'] = ".$value.";\n";
			}
?>	
		</script>
		<?php
			}
			if(!$page['lightweight'] && $user->magic->id > 0){
				include("./modules/messages.php");
			}
		?>
	</head>
	<body>
<?php
if($page['lightweight']){
	include($page['path']);
}else{
?>
		<div style="width:100%;" class="table">
			<div class="row">
				<div class="cell">
					<img src="./images/magic_banner.jpg" alt="" />
				</div>
			</div>
			<div class="row">
				<div class="cell">
					<div class="table" style="width:100%">
						<div class="row">
							<div class="leftcolumn">
								<div id="leftcolumn">
									<?php include("./modules/links.php");?>
								</div>
							</div>
							<div class="cell" style="width:100%;vertical-align:top;">
								<div id="messages" style="display:none;">&nbsp;</div>
								<div id="rightcolumn" class="rightcolumn">
									<?php
										if(!$page['https'] || $_SERVER['HTTPS']){
											include($page['path']);
											if(isset($_GET['debug'])){
												echo "<br /><hr />";
												$sql->show_queries();
												$sql->show_errors();
												if(isset($timer)) echo "Page rendered in ".$timer->get_time()." seconds.";
											}
										}else{
									?>
										<div style="width:100%;text-align:center;">
											This page must be viewed with HTTPS. Redirecting you now.
										</div>
										<script type="text/javascript">
											var t=setTimeout('window.location="<?php 
												echo "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
												?>"',3000);
										</script>
									<?php
										}
										if($user->magic->id==1){
											//$sql->show_queries();
											//$sql->show_errors();
										}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
}
?>
	</body>
</html>