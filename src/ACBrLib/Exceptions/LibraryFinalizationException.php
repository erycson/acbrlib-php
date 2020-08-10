<?php

namespace ACBrLib\Exceptions;

use Exception;

class LibraryFinalizationException extends Exception
{
    public function __construct()
    {
        parent::__construct('Falhas na finalização da biblioteca', -2);
    }
}