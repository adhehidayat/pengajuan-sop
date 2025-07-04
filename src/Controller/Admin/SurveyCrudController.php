<?php

namespace App\Controller\Admin;

use App\Entity\Survey;
use App\Repository\SurveyRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SurveyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Survey::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, Action::new('downloadLaporan', 'Unduh Laporan', '')
                ->linkToCrudAction('downloadLaporan')
                ->createAsGlobalAction()
            )
            ->disable(Crud::PAGE_EDIT)
            ->disable(Crud::PAGE_DETAIL)
            ->disable(Crud::PAGE_NEW)
            ->disable(Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->hideOnForm()->hideOnIndex(),
            TextField::new('pengajuan.contract')->renderAsHtml(),
            TextField::new('narasumber.nama', 'Responden')
        ];
    }

    public function downloadLaporan(SurveyRepository $surveyRepository): StreamedResponse
    {
        $template = $this->getParameter('kernel.project_dir') . '/public/templates/survey-template.xlsx';
        $spreadSheet = IOFactory::load($template);
        $sheet = $spreadSheet->getActiveSheet();

        $repository = $surveyRepository->findAll();

        $sheet->calculateWorksheetDimension();

        $row = 5;
        $no = 1;
        foreach ($repository as $item) {
            $sheet->getStyle("B{$row}")
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_NUMBER);

            $sheet->getColumnDimension("B")
                ->setAutoSize(true);

            $sheet->getColumnDimension("C")
                ->setAutoSize(true);

            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValueExplicit("B{$row}", $item->getNarasumber()->getNik(), DataType::TYPE_STRING);
            $sheet->setCellValue("C{$row}", $item->getNarasumber()->getNama());
            $sheet->setCellValue("D{$row}", $item->getQue1());
            $sheet->setCellValue("E{$row}", $item->getQue2());
            $sheet->setCellValue("F{$row}", $item->getQue3());
            $sheet->setCellValue("G{$row}", $item->getQue4());
            $sheet->setCellValue("H{$row}", $item->getQue5());
            $sheet->setCellValue("I{$row}", $item->getQue6());
            $sheet->setCellValue("J{$row}", $item->getQue7());
            $sheet->setCellValue("K{$row}", $item->getQue8());
            $sheet->setCellValue("L{$row}", $item->getQue9());

            $row++;
        }


        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $filename = 'laporan-' . date('Ymd') . '.xlsx';

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;

    }
}
