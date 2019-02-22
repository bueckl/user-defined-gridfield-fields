<?php
/**
 * Created by PhpStorm.
 * User: ssdt001
 * Date: 2/18/19
 * Time: 4:36 PM
 */

class CustomSummaryDataObjectExtension extends DataExtension
{

    public function updateSummaryFields(&$fields)
    {
        $currFields = Config::inst()->get($this->owner->class, 'summary_fields');
        $className = $this->owner->class;
        $newFields = null;

        if($customFields = CustomSummaryFieldHolder::get()
            ->filter(array('ClassType' => $className))
            ->first()) {
            $newFields = $customFields->CustomSummaryFields()->column('OriginalField');
        }

        if($newFields && array_key_exists(0, $newFields)) {

            if($currFields) {
                $fields = array_unique(array_merge($newFields, $currFields), SORT_REGULAR);
                $fields = array_combine(array_values($fields), array_values($fields));
            } else {
                $fields = array_combine(array_values($newFields), array_values($newFields));
            }

        } else {

            if($currFields) {
                $fields = array_combine(array_values($currFields), array_values($currFields));
            }
        }
    }


    public function updateFieldLabels(&$labels)
    {
        $field_labels = Config::inst()->get($this->owner->class, 'field_labels');

        $className = $this->owner->class;
        $newLabels = array();

        if($customFields = CustomSummaryFieldHolder::get()
            ->filter(array('ClassType' => $className))
            ->first()) {

            $summaryFields = $customFields->CustomSummaryFields();
            foreach ($summaryFields as $summaryField) {
                $newLabels[$summaryField->OriginalField] = $summaryField->LabelField;
            }
        }

        if($field_labels) {
            $labels = array_merge($newLabels, $field_labels);
        } else {
            $labels = $newLabels;
        }
    }



}