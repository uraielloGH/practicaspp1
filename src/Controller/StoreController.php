<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    #[Route('/tiendas', name: 'store_index')]
    public function index(Request $request): Response
    {
        // Cafeterías reales de Santa Fe Capital
        $stores = [
            [
                'name'    => 'Ludwing Cafecito',
                'city'    => 'Santa Fe Capital',
                'zone'    => 'Zona Centro',
                'address' => 'Cortada Falucho 2420',
                'hours'   => '8:00 a 20:30',
            ],
            [
                'name'    => 'Greña Masa Madre',
                'city'    => 'Santa Fe Capital',
                'zone'    => 'Zona Boulevard',
                'address' => 'Boulevard Gálvez 1488',
                'hours'   => '7:00 a 21:00',
            ],
            [
                'name'    => 'Lisandro Café',
                'city'    => 'Santa Fe Capital',
                'zone'    => 'Centro',
                'address' => 'San Jerónimo 2487',
                'hours'   => '7:00 a 20:30',
            ],
            [
                'name'    => 'Zulma Ale Café',
                'city'    => 'Santa Fe Capital',
                'zone'    => 'Barrio Sargento Cabral',
                'address' => 'Av. Gral. Paz 5201',
                'hours'   => '8:00 a 23:00',
            ],
        ];

        // Saber si el usuario hizo "submit" (hay parámetro q en la URL)
        $searchParamExists = $request->query->has('q');
        $search = trim((string) $request->query->get('q', ''));

        $filteredStores = [];
        $searchError = null;

        if ($searchParamExists && $search === '') {
            // E1 – el usuario apretó Buscar sin escribir nada
            $searchError = 'Por favor ingresá una ciudad, barrio o zona válida.';
            $filteredStores = $stores; // o [] si preferís no mostrar nada
        } elseif ($search === '') {
            // carga inicial sin búsqueda → mostrar todas
            $filteredStores = $stores;
        } else {
            // hay texto de búsqueda → filtramos
            $searchLower = mb_strtolower($search);

            foreach ($stores as $store) {
                $hayCoincidencia =
                    str_contains(mb_strtolower($store['city']), $searchLower) ||
                    str_contains(mb_strtolower($store['zone']), $searchLower) ||
                    str_contains(mb_strtolower($store['name']), $searchLower);

                if ($hayCoincidencia) {
                    $filteredStores[] = $store;
                }
            }
        }

        return $this->render('store/index.html.twig', [
            'stores'      => $filteredStores,
            'search'      => $search,
            'noResults'   => $search !== '' && count($filteredStores) === 0,
            'searchError' => $searchError,
        ]);
    }
}
