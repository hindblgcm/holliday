<?php

namespace App\Controller\Admin;

use App\Entity\LeaveRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ReviewLeaverequestController extends AbstractController
{
    #[Route('/admin/review/leaverequest/{id}', name: 'review_leaverequest')]
    public function __invoke(
    Request $request, LeaveRequest 
    $leaveRequest, WorkflowInterface $leaveRequestStateMachine, 
    EntityManagerInterface $entityManager): Response
    {
        $accepted = !$request->query->getBoolean('reject');
        if ($leaveRequestStateMachine->can($leaveRequest, 'accept')) {
            $transition = $accepted ? 'accept' : 'reject';
        } else {
            return new Response('Leave request already reviewed or not in the right state.');
        }
        $leaveRequestStateMachine->apply($leaveRequest, $transition);
        $entityManager->flush();
        
        return $this->render('admin/review_leaverequest.html.twig', [
            'transition' => $transition,
            'leaveRequest' => $leaveRequest,
        ]); 
    }
}
