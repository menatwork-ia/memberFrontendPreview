<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013 
 * @package    memberFrontendPreview
 * @license    GNU/LGPL 
 * @filesource
 */

// Check if we have a callback.
$strDo    = Input::getInstance()->get("do");
$strAct   = Input::getInstance()->get("act");

// Include callback class.
if (TL_MODE == 'BE' && $strDo == 'member' && $strAct == 'frontendPreview')
{
    $GLOBALS['BE_MOD']['accounts']['member']['callback'] = 'MemberFrontendPreview';
}