<?php

namespace App\Services;

class XMLModelConverter extends ModelConverter{

    private const extension = '.xml';

    public function GetExtension(){
        return Self::extension;
    }

    protected function Convert($models): string
    {
        $xmlElements = $this->ConvertModelsIntoXMLElements($models);
        
        $xmlElements = array_unique($xmlElements);

        $result = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<root>'."\n";

        foreach($xmlElements as $xmlElement){
            $result .= $xmlElement;
        }

        $result .= '</root>'."\n";

        return $result;
    }

    private function ConvertModelsIntoXMLElements($models){
        
        $xmlElements = [];
        
        foreach($models as $model)
        {    
            $xmlElements[] = $this->ConvertModelIntoXMLElement($model);
        }

        return $xmlElements;
    }

    private function ConvertModelIntoXMLElement($model){

        $xmlElement = '<element>'."\n";


        foreach($this->fields as $field){
            if ($field_arr = explode('.', $field)) {
                // read relation model
                $related_model = $model;
                foreach ($field_arr as $f) {
                    $related_model = $related_model->$f;
                }
                $xmlElement .= '<'.$field.'>'.$related_model.'</'.$field.'>'."\n";
            } else {
                $xmlElement .= '<'.$field.'>'.$model->$field.'</'.$field.'>'."\n";
            }

        }

        $xmlElement .= '</element>'."\n";
        return $xmlElement;
    }
}