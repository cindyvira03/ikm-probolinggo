<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class RajaOngkirService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.rajaongkir.url');
        $this->apiKey = config('services.rajaongkir.key');
    }

    private function request()
    {
        return Http::withHeaders([
            'key' => $this->apiKey,
            'Accept' => 'application/json'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET DATA WILAYAH
    |--------------------------------------------------------------------------
    */

    public function getProvinces(): array
    {
        $response = $this->request()
            ->get($this->baseUrl . '/destination/province');

        return $response->successful()
            ? $response->json()['data'] ?? []
            : [];
    }

    public function getCities(int $provinceId): array
    {
        $response = $this->request()
            ->get($this->baseUrl . "/destination/city/{$provinceId}");

        return $response->successful()
            ? $response->json()['data'] ?? []
            : [];
    }

    public function getDistricts(int $cityId): array
    {
        $response = $this->request()
            ->get($this->baseUrl . "/destination/district/{$cityId}");

        return $response->successful()
            ? $response->json()['data'] ?? []
            : [];
    }

    /*
    |--------------------------------------------------------------------------
    | FIND ID BERDASARKAN NAMA
    |--------------------------------------------------------------------------
    */

    public function getProvinceId(string $provinceName): ?int
    {
        $province = collect($this->getProvinces())
            ->first(
                fn($item) =>
                str_contains(
                    strtolower($item['name']),
                    strtolower(trim($provinceName))
                )
            );

        return $province['id'] ?? null;
    }

    public function getCityId(string $cityName, int $provinceId): ?int
    {
        $city = collect($this->getCities($provinceId))
            ->first(
                fn($item) =>
                str_contains(
                    strtolower($item['name']),
                    strtolower(trim($cityName))
                )
            );

        return $city['id'] ?? null;
    }

    public function getDistrictId(string $districtName, int $cityId): ?int
    {
        $district = collect($this->getDistricts($cityId))
            ->first(
                fn($item) =>
                str_contains(
                    strtolower($item['name']),
                    strtolower(trim($districtName))
                )
            );

        return $district['id'] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG ONGKIR (DISTRICT)
    |--------------------------------------------------------------------------
    */

    public function calculateDistrictCost(
        int $originDistrictId,
        int $destinationDistrictId,
        int $weight,
        string $courier
    ): ?int {

        $response = Http::asForm()
            ->withHeaders([
                'key' => $this->apiKey
            ])
            ->post(
                $this->baseUrl . '/calculate/district/domestic-cost',
                [
                    'origin' => $originDistrictId,
                    'destination' => $destinationDistrictId,
                    'weight' => $weight,
                    'courier' => $courier,
                    'price' => 'lowest'
                ]
            );

        if (!$response->successful()) {
            logger('RAJAONGKIR ERROR: ' . $response->body());
            return null;
        }

        $data = $response->json()['data'] ?? null;

        return $data[0]['cost'] ?? null;
    }
}
