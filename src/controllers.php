<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use CombinacoesLotericas\Gerador;

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})->bind('homepage');

$app->get('/gerador', function () use ($app) {

    $gerador = new Gerador(3, 5);


    gerar(0, 25, 0, 2, []);

    return new JsonResponse($retorno);

});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/' . $code . '.html.twig',
        'errors/' . substr($code, 0, 2) . 'x.html.twig',
        'errors/' . substr($code, 0, 1) . 'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
