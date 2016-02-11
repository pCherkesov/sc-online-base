<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Update engine</title>

<style type="text/css">
body {
	background: #B2B2B2 url("Images/fon.png");
	text-align: center;
}
h1 {
	text-align: center;
}
#bodys {
	position: relative;
	background: #DDDDDD;
	width: 90%;
	border: #999999 1px solid;
	padding: 10px;
	margin: auto;
}
table.main {
	border: 1px solid #999999; 
	background: #FFFFFF; 
	width: 98%;
}
th {
	color: #FFFFFF;
	background: #999999;
}
td {
	padding-top: 1px;
	padding-bottom: 1px;
	padding-left: 5px;
	padding-right: 5px;
}

caption {
	font-size: large;
	font-family: "Times New Roman", Times, serif;
	font-weight: bold;
	padding: 5px 5px 5px 5px;
}

</style>

</head>
<body>
<div id="bodys">
<?php
$UPEn = new UPEn;

if(!isset( $_SESSION['act'] )) $_SESSION['act'] = "open";
switch ( $_SESSION['act'] ) {
	case "open": $UPEn->open(); break;
	case "tree": $UPEn->echo_tree(); break;
	case "install": $UPEn->update( "up" ); break;
	default: $UPEn->open(); break;
}
?>
</div>

</body>
</html>

<?php
//===========================================================================
//============================= UPDATE ENGINE ===============================
//===========================================================================
class UPEn {
private $base_dir = "update";
private $descript = "description.upd";
private $upd_name;
private $dirs = array();
private $files = array();
	
	function open () {
		echo "<table class=\"main\">";
		echo "<caption>UPDATE ENGINE</caption>";
		echo "<tr><th>Приветствие</th></tr>";
		echo "<tr><td style=\"text-align: justify;\">Это движок установки обновлений</td></td>";
		echo "<tr><td><a href=\"" . $_SERVER['PHP_SELF'] . "\" >Далее</a></td></tr>";
		echo "</table>";
		$_SESSION['act'] = "tree";
	}
	
	function echo_tree () {
		$item = array();
		$bd = opendir( $this->base_dir );
		while(( $cur = readdir( $bd )) !== false) {
			if (strcmp( $cur , '.') == 0 || strcmp( $cur , '..') == 0) continue;
			if(filetype( $this->base_dir . "/" . $cur ) == "dir") $item[] = $cur;
		}
		closedir($bd);

		echo "<table class=\"main\">";
		echo "<caption>UPDATE ENGINE</caption>";
		echo "<tr><th>Название</th><th>Установить</th><th>Откатить</th><th>Описание</th></tr>";
		while(list( $key , $value ) = each( $item )) {
			if(file_exists( $this->base_dir . "/" . $value . "/" . $this->descript )) {
				$fid = fopen( $this->base_dir . "/" . $value . "/" . $this->descript , "r");
				$info = fread( $fid , 255);
			} else $info = "Нет файла описания";

			echo "<tr><td>" . $value . "</td>";
			echo "<td><a href=\"" . $_SERVER['PHP_SELF'] . "?act=install&upd=" . $value . "\">Установить</a></td>";
			echo "<td><a href=\"" . $_SERVER['PHP_SELF'] . "?act=unistall&upd=" . $value . "\">Откатить</a></td>";
			echo "<td style=\"text-align: left;\">" . $info . "</td></tr>";
		}
		echo "</table>";
		$_SESSION['act'] = "install";
	}
	
	function update ( $action ) {
		if(isset( $_GET['upd'] )) $this->upd_name = $_GET['upd']; 
		else self::error( "Hеверно передано название обновления" );
		$upd_path = $this->base_dir . "/" . $this->upd_name ."/files";
		$back_path = $this->base_dir . "/" . $this->upd_name ."/back";
		ob_start();
		echo "Создание списка обновляемых файлов...<br />";
		self::full_tree( $upd_path );
		echo "<br /><b>Резервное копирование</b><br />";
		self::check_dir( $back_path );
		self::copy_file( "." , $back_path );
		echo "<br /><b>Установка обновления</b><br />";
		self::check_dir( "." );
		self::copy_file( $upd_path , "." );
		$text = ob_get_contents();
		ob_end_clean();
		echo "<table class=\"main\">";
		echo "<caption>UPDATE ENGINE</caption>";
		echo "<tr><th>Произведённые изменения</th></tr>";
		echo "<tr><td style=\"text-align: justify;\">" . $text . "</td></td>";
		echo "<tr><td><a href=\"" . $_SERVER['PHP_SELF'] . "\" >UPDATE Complete</a></td></tr>";
		echo "</table>";
		$_SESSION['act'] = "";
	}
	
	
	function full_tree ( $path ) {
		$fils = self::listTree( $path );
		
		while(list( $key , $value ) = each( $fils )) {
			$this->files[] = str_replace( $path , "" , $value );
		}
		
		while(list( $key , $value ) = each( $this->dirs )) {
			$dirs[] = str_replace( $path , "" , $value );
		}
		@sort( $dirs , SORT_REGULAR );
		$this->dirs = $dirs;
	}
	
	function listTree ( $start_dir = '.' ) {
		$files = array();
		if (is_dir( $start_dir )) {
			$fh = opendir( $start_dir );
			while (( $file = readdir( $fh )) !== false) {
				if (strcmp( $file , '.') == 0 || strcmp( $file , '..') == 0) continue;
				$filepath = $start_dir . '/' . $file;
				if ( is_dir( $filepath ) ) {
					$this->dirs[] = $filepath;
					$files = array_merge( $files , self::listTree( $filepath ) );
				}
				else array_push( $files , $filepath );
			}
			closedir( $fh );
		} 
		else {
			$files = false;
		}
		return $files;
	}
	
	function check_dir ( $path ) {
		if( $this->dirs != "") {
			reset( $this->dirs );
			while(list( $key , $value ) = each( $this->dirs )) {
				if (!is_dir( $path . $value )) {
					mkdir( $path . $value );
					echo "Создание директории <b>" . $path . $value . "</b><br />";
				}
			}
		}
	}
	
	function copy_file ( $source , $destin ) {
		if( $this->files == "" ) return;
		reset( $this->files );
		while(list( $key , $value ) = each( $this->files )) {
			$source_path = $source . $value;
			$destin_path = $destin . $value;
			if(!file_exists( $source_path )) continue;
			if(file_exists( $destin_path )) { 
				if (is_writable( $destin_path )) {
					copy( $source_path , $destin_path );
					echo "Заменён файл <b>" . $destin_path ."</b><br />"; 
				} else echo "Access file error";
			} 
			else {
				copy( $source_path , $destin_path );
				echo "Создан файл <b>" . $destin_path ."</b><br />"; 
			}
		}
	}
	
	function error ( $text ) {
		session_unregister('act');
		echo "<table class=\"main\">";
		echo "<caption>UPDATE ENGINE</caption>";
		echo "<tr><th>ОШИБКА</th></tr>";
		echo "<tr><td>" . $text . "</td></td>";
		echo "</table>";
		echo "</div></body></html>";
		exit;
	}
}
?>