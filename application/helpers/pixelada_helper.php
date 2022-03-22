<?php

function print_array($a){
    echo '<pre>'.print_r($a, true).'</pre>';
}

function strtoslug($text){ 
    $text = str_replace(array('á', 'à', 'ä', 'â', 'Á', 'À', 'Ä', 'Â', 'ª'), array('a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a'), $text);
    $text = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê'), array('e', 'e', 'e', 'e', 'e', 'e', 'e', 'e'), $text);
    $text = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'i', 'i', 'i', 'i'), $text);
    $text = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô', 'º'), array('o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o'), $text);
    $text = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Ü', 'Û'), array('u', 'u', 'u', 'u', 'u', 'u', 'u', 'u'), $text);
    $text = str_replace(array('ñ', 'ç'), array('n', 'c'), $text);
    
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if(empty($text))  $text = 'n-a';

    return $text;
}