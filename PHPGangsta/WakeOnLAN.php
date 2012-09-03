<?php

namespace PHPGangsta;

class WakeOnLAN
{
    public static function wakeUp($macAddressHexadecimal, $broadcastAddress)
    {
        $macAddressHexadecimal = str_replace(':', '', $macAddressHexadecimal);

        // check if $macAddress is a valid mac address
        if (!ctype_xdigit($macAddressHexadecimal)) {
            throw new \Exception('Mac address invalid, only 0-9 and a-f are allowed');
        }

        $macAddressBinary = pack('H12', $macAddressHexadecimal);

        $magicPacket = str_repeat(chr(0xff), 6).str_repeat($macAddressBinary, 16);

        if (!$fp = fsockopen('udp://' . $broadcastAddress, 7, $errno, $errstr, 2)) {
            throw new \Exception("Cannot open UDP socket: {$errstr}", $errno);
        }
        fputs($fp, $magicPacket);
        fclose($fp);
    }
}
