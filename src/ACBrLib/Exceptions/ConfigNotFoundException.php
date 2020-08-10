<?php

namespace ACBrLib\Exceptions;

use Exception;

class ConfigNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Não foi possível localizar o arquivo INI informado', -5);
    }
}