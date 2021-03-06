int Boleto_Inicializar(const char* eArqConfig, const char* eChaveCrypt);
int Boleto_Finalizar();
int Boleto_UltimoRetorno(const char* buffer, int* bufferSize);
int Boleto_Nome(const char* buffer, int* bufferSize);
int Boleto_Versao(const char* buffer, int* bufferSize);

int Boleto_ConfigLer(const char* eArqConfig);
int Boleto_ConfigGravar(const char* eArqConfig);
int Boleto_ConfigLerValor(const char* eSessao, const char* eChave, const char* buffer, int* bufferSize);
int Boleto_ConfigGravarValor(const char* eSessao, const char* eChave, const char* valor);

int Boleto_ConfigurarDados(const char* eArquivoIni, const char* buffer, int* bufferSize);
int Boleto_IncluirTitulos(const char* eArquivoIni, const char* eTpSaida, const char* buffer, int* bufferSize);
int Boleto_LimparLista();
int Boleto_TotalTitulosLista(const char* buffer, int* bufferSize);
int Boleto_Imprimir(const char* eNomeImpressora);
int Boleto_ImprimirBoleto(int eIndice, const char* eNomeImpressora);
int Boleto_GerarPDF();
int Boleto_GerarHTML();
int Boleto_GerarRemessa(const char* eDir, int eNumArquivo, const char* eNomeArq);
int Boleto_LerRetorno(const char* eDir, const char* eNomeArq);
int Boleto_EnviarEmail(const char* ePara, const char* eAssunto, const char* eMensagem, const char* eCC);
int Boleto_SetDiretorioArquivo(const char* eDir, const char* eArq, const char* buffer, int* bufferSize);
int Boleto_ListaBancos(const char* buffer, int* bufferSize);
int Boleto_ListaCaractTitulo(const char* buffer, int* bufferSize);
int Boleto_ListaOcorrencias(const char* buffer, int* bufferSize);
int Boleto_ListaOcorrenciasEX(const char* buffer, int* bufferSize);
int Boleto_TamNossoNumero(const char* eCarteira, const char* enossoNumero, const char* eConvenio, const char* buffer, int* bufferSize);
int Boleto_CodigosMoraAceitos(const char* buffer, int* bufferSize);
int Boleto_SelecionaBanco(const char* eCodBanco, const char* buffer, int* bufferSize);
int Boleto_MontarNossoNumero(int eIndice, const char* buffer, int* bufferSize);
int Boleto_RetornaLinhaDigitavel(int eIndice, const char* buffer, int* bufferSize);
int Boleto_RetornaCodigoBarras(int eIndice, const char* buffer, int* bufferSize);