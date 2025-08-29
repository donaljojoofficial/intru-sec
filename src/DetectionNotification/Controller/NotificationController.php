<?php
// src/DetectionNotification/Controller/NotificationController.php
namespace App\DetectionNotification\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification/test', name: 'notification_test')]
    public function test(): Response
    {
        return new Response('DetectionNotification module works');
    }
}
