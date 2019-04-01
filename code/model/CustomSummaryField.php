<?php
/**
 * Created by PhpStorm.
 * User: ssdt001
 * Date: 2/18/19
 * Time: 10:22 AM
 */

class CustomSummaryField extends DataObject
{
    private static $db = array(
        'OriginalField' => 'Varchar(255)',
        'LabelField' => 'Varchar(255)',
        'SelectedType' => "Enum('DB and Relations,Functions','DB and Relations')",
        'Sort' => 'Int'
    );

    private static $has_one = array(
        'CustomSummaryFieldHolder' => 'CustomSummaryFieldHolder'
    );

    private static $summary_fields = array(
        'OriginalField',
        'LabelField'
    );

    public function getTitle() {
        return $this->OriginalField;
    }

    private static $default_sort = 'Sort';

    public function validate()
    {
        $result = parent::validate();

        if(
            $this->SelectedType == 'Functions'
            && ($objectType = $this->CustomSummaryFieldHolder()->ClassType)
            && ($funcName = $this->OriginalField)
            && !singleton($objectType)->hasMethod($funcName)
        ) {
            $result->error('Function doesn\'t exist.', 'bad');
        }

        return $result;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(array(
            'CustomSummaryFieldHolderID',
            'SelectedType',
            'LabelField',
            'OriginalField',
        ));

        if($this->ID) {
            if($this->SelectedType == 'Functions') {
                $fields->addFieldsToTab('Root.Main', array(
                    TextField::create('OriginalField', 'Custom method name'),
                    TextField::create('LabelField', 'Label name wants to display'),
                ));
            } else {
                $className = $this->CustomSummaryFieldHolder()->ClassType;
                $dbFields = $className::create()->db();

                $arr1 = array_keys($dbFields);
                $arrFields = array_combine($arr1, $arr1);

                $relationDbFields = array();
                $relations = Config::inst()->get($className, 'has_one', Config::UNINHERITED);

                if($relations) {
                    $arr = array_keys($relations);
                    foreach ($arr as $relation) {
                        $arrRelationFields = array();
                        $rFields = array_keys($relations[$relation]::create()->db());
                        foreach ($rFields as $rField){
                            $arrRelationFields[] = $relation.'.'.$rField;
                        }
                        $relationDbFields = array_merge($relationDbFields, $arrRelationFields);
                    }
                    $newFieldsArr = array_combine($relationDbFields, $relationDbFields);
                    $arrFields = array_merge($arrFields, $newFieldsArr);
                }
                $fields->addFieldsToTab('Root.Main', array(
                    DropdownField::create('OriginalField', 'Field')
                        ->setSource($arrFields),
                    TextField::create('LabelField', 'Custom Label'),
                ));
            }
        } else {
            $fields->addFieldsToTab('Root.Main',array(
                DropdownField::create('SelectedType', 'Select the summary field type and save for continue')
                    ->setSource($this->dbObject('SelectedType')->enumValues())
            ));
        }

        return $fields;
    }


}
