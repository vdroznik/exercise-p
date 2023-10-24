<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use ExercisePromo\Service\PromoService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProfileController extends Controller
{
    public function index(Request $request, Response $response, $args): Response
    {
        return $this->view->render(
            $response,
            "profile/index.html.php",
            [
                'username' => $this->session->get('userId'),
            ]
        );
    }

    public function getPromo(Request $request, Response $response): Response
    {
        /** @var PromoService $promoService */
        $promoService = $this->container->get(PromoService::class);

        $promo = $promoService->generatePromoForUser($this->session->get('userId'));

        return $response
            ->withHeader('Location', 'https://www.google.com/?query=' . $promo)
            ->withStatus(302);
    }
}
