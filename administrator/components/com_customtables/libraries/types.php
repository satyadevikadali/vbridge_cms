<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Joomlaboat
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.76
	@build			3ed July, 2018
	@created		30th May, 2018
	@package		Template Shop
	@subpackage		default.php
	@author			Ivan Komlev <http://joomlaboat.com>
	@copyright		Copyright (C) 2018. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class CTTypes
{
    public static function getField($type, $attributes, $field_value = '')
    {
        static $types = null;
        $defaults = array('name' => '', 'id' => '');
        if (!$types) {
            jimport('joomla.form.helper');
            $types = array();
        }
        if (!in_array($type, $types))
        {
            JFormHelper::loadFieldClass($type);
        }
        try
        {
            $attributes = array_merge($defaults, $attributes);
            $xml = new JXMLElement('<?xml version="1.0" encoding="utf-8"?><field />');
            foreach ($attributes as $key => $value)
            {
                if ('_options' == $key)
                {
                    foreach ($value as $_opt_value)
                    {
                        $xml->addChild('option', $_opt_value->text)->addAttribute('value', $_opt_value->value);
                    }
                    continue;
                }
                $xml->addAttribute($key, $value);
            }

            $class = 'JFormField' . $type;
            $field = new $class();

            $field->setup($xml, $field_value);

            return $field;
        }
        catch (Exception $e)
        {
            return false;
        }
    }
}

?>
