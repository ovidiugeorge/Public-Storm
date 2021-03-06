<?php
/*
    Public-Storm
    Copyright (C) 2008-2012 Mathieu Lory <mathieu@internetcollaboratif.info>
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
$sTab = new Settings::$VIEWER_TYPE;
Settings::setVar('template', 'main.tpl');

$sTab->AddData("prefix", Settings::getVar('prefix'));
$sTab->AddData("base_url", Settings::getVar('BASE_URL'));
$sTab->AddData("theme_dir", Settings::getVar('theme_dir'));

$uri = explode('/', $_SERVER['REQUEST_URI']);
#$id = array_pop($uri); # TODO : ca retourne rien ???!!!!
if( Settings::getVar('BASE_URL') != "" ) {
	$ind = 2;
} else {
	$ind = 1;
}

$current_page = ($uri[$ind+3] != NULL) && ($uri[$ind+3] != 'undefined') ? $uri[$ind+3] : 1;

$sTab->AddData("current_page", $current_page);
$sTab->AddData("nb_pages", round(aboutcron::getNbCrons() / Settings::getVar('crons per page')));
$sTab->AddData("nbcrons", aboutcron::getNbCrons());
$sTab->AddData("crons", aboutcron::getCrons($current_page==1 ? 0 : ((Settings::getVar('crons per page')*($current_page-1))+1), Settings::getVar('crons per page')));

$tabContent = $sTab->fetch("cronlist.tpl", "plugins/aboutcron");


?>