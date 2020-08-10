<?php

namespace ACBrLib\Exceptions;

use Exception;

class LibraryInitializationException extends Exception
{
    public function __construct()
    {
        parent::__construct('Falhas na inicialização da biblioteca', -1);
    }
}