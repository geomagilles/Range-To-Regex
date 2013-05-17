<?php

// Split range to ranges that has its unique pattern.
// Example for 12-345:

//  12- 19: 1[2-9]
//  20- 99: [2-9]\d
// 100-299: [1-2]\d{2}
// 300-339: 3[0-3]\d
// 340-345: 34[0-5]

function regex_for_range($min, $max)
{
    // 
    // regex_for_range(12, 345)
    // "1[2-9]|[2-9]\d|[1-2]\d{2}|3[0-3]\d|34[0-5]"
    $subpatterns = array();

    $start = $min;
    foreach(split_to_ranges($min, $max) as $stop)
    {
        $subpatterns[]=range_to_pattern($start, $stop);
        $start = $stop + 1;
    }
    return implode('|',$subpatterns);
}

function split_to_ranges($min, $max)
{
    $stops = array($max);

    $nines_count = 1;
    $stop = fill_by_nines($min, $nines_count);
    while(($min <= $stop) && ($stop < $max))
    {
        $stops[]=$stop;
        $nines_count += 1;
        $stop = fill_by_nines($min, $nines_count);
    }
    $zeros_count = 1;
    $stop = fill_by_zeros($max, $zeros_count) - 1;
    while(($min < $stop) && ($stop < $max))
    {
        $stops[]=$stop;
        $zeros_count += 1;
        $stop = fill_by_zeros($max, $zeros_count) - 1;
    }
    $stops=array_unique($stops);
    sort($stops);
    return $stops;
}

function fill_by_nines($integer, $nines_count)
{
    // replace last caracters by 9
    // (217,1) returns 219
    // (217,2) returns 299
    // (217,3) returns 999
    return (integer) (substr((string)$integer, 0, -$nines_count).str_repeat('9',$nines_count));
}


function fill_by_zeros($integer, $zeros_count)
{
    // replace last caracters by 0
    // (217,1) returns 210
    // (217,2) returns 200
    // (217,3) returns 000
    return (integer) (substr((string)$integer, 0, -$zeros_count).str_repeat('0',$zeros_count));
}

function range_to_pattern($start, $stop)
{
    $pattern = '';
    $any_digit_count = 0;

    foreach(array_map(null,str_split((string)$start),str_split((string)$stop)) as $arr)
    {
        $start_digit=$arr[0];
        $stop_digit=$arr[1];

        if ($start_digit == $stop_digit)
            $pattern=$pattern.$start_digit;
        else if ($start_digit != '0' || $stop_digit != '9')
            $pattern=$pattern.'['.$start_digit.'-'.$stop_digit.']';
        else
            $any_digit_count += 1;
    }
    if ($any_digit_count) $pattern=$pattern.'\d';
    if ($any_digit_count > 1) $pattern=$pattern.'{'.$any_digit_count.'}';

    return $pattern;
}