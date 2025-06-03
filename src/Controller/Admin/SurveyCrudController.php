<?php

namespace App\Controller\Admin;

use App\Entity\Survey;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Flex\Response;

class SurveyCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly HttpClientInterface   $_httpClient,
        private readonly ParameterBagInterface $params
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Survey::class;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->hideOnForm()->hideOnIndex(),
            ChoiceField::new("survey", "Survey")
                ->setChoices($this->apiSurvey())
                ->addWebpackEncoreEntries('survey_title_field')
                ->hideOnIndex()
            ,
            HiddenField::new('title'),
            BooleanField::new("status", "Status"),
        ];
    }

    public function apiSurvey(): array
    {
        $api = $this->params->get("api")["survey"]["link"];

        $response = $this->_httpClient->request("GET", "{$api}/public/surveys", [
            "headers" => [
                "accept" => "application/json",
            ],
        ]);

        $data = $response->toArray();

        $choices = [];
        foreach ($data as $d) {
            $choices[$d["title"]] = $d["id"];
        }

        return $choices;
    }

}
