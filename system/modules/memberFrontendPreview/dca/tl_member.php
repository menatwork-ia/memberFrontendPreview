<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013 
 * @package    memberFrontendPreview
 * @license    GNU/LGPL 
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_member']['list']['operations']['frontendPreview'] = array
(
	'label'         => &$GLOBALS['TL_LANG']['tl_member']['frontendPreview'],
	'href'          => 'act=frontendPreview',
	'icon'          => 'preview.gif',
	'attributes'    => 'target="_blank" '
);