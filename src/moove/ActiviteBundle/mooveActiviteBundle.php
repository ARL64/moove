<?php

namespace moove\ActiviteBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class mooveActiviteBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
