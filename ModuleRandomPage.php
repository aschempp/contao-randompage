<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2009
 * @author     Andreas Schempp <andreas@schempp.ch
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


class ModuleRandomPage extends Module
{
	protected $strTemplate = 'mod_html';
	
	
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### RANDOM PAGE ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		
		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE pid=?" . (BE_USER_LOGGED_IN ? '' : " AND published=1 AND (start='' OR start<?) AND (stop='' OR stop>?)") . " ORDER BY RAND()")->limit(1)->execute($this->rootPage, time(), time());
		
		if (!$objPage->numRows)
			return '';
			
		$this->redirect($this->generateFrontendUrl($objPage->row()));
	}
	
	
	protected function compile() {}
}

