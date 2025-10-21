<?php

namespace App\Helpers;

use GeoIp2\Database\Reader;

class GeoHelper
{
    public static function getAsnInfo($ip)
    {
        try {
            $reader = new Reader(storage_path('app/geoip/GeoLite2-ASN.mmdb'));
            $record = $reader->asn($ip);
            
            return [
                'asn' => 'AS' . $record->autonomousSystemNumber,
                'organization' => $record->autonomousSystemOrganization,
            ];
        } catch (\Exception $e) {
            return [
                'asn' => null,
                'organization' => null,
            ];
        }
    }

    public static function getCountryInfo($ip)
    {
        try {
            $reader = new Reader(storage_path('app/geoip/GeoLite2-Country.mmdb'));
            $record = $reader->country($ip);
            
            return [
                'country_name' => $record->country->name,
                'country_iso' => $record->country->isoCode,
            ];
        } catch (\Exception $e) {
            return [
                'country_name' => null,
                'country_iso' => null,
            ];
        }
    }
}
