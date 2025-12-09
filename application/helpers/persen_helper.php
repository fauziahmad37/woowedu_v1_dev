<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function persen($total, $number)
{
  if ( $total > 0 ) {
   return round($number / ($total / 100),2);
  } else {
    return 0;
  }
}