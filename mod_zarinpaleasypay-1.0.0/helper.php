<?php
class ModZarinpalEasyPayHelper
{
    public static function getFormItems($form_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__zarinpaleasypay_forms'))
            ->where('id = ' . (int)$form_id);
        $db->setQuery($query);
        $result = $db->loadAssoc();
        return $result;
    }
}