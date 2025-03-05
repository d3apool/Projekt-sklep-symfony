<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_show')]
    public function showCart(EntityManagerInterface $entityManager): Response
    {
        // Używamy stałego ID koszyka (1)
        $cartId = 1;

        // Pobieramy koszyk z bazy danych
        $cart = $entityManager->getRepository(Cart::class)->find($cartId);

        // Jeśli koszyk nie istnieje, tworzymy nowy
        if (!$cart) {
            $cart = new Cart();
            $cart->setTotalPrice('0.00');
            $entityManager->persist($cart);
            $entityManager->flush();
        }

        // Pobieramy elementy koszyka
        $cartItems = $entityManager->getRepository(CartItem::class)->findBy(['cart' => $cart]);

        // Pobieramy informacje o produktach dla każdego elementu koszyka
        $cartItemsWithProducts = [];
        foreach ($cartItems as $item) {
            $product = $entityManager->getRepository(Product::class)->find($item->getProduktId());
            if ($product) {
                $cartItemsWithProducts[] = [
                    'item' => $item,
                    'product' => $product
                ];
            }
        }

        return $this->render('product/show.html.twig', [
            'cartItems' => $cartItemsWithProducts,
            'totalPrice' => $cart->getTotalPrice(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function addToCart(int $id, EntityManagerInterface $entityManager): Response
    {
        // Używamy stałego ID koszyka (1)
        $cartId = 1;

        // Pobieramy produkt
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produkt nie istnieje');
        }

        // Pobieramy koszyk lub tworzymy nowy
        $cart = $entityManager->getRepository(Cart::class)->find($cartId);

        if (!$cart) {
            $cart = new Cart();
            $cart->setTotalPrice('0.00');
            $entityManager->persist($cart);
        }

        // Sprawdzamy czy produkt jest już w koszyku
        $cartItem = $entityManager->getRepository(CartItem::class)
            ->findOneBy([
                'cart' => $cart,
                'produktId' => $product->getId()
            ]);

        if ($cartItem) {
            // Zwiększamy ilość
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            // Tworzymy nowy element koszyka
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduktId($product->getId());
            $cartItem->setQuantity(1);
            $cartItem->setCenaProduktu($product->getCena());
            $entityManager->persist($cartItem);
        }

        // Aktualizujemy sumę koszyka
        $totalPrice = 0;
        foreach ($entityManager->getRepository(CartItem::class)->findBy(['cart' => $cart]) as $item) {
            $totalPrice += $item->getCenaProduktu() * $item->getQuantity();
        }
        $cart->setTotalPrice((string)$totalPrice);

        // Zapisujemy zmiany
        $entityManager->flush();

        // Przekierowujemy z powrotem do listy produktów
        return $this->redirectToRoute('products');
    }
}
