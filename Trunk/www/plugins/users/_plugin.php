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

final class users extends Plugins
{
 	public static $subdirs = array(
 		'users',
 		'utilisateurs'
 	);
 	public static $name = "users";
	public static $db;
 	
	public function __construct()
	{
		self::loadLang();
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
	
	public function getUserInfos($username)
	{
		$user = self::$db->q2("SELECT u.nom, u.prenom, u.email, u.login FROM users u WHERE u.login = :username", "users.db", array(":username" => $username));
		return $user[0];
	}
	
	public function getUsersList()
	{
		$users = self::$db->q2("SELECT u.nom, u.prenom, u.email, u.login FROM users u", "users.db", array());
		return $users;
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