<?php

namespace App\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminSecurityController extends AbstractController
{
    #[Route('/admin/security', name: 'app_admin_security')]
    public function index(): Response
    {
        return $this->render('admin_security/index.html.twig', [
            'controller_name' => 'AdminSecurityController',
        ]);
    }
}
