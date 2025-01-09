<x-app-layout>
<main class="mt-6">
                        <!-- Hero -->
                        <div class="relative overflow-hidden">
                            <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-24">
                                <div class="text-center">
                                    <h1 class="text-4xl sm:text-6xl font-bold text-gray-800 dark:text-neutral-200">
                                        Comparez les prix des carburants
                                    </h1>

                                    <p class="mt-3 text-gray-600 dark:text-neutral-400">
                                        Sélectionnez la ville de votre choix et découvrez la station essence la moins chère.
                                    </p>

                                    <div class="relative z-10 p-5 mt-5 bg-white border rounded-lg shadow-lg shadow-gray-100 dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-gray-900/20">
                                        <form action="/recherche" method="post" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label for="ville" class="block text-sm font-medium text-gray-700 dark:text-white">
                                                    Ville
                                                </label>
                                                <div class="relative mt-2">
                                                    <input type="text" name="ville" id="ville"  value="{{$user->ville}}" autocomplete="ville" class="py-3 px-4 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Entrez une ville"/>
                                                    <span class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <fieldset class="space-y-2">
                                                <legend class="text-sm font-medium text-gray-700 dark:text-white">
                                                    Type de Carburant
                                                </legend>
                                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" name="carburant" value="1" {{$user->carburant_pref == 1 ? 'checked' : ''}} class="focus:ring-blue-500 text-blue-600 dark:bg-neutral-800 dark:border-neutral-700" />
                                                        <span class="text-sm text-gray-500 dark:text-neutral-400">Gazole</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" name="carburant" value="2" {{$user->carburant_pref == 2 ? 'checked' : ''}} class="focus:ring-blue-500 text-blue-600 dark:bg-neutral-800 dark:border-neutral-700" />
                                                        <span class="text-sm text-gray-500 dark:text-neutral-400">SP95</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" name="carburant" value="3" {{$user->carburant_pref == 3 ? 'checked' : ''}} class="focus:ring-blue-500 text-blue-600 dark:bg-neutral-800 dark:border-neutral-700" />
                                                        <span class="text-sm text-gray-500 dark:text-neutral-400">E10</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" name="carburant" value="4" {{$user->carburant_pref == 4 ? 'checked' : ''}} class="focus:ring-blue-500 text-blue-600 dark:bg-neutral-800 dark:border-neutral-700" />
                                                        <span class="text-sm text-gray-500 dark:text-neutral-400">SP98</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" name="carburant" value="5" {{$user->carburant_pref == 5 ? 'checked' : ''}} class="focus:ring-blue-500 text-blue-600 dark:bg-neutral-800 dark:border-neutral-700" />
                                                        <span class="text-sm text-gray-500 dark:text-neutral-400">GPLc</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" name="carburant" value="6" {{$user->carburant_pref == 6 ? 'checked' : ''}} class="focus:ring-blue-500 text-blue-600 dark:bg-neutral-800 dark:border-neutral-700" />
                                                        <span class="text-sm text-gray-500 dark:text-neutral-400">E85</span>
                                                    </label>
                                                </div>
                                            </fieldset>

                                            <!-- Submit Button -->
                                            <div class="pt-4">
                                                <button
                                                    type="submit"
                                                    class="w-full py-3 px-6 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-900"
                                                >
                                                    <i class="fas fa-search mr-2"></i> Rechercher
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Hero -->
                        <div>
                                <div id="map" style="height: 400px; width: 100%; margin-top:24px">
                                </div>

                                <script>
                                    let map;
                                    document.addEventListener('DOMContentLoaded', function () {
                                        
                                        map = L.map('map').setView([{{$fuelPrices[0]['geom']['lat']}}, {{$fuelPrices[0]['geom']['lon']}}], 13);

                                        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            maxZoom: 19,
                                            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                                        }).addTo(map);

                                        @foreach($fuelPrices as $station)
                                            var marker = L.marker(["{{$station['geom']['lat']}}", "{{$station['geom']['lon']}}"]).addTo(map);
                                        @endforeach
                                    });

                                    document.addEventListener('click', function () {
                                        
                                        map = L.map('map').setView([{{$fuelPrices[0]['geom']['lat']}}, {{$fuelPrices[0]['geom']['lon']}}], 13);
                                        
                                        @foreach($fuelPrices as $station)
                                            var marker = L.marker(["{{$station['geom']['lat']}}", "{{$station['geom']['lon']}}"]).addTo(map);
                                        @endforeach
                                    });


                                </script>




                                <div class="flex flex-col">
                                    <div class="-m-1.5 overflow-x-auto">
                                        <div class="p-1.5 min-w-full inline-block align-middle">
                                            <div class="overflow-hidden">
                                                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Station</th>
                                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Carburant et Prix</th>
                                                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Service</th>
                                                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Horaires</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                                        @if(isset($fuelPrices) && !empty($fuelPrices))
                                                            @foreach ($fuelPrices as $station)
                                                                <tr class="hover:bg-gray-100 dark:hover:bg-neutral-700">
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $station['adresse'] }}, {{ $station['ville'] }}</td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                                @if(isset($station['prix']))
                                                                    <ul>
                                                                        @foreach (json_decode($station['prix']) as $prix)
                                                                            <li>{{ $prix->{'@nom'} }}: {{ $prix->{'@valeur'} }} EUR</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    N/A
                                                                @endif
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                                @if(isset($station['services_service']))
                                                                    <ul>
                                                                        @foreach ($station['services_service'] as $service)
                                                                            <li>{{ $service }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    N/A
                                                                @endif
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                                @if(isset($station['horaires']))
                                                                    @php
                                                                        $horaires = json_decode($station['horaires'], true);
                                                                    @endphp
                                                                    
                                                                    @if(isset($horaires['jour']))
                                                                        <ul>
                                                                            @foreach ($horaires['jour'] as $jour)
                                                                                <li>
                                                                                    {{ $jour['@nom'] }} : 
                                                                                    @if(isset($jour['horaire']['@ouverture']) && isset($jour['horaire']['@fermeture']))
                                                                                        {{ $jour['horaire']['@ouverture'] }} - {{ $jour['horaire']['@fermeture'] }}
                                                                                    @else
                                                                                        Fermé
                                                                                    @endif
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @else
                                                                        N/A
                                                                    @endif
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>

                                                                </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <p>Prix des carburants non disponible.</p>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </main>
</x-app-layout>
