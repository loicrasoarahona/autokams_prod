<?php

namespace App\Controller;

use App\Service\ApprovisionnementDetailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApprovisionnementDetailController extends AbstractController
{
    public function __construct(private ApprovisionnementDetailService $approvisionnementDetailService)
    {
    }

    #[Route('/approvisionnement_details/quantiteRestante/{id}', methods: ['GET'])]
    public function quantiteRestante($id)
    {
        try {
            $retour = $this->approvisionnementDetailService->quantiteRestante($id);
            return new JsonResponse($retour);
        } catch (\Throwable $th) {
            return new JsonResponse($th->getMessage(), 500);
        }
    }
}
