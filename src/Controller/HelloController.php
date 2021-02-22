<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

class HelloController extends AbstractController{
    private $client;
    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    /**
     * @Route("/index", name="index")
     */
    public function hello_function(): Response
    {
        return $this->render('b3/index.html.twig');
    }

    /**
     * @Route("/busca", name="busca")
     */
    public function buscaEmpresa(Request $request): Response
    {
        $code = $request->query->get('code');
        $content = $this->fetchB3StatusInvest($code);
        if (empty($content)) {
            return $this->render('b3/result.html.twig', ["response" => array("status" => "Nada a ser mostrado!")]);
        } else {
            return $this->render('b3/result.html.twig', ["response" => array("status" => "sucesso", "content" => $content)]);
        }
    }
    public function fetchB3StatusInvest($codeAcao): array
    {
        $response = $this->client->request(
            'GET',
            'https://statusinvest.com.br/home/mainsearchquery?q=' . $codeAcao
        );
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }
}