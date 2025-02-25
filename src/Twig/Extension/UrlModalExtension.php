<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class UrlModalExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('url_modal', $this->myFunction(...)),
        ];
    }

    public function myFunction(EntityDto $value): string
    {
        $id = $value->getPrimaryKeyValue();
        $explode = explode('\\', $value->getFqcn());
        $entity = array_pop($explode);

        dump($id);
        return "/modal/${entity}/${id}";
    }
}
