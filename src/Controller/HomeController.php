<?php

namespace App\Controller;

use App\Entity\LeaveRequest;
use App\Form\LeaveRequestType;
use App\Message\LeaverequestMessage;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LeaveRequestRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        LeaveRequestRepository $leaveRequestRepository,
     
        MessageBusInterface $messageBus
    ): Response {
        $leaveRequest = new LeaveRequest();

        $form = $this->createForm(LeaveRequestType::class, $leaveRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $leaveRequest->setUser($this->getUser());
            $entityManager->persist($leaveRequest);
            $entityManager->flush();
            $messageBus->dispatch(new LeaverequestMessage($leaveRequest->getId()));
            return $this->redirectToRoute('homepage');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form,
            'leaveRequests' => $leaveRequestRepository->findBy(['user' => $this->getUser()]),
        ]);
    }
}
