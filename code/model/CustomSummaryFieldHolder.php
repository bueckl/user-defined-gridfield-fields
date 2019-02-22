<?php
/**
 * Created by PhpStorm.
 * User: ssdt001
 * Date: 2/18/19
 * Time: 10:21 AM
 */

class CustomSummaryFieldHolder extends DataObject
{
    private static $db = array(
        'ClassType' => 'Text'
    );

    private static $has_many = array(
        'CustomSummaryFields' => 'CustomSummaryField'
    );

    private static $summary_fields = array(
        'ClassType'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $objArr = SS_ClassLoader::instance()->getManifest()->getDescendantsOf('dataobject');
        $combinedArr = array_combine($objArr, $objArr);

        $fields->addFieldsToTab('Root.Main', array(
            DropdownField::create('ClassType', 'Data Object')->setSource($combinedArr),
        ));

        return $fields;
    }
}