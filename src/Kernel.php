<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    // Nouvelle pour EasyAdmin
    /* public function registerBundle()
    {
        $bundles = array(
            // ...
            new EasyCorp\Bundle\EasyAdminBundle\EasyAdminBundle(),
        );
    }*/
} 
