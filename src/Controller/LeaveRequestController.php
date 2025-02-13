<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LeaveRequestController extends AbstractController
{
    #[Route('/leaverequest', name: 'leaverequest')]  
    public function index(): Response
    {
        return $this->render('leave_request/index.html.twig', [
            'controller_name' => 'LeaveRequestController',
        ]);
    }
}
