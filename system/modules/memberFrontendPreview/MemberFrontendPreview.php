<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013 
 * @package    memberFrontendPreview
 * @license    GNU/LGPL 
 * @filesource
 */

/**
 * Callback class for the tl_member.
 */
class MemberFrontendPreview extends BackendModule
{
	/**
	 * Interface
	 */
	protected function compile()
	{
		// Nothing to do.
	}

	/**
	 * Check choosen member and redirect to the preview. After setting
	 * all cookies for the member.
	 */
	public function generate()
	{
		// Get data from get.
		$intUser = Input::getInstance()->get('id');
		$strAct	 = Input::getInstance()->get('act');
		// Get some data.
		$strHash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? $this->Environment->ip : '') . 'FE_USER_AUTH');
		$time	 = time();

		// Check if we have data.
		if (empty($intUser) || empty($strAct) || $strAct != 'frontendPreview')
		{
			$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['general']);
			$this->redirect("contao/main.php?do=member");
		}

		// Get the member.
		$objMember = Database::getInstance()
				->prepare('SELECT * FROM tl_member WHERE id=?')
				->execute($intUser);
		
		// Ceck if we have this member.
		if ($objMember->numRows == 0)
		{
			$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['general']);
			$this->redirect("contao/main.php?do=member");
		}
				
		// Allow admins to switch user accounts.
		if (in_array('member', BackendUser::getInstance()->modules) || BackendUser::getInstance()->isAdmin)
		{
			// Remove old sessions.
			Database::getInstance()
					->prepare("DELETE FROM tl_session WHERE tstamp<? OR hash=?")
					->execute(($time - $GLOBALS['TL_CONFIG']['sessionTimeout']), $strHash);

			// Log in the front end user.
			if (is_numeric($intUser) && $intUser > 0)
			{
				// Insert new session.
				$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
						->execute($intUser, $time, 'FE_USER_AUTH', session_id(), $this->Environment->ip, $strHash);

				// Set cookie.
				$this->setCookie('FE_USER_AUTH', $strHash, ($time + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);
			}
			// Log out the front end user.
			else
			{
				// Remove cookie.
				$this->setCookie('FE_USER_AUTH', $strHash, ($time - 86400), $GLOBALS['TL_CONFIG']['websitePath']);
			}
			
			// Redirect to preview.
			$this->redirect("contao/preview.php");
			exit();
		}
		
		// Redirect to members.
		$this->addErrorMessage($GLOBALS['TL_LANG']['ERR']['general']);
		$this->redirect("contao/main.php?do=member");
		exit();
	}

}