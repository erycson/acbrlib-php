# ACBrLib PHP

Biblioteca PHP + FFI não oficial para chamada das funções do ACBrLib

## Instalação

```bash
composer require erycson/acbrlib-php
```

## Como usar

```php
<?php
require 'vendor/autoload.php';

// Caminho até a biblioteca do ACBrBoleto
$libraryPath = './ACBrBoleto64.dll';

$lib = new ACBrLib\Boleto($libraryPath);
$lib->Inicializar('./config.ini', '');
echo $lib->Nome() . ' - ' . $lib->Versao() . PHP_EOL;
$lib->Finalizar();


// Resultado: ACBrLibBoleto - 0.2.0.68
```

## TO DO

* Implementação de testes
* Implementação do NFe
* Implementação do SAT