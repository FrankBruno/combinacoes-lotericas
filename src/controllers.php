<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


$app->get(
    '/',
    function () use ($app) {
        return $app['twig']->render('index.html.twig', array());
    }
)->bind('homepage');

$app->post(
    '/loterias/gerador',
    function (Request $request) use ($app) {

        $loterias = [
            'frank' => [
                'inicio' => 1,
                'fim' => 4,
                'agrupamento' => 2
            ],
            'lotofacil' => [
                'inicio' => 1,
                'fim' => 25,
                'agrupamento' => 15
            ],
            'megasena' => [
                'inicio' => 1,
                'fim' => 60,
                'agrupamento' => 6
            ],
            'minas5' => [
                'inicio' => 1,
                'fim' => 34,
                'agrupamento' => 5
            ],
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

            echo "Arquivo {$nomeArquivo} foi gravado em disco." . PHP_EOL. PHP_EOL. PHP_EOL;
            $cont++;
        }

        echo '</pre>';

        return new JsonResponse("Foram gerados {$cont} arquivo(s)");
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
