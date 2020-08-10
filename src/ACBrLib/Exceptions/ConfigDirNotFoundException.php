<?php

namespace ACBrLib\Exceptions;

use Exception;

class ConfigDirNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Não foi possível encontrar o diretório do arquivo INI', -6);
    }
}