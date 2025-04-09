<?php

namespace App\Service\Chart;

use App\Components\Enum\PengajuanStatusEnum;
use App\Entity\PengajuanProgress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartPengajuanService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ChartBuilderInterface $chartBuilder
    )
    {
    }

    public function chartPengaduanByStatus(): Chart
    {
        $queryBuilder = $this->em->getRepository(PengajuanProgress::class)
            ->createQueryBuilder('u')
            ->select('u.status', 'COUNT(u.status) as jumlah')
            ->groupBy('u.status')
            ->getQuery()
            ->getArrayResult();

        $enumLabel = array_column($queryBuilder, 'status');
        $label = array_map(fn($value) => $value->value, $enumLabel);
        $data = array_column($queryBuilder, 'jumlah');

        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $label,
            'datasets' => [[
                'label' => 'Jumlah Pengaduan Berdasarkan Status',
                'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                'data' => $data,
            ]],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $chart;
    }
}
