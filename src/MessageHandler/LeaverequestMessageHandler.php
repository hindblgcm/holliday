<?php

namespace App\MessageHandler;

use App\Entity\Enum\StatusEnum;
use App\Message\LeaverequestMessage;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LeaveRequestRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
#[AsMessageHandler]
final class LeaverequestMessageHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private WorkflowInterface $leaveRequestStateMachine,
        private MailerInterface $mailer,
     #[Autowire('%admin_email%')] private string $adminEmail,
        private LeaveRequestRepository $leaveRequestRepository,
    ) {
    }
    public function __invoke(LeaverequestMessage $message): void
    {
        $leaveRequest = $this->leaveRequestRepository->find($message->id);
        if (null === $leaveRequest) {
            return;
        }
        if($this->leaveRequestStateMachine->can($leaveRequest, 'submit')){
            $this->mailer->send((new NotificationEmail())
            ->subject('New leave request submitted')
            ->htmlTemplate('emails/leaverequest_notification.html.twig')
            ->from($this->adminEmail)
            ->to($this->adminEmail)
            ->context(['leaverequest' => $leaveRequest])
   );
            $this->leaveRequestStateMachine->apply($leaveRequest, 'submit');
        }
        else{
            throw new \Exception('Leave request cannot be submitted');
        }

      
        $this->entityManager->flush();
    }
}
