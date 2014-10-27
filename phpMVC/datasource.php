<?php
	IOCContainer::getInstance()->register(new MysqliDataSource("server", "username", "password"), "DataSource");
?>