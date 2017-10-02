<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Loterias\ArquivoFactory;

$app->get(
    '/',
    function () use ($app) {
        return $app['twig']->render('index.html.twig', array());
    }
)->bind('homepage');

$app->post(
    '/loterias/gerador',
    function (Request $request) use ($app) {


        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);

        try {
            $loterias = [
                #'lotofacil' => ['inicio' => 1, 'fim' => 25, 'agrupamento' => 15],
                #'megasena' => ['inicio' => 1, 'fim' => 60, 'agrupamento' => 6],
                #'minas5' => ['inicio' => 1, 'fim' => 34, 'agrupamento' => 5],
                #'duplasena' => ['inicio' => 1, 'fim' => 50, 'agrupamento' => 6],
                #'quina' => ['inicio' => 1, 'fim' => 80, 'agrupamento' => 5],
                #'timemania' => ['inicio' => 1, 'fim' => 80, 'agrupamento' => 7],
            ];

            echo '<pre>';
            $cont = 0;

            foreach ($loterias as $nomeLoteria => $loteria) {
                $modelo = \FrankBruno\GeradorCombinacoes\ModeloFactory::criar(
                    $loteria['inicio'],
                    $loteria['fim'],
                    $loteria['agrupamento']
                );
                $gerador = new \FrankBruno\GeradorCombinacoes\Gerador($modelo);

                echo "Gerando combinações da {$nomeLoteria}: Do {$loteria['inicio']} a {$loteria['fim']}, em grupos de {$loteria['agrupamento']}." . PHP_EOL;

                $arquivo = $gerador->gerarConteudo();

                echo "Combinações foram geradas.." . PHP_EOL;
                echo "Escrevendo arquivo em disco." . PHP_EOL;

                $nomeArquivo = __DIR__ . "/../arquivos/{$nomeLoteria}.txt";

                file_put_contents($nomeArquivo, $arquivo);

                echo "Arquivo {$nomeArquivo} foi gravado em disco." . PHP_EOL . PHP_EOL . PHP_EOL;
                $cont++;
            }

            echo '</pre>';

            return new JsonResponse("Foram gerados {$cont} arquivo(s)");
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
);

$app->post(
    '/loterias/arquivos/agrupador-soma',
    function (Request $request) use ($app) {

        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);

        $retorno = [];
        $loterias = ['quina'];

        try {
            foreach ($loterias as $loteria) {

                $diretorioArquivo = __DIR__ . "/../arquivos/{$loteria}.txt";
                $arquivo = ArquivoFactory::criarArquivo($loteria, $diretorioArquivo);
                $agrupador = new Loterias\Agrupador($arquivo);

                $agrupador->gerarPorSoma();

                $retorno[] = [
                    'loteria' => $loteria,
                    'quantidade_arquivos' => $agrupador->getQuantidadeArquivosGerados(),
                    'mensagem' => 'Arquivos gerados corretamente.'
                ];
            }

            return new JsonResponse($retorno, Response::HTTP_CREATED);

        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'mensagem' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
);

$app->error(
    function (\Exception $e, Request $request, $code) use ($app) {
        if ($app['debug']) {
            return;
        }

        $templates = array(
            'errors/' . $code . '.html.twig',
            'errors/' . substr($code, 0, 2) . 'x.html.twig',
            'errors/' . substr($code, 0, 1) . 'xx.html.twig',
            'errors/default.html.twig',
        );

        return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
    }
);
