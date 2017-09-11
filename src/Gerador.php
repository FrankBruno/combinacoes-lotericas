<?php

namespace CombinacoesLotericas;

/**
 * Class Gerador
 * @package CombinacoesLotericas
 */
class Gerador
{

    const QUANTIDADE_QUINA = 80;
    const QUANTIDADE_MEGASENA = 60;
    const QUANTIDADE_LOTOFACIL = 25;

    /**
     * @var int
     */
    protected $agrupador;

    /**
     * @var int
     */
    protected $quantidade;

    /**
     * @var int
     */
    protected $indice = 1;

    /**
     * @var string
     */
    protected $combinacoes = '';

    /**
     * @var string
     */
    protected $conteudo = '';


    /**
     * Gerador constructor.
     * @param int $agrupador
     * @param int $quantidade
     */
    public function __construct($agrupador, $quantidade)
    {
        $this->agrupador = $agrupador;
        $this->quantidade = $quantidade;
    }

    public function gerarMegaSena()
    {
        for ($a = 1; $a <= self::QUANTIDADE_MEGASENA; $a++) {
            for ($b = ($a + 1); $b <= self::QUANTIDADE_MEGASENA; $b++) {
                for ($c = ($b + 1); $b <= self::QUANTIDADE_MEGASENA; $c++) {
                    for ($d = ($c + 1); $b <= self::QUANTIDADE_MEGASENA; $d++) {
                        for ($e = ($d + 1); $b <= self::QUANTIDADE_MEGASENA; $e++) {
                            for ($f = ($e + 1); $b <= self::QUANTIDADE_MEGASENA; $f++) {
                                echo "{$a},{$b},{$c},{$d},{$e},{$f}" . PHP_EOL;
                            }
                        }
                    }
                }
            }
        }

        return $this->conteudo;
    }

    public function gerar($initValue, $limitValue, $recIndex, $recLimit, $acc)
    {
        for ($i = $initValue; $i <= $limitValue; $i++) {
            $newAcc = array_merge($acc, [$i]);
            if ($recIndex < $recLimit) {
                $this->gerar($i + 1, $limitValue, $recIndex + 1, $recLimit, $newAcc);
            } else {
                echo implode(',', $newAcc) . "<br>";
            }
        }
    }

//gerar(0, 25, 0, 2, []);

}