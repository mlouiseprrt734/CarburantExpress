<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FuelPriceService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FuelPriceController extends Controller
{

    public function index(){
        $user = Auth::user();
        $result = [];
        $ville = $user->ville;
        $carburant = $user->carburant;
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

                return view('dashboard', ['fuelPrices' => $result, 'user' => $user]);
            } else {
                // if no data
                return view('dashboard', ['error' => 'Prix des carburants non disponible.', 'user' => $user]);
            }
        } else {
            //if request fails
            return view('dashboard', ['error' => 'Impossible d\'accéder aux données.', 'user' => $user]);
        }
    }


    public function getFuelPrices()
    {
        $user = Auth::user();

        // api get request
        $response = Http::get('https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records?limit=20');

        if ($response->successful()) {
            $data = $response->json();

            // data validation
            if (isset($data['results']) && !empty($data['results'])) {
                $fuelPrices = $data['results'];
                return view('dashboard', ['fuelPrices' => $fuelPrices, 'user' => $user]);
            } else {
                // if no data
                return view('dashboard', ['error' => 'Prix des carburants non disponible.', 'user' => $user]);
            }
        } else {
            //if request fails
            return view('dashboard', ['error' => 'Impossible d\'accéder aux données.', 'user' => $user]);
        }
    }

    public function searchFuelPrices(Request $request)
    {
        
        $user = Auth::user();
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

                return view('dashboard', ['fuelPrices' => $result, 'user' => $user]);
            } else {
                // if no data
                return view('dashboard', ['error' => 'Prix des carburants non disponible.', 'user' => $user]);
            }
        } else {
            //if request fails
            return view('dashboard', ['error' => 'Impossible d\'accéder aux données.', 'user' => $user]);
        }
    }

}
