<?php
/* Copyright (C) 2005-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2007-2010 Regis Houssin        <regis.houssin@capnetworks.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *		\file       htdocs/core/menus/standard/eldy_backoffice.php
 *		\brief      Gestionnaire nomme eldy du menu du haut
 *
 *		\remarks    La construction d'un gestionnaire pour le menu du haut est simple:
 *		\remarks    Toutes les entrees de menu a faire apparaitre dans la barre du haut
 *		\remarks    doivent etre affichees par <a class="tmenu" href="...?mainmenu=...">...</a>
 *		\remarks    ou si menu selectionne <a class="tmenusel" href="...?mainmenu=...">...</a>
 */


/**
 *	Class to manage top menu Eldy (for internal users)
 */
class MenuTop
{
	var $db;
	var $require_left=array("rh_backoffice");     // Si doit etre en phase avec un gestionnaire de menu gauche particulier
	var $type_user=0;								// Put 0 for internal users, 1 for external users
	var $atarget="";                                // Valeur du target a utiliser dans les liens


	/**
     *  Constructor
     *
     *  @param      DoliDb		$db      Database handler
	 */
	function __construct($db)
	{
		$this->db=$db;
	}


	/**
	 *  Show menu
	 *
	 * 	@return		void
	 */
	function showmenu()
	{
		require_once dirname(__FILE__).'/rh.lib.php';

		print_rh_menu($this->db,$this->atarget,$this->type_user);
	}

}


/**
 *  \class      MenuLeft
 *  \brief      Classe permettant la gestion du menu du gauche Eldy
 */
class MenuLeft
{
    var $db;
    var $menu_array;
    var $menu_array_after;


    /**
     *  Constructor
     *
	 *  @param	DoliDB		$db     			Database handler
     *  @param  array		&$menu_array    	Table of menu entries to show before entries of menu handler
     *  @param  array		&$menu_array_after  Table of menu entries to show after entries of menu handler
     */
    function __construct($db,&$menu_array,&$menu_array_after)
    {
        $this->db=$db;
        $this->menu_array=$menu_array;
        $this->menu_array_after=$menu_array_after;
    }


    /**
     *  Show menu
     *
     *  @return     int     Number of menu entries shown
     */
    function showmenu()
    {
        require_once dirname(__FILE__).'/rh.lib.php';

        $res=print_left_rh_menu($this->db,$this->menu_array,$this->menu_array_after);

        return $res;
    }

}

?>