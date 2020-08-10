<?php

namespace ACBrLib\Exceptions;

use Exception;

class ConfigReadException extends Exception
{
    public function __construct()
    {
        parent::__construct('Erro ao ler a configuração informada', -3);
    }
}