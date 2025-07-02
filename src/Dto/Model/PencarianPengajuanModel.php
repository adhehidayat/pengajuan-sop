<?php

namespace App\Dto\Model;

use Symfony\Component\Serializer\Attribute\Groups;

class PencarianPengajuanModel
{
    #[Groups("pencarian_read")]
    public int $id;

    #[Groups("pencarian_read")]
    public string $contract;

    #[Groups("pencarian_read")]
    public $narasumber;

    #[Groups("pencarian_read")]
    public $progress;

    #[Groups("pencarian_read")]
    public $historyProgress;

    #[Groups("pencarian_read")]
    public $files;

    #[Groups("pencarian_read")]
    public bool $isSurvey;

}