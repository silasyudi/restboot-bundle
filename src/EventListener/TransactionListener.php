<?php

namespace SilasYudi\RestBootBundle\EventListener;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use SilasYudi\RestBootBundle\Reflections\ReflectionsUtil;
use SilasYudi\RestBootBundle\Transaction\Annotation\Transaction;
use SilasYudi\RestBootBundle\Transaction\TransactionManager;

class TransactionListener
{

    private ManagerRegistry $managerRegistry;
    private ?TransactionManager $transactionManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->transactionManager = null;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        /** @var Transaction $annotation */
        $annotation = (new ReflectionsUtil($controller))->getMethodOrClassAnnotation(Transaction::class);

        if ($annotation) {
            $connection = $this->managerRegistry->getConnection($annotation->getConnection());
            $this->transactionManager = new TransactionManager($connection);
            $this->transactionManager->begin();
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if ($this->transactionManager) {
            $this->transactionManager->commit();
        }
    }

    public function onKernelException(ExceptionEvent $event)
    {
        if ($this->transactionManager) {
            $this->transactionManager->rollback();
        }
    }
}
