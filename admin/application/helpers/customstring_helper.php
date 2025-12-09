<?php

/**
 * 
 *
 * @param integer $num
 * @return void
 */
function str_random(int $num = 10): string {
    $out = '';
    try
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = strlen($characters);
        $out = '';
        for($i=0;$i<$num;$i++)
            $out .= $characters[random_int(0, $length - 1)];

    }
    catch(Exception $e)
    {
        throw new Exception($e, 500);
    }

    return $out;
}

/**
 * Create UUID string
 *
 * @return string
 */
function create_uuid4(): string {
    $out = '';
    
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
    $out = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

    return $out;
}


