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
        $result = [];
        $ville = $request->input('ville');
        $carburant = $request->input('carburant');
        switch($carburant) {
            case '1':
                $carburant = 'Gazole';
                break;
            case '2':
                $carburant = 'SP95';
                break;
            case '3':
                $carburant = 'E10';
                break;
            case '4':
                $carburant = 'SP98';
                break;
            case '5':
                $carburant = 'GPLc';
                break;
            case '6':
                $carburant = 'E85';
                break;
            default:
                $carburant = 'Gazole';
        }

        // api get request with where ville and carburant
        $response = Http::get('https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records', [
            'where' => "ville=\"$ville\" AND carburants_disponibles=\"$carburant\"",
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // data validation
            if (isset($data['results']) && !empty($data['results'])) {
                $fuelPrices = $data['results'];

                foreach($fuelPrices as $fuelPrice) {
                    if($fuelPrice[strtolower($carburant).'_prix'] != null) {
                        $result[] = $fuelPrice;;
                    }
                }

                return view('welcome', ['fuelPrices' => $result]);
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
