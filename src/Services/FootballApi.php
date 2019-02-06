<?php
/**
 * Created by PhpStorm.
 * User: Mateusz
 * Date: 2019-01-18
 * Time: 14:21
 */

namespace App\Services;


use App\Entity\Timetable;

class FootballApi
{

    public function callApi($data, $url, $method, $em)
    {

        $token = $em->getRepository('App:FootballData')->findOneById(['id' => '1']);
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            $token->getToken(),
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($curl);
        //return $result;
        return json_decode($result, true);

    }

    public function saveAllMatchesToDatabase($em)
    {
        //34 kolejki w bundeslidze zawsze

        for ($i = 1; $i <= 34; $i++) {
            $url = 'https://api.football-data.org/v2/competitions/BL1/matches?matchday=' . $i . '';
            $method = "GET";
            $matches = $this->callApi(false, $url, $method, $em);
            if ($i % 10 == 0) { //10 zapytań darmowych w ciągu minuty i blokada
                sleep(60);
            }
            foreach ($matches['matches'] as $match) {
                $dateOfMatch = date('Y-m-d H-i-s', strtotime($match['utcDate']));
                $Timetable = new Timetable();
                $Timetable->setAwayTeam($match['awayTeam']['name']);
                $Timetable->setHomeTeam($match['homeTeam']['name']);
                $Timetable->setHomeTeamScore($match['score']['fullTime']['homeTeam']);
                $Timetable->setAwayTeamScore($match['score']['fullTime']['awayTeam']);
                $Timetable->setDate(\DateTime::createFromFormat('Y-m-d H-i-s', $dateOfMatch));
                $Timetable->setIdApiMatch($match['id']);
                $Timetable->setMatchday($i);
                $Timetable->setSeason($match['season']['id']);
                $Timetable->setStatus($match['status']);
                $em->persist($Timetable);
                $em->flush();
            }

        }


    }

    public function updateMatches($em)
    {
        $counter = 1;
        for ($i = $em->getRepository('App:Timetable')->findActuallyMatchday()[0]->getMatchday(); $i <= 34; $i++) {
            $url = 'https://api.football-data.org/v2/competitions/BL1/matches?matchday=' . $i . '';
            $method = "GET";
            $matches = $this->callApi(false, $url, $method, $em);
            if ($counter % 10 == 0) { //10 zapytań darmowych w ciągu minuty i blokada
                sleep(60);
            }
            $counter++;
            foreach ($matches['matches'] as $match) {
                $game = $em->getRepository('App:Timetable')->findBy(['IdApiMatch' => $match['id']]);
                if ($em->getRepository('App:Timetable')->findBy(['IdApiMatch' => $match['id']])[0]->getStatus() == "SCHEDULED"
                    && $match['status'] == "FINISHED") {
                    $game[0]->setHomeTeamScore($match['score']['fullTime']['homeTeam']);
                    $game[0]->setAwayTeamScore($match['score']['fullTime']['awayTeam']);
                    $game[0]->setStatus("FINISHED");
                    $em->persist($game[0]);
                    $em->flush();

                }
            }
        }
    }
}