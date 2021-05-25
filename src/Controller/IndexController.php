<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends BaseController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        $text = file_get_contents(dirname(__DIR__, 2) . '/public/rules.md');
        $data = [
                "message" => "This page is a framework test",
                "text" => $text,
        ];

        return $this->render('index.html.twig', $data);
    }

    /**
     * @Route("/test")
     */
    public function test(): Response
    {
        return $this->render(
            'migratedIndex.html.twig',
            [   "header" => "Test Route",
                "message" => "This page is a test",
            ]
        );
    }
}
