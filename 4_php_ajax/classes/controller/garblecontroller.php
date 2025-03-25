<?php

namespace controller;

class garbleController
{
    protected $garbleKey;

    // =============================================================================================
    public function __construct()
    {
        $this->garbleKey = "1AS^&DXko..!";
    }

    // =============================================================================================
    public function garble(string $str) : string
    {
        $ky = str_replace(chr(32), '', $this->garbleKey);
        $kl = strlen($ky) < 32 ? strlen($ky) : 32;
        $k = array();

        for ($i = 0; $i < $kl; $i++)
        {
            $k[$i] = ord($ky[$i]) & 0x1F;
        }
        
        $j = 0;

        for ($i = 0; $i < strlen($str); $i++)
        {
            $e = ord($str[$i]);
            $str[$i] = $e & 0xE0 ? chr($e^$k[$j]) : chr($e);
            $j++;
            $j = $j == $kl ? 0 : $j;
        }

        return $str;
    }

    // =============================================================================================
}