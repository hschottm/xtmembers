<?php

/**
 * @copyright  Helmut Schottmüller
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @license    LGPL
 */

namespace Contao;

/**
 * Class FormAgreement
 *
 * User agreement.
 * @copyright  Helmut Schottmüller 2008
 * @author     Helmut Schottmüller <helmut.schottmueller@aurealis.de>
 * @package    Controller
 */
class FormAgreement extends \Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_agreement';

	/**
	 * Initialize the object
	 * @param array
	 */
	public function __construct($arrAttributes=false)
	{
		parent::__construct($arrAttributes);

		$this->mandatory = true;
	}


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;
			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$accept = deserialize($this->getPost('accept_agreement'));

		if (!$accept)
		{
			$this->addError($GLOBALS['TL_LANG']['ERR']['agreement']);
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		return sprintf('<input type="checkbox" name="accept_agreement" value="1" id="ctrl_%s" class="agreement%s" %s /> <label for="ctrl_%s" class="agreement_check_text">%s</label>',
						$this->strId,
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->getAttributes(),
						$this->strId,
						$this->agreement_headline
		);
	}


	/**
	 * Generate the agreement
	 * @return string
	 */
	public function generateAgreement()
	{
		return sprintf('<div class="agreement_text%s">%s</div>',
						(strlen($this->strClass) ? ' ' . $this->strClass : ''),
						$this->agreement_text);
	}
}

?>