<?php

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use SymfonyBoot\SymfonyBootBundle\SymfonyBootBundle;

return [
    new FrameworkBundle(),
    new DoctrineBundle(),
    new SymfonyBootBundle(),
];
