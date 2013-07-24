<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2009-2013
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @license    LGPL
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

