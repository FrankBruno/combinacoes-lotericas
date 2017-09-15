<?php

namespace Loterias;

class Agrupador
{

    /**
     * @var Arquivo
     */
    private $arquivo;

    /**
     * @var array
     */
    private $arquivosGerados = [];

    /**
     * Agrupador constructor.
     * @param Arquivo $arquivo
     */
    public function __construct(Arquivo $arquivo)
    {
        $this->arquivo = $arquivo;
    }

    public function gerarPorSoma()
    {
        $this->agruparPorSoma();

        return 'Arquivo gerado';
    }

    private function agruparPorSoma()
    {

        $linhas = array_filter(explode(PHP_EOL, $this->arquivo->getConteudo()));

        if (empty($linhas)) {
            throw new \Exception('Nao foi possível agrupar arquivos por soma dos resultados: Arquivo informado não possui registros.');
        }

        foreach ($linhas as $l => $linha) {

            $dezenas = explode(',', $linha);
            $soma = array_sum($dezenas);

            if (!isset($this->arquivosGerados[$soma])) {
                $this->arquivosGerados[$soma] = $linha ;
                continue;
            }

            $this->arquivosGerados[$soma] .= PHP_EOL . $linha ;
        }

        $this->salvarArquivosGerados();
    }

    private function salvarArquivosGerados()
    {
        if (empty($this->arquivosGerados)) {
            throw new \Exception(('Não foi possível criar arquivos agruapdos: Nenhum arquivo encontrado.'));
        }

        foreach ($this->arquivosGerados as $soma => $conteudo) {

            $diretorio = __DIR__ . "/../arquivos/{$this->arquivo->getTipo()}";
            $nomeArquivo = "/{$soma}.txt";

            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0777, true);
            }

            file_put_contents($diretorio . $nomeArquivo, $conteudo, true);
        }
    }

    /**
     * @return int
     */
    public function getQuantidadeArquivosGerados()
    {
        return count($this->arquivosGerados);
    }
}