<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="&#1042;&#1099;&#1073;&#1077;&#1088;&#1080;&#1090;&#1077; &#1088;&#1072;&#1089;&#1096;&#1080;&#1088;&#1077;&#1085;&#1080;&#1077; &#1076;&#1083;&#1103; &#1087;&#1072;&#1082;&#1086;&#1074;&#1082;&#1080;" content="text/html; charset=iso-8859-1" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>Installation database</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
<meta http-equiv="&#1042;&#1099;&#1073;&#1077;&#1088;&#1080;&#1090;&#1077; &#1088;&#1072;&#1089;&#1096;&#1080;&#1088;&#1077;&#1085;&#1080;&#1077; &#1076;&#1083;&#1103; &#1087;&#1072;&#1082;&#1086;&#1074;&#1082;&#1080;" content="text/html; charset=iso-8859-1" />
</head>
<body>
<?php
if (!isset($_POST['submit'])){
	echo "<form action='".$_SERVER['PHP_SELF']."' METHOD='post'>";
	echo "<table border='0' align='left' style='font-weight:bold;';><tr align='right'><td>";
	echo "Name BD: <input type='text' name='db' size='10' value=''></td>";
	echo "<td>Prefix: <input type='text' name='prefix' size='10' value='work_'></td></tr>";
	echo "<tr align='right'><td>User BD: <input type='text' name='user' size='10' value=''></td>";
	echo "<td>Pass BD: <input type='text' name='pass' size=10' value=''></td></tr>";
	echo "<tr align='right'><td colspan='2'><input type='submit' name='submit' value='&raquo; &raquo;'></td></tr></table>";
	exit;
}
//---------------------------------------------------------------------------------
if (!mysql_connect("localhost",$_POST['user'], $_POST['pass'])){
    echo "<center><H1>!!! Fatal error connect mySQL server !!!</H1></center>";exit();
}
else {
	mysql_query('SET character_set_results="cp1251"');
	mysql_query("SET NAMES cp1251");
	
    if (mysql_select_db($_POST['db'])){
    	echo "Connected database ".$_POST['db']."...<br />";
    }
    else {
    	echo "<center><H1>Disconnected database =OnLine=</H1></center>";exit();
    }
}
	
	mysql_query ("CREATE TABLE `".$_POST['prefix']."type` (
	  `id_type` int(11) NOT NULL auto_increment,
	  `type` varchar(16) NOT NULL default '',
	  PRIMARY KEY  (`id_type`)
	) ENGINE=MyISAM DEFAULT CHARSET=cp1251;")or exit(mysql_error());
	echo "Add table ".$_POST['prefix']."type...<br />";

    mysql_query ("CREATE TABLE `".$_POST['prefix']."brand` (
	  `id_brand` int(11) NOT NULL auto_increment,
	  `brand` char(20) NOT NULL default '',
	  PRIMARY KEY  (`id_brand`)
	) ENGINE=MyISAM DEFAULT CHARSET=cp1251;")or exit(mysql_error());
	echo "Add table ".$_POST['prefix']."brand...<br />";
	
	mysql_query ("CREATE TABLE `".$_POST['prefix']."model` (
	  `id_model` int(11) NOT NULL auto_increment,
	  `id_type`  int(4) default NULL,
	  `id_brand` int(4) default NULL,
	  `model` text NOT NULL,
	  PRIMARY KEY  (`id_model`)
	) ENGINE=MyISAM DEFAULT CHARSET=cp1251;")or exit(mysql_error());
	echo "Add table ".$_POST['prefix']."model...<br />";

	mysql_query ("CREATE TABLE `".$_POST['prefix']."client` (
	  `id_client` int(11) NOT NULL auto_increment,
	  `client` text NOT NULL,
	  `client_tel_0` text,
	  PRIMARY KEY  (`id_client`)
	) ENGINE=MyISAM DEFAULT CHARSET=cp1251;")or exit(mysql_error());
	echo "Add table ".$_POST['prefix']."client...<br />";
	
	mysql_query ("CREATE TABLE `".$_POST['prefix']."worker` (
	  `id_worker` int(4) NOT NULL auto_increment,
	  `worker` char(15) NOT NULL default '',
	  PRIMARY KEY  (`id_worker`)
	) ENGINE=MyISAM DEFAULT CHARSET=cp1251;")or exit(mysql_error());
	echo "Add table ".$_POST['prefix']."worker...<br />";
	 
	mysql_query ("CREATE TABLE `".$_POST['prefix']."prin` (
	  `id_prin` int(4) NOT NULL auto_increment,
	  `prin` text NOT NULL,
	  PRIMARY KEY  (`id_prin`)
	) ENGINE=MyISAM DEFAULT CHARSET=cp1251;")or exit(mysql_error());
	echo "Add table ".$_POST['prefix']."prin...<br />";

	mysql_query ("CREATE TABLE `".$_POST['prefix']."remont` (
	  `id_r` int(11) NOT NULL auto_increment,
	  `pass` text,
	  `date` date NOT NULL default '0000-00-00',
	  `string` varchar(7) NOT NULL default 'NNNNNNN',
	  `id_client` int(11) NOT NULL default '0',
	  `client_fio` text,
	  `client_tel` text,
	  `id_model` int(11) default NULL,
	  `complect` text,
	  `defect` text,
	  `serial` text,
	  `counter` int(11) default '0',
	  `id_prin` int(4) NOT NULL default '0',
	  `id_worker` int(4) NOT NULL default '0',
	  `complete` enum('N', 'Y') NOT NULL default 'N',
	  `buh_check` enum('N', 'Y') NOT NULL default 'N',
	  `buh_text` text,
	  `hidden`  enum('N', 'Y') NOT NULL default 'N',
	  PRIMARY KEY  (`id_r`)
	) ENGINE=MyISAM DEFAULT CHARSET=cp1251;")or exit(mysql_error());
	
	echo "Add table ".$_POST['prefix']."remont...<br />";

	mysql_query ("CREATE TABLE `".$_POST['prefix']."work` (
	  `id` int(11) NOT NULL auto_increment,
	  `id_r` int(11) NOT NULL default '0',
	  `date` date NOT NULL default '0000-00-00',
	  `text` text,
	  `price` int(4) NOT NULL default '0',
	  `hard` text,
	  `hard_price` int(4) NOT NULL default '0',
	  `hidden` enum('N', 'Y') NOT NULL default 'N',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=cp1251;")or exit(mysql_error());
	echo "Add table ".$_POST['prefix']."work...<br />";
	
	echo "...........................................<br />";	
	
mysql_query ("INSERT INTO `".$_POST['prefix']."type` VALUES (1, 'принтер');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."type` VALUES (2, 'копир');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."type` VALUES (3, 'МФУ');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."type` VALUES (4, 'Факс');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."type` VALUES (5, 'Сист. блок');")or exit(mysql_error());
	echo "Add from ".$_POST['prefix']."brand value...<br />";
	
mysql_query ("INSERT INTO `".$_POST['prefix']."brand` VALUES (1, 'ПК:');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."brand` VALUES (2, 'HP');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."brand` VALUES (3, 'Xerox');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."brand` VALUES (4, 'Canon');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."brand` VALUES (5, 'Ricoh');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."brand` VALUES (6, 'Epson');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."brand` VALUES (7, 'Panasonic');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."brand` VALUES (8, 'Minolta');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."brand` VALUES (9, 'Samsung');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."brand` VALUES (10, 'Sharp');")or exit(mysql_error());
	echo "Add from ".$_POST['prefix']."brand value...<br />";

mysql_query	("INSERT INTO `".$_POST['prefix']."model` VALUES (1, 5, 1, 'Сист. блок');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (2, 3, 3, 'WC M20');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (3, 3, 3, 'PE 114');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (4, 1, 2, 'LJ 1200');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (5, 3, 3, 'WC M15');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (6, 1, 2, 'LJ 1100');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (7, 1, 2, 'LJ 1300');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (8, 1, 2, 'LJ 1010');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (9, 2, 4, 'FC 220');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (10, 1, 6, 'C42UX');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (11, 2, 8, 'EP 1030');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (12, 2, 8, 'EP 1054');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (13, 1, 9, 'ML-1210');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (14, 1, 4, 'LBP 800');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (15, 1, 4, 'LBP 810');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (16, 3, 3, 'WC PE 16');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (17, 1, 2, 'LJ 1150');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (18, 4, 7, 'KX-FT902');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (19, 1, 2, 'DJ 3820');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (20, 1, 4, 'Stylus Photo 810');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (21, 1, 6, 'Stylus Photo 810');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (22, 3, 8, 'Di 151');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (23, 3, 8, 'Di 152');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (24, 3, 9, 'SCX-4100');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (25, 1, 2, 'LJ 5000');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (26, 1, 2, 'LJ 5100');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."model` VALUES (27, 1, 2, 'LJ 5L');")or exit(mysql_error());
	echo "Add from ".$_POST['prefix']."model value...<br />";
	
mysql_query ("INSERT INTO `".$_POST['prefix']."worker` VALUES (1, '*Не задан*');")or exit(mysql_error());
mysql_query ("INSERT INTO `".$_POST['prefix']."worker` VALUES (2, 'Черкесов П.В.');")or exit(mysql_error());
	echo "Add from ".$_POST['prefix']."worker value...<br />";
	
mysql_query ("INSERT INTO `".$_POST['prefix']."prin` VALUES (1, 'Черкесов П.В.');")or exit(mysql_error());
	echo "Add from ".$_POST['prefix']."prin value...<br />";

	echo "...........................................<br />";	

	$file = fopen ("config.php", "w") or die("Ошибка чтения файла");
	flock ($file, 2);
	fputs ($file, '<?php');
	fputs ($file, ' $S_CONFIG["db"]="'.$_POST['db'].'";');
	fputs ($file, ' $S_CONFIG["prefix"]="'.$_POST['prefix'].'";');
	fputs ($file, ' $S_CONFIG["user"]="'.$_POST['user'].'";');
	fputs ($file, ' $S_CONFIG["pass"]="'.$_POST['pass'].'";');
	fputs ($file, ' ?>');
    flock ($file, 3);
    fclose ($file);
	echo "Create config.php...<br />";
	echo "...........................................<br />";	
	echo "DONE";
?>
<a href="index.php">На главную</a>
</body>
</html>
