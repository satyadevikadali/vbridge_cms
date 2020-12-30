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

class ESTables
{
	
	
	public static function checkIfTableExists($mysqltablename)
	{
		$conf = JFactory::getConfig();
		$database = $conf->get('db');
		$dbprefix = $conf->get('dbprefix');
		
		$db = JFactory::getDBO();
		
		$t=str_replace('#__',$dbprefix,$mysqltablename);
		
		$query = 'SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = "'.$database.'" AND table_name = "'.$t.'" LIMIT 1';
		
		$db->setQuery( $query );
		if (!$db->query())    die ( $db->stderr());
		
		$rows = $db->loadObjectList();
		if(count($rows)!=1)
			return false;
		
		$c=$rows[0]->c;
		if($c==1)
			return true;

		return false;
	}
	
	public static function getTableName($tableid = 0)
	{
		$db = JFactory::getDBO();
		
		$jinput = JFactory::getApplication()->input;
		
		if($tableid==0)
			$tableid=JFactory::getApplication()->input->get('tableid',0,'INT');
		
		$query = 'SELECT tablename FROM #__customtables_tables AS s WHERE id='.$tableid.' LIMIT 1';
		$db->setQuery( $query );
		if (!$db->query())    die ( $db->stderr());
		
		$rows = $db->loadObjectList();
		if(count($rows)!=1)
			return '';

		return $rows[0]->tablename;
	}
	
	public static function getTableID($tablename)
	{
		$db = JFactory::getDBO();
		
		if($tablename=='')
			return 0;
		
		$query = 'SELECT id FROM #__customtables_tables AS s WHERE tablename="'.$tablename.'" LIMIT 1';
		$db->setQuery( $query );
		if (!$db->query())    die ( $db->stderr());
		
		$rows = $db->loadObjectList();
		if(count($rows)!=1)
			return 0;

		return $rows[0]->id;
	}

	public static function getTableRowByID($tableid)
	{
		$db = JFactory::getDBO();
		
		if($tableid==0)
			return 0;
		
		$query = 'SELECT * FROM #__customtables_tables AS s WHERE id="'.$tableid.'" LIMIT 1';
		$db->setQuery( $query );
		if (!$db->query())    die ( $db->stderr());
		
		$rows = $db->loadObjectList();
		if(count($rows)!=1)
			return 0;

		return $rows[0];
	}
	
	public static function getTableRowByIDAssoc($tableid)
	{
		$db = JFactory::getDBO();
		
		if($tableid==0)
			return 0;
		
		$query = 'SELECT * FROM #__customtables_tables AS s WHERE id="'.$tableid.'" LIMIT 1';
		$db->setQuery( $query );
		if (!$db->query())    die ( $db->stderr());
		
		$rows = $db->loadAssocList();
		if(count($rows)!=1)
			return 0;

		return $rows[0];
	}
	
	public static function getTableRowByName($tablename = '')
	{
		$db = JFactory::getDBO();
		
		if($tablename=='')
			return 0;
		
		$query = 'SELECT * FROM #__customtables_tables AS s WHERE tablename="'.$tablename.'" LIMIT 1';
		$db->setQuery( $query );
		if (!$db->query())    die ( $db->stderr());
		
		$rows = $db->loadObjectList();
		if(count($rows)!=1)
			return '';

		return $rows[0];
	}
	public static function getTableRowByNameAssoc($tablename = '')
	{
		$db = JFactory::getDBO();
		
		if($tablename=='')
			return 0;
		
		$query = 'SELECT * FROM #__customtables_tables AS s WHERE tablename="'.$tablename.'" LIMIT 1';
		$db->setQuery( $query );
		if (!$db->query())    die ( $db->stderr());
		
		$rows = $db->loadAssocList();
		if(count($rows)!=1)
			return '';

		return $rows[0];
	}
	
	
	
	public static function getOrderingQuery(&$ordering,&$query,&$inner,$esordering,$langpostfix,$tablename)
	{
						if(stripos($esordering,'.user')!==false)
						{		//user
								
								$oPair=explode(' ',$esordering);
								$oPair2=explode('.',$oPair[0]);
								
								$fieldname=$oPair2[0];
								if(isset($oPair[1]))
										$direction=$oPair[1];
								else
										$direction='';
								
								$inner[]='LEFT JOIN #__users ON #__users.id=es_'.$fieldname.'';
								
								$query.=', name AS t1';
								
								$ordering[]='#__users.name'.($direction!='' ? ' DESC' : '');
								
						}
						elseif(!(stripos($esordering,'.customtables')===false))
						{		//custom tables

								$oPair=explode(' ',$esordering);
								$oPair2=explode('.',$oPair[0]);
								
								$fieldname=$oPair2[0];
								if(isset($oPair[1]))
										$direction=$oPair[1];
								else
										$direction='';
								
								$join_found=false;
								foreach($inner as $i)
								{
										if(!(strpos($i,'#__customtables_options')===false))
										{
											$join_found=true;	
										}
								}
						
								if(!$join_found)
								{
										$inner[]='LEFT JOIN #__customtables_options ON familytreestr=es_'.$fieldname.'';
								}
								
										$query.=', #__customtables_options.title'.$langpostfix.' AS t1';
								
										$ordering[]='title'.$langpostfix.($direction!='' ? ' DESC' : '');
								

						}//if(stripos($esordering,'customtables')===false)
						elseif(stripos($esordering,'.sqljoin')!==false)
						{		//sql join
								$oPair=explode(' ',$esordering);
								$oPair2=explode('.',$oPair[0]);
								
								$fieldname=$oPair2[0];
								if(isset($oPair[1]))
										$direction=$oPair[1];
								else
										$direction='';
										
								if(isset($oPair2[2]))
								{
										
										$typeparams=explode(',',$oPair2[2]);
										
										$join_table=$typeparams[0];
										$join_field='';
										if(isset($typeparams[1]))
												$join_field=$typeparams[1];
												
										if($join_table!='' and $join_field!='')
										{
											/*
												$inner[]='LEFT JOIN #__customtables_table_'.$join_table.' ON #__customtables_table_'.$join_table.'.id='.$tablename.'.es_'.$fieldname.'';
								
												$query.=', #__customtables_table_'.$join_table.'.es_'.$join_field.' AS t1';
								
												$ordering[]='#__customtables_table_'.$join_table.'.es_'.$join_field.($direction!='' ? ' DESC' : '');
												*/
											
											//$ordering[]='#__customtables_table_'.$join_table.'.es_'.$join_field.($direction!='' ? ' DESC' : '');
											
											$w='#__customtables_table_'.$join_table.'.id='.$tablename.'.es_'.$fieldname;
											$ordering[]='(SELECT #__customtables_table_'.$join_table.'.es_'.$join_field.' FROM #__customtables_table_'.$join_table.' WHERE '.$w.') '.($direction!='' ? ' DESC' : '');
											
										}
								}
								

						}//elseif(stripos($this->esordering,'.sqljoin')!==false)
						else
						{
								if(stripos($esordering,' DESC')===false)
										$ordering[]='es_'.$esordering.'';
								else
								{
										$c=str_replace(' DESC',' DESC',$esordering);
										$c=str_replace(' desc',' desc',$c);
										$ordering[]='es_'.$c;
								}
						}//if(stripos($esordering,'customtables')===false)
				
	}
	
	
	
	
	
	
	
}

?>
