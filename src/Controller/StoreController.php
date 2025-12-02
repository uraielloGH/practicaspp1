<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    /**
     * Página principal de tiendas.
     *
     * El usuario puede:
     *  - Ver todas las cafeterías disponibles (vista inicial)
     *  - Usar el buscador para filtrar por ciudad, zona o nombre
     *  - Recibir mensajes de error si busca sin escribir nada
     *  - Ver mensaje “sin resultados” cuando no coinciden tiendas
     *
     * Este método implementa todo el CU005.
     */
    #[Route('/tiendas', name: 'store_index')]
    public function index(Request $request): Response
    {
        /**
         * Lista fija de cafeterías (versión académica sin BD).
         * Cada tienda tiene datos básicos para mostrar en la grilla.
         */
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

        /**
         * Buscar parámetro "q" en la URL
         * - Si existe pero está vacío → error
         * - Si no existe → carga inicial (mostrar todas)
         * - Si tiene texto → se filtra
         */
        $searchParamExists = $request->query->has('q');
        $search = trim((string) $request->query->get('q', ''));

        $filteredStores = [];
        $searchError = null;

        if ($searchParamExists && $search === '') {
            // Usuario hizo clic en “Buscar” sin escribir nada → error FA01
            $searchError = 'Por favor ingresá una ciudad, barrio o zona válida.';
            $filteredStores = $stores; // Podría usarse [] según preferencia
        } 
        elseif ($search === '') {
            // Vista inicial del CU: mostrar todas las tiendas
            $filteredStores = $stores;
        } 
        else {
            /**
             * Filtrado real: comparamos el texto de búsqueda
             * con city / zone / name de cada tienda.
             */
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

        /**
         * Render final:
         * - stores: lista filtrada o completa
         * - search: texto ingresado
         * - noResults: true si hubo búsqueda pero sin coincidencias
         * - searchError: mensaje para FA01 (criterio vacío)
         */
        return $this->render('store/index.html.twig', [
            'stores'      => $filteredStores,
            'search'      => $search,
            'noResults'   => $search !== '' && count($filteredStores) === 0,
            'searchError' => $searchError,
        ]);
    }
}
