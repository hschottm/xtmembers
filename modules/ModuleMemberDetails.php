<?php

/**
 * @copyright  Helmut Schottmüller
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @license    LGPL
 */

namespace Contao;

class ModuleMemberDetails extends \Xtmembers\ModuleMemberlist
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_memberlist_details';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### MEMBER DETAILS ###';
			return $objTemplate->parse();
		}
		
		// The following two lines is just to prevent the parent class from not continuing
		$this->ml_groups = 'a:2:{i:0;s:1:" ";i:1;s:1:" ";}';
		$this->ml_fields = 'a:2:{i:0;s:1:" ";i:1;s:1:" ";}';
		
		return parent::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile()
	{
		$this->arrMlGroups = $this->getAllGroups();
		\Input::setGet('show', $this->singlemember);
		parent::compile();
	}
	
	protected function getAllGroups()
	{
		return $this->Database->prepare("SELECT id FROM tl_member_group")
			->execute()
			->fetchEach('id');
	}

	/**
	 * List a single member
	 * @param integer
	 */
	protected function listSingleMember($id)
	{
		parent::listSingleMember($id);
		$objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")
				->limit(1)
				->execute($id);
		if ($this->show_member_name)
		{
			global $objPage;
			$objPage->pageTitle = trim(htmlspecialchars($objMember->firstname . " " . $objMember->lastname));
		}
		$this->Template->membergroups = deserialize($objMember->groups, true);
	}
}

?>