<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function index(ProductRepository $productRepository): Response
    {
        var_dump('dududucos'); die();
        // Pobranie wszystkich produktów z bazy danych
        $products = $productRepository->findAll();

        // Przekazanie danych do widoku
        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }
}
