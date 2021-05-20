<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Bank;
use App\BusinessLogic\BankBusinessLogic;

class BankController extends AbstractController
{
    private $session;
    private $request;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->request = Request::createFromGlobals();
    }

    /**
     * @Route("/bank", name="bank")
     */
    public function index(): Response
    {

        return $this->render('buy-credit.html.twig', [
        ]);
    }

    /**
     * @Route("/buy-credit", name="buy-credit")
     */
    public function buyCredit(): Response
    {
        $user = $this->getUser();
        $playerName = $user->getUsername();

        $repository = $this->getDoctrine()->getRepository(Bank::class);
        $account = $repository->findOneBy(['player_name' => $playerName]);

        if (!$account) {
            $this->addAccount($playerName);
        }

        $creditToAdd = $this->request->request->get('credit');

        $logic = new BankBusinessLogic($this->getDoctrine());
        $logic->updateDbWithNewCredit($creditToAdd, $playerName);

        return $this->redirectToRoute('app_game21_game21play');
    }

    /**
     * @Route("/open-account", name="open-account")
    */
    public function addAccount(string $playerName)
    {
        //BankBusinessLogic method to add credit to db
        $logic = new BankBusinessLogic($this->getDoctrine());
        $logic->addAccount($playerName);
    }
}
