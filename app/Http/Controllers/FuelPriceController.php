<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Services\FuelPriceService;
use Illuminate\Routing\Controller;

class FuelPriceController extends Controller
{
    public function getFuelPrices()
    {
        // api get request
        $response = Http::get('https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records?limit=20');

        if ($response->successful()) {
            $data = $response->json();

            // data validation
            if (isset($data['results']) && !empty($data['results'])) {
                $fuelPrices = $data['results'];
                return view('welcome', ['fuelPrices' => $fuelPrices]);
            } else {
                // if no data
                return view('welcome', ['error' => 'Prix des carburants non disponible.']);
            }
        } else {
            //if request fails
            return view('welcome', ['error' => 'Impossible d\'accéder aux données.']);
        }
    }

    public function searchFuelPrices(Request $request)
    {
        $ville = $request->input('ville');

        // api get request with where ville
        $response = Http::get('https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records', [
            'where' => "ville='$ville'"
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // data validation
            if (isset($data['results']) && !empty($data['results'])) {
                $fuelPrices = $data['results'];
                return view('welcome', ['fuelPrices' => $fuelPrices]);
            } else {
                // if no data
                return view('welcome', ['error' => 'Prix des carburants non disponible.']);
            }
        } else {
            //if request fails
            return view('welcome', ['error' => 'Impossible d\'accéder aux données.']);
        }
    }

}
