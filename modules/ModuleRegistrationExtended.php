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
		if ($this->allow_groupselection)
		{
			$fields = implode(",", $this->editable);
			if (strpos($fields, "password"))
			{
				$this->editable = explode(",", str_replace("password", "password,groupselection", $fields));
			}
			else
			{
				$this->editable = explode(",", $fields . ",groupselection");
			}
		}
		parent::compile();

		if ($this->show_agreement)
		{
			$arrAgreement = array
			(
				'id' => 'registration',
				'label' => $GLOBALS['TL_LANG']['tl_member']['agreement'],
				'type' => 'textarea',
				'mandatory' => true,
				'required' => true,
				'accept_agreement' => $this->accept_agreement,
				'agreement_text' => $this->agreement_text,
				'agreement_headline' => $this->agreement_headline,
				'accept' => $GLOBALS['TL_LANG']['tl_member']['accept_agreement'],
				'tableless' => $this->tableless
			);

			/** @var \FormAgreement $strClass */
			$strClass = $GLOBALS['TL_FFL']['agreement'];
			// Fallback to default if the class is not defined
			if (!class_exists($strClass))
			{
				$strClass = 'FormAgreement';
			}

			/** @var \FormAgreement $objAgreement */
			$objAgreement = new $strClass($arrAgreement);
			if (\Input::post('FORM_SUBMIT') == 'tl_registration')
			{
				$objAgreement->validate();
			}

			$objAgreement->rowClass = 'row_'.$i . (($i == 0) ? ' row_first' : '') . ((($i % 2) == 0) ? ' even' : ' odd');
			$strAgreement = $objAgreement->parse();

			$this->Template->fields .= $strAgreement;
			$arr = $this->Template->categories;
			$arr[$this->agreement_headline] = array('agreement' => $strAgreement);

			$newArray = array();
			$inserted = false;
			foreach ($this->Template->categories as $key => $value)
			{
				if (strlen($key) == 0 && is_array($value) && strcmp(array_keys($value)[0], 'captcha') == 0)
				{
					$newArray[$GLOBALS['TL_LANG']['tl_member']['agreement']] = array('agreement' => $strAgreement);
					$inserted = true;
				}
				$newArray[$key] = $value;
			}
			if (!$inserted)
			{
				$newArray[] = array('agreement' => $strAgreement);
			}
			$this->Template->categories = $newArray;
		}
	}
}

?>