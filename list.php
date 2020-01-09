<?php
	$path = "./";

	if (array_key_exists("path", $_GET) && trim($_GET['path'] != "")){
		$path = $_GET['path'];
	}

	if ($handle = opendir($path)) {
		$list = array();

		while (false !== ($entry = readdir($handle))) {
			$list[] = $entry;
		}
		
		closedir($handle);

	}

	echo "<h2>Path: $path</h2>\n";
	echo "<h3>Entries:</h3>\n";

	echo "<ul>";
	
	sort($list);
	foreach ($list as $entry){
		$curpath = $path . "/" . $entry;
		$curpath = str_replace("//", "/", $curpath);

		if ($entry != "list.php") {
			if (is_dir($curpath)){
				if ($entry == "."){
					$curpath = $path;
				} else if ($entry == ".."){
					if ($path == "." || $path == "./"){
						$curpath = "./../";
					} else {
						$s = explode("/", $path);
						array_pop($s);
						$curpath = implode("/", $s);
					}
				}
				echo "<li><a href=\"list.php?path=$curpath\">$entry</a></li>\n";
			} else {
				$ext = pathinfo($curpath, PATHINFO_EXTENSION);
				$url = "#";

				if (in_array($ext, array("js", "txt", "htm", "html", "txt", "zip", "7z"))){
					$url = "http".(!empty($_SERVER['HTTPS'])?"s":"") . "://". $_SERVER['SERVER_NAME'];
					$uarr = explode("list.php", $_SERVER['REQUEST_URI']); 
					
					$url .= $uarr[0] . $curpath;
					echo "<li><a href=\"" . $url .  "\" target=\"_blank\">$entry</a></li>";
				} else {
					echo "<li><a href=\"#\">$entry</a></li>";
				}
				
			}
		}
	}

	echo "</ul>";