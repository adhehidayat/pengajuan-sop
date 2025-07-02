<?php

namespace App\Dto\Model;

use Symfony\Component\Uid\Uuid;

class NarasumberModel
{
    public int $id;
    public string $nik;
    public string $namaLengkap;

    public function __construct($id, $nik, $namaLengkap)
    {
        $this->id = $id;
        $this->nik = $nik; //substr_replace($nik, 'xxxxxxxx', 6, 8);
        $this->namaLengkap = $namaLengkap;
    }
}