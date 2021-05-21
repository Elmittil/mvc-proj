<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ScoreRepository;
use App\Repository\DicestatisticRepository;
use App\Entity\Score;
use App\Entity\Dicestatistic;
use App\BusinessLogic\BankBusinessLogic;


class ScoresController extends AbstractController
{
    /**
     * @Route("/top-scores", name="show_scores")
     */
    public function showScores(ScoreRepository $scoreRepository, DicestatisticRepository $diceRepository): Response
    {
        $scoresGame21 = $scoreRepository->showAllSortedDesc("game21");
        $allScoresGame21 = array();

        foreach ($scoresGame21 as $score) {
            array_push($allScoresGame21, [$score->getPlayerName(), $score->getScore()]);
        }


        $histogram = $this->getDiceHistogram($diceRepository);

        $data = [
            "header" => "Game 21",
            "scoresGame21" => $allScoresGame21,
            "histogram" => $histogram,
        ];

        // var_dump($histogram);
        return $this->render('scores.html.twig', $data);
    }

    private function getDiceHistogram(DicestatisticRepository $diceRepository)
    {
        $logic = new BankBusinessLogic($this->getDoctrine());
        $allDiceObjects = $logic->getDieObjectsFromDB();

        $totalRolls = $diceRepository->getTotalDiceRolls();
        $histogram = array();
        foreach ($allDiceObjects as $dieObject)
        {
            $occurrence = $dieObject->getOccurrence();
            $percentage = ($occurrence / $totalRolls) * 100;
            array_push($histogram, [$occurrence, $percentage]);
        }
        return $histogram;
    }
}
