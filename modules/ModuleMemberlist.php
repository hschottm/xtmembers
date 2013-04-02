<?php

/**
 * @copyright  Helmut Schottmüller
 * @author     Helmut Schottmüller <https://github.com/hschottm>
 * @license    LGPL
 */

namespace Xtmembers;

class ModuleMemberlist extends \Contao\ModuleMemberlist
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_memberlist_simple';
	protected $currentMember = null;

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		$this->memberlist_where = $this->replaceInsertTags($this->memberlist_where);
		$this->strTemplate = $this->memberlist_template;
		return parent::generate();
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
		$this->currentMember = $objMember;
		if ($this->show_member_name)
		{
			global $objPage;
			$objPage->pageTitle = trim(htmlspecialchars($objMember->firstname . " " . $objMember->lastname));
		}
		$this->Template->membergroups = deserialize($objMember->groups, true);
	}


	/**
	 * List all members
	 */
	protected function listAllMembersInCaseOfCustomWhere()
	{
		$time = time();
		$arrFields = $this->arrMlFields;
		$intGroupLimit = (count($this->arrMlGroups) - 1);
		$arrValues = array();
		$strWhere = '';

		// Search query
		if (\Input::get('search') && \Input::get('for') != '' && \Input::get('for') != '*')
		{
			$strWhere .= \Input::get('search') . " REGEXP ? AND ";
			$arrValues[] = \Input::get('for');
		}

		$strOptions = '';
		$arrSortedFields = array();

		// Sort fields
		foreach ($arrFields as $field)
		{
			$arrSortedFields[$field] = $GLOBALS['TL_DCA']['tl_member']['fields'][$field]['label'][0];
		}

		natcasesort($arrSortedFields);

		// Add searchable fields to drop-down menu
		foreach ($arrSortedFields as $k=>$v)
		{
			$strOptions .= '  <option value="' . $k . '"' . (($k == \Input::get('search')) ? ' selected="selected"' : '') . '>' . $v . '</option>' . "\n";
		}

		$this->Template->search_fields = $strOptions;
		$strWhere .= "(";

		// Filter groups
		for ($i=0; $i<=$intGroupLimit; $i++)
		{
			if ($i < $intGroupLimit)
			{
				$strWhere .= "groups LIKE ? OR ";
				$arrValues[] = '%"' . $this->arrMlGroups[$i] . '"%';
			}
			else
			{
				$strWhere .= "groups LIKE ?) AND ";
				$arrValues[] = '%"' . $this->arrMlGroups[$i] . '"%';
			}
		}

		// List active members only
		if (in_array('username', $arrFields))
		{
			$strWhere .= "(publicFields!='' OR allowEmail=? OR allowEmail=?) AND disable!=1 AND (start='' OR start<=?) AND (stop='' OR stop>=?)";
			array_push($arrValues, 'email_member', 'email_all', $time, $time);
		}
		else
		{
			$strWhere .= "publicFields!='' AND disable!=1 AND (start='' OR start<=?) AND (stop='' OR stop>=?)";
			array_push($arrValues, $time, $time);
		}
		
		// NEW LINE BY HS
		$strWhere .= ((strlen($strWhere) == 0) ? "" : " AND ") . $this->memberlist_where;
		// NEW LINE BY HS

		// Get total number of members
		$objTotal = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_member WHERE " . $strWhere)
						 ->execute($arrValues);

		// Split results
		$page = \Input::get('page') ? \Input::get('page') : 1;
		$per_page = \Input::get('per_page') ? \Input::get('per_page') : $this->perPage;
		$order_by = \Input::get('order_by') ? \Input::get('order_by') . ' ' . \Input::get('sort') : 'username';

		// Begin query
		$objMemberStmt = $this->Database->prepare("SELECT id, username, publicFields, " . implode(', ', $this->arrMlFields) . " FROM tl_member WHERE " . $strWhere . " ORDER BY " . $order_by);

		// Limit
		if ($per_page)
		{
			$objMemberStmt->limit($per_page, (($page - 1) * $per_page));
		}

		$objMember = $objMemberStmt->execute($arrValues);
		$this->currentMember = $objMember;

		// Prepare URL
		$strUrl = preg_replace('/\?.*$/', '', $this->Environment->request);
		$this->Template->url = $strUrl;
		$blnQuery = false;

		// Add GET parameters
		foreach (preg_split('/&(amp;)?/', $_SERVER['QUERY_STRING']) as $fragment)
		{
			if (strlen($fragment) && strncasecmp($fragment, 'order_by', 8) !== 0 && strncasecmp($fragment, 'sort', 4) !== 0 && strncasecmp($fragment, 'page', 4) !== 0)
			{
				$strUrl .= (!$blnQuery ? '?' : '&amp;') . $fragment;
				$blnQuery = true;
			}
		}

		$strVarConnector = $blnQuery ? '&amp;' : '?';

		// Prepare table
		$arrTh = array();
		$arrTd = array();

		// THEAD
		for ($i=0; $i<count($arrFields); $i++)
		{
			$class = '';
			$sort = 'asc';
			$strField = strlen($label = $GLOBALS['TL_DCA']['tl_member']['fields'][$arrFields[$i]]['label'][0]) ? $label : $arrFields[$i];

			if (\Input::get('order_by') == $arrFields[$i])
			{
				$sort = (\Input::get('sort') == 'asc') ? 'desc' : 'asc';
				$class = ' sorted ' . \Input::get('sort');
			}

			$arrTh[] = array
			(
				'link' => $strField,
				'href' => (ampersand($strUrl) . $strVarConnector . 'order_by=' . $arrFields[$i]) . '&amp;sort=' . $sort,
				'title' => specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['list_orderBy'], $strField)),
				'class' => $class . (($i == 0) ? ' col_first' : '')
			);
		}

		$start = -1;
		$limit = $objMember->numRows;

		// TBODY
		while ($objMember->next())
		{
			$publicFields = deserialize($objMember->publicFields, true);
			$class = 'row_' . ++$start . (($start == 0) ? ' row_first' : '') . ((($start + 1) == $limit) ? ' row_last' : '') . ((($start % 2) == 0) ? ' even' : ' odd');

			foreach ($arrFields as $k=>$v)
			{
				$value = '-';

				if ($v == 'username' || in_array($v, $publicFields))
				{
					$value = $this->formatValue($v, $objMember->$v);
				}

				$arrData = $objMember->row();
				unset($arrData['publicFields']);

				$arrTd[$class][$k] = array
				(
					'raw' => $arrData,
					'content' => $value,
					'class' => 'col_' . $k . (($k == 0) ? ' col_first' : ''),
					'id' => $objMember->id,
					'field' => $v
				);
			}
		}

		$this->Template->col_last = 'col_' . ++$k;
		$this->Template->thead = $arrTh;
		$this->Template->tbody = $arrTd;

		// Pagination
		$objPagination = new \Pagination($objTotal->count, $per_page);
		$this->Template->pagination = $objPagination->generate("\n  ");
		$this->Template->per_page = $per_page;

		// Template variables
		$this->Template->action = $this->getIndexFreeRequest();
		$this->Template->search_label = specialchars($GLOBALS['TL_LANG']['MSC']['search']);
		$this->Template->per_page_label = specialchars($GLOBALS['TL_LANG']['MSC']['list_perPage']);
		$this->Template->fields_label = $GLOBALS['TL_LANG']['MSC']['all_fields'][0];
		$this->Template->keywords_label = $GLOBALS['TL_LANG']['MSC']['keywords'];
		$this->Template->search = \Input::get('search');
		$this->Template->for = \Input::get('for');
		$this->Template->order_by = \Input::get('order_by');
		$this->Template->sort = \Input::get('sort');
	}
	
	/**
	 * List all members
	 */
	protected function listAllMembers()
	{
		if (strlen($this->memberlist_sort) && (!strlen(\Input::get('order_by'))) && (in_array($this->memberlist_sort, $this->arrMlFields))) 
		{
			\Input::setGet('order_by', $this->memberlist_sort);
			\Input::setGet('sort', 'asc');
		}
		if (strlen($this->memberlist_where) > 0)
		{
			$this->listAllMembersInCaseOfCustomWhere();
		}
		else
		{
			parent::listAllMembers();
		}
		if ($this->memberlist_jumpTo)
		{
			$page = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
									  ->limit(1)
									  ->execute($this->memberlist_jumpTo);
			$pagerow = $page->row();
			$arr = $this->Template->tbody;
			$urlparameter = (strlen($GLOBALS['TL_CONFIG']['memberurlparameter'])) ? $GLOBALS['TL_CONFIG']['memberurlparameter'] : 'member';
			foreach ($arr as $key => $member)
			{
				foreach ($member as $colidx => $col)
				{
					$arr[$key][$colidx]['jumpTo'] = $this->generateFrontendUrl($pagerow, '/' . $urlparameter . '/' . $this->Template->tbody[$key][$colidx]['id']);
				}
			}
			$this->Template->tbody = $arr;
		}
		$this->Template->show_searchfield = $this->show_searchfield;
		$this->Template->showDetailsColumn = $this->memberlist_showdetailscolumn;
		$this->Template->detailsColumn = $this->memberlist_detailscolumn;
	}


	/**
	 * Format a value
	 * @param string
	 * @param mixed
	 * @param boolean
	 * @return mixed
	 */
	protected function formatValue($k, $value, $blnListSingle=false)
	{
		$data = deserialize($value);

		// Return if empty
		if (is_string($data) && !strlen($data))
		{
			return '-';
		}

		// Array
		if ($GLOBALS['TL_DCA']['tl_member']['fields'][$k]['inputType'] == 'avatar')
		{
			$maxlength  = $GLOBALS['TL_CONFIG']['avatar_maxsize'];
			$extensions = $GLOBALS['TL_CONFIG']['avatar_filetype'];
			$uploadFolder  = $GLOBALS['TL_CONFIG']['avatar_dir'];
			$storeFile  = $uploadFolder != '' ? true : false;
			$arrImage  = deserialize($GLOBALS['TL_CONFIG']['avatar_maxdims']);

			$this->import('FrontendUser', 'User');

			$objFile = \FilesModel::findByPk($data);
			if ($this->currentMember)
			{
				$strAlt = $this->currentMember->firstname . " " . $this->currentMember->lastname;
			}

			if ( $objFile !== null )     
			  return '<img src="' . TL_FILES_URL . \Image::get($objFile->path, $arrImage[0], $arrImage[1], $arrImage[2]) . '" width="' . $arrImage[0] . '" height="' . $arrImage[1] . '" alt="' . $strAlt . '" class="avatar">';
			elseif ( $this->User->gender != '' )
				return '<img src="' . TL_FILES_URL . \Image::get("system/modules/avatar/assets/" . $this->User->gender . ".png", $arrImage[0], $arrImage[1], $arrImage[2]) . '" width="' . $arrImage[0] . '" height="' . $arrImage[1] . '" alt="Avatar" class="avatar">';       
			else
				return  '<img src="' . TL_FILES_URL . \Image::get("system/modules/avatar/assets/male.png", $arrImage[0], $arrImage[1], $arrImage[2]) . '" width="' . $arrImage[0] . '" height="' . $arrImage[1] . '" alt="Avatar" class="avatar">';       
		}
		else
		{
			return parent::formatValue($k, $value, $blnListSingle=false);
		}
	}

	private function getPublicFields($objMember)
	{
		$publicFields = deserialize($objMember->publicFields, true);
		foreach ($publicFields as $key => $field)
		{
			try
			{
				$objMember->$field;
			}
			catch (Exception $e)
			{
				unset($publicFields[$key]);
			}
		}
		return $publicFields;
	}
}

?>