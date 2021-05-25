<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ScoreRepository;
use App\Repository\WinpergameRepository;
use App\Repository\DicestatisticRepository;
use App\Entity\Score;
use App\Entity\Winpergame;
use App\Entity\Dicestatistic;
use App\BankBusinessLogic\BankBusinessLogic;

class ScoresController extends BaseController
{
    // private $request;

    // public function __construct()
    // {
    //     $this->request = Request::createFromGlobals();
    // }
    /**
     * @Route("/top-scores", name="show_scores", methods={"GET"})
     */
    public function showScores(WinpergameRepository $winRepository, ScoreRepository $scoreRepository, DicestatisticRepository $diceRepository): Response
    {
        $allScoresGame21 = array();
        $whichPage = $this->request->query->get('kind');
        if ($whichPage === "top" || is_null($whichPage)) {
            $allScoresGame21 = $this->getTopScores21($scoreRepository);
        }
        if ($whichPage === "bottom") {
            $allScoresGame21 = $this->getBottomScores21($scoreRepository);
        }
        $highestWins = $this->getWins($winRepository);
        $histogram = $this->getDiceHistogram($diceRepository);

        $data = [
            "header" => "Game 21",
            "scoresGame21" => $allScoresGame21,
            "histogram" => $histogram,
            "wins" => $highestWins,
        ];

        // var_dump($highestWins);
        return $this->render('scores.html.twig', $data);
    }

    private function getDiceHistogram(DicestatisticRepository $diceRepository)
    {
        $logic = new BankBusinessLogic($this->getDoctrine());
        $allDiceObjects = $logic->getDieObjectsFromDB();

        $totalRolls = $diceRepository->getTotalDiceRolls();
        $histogram = array();
        foreach ($allDiceObjects as $dieObject) {
            $occurrence = $dieObject->getOccurrence();
            $percentage = ($occurrence / $totalRolls) * 100;
            array_push($histogram, [$occurrence, $percentage]);
        }
        return $histogram;
    }

    private function getTopScores21(ScoreRepository $scoreRepository)
    {
        $scoresGame21 = $scoreRepository->showTop10Scores("game21");
        $allScoresGame21 = array();

        foreach ($scoresGame21 as $score) {
            array_push($allScoresGame21, [$score->getPlayerName(), $score->getScore()]);
        }
        return $allScoresGame21;
    }

    private function getBottomScores21(ScoreRepository $scoreRepository)
    {
        $scoresGame21 = $scoreRepository->showBottom10Scores("game21");
        $allScoresGame21 = array();

        foreach ($scoresGame21 as $score) {
            array_push($allScoresGame21, [$score->getPlayerName(), $score->getScore()]);
        }
        return $allScoresGame21;
    }


    private function getWins(WinpergameRepository $winRepository): array
    {
        $wins = $winRepository->showTopTenWins();
        $winsArray = array();

        foreach ($wins as $win) {
            array_push($winsArray, [$win->getPlayerName(), $win->getBetWon()]);
        }
        return $winsArray;
    }
}
