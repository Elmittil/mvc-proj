<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\BankBusinessLogic\BankBusinessLogic;

class BaseController extends AbstractController
{

    public $session;
    public $request;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->request = Request::createFromGlobals();
    }

    public function isLoggedIn(): bool
    {
        $user = $this->getUser();
        if ($user) {
            return true;
        }
        return false;
    }

    public function extractUserData(): array
    {
        $user = $this->getUser();
        if ($user) {
            $playerName = $user->getUsername();
            $playerCredit = $this->getUserCredit($playerName);
            if ($playerCredit === null) {
                $playerCredit = "This player has no credit";
            }
        }

        if (!$user) {
            $playerName = "anonymous";
            $playerCredit = "no credit. Please register to bet";
        }

        $userData = [$playerName, $playerCredit];
        return $userData;
    }

    public function getUserCredit(string $playerName): ?int
    {
        $logic = new BankBusinessLogic($this->getDoctrine());
        $credit = $logic->getCreditTotal($playerName);
        return $credit;
    }
}
