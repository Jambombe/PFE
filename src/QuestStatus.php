<?php


namespace App;


class QuestStatus
{
    /* Quete creee */
    const CREATED = 0;

    /* Quete assignee à un ChildUser */
    const ASSIGNATED = 1;

    /* Quete retournée par le ChildUser */
    const RETURNED = 2;

    /* Quete validée par le ParentUser */
    const VALIDATED = 3;

    /* Quete non validée par le ParentUser ou temps imparti dépassé */
    const FAILED = 4;

}