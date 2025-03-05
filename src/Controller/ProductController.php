<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CartItem;
use App\Entity\Cart;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function index(ProductRepository $productRepository): Response
    {
        // Pobranie wszystkich produktów z bazy danych
        $products = $productRepository->findAll();

        // Przekazanie danych do widoku
        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

}
