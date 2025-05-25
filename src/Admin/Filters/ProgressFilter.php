<?php

namespace App\Admin\Filters;

use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class   ProgressFilter implements FilterInterface
{
    use FilterTrait;

    public static function new(string $propertyName, $label = null): ProgressFilter
    {
        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(ChoiceType::class)
            ;
    }

    public function apply(QueryBuilder $queryBuilder, FilterDataDto $filterDataDto, ?FieldDto $fieldDto, EntityDto $entityDto): void
    {
        $alias = $filterDataDto->getEntityAlias();
        $value = $filterDataDto->getValue();

        $queryBuilder
            ->join('entity.pengajuanProgress' , 'pengajuanProgress')
            ->andWhere('pengajuanProgress.status =:status')
            ->setParameter('status', $value->value)
            ;

//
//        $subQuery = $queryBuilder->getEntityManager()->createQueryBuilder()
//            ->select('IDENTITY(pp.pengajuan)')
//            ->from(PengajuanProgress::class, 'pp')
//            ->where('pp.createAt = (SELECT MAX(pp2.createAt) FROM App\Entity\PengajuanProgress pp2 WHERE pp2.pengajuan = pp.pengajuan)')
//            ->andWhere('pp.status = :status')
//        ;
//
//        $queryBuilder->andWhere($queryBuilder->expr()->in("$alias.id", $subQuery->getDQL()))
//            ->setParameter('status', $value->value);
    }

    public function renderExpanded(bool $isExpanded = true): self
    {
        $this->dto->setFormTypeOption('expanded', $isExpanded);

        return $this;
    }

    public function canSelectMultiple(bool $selectMultiple = true): self
    {
        $this->dto->setFormTypeOption('value_type_options.multiple', $selectMultiple);

        return $this;
    }

    public function choiceList(?array $choices): self
    {
        $this->dto->setFormTypeOption('choices', $choices);

        return $this;
    }

}
