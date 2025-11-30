<?php

namespace App\Controller;

use App\Form\RecommendationSurveyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecommendationController extends AbstractController
{
    #[Route('/recomendaciones', name: 'recommendations_survey')]
    public function survey(Request $request): Response
    {
        // Aseguramos que el usuario esté logueado
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(RecommendationSurveyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Generar recomendaciones basadas en las respuestas
            $recommendations = $this->generateRecommendations($data);

            return $this->render('recommendations/result.html.twig', [
                'recommendations' => $recommendations,
                'answers' => $data,
            ]);
        }

        return $this->render('recommendations/survey.html.twig', [
            'surveyForm' => $form->createView(),
        ]);
    }

    private function generateRecommendations(array $data): array
    {
        $foods = [];
        $tips = [];
        $exercises = [];

        $actividad = $data['activityLevel'] ?? null;
        $objetivo  = $data['mainGoal'] ?? null;
        $sabor     = $data['flavorPreference'] ?? null;
        $restric   = $data['restriction'] ?? null;
        $desayuno  = $data['breakfastImportance'] ?? null;

        // Alimentos según preferencia
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

        // Consejos según objetivo
        if ($objetivo === 'energia') {
            $tips[] = 'Incluí una fuente de proteína y carbohidratos complejos en el desayuno.';
        } elseif ($objetivo === 'peso') {
            $tips[] = 'Priorizá alimentos ricos en fibra y evitá bebidas azucaradas.';
        } else {
            $tips[] = 'Aumentá el consumo de frutas, verduras y alimentos integrales.';
        }

        // Restricciones
        if ($restric === 'vegano') {
            $foods[] = 'Tostadas integrales con hummus y tomate.';
            $tips[]  = 'Incluí proteínas vegetales como legumbres y frutos secos.';
        } elseif ($restric === 'vegetariano') {
            $foods[] = 'Omelette de claras con espinaca y queso.';
        }

        // Actividad física
        if ($actividad === 'baja') {
            $exercises[] = 'Caminatas de 20–30 minutos, 3–4 veces por semana.';
        } elseif ($actividad === 'media') {
            $exercises[] = 'Bici o caminatas de 30–40 minutos, 3–5 veces por semana.';
        } else {
            $exercises[] = 'Rutinas combinadas de fuerza y cardio.';
        }

        // Importancia del desayuno
        if ($desayuno === 'baja') {
            $tips[] = 'Intentá incorporar un desayuno ligero para no pasar tantas horas en ayunas.';
        }

        return [
            'foods' => array_unique($foods),
            'tips' => array_unique($tips),
            'exercises' => array_unique($exercises),
        ];
    }
}
