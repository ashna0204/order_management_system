<?php

if(!function_exists('number_formatter')){
function number_formatter(
    $numbers,
    $decimals=2,
    $currency="$",
    $decimalSeparator=".",
    $thousandSeparators=",",
    $options=[
        'round'=>true,
        'show_decimals'=>true,
        'use_currency'=>true,
        'use_separators'=>true,
    ]
){

    if($options['round']){
        $number=round($numbers, $decimals);
    }

    if(!$options['use_separators']){
        $thousandSeparators='';
    }

    if(!$options['show_decimals']){
     $decimals=0;
    }

    $formatted= number_format($number,$decimals, $decimalSeparator,$thousandSeparators);

    return $options['use_currency'] ? $currency . $formatted : $formatted; 
}
}
?>