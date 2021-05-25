<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Dice\DiceHand;
use App\Entity\Score;
use App\Entity\User;
use App\Entity\Bank;
use App\BusinessLogic\BankBusinessLogic;

require_once __DIR__ . "/../../bin/bootstrap.php";

class Game21Controller extends BaseController
{
    private $session;
    private $request;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->request = Request::createFromGlobals();
    }

    /**
     * @Route("/start")
     */
    public function game21start(): Response
    {
        $userData = $this->extractUserData();
        $data = [
            "header" => "Game21",
            'player' => $userData[0],
        ];

        $this->session->set('rollPlayer', 0);
        $this->session->set('rollComputer', 0);
        $this->session->set('score', array());
        $this->session->set('totalPlayer', 0);
        $this->session->set('totalComputer', 0);
        $this->session->set('message', "");

        return $this->render('game21.html.twig', $data);
    }

    /**
     * @Route("/play", methods={"GET", "POST"})
     */
    public function game21play(): Response
    {
        $userData = $this->extractUserData();
        $loggedIn = $this->isLoggedIn();

        $diceQty =  $this->session->get('diceQty');

        $bet = $this->request->request->get('bet-ammount');
        if (array_key_exists('bet-button', $_POST)) {
            $this->session->set('bet', $bet);
        }
        $bet = $this->session->get('bet');

        if (array_key_exists('button1', $_POST)) {
            $this->buttonRoll((int)$diceQty, $userData[0]);
        } else if (array_key_exists('button2', $_POST)) {
            $this->buttonPass((int)$diceQty, $userData[0]);
        }
        $userData = $this->extractUserData();
        $data = [
            "header" => "GAME 21",
            'player' => $userData[0],
            'credit' => $userData[1],
            'loggedIn' => $loggedIn,
            'bet' => $bet,
        ];
        return $this->render('play.html.twig', $data);
    }

    /**
     * @Route("/set-hand",  methods={"POST"})
     */
    public function game21setHand(): Response
    {
        if (null == $this->request->request->get('diceQty')) {
            $diceQty = 1;
            $this->get('session')->set('diceQty', $diceQty);

            return $this->redirectToRoute('app_game21_game21play');
        }

        $diceQty = $this->request->request->get('diceQty');
        $this->get('session')->set('diceQty', $diceQty);

        return $this->redirectToRoute('app_game21_game21play');
    }

    /**
     * @Route("/reset",  methods={"GET"})
     */
    public function game21reset(): Response
    {
        $this->resetGame();
        return $this->redirectToRoute('app_game21_game21play');
    }

    /**
     * @Route("/save-score",  methods={"GET"})
     */
    public function game21SaveScore(): Response
    {
        $user = $this->getUser();
        if (!$user){
            $this->resetGame();
            return $this->redirectToRoute('app_game21_game21start');
        }
        $playerName = $user->getUsername();

        $totalScores = $this->session->get('score');
        $playerScore = $this->countScores($totalScores);

        $score = new Score();
        if ($playerName) {
            $score->setPlayerName($playerName);
        }
        if (!$playerName) {
            $score->setPlayerName("anonymous");
        }
        $score->setScore($playerScore);
        $score->setGame("game21");

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($score);
        $entityManager->flush();

        $this->resetGame();
        return $this->redirectToRoute('app_game21_game21start');
    }

    private function buttonRoll(int $diceQty, string $playerName): void
    {
        $oldTotalPlayer = $this->session->get('totalPlayer');
        $hand = $this->setupAndRoll($diceQty);

        $newTotalPLayer = $oldTotalPlayer + $hand;

        $this->session->set('rollPlayer', $hand);
        $this->session->set('totalPlayer', $newTotalPLayer);

        if ($this->checkIfOver21("COMPUTER", $newTotalPLayer, $playerName)) {
            return;
        }

        $oldTotalComputer = $this->session->get('totalComputer');
        $newTotalComputer = $oldTotalComputer;

        if ($this->shouldComputerRoll($newTotalPLayer, $oldTotalComputer)) {
            $hand = $this->setupAndRoll($diceQty);
            $this->session->set('rollComputer', $hand);
            $newTotalComputer = $oldTotalComputer + $hand;
            $this->session->set('totalComputer', $newTotalComputer);
        }

        if ($this->checkIfOver21("YOU", $newTotalComputer, $playerName)) {
            return;
        }

        if ($this->checkIf21($newTotalComputer, $playerName)) {
            return;
        }
    }

    private function buttonPass(int $diceQty, string $playerName): void
    {
        $totalPlayer = $this->session->get('totalPlayer');
        $totalComputer = $this->session->get('totalComputer');

        $computerHands = array();

        while ($totalComputer <= $totalPlayer) {
            $hand = $this->setupAndRoll($diceQty);
            $totalComputer = $totalComputer + $hand;
            array_push($computerHands, $hand);
        }
        $this->session->set('rollComputer', implode('+', $computerHands));
        $this->session->set('totalComputer', $totalComputer);

        if ($totalComputer <= 21) {
            $this->computerWins($playerName);
            return;
        }

        $this->playerWins($playerName);
        return;
    }

    private function resetGame()
    {
        $this->session->set('rollPlayer', 0);
        $this->session->set('rollComputer', 0);
        $this->session->set('totalPlayer', 0);
        $this->session->set('totalComputer', 0);
        $this->session->set('message', "");
        $this->session->remove('bet');
    }

    private function setupAndRoll(int $diceQty): int
    {
        $hand = new DiceHand($diceQty, "regular");
        $hand->roll($diceQty);
        $rolled =  $hand->getRollSum();
        $handRolledDice = $hand->getLastHandRollArray($diceQty);
        $this->saveRolledDiceToDB($handRolledDice);
        return $rolled;
    }

    private function shouldComputerRoll(int $playerScore, int $computerScore): bool
    {
        if ($computerScore < 21 && $computerScore < $playerScore) {
            return true;
        }
        return false;
    }
    private function computerWins(string $playerName)
    {
        //set winner message
        $message = "COMPUTER WON!!!";
        $this->session->set('message', $message);

        //adjust score chart array in session
        $newScore = $this->session->get('score');
        array_push($newScore, ["", "x"]);
        $this->session->set('score', $newScore);

        //remove the amount bet from the user pot
        $bet = $this->session->get('bet');
        $this->payBet(-$bet, $playerName);
    }

    private function playerWins(string $playerName)
    {
        //set winner message
        $message = "YOU WON!!!";
        $this->session->set('message', $message);

        //adjust score chart array in session
        $newScore = $this->session->get('score');
        array_push($newScore, ["x", ""]);
        $this->session->set('score', $newScore);

        //add the amount bet to user pot
        $bet = $this->session->get('bet');
        $this->payBet($bet, $playerName);
        $this->recordWinningBet($playerName, $bet);
    }

    private function payBet(?int $bet, string $playerName)
    {
        if ($playerName === "anonymous") {
            return;
        }
        $logic = new BankBusinessLogic($this->getDoctrine());
        $logic->updateDbWithNewCredit($bet, $playerName);
        $this->session->remove('bet');
    }

    private function recordWinningBet(string $playerName, int $bet)
    {
        $logic = new BankBusinessLogic($this->getDoctrine());
        $logic->addWinRecord($playerName, $bet);
    }

    private function checkIfOver21(string $who, int $total, string $playerName): bool
    {
        if ($total > 21) {
            if ($who === "COMPUTER") {
                $this->computerWins($playerName);
            }
            if ($who === "YOU") {
                $this->playerWins($playerName);
            }
            return true;
        }
        return false;
    }

    private function checkIf21(int $total, string $playerName): bool
    {
        if ($total == 21) {
            $this->computerWins($playerName);
            return true;
        }
        return false;
    }

    private function countScores(array $scores): int
    {
        $playersScores = array();
        foreach ($scores as $score) {
                array_push($playersScores, $score[0]);
        }
        $counts = array_count_values($playersScores);
        if (array_key_exists("x", $counts)) {
            return $counts["x"];
        }
        return 0;
    }

    

    

    

    private function saveRolledDiceToDB(array $rolledDice)
    {
        $logic = new BankBusinessLogic($this->getDoctrine());
        foreach ($rolledDice as $die)
        {
            $logic->saveRolledDiceValuesToDB($die);
        }
    }
}
