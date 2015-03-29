<?php
/**
 * Created by Apropos (sir.apropos.of.nothing@gmail.com).
 * Date: 9/8/13
 * Time: 12:21 AM
 */
	$config['base_path'] = "/phpMVC/";
	$config['base_dir'] = "./";
	$config['classes_dir'] = $config['base_dir']."classes/";
	$config['controller_dir'] = $config['base_dir']."controllers/";
	$config['view_dir'] = $config['base_dir']."views/";
	$config['model_dir'] = $config['base_dir']."models/";
	$config['taglib_dir'] = $config['classes_dir']."taglibs/impl/";
	$config['config_class'] = "MVCConfig";
	$config['config_path'] = $config['classes_dir']."config/".$config['config_class'].".php";
	$config['cache_dir'] = $config['base_dir']."classes.cache";
?>