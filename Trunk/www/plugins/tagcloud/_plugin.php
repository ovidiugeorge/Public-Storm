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
 */

final class tagcloud extends Plugins
{
 	public static $subdirs = array('tagcloud');
 	public static $name = "tagcloud";
 	public static $cloud;
 	public static $words = array();
 	
	public function __construct()
	{
		//require(Settings::getVar('prefix') . 'conf/tagcloud.php');
		self::loadLang();
		require_once("./plugins/tagcloud/classes/wordcloud.class.php");
		Settings::addCss('screen', Settings::getVar('theme_dir') . '/plugins/tagcloud/styles/wordcloud.css');
		self::$cloud = new wordCloud(self::$words);
		//print "version ".self::$version;
	}
	
	public function showCloud()
	{
		$myCloud = self::$cloud->showCloud("array");
		$c = "";
		//print_r(self::$cloud);
		foreach ($myCloud as $key => $value) {
			$c .= ' <a href="'.Settings::getVar('BASE_URL_HTTP').'/storm/'.modifier_url($value['word']).'/" class="word size'.$value['sizeRange'].'">'.$value['word'].'</a> &nbsp;';
		}
		return $c;
	}
	
	public function addWord($word, $value)
	{
		//print_r(self::getWords());
		return self::$cloud->addWord($word, $value);
	}
	
	public function getWords()
	{
		return self::$words;
	}
	
	public function loadLang()
	{
		return parent::loadLang(self::$name);
	}
	
	public function getVersion()
	{
		return parent::getVersion();
	}
	
	public function getName()
	{
		return self::$name;
	}
	
	public function getDescription()
	{
		return parent::getDescription();
	}
	
	public function getAuthor()
	{
		return self::getAuthor();
	}
	
	public function getIcon()
	{
		return parent::getIcon(self::$name);
	}
	
	public function getStatus()
	{
		return parent::getStatus(self::$name);
	}
	
	public function getSubDirs()
	{
		return self::$subdirs;
	}
}



?>