<?php

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use SilasYudi\RestBootBundle\RestBootBundle;

return [
    new FrameworkBundle(),
    new DoctrineBundle(),
    new RestBootBundle(),
];
