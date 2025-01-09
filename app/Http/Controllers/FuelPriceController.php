<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FuelPriceService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FuelPriceController extends Controller
{
    private $limit = 5;

    public function index()
    {
        return $this->searchFuelPrices(request(), 1);
    }

    public function searchFuelPrices(Request $request, $page = 1)
    {
        $user = Auth::user();
        $result = [];
        $ville = $request->input('ville', $user->ville);
        $carburant = $request->input('carburant', $user->carburant);
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
        $carb_requete = strtolower($carburant);
        $offset = ($page - 1) * $this->limit;

        // Get total count first
        $countResponse = Http::get('https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records', [
            'where' => "ville=\"$ville\" AND carburants_disponibles=\"$carburant\"",
            'limit' => 1,
        ]);

        $totalCount = 0;
        if ($countResponse->successful()) {
            $countData = $countResponse->json();
            $totalCount = $countData['total_count'];
        }

        // Main data request
        $response = Http::get('https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records', [
            'where' => "ville=\"$ville\" AND carburants_disponibles=\"$carburant\"",
            'order_by' => "{$carb_requete}_prix ASC",
            'limit' => $this->limit,
            'offset' => $offset,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['results']) && !empty($data['results'])) {
                $fuelPrices = $data['results'];

                foreach($fuelPrices as $fuelPrice) {
                    if($fuelPrice[strtolower($carburant).'_prix'] != null) {
                        $result[] = $fuelPrice;
                    }
                }

                return view('dashboard', [
                    'fuelPrices' => $result,
                    'user' => $user,
                    'currentPage' => $page,
                    'totalPages' => ceil($totalCount / $this->limit),
                    'ville' => $ville,
                    'carburant' => $request->input('carburant'),
                ]);
            }
        }

        return view('dashboard', [
            'error' => 'Prix des carburants non disponible.',
            'user' => $user,
            'currentPage' => 1,
            'totalPages' => 0
        ]);
    }
}
