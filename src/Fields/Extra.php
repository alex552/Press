<?php


namespace alex552\Press\Fields;



use alex552\Press\MarkdownParser;

class Body
{
    public static function process($type,$value){
        return [
            $type => MarkdownParser::parse($value),
        ];
    }
}