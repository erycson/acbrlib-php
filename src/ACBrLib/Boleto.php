<?php

namespace ACBrLib;

use FFI;

use ACBrLib\Exceptions\MethodCallException;
use ACBrLib\Exceptions\ConfigReadException;
use ACBrLib\Exceptions\ConfigNotFoundException;
use ACBrLib\Exceptions\ConfigDirNotFoundException;
use ACBrLib\Exceptions\LibraryFinalizationException;
use ACBrLib\Exceptions\LibraryInitializationException;

class Boleto
{
    /**
     * Undocumented variable
     *
     * @var \FFI
     */
    protected $cdecl;

    /**
     * Inicializa a classe de comunicação com o ACBr Boleto
     * 
     * @param string $libraryPath Caminho até a biblioteca
     */
    public function __construct($libraryPath)
    {
        $headerPath = __DIR__ . '/../headers/ACBrBoleto.h';

        $this->cdecl = FFI::cdef(
            file_get_contents($headerPath),
            $libraryPath
        );
    }

    /**
     * Método usado para inicializar o componente para uso da biblioteca
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_Inicializar.html
     * @param string $eArqConfig Localização do arquivo INI, pode ser em branco neste caso o ACBrLib vai criar um novo arquivo INI.
     * @param string $eChaveCrypt Chave de segurança para criptografar as informações confidencias, pode ser em branco neste caso será usado a senha padrão.
     * @return void
     */
    public function Inicializar(string $eArqConfig = '', string $eChaveCrypt = ''): void
    {
        $result = $this->cdecl->Boleto_Inicializar(
            $eArqConfig,
            $eChaveCrypt,
        );

        switch ($result) {
            case -1:
                throw new LibraryInitializationException;
            case -5:
                throw new ConfigNotFoundException;
            case -6:
                throw new ConfigDirNotFoundException;
        }
    }

    public function SAT_Inicializar(string $eArqConfig = '', string $eChaveCrypt = ''): void
    {
        $result = $this->cdecl->SAT_Inicializar(
            $eArqConfig,
            $eChaveCrypt,
        );

        switch ($result) {
            case -1:
                throw new LibraryInitializationException;
            case -5:
                throw new ConfigNotFoundException;
            case -6:
                throw new ConfigDirNotFoundException;
        }
    }

    /**
     * Método usado para remover o componente ACBrBoleto e suas classes da memoria
     * 
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_Finalizar.html
     * @return void
     */
    public function Finalizar(): void
    {
        $result = $this->cdecl->Boleto_Finalizar();

        switch ($result) {
            case -2:
                throw new LibraryFinalizationException;
        }
    }

    /**
     * Método usado retornar o ultimo retorno processado pela biblioteca
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_UltimoRetorno.html
     * @return mixed
     */
    public function UltimoRetorno(): string
    {
        $buffer = FFI::new('char[1024]');
        $size = FFI::new('int');
        $size->cdata = 1024;

        $result = $this->cdecl->Boleto_UltimoRetorno(
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            throw new MethodCallException(FFI::string($buffer));
        }

        return FFI::string($buffer);
    }

    /**
     * Método que retornar o nome da biblioteca
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_Nome.html
     * @return string
     */
    public function Nome(): string
    {
        $buffer = FFI::new('char[20]');
        $size = FFI::new('int');
        $size->cdata = 20;

        $result = $this->cdecl->Boleto_Nome(
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }

        return FFI::string($buffer);
    }

    /**
     * Método que retornar a versão da biblioteca
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_Versao.html
     * @return string
     */
    public function Versao(): string
    {
        $buffer = FFI::new('char[10]');
        $size = FFI::new('int');
        $size->cdata = 10;

        $result = $this->cdecl->Boleto_Versao(
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }

        return FFI::string($buffer);
    }

    /**
     * Método usado para ler a configuração da biblioteca do arquivo INI informado
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_ConfigLer.html
     * @param string $eArqConfig Arquivo INI para ler, se informado vazio será usado o valor padrão
     * @return void
     */
    public function ConfigLer(string $eArqConfig = ''): void
    {
        $result = $this->cdecl->Boleto_ConfigLer($eArqConfig);

        switch ($result) {
            case -5:
                throw new ConfigNotFoundException;
            case -6:
                throw new ConfigDirNotFoundException;
            case -10:
                $this->UltimoRetorno();
        }
    }

    /**
     * Método usado para gravar a configuração da biblioteca no arquivo INI informado
     *
     * @param string $eArqConfig Arquivo INI para ler, se informado vazio será usado o valor padrão
     * @return void
     */
    public function ConfigGravar(string $eArqConfig = ''): void
    {
        $result = $this->cdecl->Boleto_ConfigGravar($eArqConfig);

        switch ($result) {
            case -5:
                throw new ConfigNotFoundException;
            case -6:
                throw new ConfigDirNotFoundException;
            case -10:
                $this->UltimoRetorno();
        }
    }

    /**
     * Método usado para ler um determinado item  da configuração
     * 
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_ConfigLerValor.html
     * @param string $eSessao Nome da sessão de configuração
     * @param string $eChave Nome da chave da sessão
     * @return string
     */
    public function ConfigLerValor(string $eSessao, string $eChave): string
    {
        $buffer = FFI::new('char[128]');
        $size = FFI::new('int');
        $size->cdata = 128;

        $result = $this->cdecl->Boleto_ConfigLerValor(
            $eSessao,
            $eChave,
            $buffer,
            FFI::addr($size)
        );

        switch ($result) {
            case -1:
                throw new LibraryInitializationException;
            case -3:
                throw new ConfigReadException;
        }

        return FFI::string($buffer);
    }

    /**
     * Método usado para gravar um determinado item  da configuração
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_ConfigGravarValor.html
     * @param string $eSessao Nome da sessão de configuração
     * @param string $eChave Nome da chave da sessão
     * @param string $sValor Valor para ser gravado na configuração lembrando que o mesmo deve ser um valor string compatível com a configuração
     * @return void
     */
    public function ConfigGravarValor(
        string $eSessao,
        string $eChave,
        string $sValor
    ): void {
        $result = $this->cdecl->Boleto_ConfigGravarValor(
            $eSessao,
            $eChave,
            $sValor
        );

        switch ($result) {
            case -1:
                throw new LibraryInitializationException;
            case -3:
                throw new ConfigReadException;
        }
    }

    /**
     * Método responsável por Alterar as Configurações do Cedente, Banco e Conta utilizando um arquivo .INI
     * * obs: Os dados do Cedente também podem ser configurados utilizando a biblioteca do componente. veja em: Configurações da Biblioteca
     * * IMPORTANTE: As informações do Cedente passadas através deste método não atualizam as configurações da Biblioteca ( Configurações da Biblioteca ), ou seja, servirá apenas para o(s) titulo(s) atuais (em memória),  que estão sendo atualizados no Boleto. Ao gravar as configurações da lib, (Boleto_ConfigGravar), volta a prevalecer os dados configurados na Biblioteca "ACBrLib.ini"
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_ConfigurarDados.html
     * @param string $eArquivoIni Arquivo Cedente no formato ini do ACBr
     * @return void
     */
    public function ConfigurarDados(string $eArquivoIni): string {
        $buffer = FFI::new('char[128]');
        $size = FFI::new('int');
        $size->cdata = 128;

        $result = $this->cdecl->Boleto_ConfigurarDados(
            $eArquivoIni,
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }

        return FFI::string($buffer);
    }

    /**
     * Método responsável por Incluir os Títulos utilizando um arquivo .INI
     * * obs: Podem ser adicionados todos os Títulos do Cedente em um único arquivo, adicionando número sequencial nas chaves.
     * * ex:
     * *   [Titulo1]
     * *   ...
     * *   [Tutulo2]
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_IncluirTitulos.html
     * @param string $eArquivoIni Arquivo Titulos no formato ini do ACBr
     * @param string $eTipoSaida Se informado o tipo de Saída será executado a tarefa conforme solicitação abaixo: P - PDF, I - Impressora, E - e-mail
     * @return string
     */
    public function IncluirTitulos(string $eArquivoIni, string $eTipoSaida): string {
        $buffer = FFI::new('char[128]');
        $size = FFI::new('int');
        $size->cdata = 128;

        $result = $this->cdecl->Boleto_IncluirTitulos(
            $eArquivoIni,
            $eTipoSaida,
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }

        return FFI::string($buffer);
    }

    /**
     * Método para impressão dos Boletos adicionados na Lista
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_Imprimir.html
     * @param string $eNomeImpressora String com nome exato da impressora instalada, para impressão
     * @return void
     */
    public function Imprimir(string $eNomeImpressora = ''): void {
        $result = $this->cdecl->Boleto_Imprimir($eNomeImpressora);

        if ($result == -10) {
            $this->UltimoRetorno();
        }
    }

    /**
     * Método para impressão individual de Boleto da Lista
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_ImprimirBoleto.html
     * @param integer $eIndice Índice do Titulo na lista a ser impresso (Inicia com 0)
     * @param string $eNomeImpressora String com nome exato da impressora instalada, para impressão
     * @return void
     */
    public function ImprimirBoleto(int $eIndice, string $eNomeImpressora = ''): void {
        $result = $this->cdecl->Boleto_ImprimirBoleto($eIndice, $eNomeImpressora);

        if ($result == -10) {
            $this->UltimoRetorno();
        }
    }

    /**
     * Método responsável por Gerar o arquivo de Remessa dos Títulos adicionados a Lista
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_GerarRemsessa.html
     * @param string $eDirArqRemessa Diretório para geração do arquivo de remessa
     * @param integer $NumeroArquivo Numero Sequencial do arquivo
     * @param string $eNomeArquivo Nome do Arquivo
     * @return void
     */
    public function GerarRemsessa(
        string $eDirArqRemessa = '',
        int $NumeroArquivo = 0,
        string $eNomeArquivo = ''
    ): void {
        $result = $this->cdecl->Boleto_GerarRemsessa(
            $eDirArqRemessa,
            $NumeroArquivo,
            $eNomeArquivo
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }
    }

    /**
     * Método responsável pela Leitura do arquivo de Retorno e popular os campos lidos na lista de Títulos
     * * obs: As informações de Diretório e Nome de Arquivo também podem ser configurados utilizando a biblioteca do componente.  veja em: Configurações da Biblioteca
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_LerRetorno.html
     * @param string $eDirArqRetorno Diretório para leitura do arquivo Retorno
     * @param string $eNomeArquivo Nome do Arquivo
     * @return void
     */
    public function LerRetorno(string $eDirArqRetorno = '', string $eNomeArquivo = ''): void {
        $result = $this->cdecl->Boleto_LerRetorno($eDirArqRetorno, $eNomeArquivo);

        if ($result == -10) {
            $this->UltimoRetorno();
        }
    }

    /**
     * Método para retorno do Total de Títulos da Lista
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_TotalTitulosLista.html
     * @return integer
     */
    public function TotalTitulosLista(): int {
        $buffer = FFI::new('char[6]');
        $size = FFI::new('int');
        $size->cdata = 6;

        $result = $this->cdecl->Boleto_TotalTitulosLista(
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }

        return +FFI::string($buffer);
    }

    /**
     * Método para limpar os Títulos da Lista
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_LimparLista.html
     * @return void
     */
    public function LimparLista(): void {
        $result = $this->cdecl->Boleto_LimparLista();

        if ($result == -10) {
            $this->UltimoRetorno();
        }
    }

    /**
     * Método para envio de e-mail dos boletos adicionados a lista
     * * obs: As configurações para envio de email devem ser previamente configurados utilizando a biblioteca do componente.  veja em: Configurações da Biblioteca
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_EnviarEmail.html
     * @param string $ePara Endereço de e-mail para envio
     * @param string $eAssunto Descrição do Assunto do e-mail
     * @param string $eMensagem String com Mensagem no corpo do e-mail
     * @param string $eCC Endereço de e-mail para cópia
     * @return void
     */
    public function EnviarEmail(
        string $ePara,
        string $eAssunto = '',
        string $eMensagem = '',
        string $eCC = ''
    ): void {
        $result = $this->cdecl->Boleto_EnviarEmail(
            $ePara,
            $eAssunto,
            $eMensagem,
            $eCC
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }
    }

    /**
     * Método para selecionar Banco do Cedente, antes da Inclusão do Título
     * * obs: Todas as configurações do cedente, assim como o Banco também podem ser previamente configurados utilizando a biblioteca do componente.  veja em: Configurações da Biblioteca
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_SelecionaBanco.html
     * @param string $CodBanco Código do banco a ser utilizado ex: 001
     * @return string
     */
    public function SelecionaBanco(string $CodBanco): string {
        $buffer = FFI::new('char[128]');
        $size = FFI::new('int');
        $size->cdata = 128;

        $result = $this->cdecl->Boleto_SelecionaBanco(
            $CodBanco,
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }

        return FFI::string($buffer);
    }

    /**
     * Método para geração dos Boletos adicionados a Lista em formato HTML
     * * obs: As configurações do diretório para geração podem ser configurados utilizando a biblioteca do componente.  veja em: Configurações da Biblioteca ou utilizando o Método Boleto_SetDiretorioArquivo
     * 
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_GerarHTML.html
     * @return void
     */
    public function GerarHTML(): void {
        $result = $this->cdecl->Boleto_GerarHTML();

        if ($result == -10) {
            $this->UltimoRetorno();
        }
    }

    /**
     * Método para geração de PDF dos Boletos adicionados a Lista
     * * obs: As configurações do diretório para geração podem ser configurados utilizando a biblioteca do componente.  veja em: Configurações da Biblioteca ou utilizando o Método Boleto_SetDiretorioArquivo
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_GerarPDF.html
     * @return void
     */
    public function GerarPDF(): void {
        $result = $this->cdecl->Boleto_GerarPDF();

        if ($result == -10) {
            $this->UltimoRetorno();
        }
    }

    /**
     * Método para Listar os Bancos disponíveis no Componente ACBrLibBoleto
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_ListaBancos.html
     * @return string[]
     */
    public function ListaBancos(): array {
        $buffer = FFI::new('char[512]');
        $size = FFI::new('int');
        $size->cdata = 512;

        $result = $this->cdecl->Boleto_ListaBancos(
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }

        return explode('|', FFI::string($buffer));
    }

    /**
     * Método para Listar as Características de Carteiras aceitas pelo componente
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_ListaCaractTitulo.html
     * @return string[]
     */
    public function ListaCaractTitulo(): array {
        $buffer = FFI::new('char[128]');
        $size = FFI::new('int');
        $size->cdata = 128;

        $result = $this->cdecl->Boleto_ListaCaractTitulo(
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }

        return explode('|', FFI::string($buffer));
    }

    /**
     * Método para Listar os tipos de ocorrências aceito pelos Bancos
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_ListaOcorrencias.html
     * @return string[]
     */
    public function ListaOcorrencias(): array {
        $buffer = FFI::new('char[10240]');
        $size = FFI::new('int');
        $size->cdata = 10240;

        $result = $this->cdecl->Boleto_ListaOcorrencias(
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }
        
        return explode('|', FFI::string($buffer));
    }

    /**
     * Método para Listar os códigos e tipos de ocorrências aceito pelos Bancos
     * * A listagem de Código retornado pode ser utilizada como índice para geração do título ou Identificação da Ocorrência no Retorno. Campo = "OcorrenciaOriginal.TipoOcorrencia"
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_ListaOcorrenciasEX.html
     * @return string[]
     */
    public function ListaOcorrenciasEX(): array {
        $buffer = FFI::new('char[12288]');
        $size = FFI::new('int');
        $size->cdata = 12288;

        $result = $this->cdecl->Boleto_ListaOcorrenciasEX(
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }
        
        $options = [];
        foreach (explode('|', FFI::string($buffer)) as $item) {
            $item = explode('-', $item);
            $options[$item[0]] = $item[1];
        }

        return $options;
    }

    /**
     * Método para Listar os códigos de Mora aceito pelos Bancos
     * * obs: A listagem de Código retornado pode ser utilizada como índice para a geração do Título campo: "CodigoMora"
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_CodigosMoraAceitos.html
     * @return string
     */
    public function CodigosMoraAceitos(): string {
        $buffer = FFI::new('char[20]');
        $size = FFI::new('int');
        $size->cdata = 20;

        $result = $this->cdecl->Boleto_CodigosMoraAceitos(
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }
        
        return FFI::string($buffer);
    }

    /**
     * Método para Configurar o Diretório para Geração de Boletos PDF ou HTML
     * * obs: As informações de Diretório e Nome de Arquivo também podem ser configurados utilizando a biblioteca do componente. Veja em: Configurações da Biblioteca
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_SetDiretorioArquivo.html
     * @param string $sDiretorio String com caminho do Diretório
     * @param string $sArquivo String com Nome do Arquivo
     * @return string
     */
    public function SetDiretorioArquivo(string $sDiretorio, string $sArquivo = ''): string {
        $buffer = FFI::new('char[20]');
        $size = FFI::new('int');
        $size->cdata = 20;

        $result = $this->cdecl->Boleto_SetDiretorioArquivo(
            $sDiretorio,
            $sArquivo,
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }
        
        return FFI::string($buffer);
    }

    /**
     * Método para Calcular o Tamanho do campo Nosso Número
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_TamNossoNumero.html
     * @param string $sCarteira String com numero da Carteira
     * @param string $sNossoNumero String com Nosso Numero
     * @param string $sConvenio String com número do Convenio
     * @return string
     */
    public function TamNossoNumero(
        string $sCarteira,
        string $sNossoNumero,
        string $sConvenio
    ): int {
        $buffer = FFI::new('char[6]');
        $size = FFI::new('int');
        $size->cdata = 6;

        $result = $this->cdecl->Boleto_TamNossoNumero(
            $sCarteira,
            $sNossoNumero,
            $sConvenio,
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }
        
        return +FFI::string($buffer);
    }

    /**
     * Método para Calcular e Retornar o NossoNumero completo de acordo com o Banco Configurado
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_MontarNossoNumero.html
     * @param string $Indice Indice do Titulo na lista a ser Calculado (Inicia com 0)
     * @return integer
     */
    public function MontarNossoNumero(string $Indice): string {
        $buffer = FFI::new('char[50]');
        $size = FFI::new('int');
        $size->cdata = 50;

        $result = $this->cdecl->Boleto_MontarNossoNumero(
            $Indice,
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }
        
        return FFI::string($buffer);
    }

    /**
     * Método para Calcular e Retornar a Linha Digitável do Boleto
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_RetornarLinhaDigitavel.html
     * @param string $Indice Indice do Titulo na lista a ser Calculado (Inicia com 0)
     * @return string
     */
    public function RetornarLinhaDigitavel(string $Indice): string {
        $buffer = FFI::new('char[60]');
        $size = FFI::new('int');
        $size->cdata = 60;

        $result = $this->cdecl->Boleto_RetornarLinhaDigitavel(
            $Indice,
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }
        
        return FFI::string($buffer);
    }

    /**
     * Método para Calcular e Retornar o Código de Barras do Boleto
     *
     * @see https://acbr.sourceforge.io/ACBrLib/Boleto_RetornaCodigoBarras.html
     * @param string $Indice Indice do Titulo na lista a ser Calculado (Inicia com 0)
     * @return string
     */
    public function RetornaCodigoBarras(string $Indice): string {
        $buffer = FFI::new('char[50]');
        $size = FFI::new('int');
        $size->cdata = 50;

        $result = $this->cdecl->Boleto_RetornaCodigoBarras(
            $Indice,
            $buffer,
            FFI::addr($size)
        );

        if ($result == -10) {
            $this->UltimoRetorno();
        }
        
        return FFI::string($buffer);
    }
}
