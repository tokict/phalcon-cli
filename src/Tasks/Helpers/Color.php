<?php

namespace Danzabar\CLI\Tasks\Helpers;

use Danzabar\CLI\Tasks\Helpers\Helper;

/**
 * The table helper class
 *
 * @package CLI
 * @subpackage Tasks\Helpers
 * @author Dan Cox
 */


class Color extends Helper
{

    const black = "0;30";
    const brown  = "0;33";
    const gray  = "0;37";
    const blue  = "1;34";
    const green  = "1;32";
    const cyan  = "1;36";
    const red = "1;31";
    const purple  = "1;35";
    const yellow  = "1;33";
    const white  = "1;37";


    public static function paint($string, $color){

        if($color){
            return "\033[".$color."m ".$string." \033[0m";
        }

       return $string;

   }
} // END class Table extends Helper
