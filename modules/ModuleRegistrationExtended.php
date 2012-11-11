<?php

/**
 * @copyright  Helmut Schottmüller
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @license    LGPL
 */

namespace Contao;

/**
 * Class ModuleRegistrationExtended
 *
 * Front end module "registration".
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    Controller
 */
class ModuleRegistrationExtended extends ModuleRegistration
{
	/**
	 * Generate module
	 */
	protected function compile()
	{
		if ($this->show_agreement)
		{
			$GLOBALS['TL_FFL'] += array('agreement' => 'FormAgreement');
			$arr = $this->editable;
			array_push($arr, "agreement");
			$this->editable = $arr;
		}

		parent::compile();

		$this->Template->agreementDetails = $GLOBALS['TL_LANG']['tl_member']['agreement'];
	}
}

?>