<?php

function toSecond($stime = NULL) {
    if($stime == NULL)
        $stime = date('Y-m-d H:i:s');
    $zhongkong_time = ((date("Y", $stime) - 2000) * 12 * 31 + ((date("n", $stime)-1)*31) + date("j", $stime) - 1) * (24 * 60 * 60) +((intval(date("H", $stime))-7) * 60 + intval(date("i", $stime))) * 60 + intval(date("s", $stime));
    return $zhongkong_time;
}

function formatTimezone($tz) {
    //if(!date('H:i', strtotime($tstart)) || !date('H:i', strtotime($tend)))
    //    return false;
    $_start = explode(':', $tz['start']);
    $_end = explode(':', $tz['end']);
    $start = intval(($_start[0] * 100)) + intval($_start[1]); 
    $end = intval(($_end[0] * 100)) + intval($_end[1]); 
    $new = ($start << 16) + ($end &(0xFFF));
    return $new;
}