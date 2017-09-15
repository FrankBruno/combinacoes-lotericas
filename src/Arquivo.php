<?php

namespace Loterias;

/**
 * Class Arquivo
 * @package FrankBruno\Loterias
 */
class Arquivo
{
    /**
     * @var string
     */
    private $tipo = '';

    /**
     * @var string
     */
    private $conteudo = '';

    /**
     * Arquivo constructor.
     * @param string $tipo
     * @param string $conteudo
     */
    public function __construct($tipo, $conteudo)
    {
        $this->tipo = $tipo;
        $this->conteudo = $conteudo;
    }

    /**
     * @return string
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * @return string
     */
    public function getConteudo(): string
    {
        return $this->conteudo;
    }
}