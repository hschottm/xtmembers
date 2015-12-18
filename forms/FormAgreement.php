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
				case 'agreement_text':
					$this->agreement_text = $varValue;
					break;
				case 'agreement_headline':
					$this->agreement_headline = $varValue;
					break;
				case 'accept_agreement':
					$this->accept_agreement = $varValue;
					break;
				case 'accept':
					$this->accept = $varValue;
					break;
			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}

	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'agreement_text':
				return $this->agreement_text;
				break;
			case 'agreement_headline':
				return $this->agreement_headline;
				break;
			case 'accept_agreement':
				return (($this->accept_agreement) ? true : false);
				break;
			case 'accept':
				return $this->accept;
				break;
			default:
				return parent::__get($strKey);
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

		// Add class "error"
		if (!$accept)
		{
			$this->class = 'error';
		}
	}

	public function generateLabel()
	{
		return "";
	}

	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		return sprintf('<fieldset id="ctrl_agreement" class="checkbox_container"><span><input type="checkbox" name="%s" id="agreement_%s" class="checkbox" value="%s"%s%s%s <label id="lbl_%s" for="agreement_%s">%s</label></span></fieldset> ',
								"accept_agreement",
								$this->strId,
								"1",
								"",
								$this->getAttributes(),
								$this->strTagEnding,
								$this->strId,
								$this->strId,
								$this->accept);
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