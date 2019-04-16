<?php


namespace App\Service;


class QuestStatus
{
    /* Quete creee */
    const CREATED = 0;
    public $CREATED =  [
        'i'=> 0,
        'icon' => 'a',
        'color' => 'a',
        'colorclass' => 'success',
        ];

    /* Quete assignee à un ChildUser */
    const ASSIGNATED = 1;
    public $ASSIGNATED =  [
        'i'=> 1,
        'icon' => '',
        'color' => '',
        'colorclass' => '',
    ];

    /* Quete retournée par le ChildUser */
    const RETURNED = 2;
    public $RETURNED =  [
        'i'=> 2,
        'icon' => '',
        'color' => '',
        'colorclass' => '',
    ];

    /* Quete validée par le ParentUser */
    const VALIDATED = 3;
    public $VALIDATED =  [
        'i'=> 3,
        'icon' => 'fa-check',
        'color' => '#108009',
        'colorclass' => 'success',
    ];

    /* Quete non validée par le ParentUser ou temps imparti dépassé */
    const FAILED = 4;
    public $FAILED =  [
        'i'=> 4,
        'icon' => 'fa-times',
        'color' => '#cc1e27',
        'colorclass' => 'danger',
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