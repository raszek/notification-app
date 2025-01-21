<?php

namespace App\Controller;

use App\Form\NotificationForm;
use App\Form\NotificationType;
use App\Service\Notification\NotificationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends Controller
{

    public function __construct(
        private readonly NotificationService $notificationService
    ) {
    }

    #[Route('/notifications/send', 'app_notification_send')]
    public function sendNotifications(Request $request): Response
    {
        $form = $this->createForm(NotificationType::class, new NotificationForm());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->notificationService->sendNotifications($form->getData()->content);

            $this->addFlash('success', 'Successfully sent messages');

            return $this->redirectToRoute('app_notification_send');
        }

        return $this->render('notification/send.html.twig', [
            'form' => $form
        ]);
    }

}
