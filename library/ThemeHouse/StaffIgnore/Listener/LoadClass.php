<?php

class ThemeHouse_StaffIgnore_Listener_LoadClass extends ThemeHouse_Listener_LoadClass
{

    protected function _getExtendedClasses()
    {
        return array(
            'ThemeHouse_StaffIgnore' => array(
                'model' => array(
                    'XenForo_Model_UserIgnore'
                ), /* END 'model' */
            ), /* END 'ThemeHouse_StaffIgnore' */
        );
    } /* END _getExtendedClasses */

    public static function loadClassModel($class, array &$extend)
    {
        $extend = self::createAndRun('ThemeHouse_StaffIgnore_Listener_LoadClass', $class, $extend, 'model');
    } /* END loadClassModel */
}