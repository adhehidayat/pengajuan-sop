<?php

namespace App\EventSubscriber;

use App\Entity\Pengajuan;
use App\Entity\PengajuanProgress;
use App\Entity\PengajuanProgressHistory;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PengajuanProgressSubscriber implements EventSubscriberInterface
{

    public function __construct(private EntityManagerInterface $registry)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => ['setPersist'],
            AfterEntityUpdatedEvent::class => ['setUpdated']
        ];
    }

    public function setPersist(AfterEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Pengajuan)) {
            return;
        }

        if ($entity->getPengajuanProgress()) {
            $this->persistPengajuanProgressHistory($entity);
        }
    }

    public function setUpdated(AfterEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Pengajuan)) {
            return;
        }

        if ($entity->getPengajuanProgress()) {
            $this->persistPengajuanProgressHistory($entity);
        }

    }


    private function persistPengajuanProgressHistory($entity): void
    {
        /** @var PengajuanProgress $progress */
        $progress = $entity->getPengajuanProgress();


        $history = new PengajuanProgressHistory();
        $history->setPengajuan($progress->getPengajuan());
        $history->setUser($progress->getUser());
        $history->setKet($progress->getKet() ?? null);
        $history->setCreateAt($progress->getCreateAt());
        $history->setStatus($progress->getStatus());

        $this->registry->persist($history);
        $this->registry->flush();
    }



}
