<?php
/**
 * Created by PhpStorm.
 * User: ssdt001
 * Date: 2/22/19
 * Time: 2:45 PM
 */

class CustomSummarySiteConfigExtension extends DataExtension
{

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.CustomSummary',
            GridField::create(
                'CustomSummary',
                'Custom Summary Fields For Data Object',
                CustomSummaryFieldHolder::get(),
                new GridFieldConfig_RecordEditor(5))
        );
    }

}