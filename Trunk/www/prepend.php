<?php
/*
    Public-Storm
    Copyright (C) 2008-2010 Mathieu Lory <mathieu@internetcollaboratif.info>
    This file is part of Public-Storm.

    Public-Storm is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Public-Storm is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Public-Storm. If not, see <http://www.gnu.org/licenses/>.
    
    Project started on 2008-11-22 with help from Serg Podtynnyi
    <shtirlic@users.sourceforge.net>
 */


/*
preg_match("/([\w]{2})\/statistiques-(.*?)\.html$/", $_SERVER['REQUEST_URI'], $match);
if ( isset($match[2]) )
{
	# redirection des anciennes urls :
	i18n::setLang($match[2]);
	header("HTTP/1.1 301 moved Permanently", true, 301);
	header("Location: ".Settings::getVar('BASE_URL_HTTP')."/mot/".$match[2]."/", true, 301);
	exit;
}
*/

preg_match("/noexist_([a-zA-Z0-9]+)/", $_SERVER['REQUEST_URI'], $match);
#print $match[1]."<----";
if ( isset($match[1]) )
{
	header('Status: 404 Not Found', true, 404);
	header('HTTP/1.1 404 Not Found', true, 404);
	print "404 Not Found ; your request did'nt return anything, try going to the home page and/or verify your url string.";
	exit;
}

header('Status: 200 OK', false, 200);
header('HTTP/1.1 200 OK', false, 200);

#print "->uid = ".$_COOKIE["uid"]."<br />";
#print "->persistentConnection = ".$_COOKIE["persistentConnection"]."<br />";

/* Gestion de la connexion persistente par cookies */
if (
	$_COOKIE["persistentConnection"] == "1" &&
	$_COOKIE["uid"] &&
	!$_SESSION["uid"]
)
{
	User::authentificationByUid($_COOKIE["uid"]);
}


/* Gestion des plugins */
$n = 0;
$plug = new Plugins;
foreach( $plug->listPlugins() as $pluginName )
{
	if ( $plug->isActive($pluginName) )
	{
		$plugins[$n] = $plug->LoadPlugin($pluginName);
		$pluginsNames[$pluginName] = $n;
		$statuses[$pluginName] = 1;
		//print $pluginName."<br />";
		$n++;
	}
	else
	{
		$statuses[$pluginName] = 0;
	}
}


/* Load languages */
$locale = $_COOKIE["locale"] != "" ? $_COOKIE["locale"] : LANG;
i18n::setLocale($locale);
/* end Load languages */


/* init AdminMenu only when all plugins are loaded */
$n = 0;
foreach( $plug->listPlugins() as $pluginName )
{
	if ( method_exists($plugins[$n], 'initAdminMenu') )
	{
		$plugins[$n]->initAdminMenu();
	}
	$n++;
}
/* end Admin Menu */

/* check if config file exists and if the database is installed */
$f3 = new File(Settings::getVar('conf_dir') . '_global_db.php');
if ( !$f3->Exists() && $qdirs[0] != 'install' )
{
	header('Location: ' . Settings::getVar('BASE_URL_HTTP') . 'install/index.php');
	exit;
}

/* Gestion de la page appellée en fonction de l'url */
$listRegisteredSubdirs = Settings::getSubdirsRegistered();
//print_r($listRegisteredSubdirs);
//print $qdirs[1];
if ( $pluginName = searchInList($qdirs[0], $listRegisteredSubdirs) )
{
	Settings::setVar('prefix', '../');

	$f = new File(Settings::getVar('plug_dir') . strtolower($pluginName . '/_plugin.php'));
	if ( $f->Exists() )
	{
		$f2 = new File(Settings::getVar('plug_dir') . strtolower($pluginName . '/index.php'));
		if ( !isset($content) && $page == "index.php" && !$f2->Exists() )
		{
			$i = $pluginsNames[$pluginName];
			$sPlug = new Settings::$VIEWER_TYPE;
			$author = preg_replace('/(.*?) <(.*?)>/i', '<a href="mailto:$2">$1</a>', $plugins[$i]->GetAuthor($plugins[$i]->getName()));
			$sPlug->AddData("title", $pluginName);
			$sPlug->AddData("plugininfos", $plugins[$i]->pluginGetInfos($plugins[$i]->getName()));
			$sPlug->AddData("description", $plugins[$i]->getDescription($plugins[$i]->getName()));
			$sPlug->AddData("author", $author);
			$sPlug->AddData("version", $plugins[$i]->getVersion($plugins[$i]->getName()));
			$sPlug->AddData("listplugins", Plugins::listPages($plugins[$i]->getName()));
			#$sPlug->->AddData("i18n", i18n::getLng());
			$content = $sPlug->fetch('pluginListPages.tpl', '');
			//print $plugins[$i]->getName();
		}
		else
		{
			require_once(Settings::getVar('plug_dir') . strtolower($pluginName . "/" .$page));
		}
	}
	else
	{
		$content = 'Error: Plugin definition class is not present for '.$pluginName;
	}
}
elseif( !$qdirs[0] )
{
	#TODO
}
else
{
	header('Status: 404 Not Found', true, 404);
	header('HTTP/1.1 404 Not Found', true, 404);
	print "'".$qdirs[0]."', 404 Not Found ; your request did'nt return anything, try going to the home page and/or verify your url string.";
	exit;
}


// Function for looking for a value in a multi-dimensional array
function searchInList($dir, $plugins)
{
	foreach( $plugins as $plugin => $listeRsd ) /*rsd means registeredSubDir*/
	{
		foreach( $listeRsd as $key => $rsd ) /*rsd means registeredSubDir*/
		{
			if ( $rsd == $dir ) 
			{
				return $plugin;
			}
		}
	}
}

?>