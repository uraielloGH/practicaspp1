<?php

namespace App\Controller;

use App\Form\RecommendationSurveyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * CU004 – Obtener recomendaciones
 *
 * Controlador que muestra la encuesta de hábitos de desayuno
 * y genera recomendaciones personalizadas según las respuestas.
 */
class RecommendationController extends AbstractController
{
    /**
     * Muestra la encuesta y procesa las respuestas del usuario.
     */
    #[Route('/recomendaciones', name: 'recommendations_survey')]
    public function survey(Request $request): Response
    {
        // El usuario tiene que estar logueado para usar este CU
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Crear y manejar el formulario de encuesta
        $form = $this->createForm(RecommendationSurveyType::class);
        $form->handleRequest($request);

        // Si el usuario envió el formulario y los datos son válidos,
        // se generan las recomendaciones y se muestra la vista de resultado.
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $recommendations = $this->generateRecommendations($data);

            return $this->render('recommendations/result.html.twig', [
                'recommendations' => $recommendations,
                'answers'         => $data,
            ]);
        }

        // Primera carga de la página o formulario con errores:
        // se vuelve a mostrar la encuesta con los mensajes de validación.
        return $this->render('recommendations/survey.html.twig', [
            'surveyForm' => $form->createView(),
        ]);
    }

    /**
     * Lógica de armado de recomendaciones en base a las respuestas de la encuesta.
     *
     * Nota: para el trabajo práctico se resuelve en código;
     * en un escenario real podría consultarse una base de datos o un motor de reglas.
     */
    private function generateRecommendations(array $data): array
    {
        $foods      = [];
        $tips       = [];
        $exercises  = [];

        $actividad  = $data['activityLevel']        ?? null;
        $objetivo   = $data['mainGoal']             ?? null;
        $sabor      = $data['flavorPreference']     ?? null;
        $restric    = $data['restriction']          ?? null;
        $desayuno   = $data['breakfastImportance']  ?? null;

        // Propuestas de alimentos según preferencia de sabor
        if ($sabor === 'dulce') {
            $foods[] = 'Avena con frutas y miel';
            $foods[] = 'Yogur natural con granola y frutos rojos';
        } elseif ($sabor === 'salado') {
            $foods[] = 'Tostadas integrales con palta y huevo';
            $foods[] = 'Omelette de claras con verduras';
        } else {
            $foods[] = 'Tostadas integrales con queso untable y fruta';
            $foods[] = 'Smoothie de frutas con semillas';
        }

        // Consejos generales según objetivo principal
        if ($objetivo === 'energia') {
            $tips[] = 'Incluí una fuente de proteína y carbohidratos complejos en el desayuno.';
        } elseif ($objetivo === 'peso') {
            $tips[] = 'Priorizá alimentos ricos en fibra y evitá bebidas azucaradas.';
        } else {
            $tips[] = 'Aumentá el consumo de frutas, verduras y alimentos integrales.';
        }

        // Ajustes por restricciones alimentarias
        if ($restric === 'vegano') {
            $foods[] = 'Tostadas integrales con hummus y tomate.';
            $tips[]  = 'Incluí proteínas vegetales como legumbres y frutos secos.';
        } elseif ($restric === 'vegetariano') {
            $foods[] = 'Omelette de claras con espinaca y queso.';
        }

        // Recomendaciones de actividad física según nivel declarado
        if ($actividad === 'baja') {
            $exercises[] = 'Caminatas de 20–30 minutos, 3–4 veces por semana.';
        } elseif ($actividad === 'media') {
            $exercises[] = 'Bici o caminatas de 30–40 minutos, 3–5 veces por semana.';
        } else {
            $exercises[] = 'Rutinas combinadas de fuerza y cardio.';
        }

        // Si el usuario le da poca importancia al desayuno, se suma un tip específico
        if ($desayuno === 'baja') {
            $tips[] = 'Intentá incorporar un desayuno ligero para no pasar tantas horas en ayunas.';
        }

        return [
            'foods'     => array_unique($foods),
            'tips'      => array_unique($tips),
            'exercises' => array_unique($exercises),
        ];
    }
}
