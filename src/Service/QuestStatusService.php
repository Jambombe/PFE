<?php


namespace App\Service;


class QuestStatusService
{
    /* Quete creee */
    const CREATED = 0;
    public $CREATED =  [
        's'=> self::CREATED,
        'icon' => 'a',
        'color' => 'a',
        'colorclass' => '',
    ];

    /* Quete assignee à un ChildUser */
    const ASSIGNATED = 1;
    public $ASSIGNATED =  [
        's'=> self::ASSIGNATED,
        'icon' => 'ra ra-crossed-swords',
        'color' => '',
        'colorclass' => 'assignated',
    ];

    /* Quete retournée par le ChildUser */
    const RETURNED = 2;
    public $RETURNED =  [
        's'=> self::RETURNED,
        'icon' => '',
        'color' => '',
        'colorclass' => '',
    ];

    /* Quete validée par le ParentUser */
    const VALIDATED = 3;
    public $VALIDATED =  [
        's'=> self::VALIDATED,
        'icon' => 'ra ra-muscle-up',
        'color' => '#77cc77',
        'colorclass' => 'valid',
    ];

    /* Quete non validée par le ParentUser ou temps imparti dépassé */
    const FAILED = 4;
    public $FAILED =  [
        'i'=> self::FAILED,
        'icon' => 'ra ra-player-despair',
        'color' => '#ff6961',
        'colorclass' => 'fail',
    ];

    public function statusByNumber($nb) {

        switch ($nb) {
            case 0:
                return $this->CREATED;
                break;
            case 1:
                return $this->ASSIGNATED;
                break;
            case 2:
                return $this->RETURNED;
                break;
            case 3:
                return $this->VALIDATED;
                break;
            case 4:
                return $this->FAILED;
                break;
            default:
                return null;
        }
    }

}