<?php

namespace Loterias;

/**
 * Class ArquivoFactory
 * @package Frankbruno\Loterias
 */
class ArquivoFactory
{
    /**
     * @param string $tipo Tipo do arquivo informado
     * @param string $diretorio Diretorio do arquivo
     * @return Arquivo
     * @throws \Exception
     */
    public static function criarArquivo($tipo, $diretorio)
    {
        $conteudo = file_get_contents($diretorio);

        if (empty($conteudo)) {
            throw new \Exception("Diretório informado inválido: Conteúdo do arquivo {$diretorio} não encontado.");
        }

        return new Arquivo(strtolower($tipo), $conteudo);
    }
}