<?php

namespace ACBrLib\Exceptions;

use Exception;

class MethodCallException extends Exception
{
    public function __construct($mensagem)
    {
        parent::__construct("Falha na execução do método: {$mensagem}", -10);
    }
}