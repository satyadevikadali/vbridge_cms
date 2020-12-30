<?php
/*
	@version		1.0.76
	@build			3ed July, 2018
	@created		24th May, 2018
	@package		Custom Tables
	@author			Ivan Komlev <https://joomlaboat.com>
	@copyright		Copyright (C) 2018. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html

*/

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_customtables'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'languages.php');
require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_customtables'.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'fields.php');

function checkTableFields($establename,$tableid)
{
    $conf = JFactory::getConfig();
	$dbprefix = $conf->get('dbprefix');

    $db = JFactory::getDBO();
    $db->setQuery('SHOW TABLES LIKE "'.$dbprefix.'customtables_table_'.$establename.'"');
    if (!$db->query()) return false;
    $a=$db->loadAssocList();


    if(count($db->loadAssocList())==0)
        return false;

    $ExistingFields=ESFields::getExistingFields($establename);
    $jinput = JFactory::getApplication()->input;

    $LangMisc	= new ESLanguages;
    $languages=$LangMisc->getLanguageList();




    $complete_table_name='#__customtables_table_'.$establename;

    $link='/administrator/index.php?option=com_customtables&view=listoffields&tableid='.$tableid;

    $query = 'SELECT fieldname,CONCAT("es_",fieldname) AS thefieldname, type, typeparams FROM #__customtables_fields WHERE published=1 AND tableid='.$tableid.'';
    $db->setQuery( $query );

    $projected_fields = $db->loadAssocList();
    echo '<br/><hr/>';

    //Delete unnesesary fields:
    $projected_fields[]=['thefieldname'=>'id','type'=>'_id','typeparams'=>''];
    $projected_fields[]=['thefieldname'=>'published','type'=>'_published','typeparams'=>''];


    $task=$jinput->getCmd('task');
    $taskfieldname=$jinput->    getCmd('fieldname');

    foreach($ExistingFields as $existing_field)
    {
        //print_r($existing_field);

        $field_mysql_type=$existing_field['Type'];
        if($existing_field['Null']=='YES')
            $field_mysql_type.=' NULL';
        else
            $field_mysql_type.=' NOT NULL';

        $default=$existing_field['Default'];
        if($default!=null)
            $field_mysql_type.=' DEFAULT '.$default;

        //echo 'field='.$existing_field['Field'].'<br/>';
        //echo '$field_mysql_type='.$field_mysql_type.'<br/>';




        $exst_field=$existing_field['Field'];
        $found=false;
        $found_field='';
        $found_fieldparams='';
        foreach($projected_fields as $projected_field)
        {


            if($projected_field['type']=='multilangstring' or $projected_field['type']=='multilangtext')
            {
                $morethanonelang=false;
				foreach($languages as $lang)
				{
					$fieldname=$projected_field['thefieldname'];
					if($morethanonelang)
						$fieldname.='_'.$lang->sef;


                    if($exst_field==$fieldname)
                    {
                        $PureFieldType=ESFields::getPureFieldType($projected_field['type'], $projected_field['typeparams']);
                        $found_field=$projected_field['thefieldname'];
                        $found_fieldparams=$projected_field['typeparams'];
                        $found=true;
                        break;
                    }
                    $morethanonelang=true;
                }
            }
            elseif($projected_field['type']=='imagegallery')
            {
                if($exst_field==$projected_field['thefieldname'])
                {
                    $gallery_table_name='#__customtables_gallery_'.$establename.'_'.$projected_field['fieldname'];//.'_imagegallery';
                    checkGalleryTitleFields($gallery_table_name,$languages,$establename,$projected_field['fieldname']);

                    $PureFieldType=ESFields::getPureFieldType($projected_field['type'], $projected_field['typeparams']);
                    $found_field=$projected_field['thefieldname'];
                    $found_fieldparams=$projected_field['typeparams'];
                    $found=true;
                    break;
                }

            }
            elseif($projected_field['type']=='filebox')
            {
                if($exst_field==$projected_field['thefieldname'])
                {
                    $filebox_table_name='#__customtables_filebox_'.$establename.'_'.$projected_field['fieldname'];
                    checkFilesTitleFields($filebox_table_name,$languages,$establename,$projected_field['fieldname']);

                    $PureFieldType=ESFields::getPureFieldType($projected_field['type'], $projected_field['typeparams']);
                    $found_field=$projected_field['thefieldname'];
                    $found_fieldparams=$projected_field['typeparams'];
                    $found=true;
                    break;
                }

            }
            elseif($projected_field['type']=='dummy')
            {
                if($exst_field==$projected_field['thefieldname'])
                {
                    $found=false;
                    break;
                }

            }
            else
            {
                if($exst_field==$projected_field['thefieldname'])
                {
                    $PureFieldType=ESFields::getPureFieldType($projected_field['type'], $projected_field['typeparams']);
                    $found_field=$projected_field['thefieldname'];
                    $found_fieldparams=$projected_field['typeparams'];
                    $found=true;
                    break;
                }
            }
        }

        if(!$found)
        {
            //Delete field
            if($task=='deleteurfield' and $taskfieldname==$exst_field)
            {
                if(ESFields::deleteMYSQLField($complete_table_name,$exst_field))
                    echo '<p>Field "<span style="color:green;">'.$exst_field.'</span>" not registered. <span style="color:green;">Deleted.</span></p>';
            }
            else
                echo '<p>Field "<span style="color:red;">'.$exst_field.'</span>" not registered. <a href="'.$link.'&task=deleteurfield&fieldname='.$exst_field.'">Delete?</a></p>';
        }
        else
        {

           // echo '$found_field='.$found_field.'<br/>';
            if($field_mysql_type!=$PureFieldType)
            {
                if($task=='fixfieldtype' and $taskfieldname==$exst_field)
                {
                    if(ESFields::fixMYSQLField($complete_table_name,$found_field,$PureFieldType))
                    {
                        echo '<p>Field "<span style="color:green;">'.str_replace('es_','',$found_field).'</span>" Fixed';
                    }
                }
                else
                {
                    echo '<p>Field "<span style="color:orange;">'.str_replace('es_','',$found_field).' ('.$found_fieldparams.')</span>"'
                        .' has wrong type "<span style="color:red;">'.$field_mysql_type.'</span>" insteas of "<span style="color:green;">'.$PureFieldType.'</span>" <a href="'.$link.'&task=fixfieldtype&fieldname='.$exst_field.'">Fix?</a></p>';
                }
            }

        }

    }

//die;
    //Add missing fields
    foreach($projected_fields as $projected_field)
    {
        $proj_field=$projected_field['thefieldname'];
        $fieldtype=$projected_field['type'];
        if($fieldtype!='dummy')
        {
            checkField($ExistingFields,$establename,$proj_field,$fieldtype,$projected_field['typeparams'],$languages);

        }

    }

}
    function checkGalleryTitleFields($gallery_table_name,&$languages,$establename,$esfieldname)
    {

        if(!ESTables::checkIfTableExists($gallery_table_name))
        {

            ESFields::CreateImageGalleryTable($establename,$esfieldname);
            echo '<p>Image Gallery Table "<span style="color:green;">'.$gallery_table_name.'</span>" <span style="color:green;">Created.</span></p>';

        }

                    $g_ExistingFields=ESFields::getExistingFields($gallery_table_name,false);

                    $morethanonelang=false;
                    foreach($languages as $lang)
                    {
                    	$g_fieldname='title';
                    	if($morethanonelang)
                    		$g_fieldname.='_'.$lang->sef;

                        $g_found=false;

                        foreach($g_ExistingFields as $g_existing_field)
                        {
                            $g_exst_field=$g_existing_field['Field'];
                            if($g_exst_field==$g_fieldname)
                            {
                                $g_found=true;
                                break;
                            }
                        }

                        if(!$g_found)
                        {
                            ESFields::AddMySQLFieldNotExist($gallery_table_name, $g_fieldname, 'varchar(100) NULL', '');
                            echo '<p>Image Gallery Field "<span style="color:green;">'.$g_fieldname.'</span>" <span style="color:green;">Added.</span></p>';
                        }

                        $morethanonelang=true;
                    }
    }

    function checkFilesTitleFields($filebox_table_name,&$languages,$establename,$esfieldname)
    {

        if(!ESTables::checkIfTableExists($filebox_table_name))
        {

            ESFields::CreateFileBoxTable($establename,$esfieldname);
            echo '<p>File Box Table "<span style="color:green;">'.$filebox_table_name.'</span>" <span style="color:green;">Created.</span></p>';
        }

                    $g_ExistingFields=ESFields::getExistingFields($filebox_table_name,false);

                    $morethanonelang=false;
                    foreach($languages as $lang)
                    {
                    	$g_fieldname='title';
                    	if($morethanonelang)
                    		$g_fieldname.='_'.$lang->sef;

                        $g_found=false;

                        foreach($g_ExistingFields as $g_existing_field)
                        {
                            $g_exst_field=$g_existing_field['Field'];
                            if($g_exst_field==$g_fieldname)
                            {
                                $g_found=true;
                                break;
                            }
                        }

                        if(!$g_found)
                        {
                            ESFields::AddMySQLFieldNotExist($filebox_table_name, $g_fieldname, 'varchar(100) NULL', '');
                            echo '<p>File Box Field "<span style="color:green;">'.$g_fieldname.'</span>" <span style="color:green;">Added.</span></p>';
                        }

                        $morethanonelang=true;
                    }
    }

    function addField($establename,$fieldname,$fieldtype,$typeparams)
    {
        $PureFieldType=ESFields::getPureFieldType($fieldtype,$typeparams);
        ESFields::AddMySQLFieldNotExist('#__customtables_table_'.$establename, $fieldname, $PureFieldType, '');

        echo '<p>Field "<span style="color:green;">'.$fieldname.'</span>" <span style="color:green;">Added.</span></p>';
    }

    function checkField($ExistingFields,$tablename,$proj_field,$fieldtype,$typeparams,&$languages)
    {
        if($fieldtype=='multilangstring' or $fieldtype=='multilangtext')
        {
            $found=false;
            $morethanonelang=false;
        	foreach($languages as $lang)
        	{
        		$fieldname=$proj_field;
        		if($morethanonelang)
        			$fieldname.='_'.$lang->sef;

                $found=false;
                foreach($ExistingFields as $existing_field)
                {
                    //echo '$fieldname: '.$fieldname.'<br/>';
                    //echo '$existing_field[Field]: '.$existing_field['Field'].'<br/>';
                    if($fieldname==$existing_field['Field'])
                    {
                        $found=true;
                        break;
                    }
                }

                if(!$found)
                {
                    //Add field
                    addField($tablename,$fieldname,$fieldtype,$typeparams);
                }

                $morethanonelang=true;
            }
        }
        else
        {
            $found=false;
            foreach($ExistingFields as $existing_field)
            {
                if($proj_field==$existing_field['Field'])
                {
                    $found=true;
                    break;
                }
            }

            if(!$found)
            {
                //Add field
                addField($tablename,$proj_field,$fieldtype,$typeparams);

            }
        }
    }



?>
