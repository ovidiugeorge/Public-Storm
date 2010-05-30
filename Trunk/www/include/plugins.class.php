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

/**
 * @package    Public-Storm
 * @subpackage Plugins
 * @author     Mathieu Lory <mathieu@internetcollaboratif.info>
 */

if (basename($_SERVER["SCRIPT_NAME"])==basename(__FILE__))die();

class Plugins
{
	public static $activatedPlugins = array();
	public static $loadedPlugins = array();
	public static $db;
 	public static $version;
 	public static $name;
 	public static $description;
 	public static $author;
 	public static $icon;
	
	public function __construct()
	{
		if ( !class_exists(Settings::$DB_TYPE) )
		{
			Debug::Log("Classe introuvable : ".Settings::$DB_TYPE, ERROR);
		}
		else
		{
			if ( self::$db = new Settings::$DB_TYPE )
			{
				return true;
			}
			else
			{
				Debug::Log($err, ERROR);
				return false;
				exit($err);
			}
		}
	}
	
	public static function isActive($pluginName)
	{
		$res = self::$db->q('SELECT status FROM plugins WHERE name="%s"', 'plugins.db', array($pluginName));
		//print_r($res);
		//print $pluginName." ".$res['status']."<br />";
		self::$activatedPlugins[$pluginName] = $res[1]['status'];
		return self::$activatedPlugins[$pluginName];
	}
	
	public static function listPlugins()
	{
		$res = self::$db->q('SELECT name FROM plugins ORDER BY sort ASC', 'plugins.db', array($pluginName));
		$p = array();
		foreach($res as $plugin)
		{
			if ( isset($plugin['name']) ) {array_push($p, $plugin['name']);}
		}
		//print_r($p);
		return $p;
		//return file::GetDirs(Settings::getVar('plug_dir'));
	}
	
	public static function listAllDatasPlugins()
	{
		$res = self::$db->q('SELECT * FROM plugins ORDER BY name ASC', 'plugins.db', array($pluginName));
		$p = array();
		foreach($res as $plugin)
		{
			$pluginName = $plugin['name'];
			self::$activatedPlugins[$pluginName] = $plugin['status'];
			$plugin[] = $plugin['status'];
			
			$icon = self::getIcon($plugin['name']);
			$plugin['icon'] = $icon;
			$plugin[] = $icon;
			
			$author = preg_match("/(.*?) <(.*?)>/", $plugin['author'], $auth);
			$plugin['author_name'] = $auth[1];
			$plugin[] = $plugin['author_name'];
			$plugin['author_email'] = $auth[2];
			$plugin[] = $plugin['author_email'];
			
			array_push($p, $plugin);
		}
		//print_r($p);
		return $p;
	}
	
	public static function LoadPlugin($pluginName)
	{
		array_push(self::$loadedPlugins, $pluginName);
		$f = new File(Settings::getVar('plug_dir') . strtolower($pluginName) . '/_plugin.php');
		if ( $f->Exists() )
		{
			require(Settings::getVar('plug_dir') . strtolower($pluginName) . '/_plugin.php');
			if( DEBUG )
			{
				Debug::Log('Plugin "' . strtolower($pluginName) . '" is activated', NOTICE);
			}
		}
		else
		{
			if( DEBUG )
			{
				Debug::Log(Settings::getVar('plug_dir') . strtolower($pluginName) . '/_plugin.php' . " doesn't exists", NOTICE);
			}
			return false;
		}
		
		if ( $p = new $pluginName )
		{
			/* register subdirs from plugin */
			$subdirs = $p->getSubDirs();
			if ( is_array($subdirs) )
			{
				foreach ( $subdirs as $dir )
				{
					if ( $registred = Settings::registerSubdir($dir, $pluginName) )
					{
						//print_r($registred);
						Debug::Log('Folder "' . $dir . '" is registered by "' . $pluginName . '"' , NOTICE);
					}
				}
			}
			return $p;
		}
	}
	
	public function listPages($pluginName)
	{
		$liste = array();
		foreach ( scandir(Settings::getVar('plug_dir') . '/' . strtolower($pluginName) . '/') as $node )
		{
			if ( ereg('.*\.php$', $node) && $node != strtolower($pluginName).'.php' )
			{
				//$liste .= '<li><a href="'.$node.'">'.$node.'</a></li>';
				array_push($liste, $node);
			}
		}
		return $liste;
	}
	
	public static function deActivatePlugin($pluginName)
	{
		Debug::Log($pluginName . " is de-activated", NOTICE);
		self::$db->u('UPDATE plugins SET status="0" WHERE name="%s"', 'plugins.db', array($pluginName));
		self::$activatedPlugins[$pluginName] = 0;
		//self::$activatedPlugins
	}
	
	public static function activatePlugin($pluginName)
	{
		Debug::Log($pluginName . " is activated", NOTICE);
		self::$db->u('UPDATE plugins SET status="1" WHERE name="%s"', 'plugins.db', array($pluginName));
		self::$activatedPlugins[$pluginName] = 1;
		//self::$activatedPlugins
	}
	
	public static function pluginGetInfos($pluginName)
	{
		if ( in_array($pluginName, self::$activatedPlugins) )
		{
			$p = new $pluginName;
			return array(
				'name' => $p->getName(),
				'version' => $p->getVersion(),
				'author' => $p->getAuthor(),
				'description' => $p->getDescription(),
				'isActive' => $p->isActive($pluginName),
				'icon' => $p->getIcon($pluginName),
			);
		}
		else
		{
			return 'plugin "' . $pluginName . '" not activated !';
		}
	}
	
	public function loadLang($name)
	{
		$l = new File(Settings::getVar('plug_dir') . strtolower($name) . '/langs/' . $_SESSION["LANG"] . '.php');
		if ( $l->Exists() )
		{
			require_once(Settings::getVar('plug_dir') . strtolower($name) . '/langs/' . $_SESSION["LANG"] . '.php');
		}
		else
		{
			$l2 = new File(Settings::getVar('plug_dir') . strtolower($name) . '/langs/' . LANG . '.php');
			if ( $l2->Exists() )
			{
				require_once(Settings::getVar('plug_dir') . strtolower($name) . '/langs/' . LANG . '.php');
			}
		}
		if ( isset($langPlug) )
		{
			#i18n::setLng(array_merge(i18n::getLng(), $langPlug));
		}
	}
	
	public function getIcon($name)
	{
		//$file = Settings::getVar('theme_dir') . 'plugins/' . strtolower($name) . '/img/icon.png';
		$file = Settings::getVar('plug_path') . strtolower($name) . '/img/icon.png';
		//print $file."<br />";
		$icon = new File($file);
		if ( $icon->Exists() )
		{
			self::$icon = Settings::getVar('theme_dir') . 'plugins/' . strtolower($name) . '/img/icon.png';;
			return self::$icon;
		}
	}
	
	public function getStatus($pluginName)
	{
		return self::isActive($pluginName);
	}
	
	public function getName()
	{
		return self::$name;
	}
	
	public function getVersion()
	{
		return self::$version;
	}
	
	public function getDescription()
	{
		return self::$description;
	}
	
	public function getAuthor()
	{
		return self::$author;
	}
}

?>