<?php

namespace App\Message;

use App\Entity\Narasumber;
use App\Entity\Pengajuan;

class PengajuanApprovedMessage
{
    public function __construct(
        public string $pengajuan,
        public array  $responden,
        public string $survey
    )
    {
    }
}