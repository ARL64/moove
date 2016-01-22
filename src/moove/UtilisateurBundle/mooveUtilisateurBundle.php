<?php

namespace moove\UtilisateurBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class mooveUtilisateurBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
