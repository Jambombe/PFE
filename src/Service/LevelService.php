<?php


namespace App\Service;

/**
 * Class LevelService
 * @package App\Service
 *
 * Ce service permet de convertir les points d'exp du ChildUser en niveau
 */
class LevelService
{

    const LEVEL_1 = 0;
    const LEVEL_2 = 100;
    const LEVEL_MAX = 100;
    const COEFFICIENT = 1.2;


    /**
     * @param $exp: Les points d'exp totaux
     * @return array [
     *          level: Le niveau en fonction de l'exp
     *          expLeft: Les points d'exp restant
     *          expCurrentLv: Le total de points d'exp pour passer du niveau courrant au suivant
     * ]
     */
    public function infosFromExp($exp) {

        $level = 1;
        $expLevel = $this->expForLevelN($level+1);

        while ($exp >= $expLevel) {
            $exp -= $expLevel;
            $level++;
            $expLevel = $this->expForLevelN($level+1);
        }
        // Si il reste 0 exp, c'est que l'exp totale correspond EXACTEMENT au seuil
        // d'un niveau en particulier.
        // On considère que le joueur à lvlup, avec 0exp

        return [
            'level' => $level,
            'expLeft' => $exp,
            'expCurrentLv' => round($this->expForLevelN($level+1))
        ];
    }

    /**
     * Retourne les points d'exp nécessaire pour passer du niveau $level -1 au niveau $level
     * @param $level
     * @return float|int
     */
    public function expForLevelN($level) {
        $exp = $this->expForLevelNWorker($level);
        return $exp;
    }

    /**
     * Fonction appelée récurssivement par expForLevelN
     * @param $level
     * @return float|int
     */
    private function expForLevelNWorker($level) {
        if ($level >= 3) {
            return round($this->expForLevelNWorker($level-1) * LevelService::COEFFICIENT);
            // return Math.pow(expForLevelNWorker(level-1), 2)*0.03;
            // return expForLevelNWorker(level-1)*(1+((1-level/LEVEL_MAX)/(level-2)));
        } else if ($level === 2){
            return LevelService::LEVEL_2;
        } else {
            return LevelService::LEVEL_1;
        }
    }

    /**
     * Retourne le total d'exp necessaire pour atteindre le niveau $level depuis 0
     * @param $level
     * @return float|int
     */
    function totalExpForLevelN($level)
    {
        $totalExp = $this->expForLevelN(2);

        for ($i = 3; $i <= $level; $i++) {
            $totalExp += $this->expForLevelN($i);
        }

      return $totalExp;
    }
}