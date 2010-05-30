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

$sPlug = new Settings::$VIEWER_TYPE;
Settings::setVar('template', 'main.tpl');

require_once(Settings::getVar('inc_dir') . "securimage/securimage.php");
$securimage = new Securimage();

$user_infos = array();
$user_infos['prenom'] = $_POST['prenom'];
$user_infos['nom'] = $_POST['nom'];
$user_infos['email'] = $_POST['email'];
$user_infos['login'] = $_POST['login'];
$user_infos['password'] = $_POST['password'];

$sPlug->AddData("user_infos", $user_infos);
$sPlug->AddData("base_url", Settings::getVar('base_url_http'));
$sPlug->AddData("theme_dir", Settings::getVar('theme_dir'));
$sPlug->AddData("current_lang", $_SESSION['LANG']);
Settings::setVar('title', i18n::_("creer_un_compte"));
$sPlug->AddData("title", Settings::getVar('title'));
Settings::setVar('description', '&nbsp;');
#$sPlug->->AddData("i18n", i18n::getLng());

if ( $_POST )
{
	if ( $securimage->check($_POST['captcha_code']) == false )
	{
		$_SESSION["message"] = i18n::_("The code you entered was incorrect. Go back and try again.");
		Settings::setVar('pageview', '/creer-un-compte-captcha-error');
	}
	else
	{
		$_POST['lang'] = $_POST['lang'] != "" ? $_POST['lang'] : $_SESSION['LANG'];
		if ( User::userAdd($_POST) )
		{
			$_SESSION["message"] = i18n::_("Vérifier voter boite de réception email !");
			Settings::setVar('pageview', '/creer-un-compte-ok');
			User::sendWelcomeMail($_POST, $sPlug);
		}
		else
		{
			$_SESSION["message"] = i18n::_("Erreur lors de la création du compte !");
			Settings::setVar('pageview', '/creer-un-compte-error');
		}
	}
}



$breadcrumb = Settings::getVar('breadcrumb');
array_push($breadcrumb, array("name" => Settings::getVar('title')));
Settings::setVar('breadcrumb', $breadcrumb);
$content = $sPlug->fetch("creer-un-compte.tpl", "plugins/users");

?>