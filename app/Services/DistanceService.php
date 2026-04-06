<?php

namespace App\Services;

class DistanceService
{
    /**
     * Toko pusat di Bantul (koordinat default)
     * Sesuaikan dengan lokasi toko Anda di Bantul
     */
    private const STORE_LATITUDE = -7.8847;   // Bantul
    private const STORE_LONGITUDE = 110.2929; // Bantul

    /**
     * Hitung jarak menggunakan Haversine formula (dalam kilometer)
     * Formula ini menghitung jarak great circle distance antara dua titik di bumi
     * 
     * @param float $lat1 Latitude customer
     * @param float $lng1 Longitude customer
     * @param float $lat2 Latitude toko (default)
     * @param float $lng2 Longitude toko (default)
     * @return float Jarak dalam kilometer (dibulatkan)
     */
    public static function calculateDistance(
        float $lat1,
        float $lng1,
        float $lat2 = self::STORE_LATITUDE,
        float $lng2 = self::STORE_LONGITUDE
    ): float {
        $earthRadiusKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadiusKm * $c;

        return round($distance, 2);
    }

    /**
     * Dummy: Dapatkan koordinat dari alamat menggunakan simple geocoding
     * 
     * Dalam implementasi production, gunakan Google Maps API atau Mapbox API
     * Untuk sekarang, kita gunakan hardcoded data atau simple parsing
     * 
     * @param string $address Alamat customer
     * @return array ['latitude' => float, 'longitude' => float] atau null
     */
    public static function getCoordinatesFromAddress(string $address): ?array
    {
        // ===== BANTUL & SEKITARNYA (Area melayani max 10km) =====
        $addressMappings = [
            'bantul' => ['latitude' => -7.8847, 'longitude' => 110.2929],
            'yogyakarta' => ['latitude' => -7.8000, 'longitude' => 110.3700],
            'sleman' => ['latitude' => -7.6500, 'longitude' => 110.4100],
            'depok' => ['latitude' => -7.7972, 'longitude' => 110.4167],
            'mertoyudan' => ['latitude' => -7.8500, 'longitude' => 110.3000],
            'kasihan' => ['latitude' => -7.8600, 'longitude' => 110.3400],
            'piyungan' => ['latitude' => -7.8300, 'longitude' => 110.3800],
            'sewon' => ['latitude' => -7.9000, 'longitude' => 110.3200],
        ];

        $lowerAddress = strtolower($address);
        foreach ($addressMappings as $key => $coords) {
            if (strpos($lowerAddress, $key) !== false) {
                return $coords;
            }
        }

        // Jika tidak ketemu, return null
        return null;
    }

    /**
     * Hitung jarak dari alamat customer
     * 
     * @param string $address Alamat customer
     * @return float|null Jarak dalam km atau null jika tidak bisa di-geocode
     */
    public static function getDistanceFromAddress(string $address): ?float
    {
        $coords = self::getCoordinatesFromAddress($address);

        if (!$coords) {
            return null;
        }

        return self::calculateDistance($coords['latitude'], $coords['longitude']);
    }

    /**
     * Get toko koordinat (untuk reference)
     */
    public static function getStoreCoordinates(): array
    {
        return [
            'latitude' => self::STORE_LATITUDE,
            'longitude' => self::STORE_LONGITUDE,
        ];
    }
}
