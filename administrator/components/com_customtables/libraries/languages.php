<?php
/**
 * CustomTables Joomla! 3.x Native Component
 * @version 1.0.76
 * @author Ivan komlev <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @license GNU/GPL
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');

class ESLanguages
{
	var $LanguageList;
	function __construct()
	{
		$this->getLanguageList();
	}
	
		 
	function getLangPostfix()
	{
		$langObj=JFactory::getLanguage();
		$nowLang=$langObj->getTag();
		$index=0;
		foreach($this->LanguageList as $lang)
		{
			if($lang->language==$nowLang)
			{
				if($index==0)
					return '';
				else
					return '_'.$lang->sef;
			}
			
			$index++;
		}
		return '';
	}
	
	function getLanguageList()
	{
		$db = JFactory::getDBO();
		
		$query ='SELECT lang_id AS id, lang_code AS language, title AS caption, title, sef FROM #__languages WHERE published=1 ORDER BY lang_id';
			
		$db->setQuery( $query );
		
		if (!$db->query())    die( $db->stderr());
		
		$rows = $db->loadObjectList();
		
		$this->LanguageList=$rows;
		
		return $rows;
	}

	function getLanguageTagByID($id)
	{
		
		foreach($this->LanguageList as $lang)
		{
			if($lang->id==$id)
				return $lang->language;
		}
		return '';
	}

	function getLanguageByCODE($code)
	{
		$db = JFactory::getDBO();
		
		$query = ' SELECT lang_id AS id FROM #__languages WHERE lang_code="'.$code.'" LIMIT 1';
			
		
		$db->setQuery( $query );
		$rows= $db->loadObjectList();
		if(count($rows)!=1)
			return -1;
		
		return $rows[0]->id;
	}
}

?>
