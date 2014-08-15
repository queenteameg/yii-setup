<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XMenu
 *
 * @author mohamed
 */
class XMenu extends CApplicationComponent {

    private static $menuTree = array();

    public static function getMenuTree() {
        if (empty(self::$menuTree)) {
            $rows = Menu::model()->findAll('parent_id IS NULL');
            foreach ($rows as $item) {
                self::$menuTree[] = self::getMenuItems($item);
            }
        }
        return self::$menuTree;
    }

    private static function getMenuItems($modelRow) {

        if (!$modelRow)
            return;

        if (isset($modelRow->Childs)) {
            $chump = self::getMenuItems($modelRow->Childs);
            if ($chump != null)
                $res = array('label' => $modelRow->title, 'items' => $chump, 'url' => Yii::app()->createUrl($modelRow->controller . '/' . $modelRow->action, array('id' => $modelRow->id)));
            else
                $res = array('label' => $modelRow->title, 'url' => Yii::app()->createUrl($modelRow->controller . '/' . $modelRow->action, array('id' => $modelRow->id)));
            return $res;
        } else {
            if (is_array($modelRow)) {
                $arr = array();
                foreach ($modelRow as $leaves) {
                    $arr[] = self::getMenuItems($leaves);
                }
                return $arr;
            } else {
                return array('label' => ($modelRow->title), 'url' => Yii::app()->createUrl($modelRow->controller . '/' . $modelRow->action, array('id' => $modelRow->id)));
            }
        }
    }

}
