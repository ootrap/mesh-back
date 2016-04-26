<?php 
namespace Star\utils;


/**
 * 随机生产指定位数数字
 */
trait RandomNum
{
    private function randomNum($digits = 4)
    {
        return str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
    }
}
