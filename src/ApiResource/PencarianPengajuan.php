<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Dto\Model\NarasumberModel;
use App\Dto\Model\PencarianPengajuanModel;
use App\Entity\Narasumber;
use App\Entity\Pengajuan;
use App\Entity\User;
use App\State\PencarianPengajuanProvider;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: 'public/pencarian/pengajuans',
            paginationEnabled: false,
            normalizationContext: ["groups" => ["pencarian_read", 'ptsp_read']],
            output: PencarianPengajuanModel::class,
            provider: PencarianPengajuanProvider::class,
            parameters: [
                new QueryParameter(key: 'contract', filter: SearchFilter::class),
                new QueryParameter(key: 'user.nik', filter: SearchFilter::class)
            ]
        )
    ]
)]
class PencarianPengajuan
{

}