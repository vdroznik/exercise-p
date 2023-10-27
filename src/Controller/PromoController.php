<?php

declare(strict_types=1);

namespace ExercisePromo\Controller;

use ExercisePromo\Auth\Auth;
use ExercisePromo\Repository\IpsRepository;
use ExercisePromo\Service\PromoService;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class PromoController
{
    private const MAX_PROMOS_PER_IP = 1000;

    public function __construct(
        private Auth $auth,
        private PromoService $promoService,
        private IpsRepository $ipsRepo,
        private SessionInterface $session,
    ) {}

    public function getPromo(Request $request, Response $response): Response
    {
        $ip = $request->getServerParams()['REMOTE_ADDR'];
        $ipPacked = ip2long($ip);

        $cnt = $this->ipsRepo->getCountByIp($ipPacked);
        if ($cnt >= self::MAX_PROMOS_PER_IP) {
            $flash = $this->session->getFlash();
            $flash->add('error', 'The number of promo codes issued for this ip is too high.');

            return $response
                ->withHeader('Location', '/profile')
                ->withStatus(302);
        }

        $promo = $this->promoService->findOrCreatePromoForUser(
            $this->auth->getUser(),
            $ipPacked
        );

        return $response
            ->withHeader('Location', 'https://www.google.com/?query=' . $promo->code)
            ->withStatus(302);
    }
}
