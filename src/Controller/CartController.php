<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function removeItem(int $id, EntityManagerInterface $entityManager): Response
    {
        // Znajdź element koszyka na podstawie id_produktuW
        $cartItem = $entityManager->getRepository(CartItem::class)->find($id);

        if (!$cartItem) {
            throw $this->createNotFoundException('Element koszyka nie istnieje');
        }

        // Usuń element koszyka
        $entityManager->remove($cartItem);
        $entityManager->flush();

        // Zaktualizuj cenę całkowitą koszyka
        $cartId = 1; // Stały ID koszyka
        $cart = $entityManager->getRepository(Cart::class)->find($cartId);
        $this->updateCartTotalPrice($entityManager, $cart);

        return $this->redirectToRoute('cart_show');
    }

    #[Route('/cart/update-quantity/{id}', name: 'cart_update_quantity')]
    public function updateQuantity(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Znajdź element koszyka na podstawie id_produktuW
        $cartItem = $entityManager->getRepository(CartItem::class)->find($id);

        if (!$cartItem) {
            throw $this->createNotFoundException('Element koszyka nie istnieje');
        }

        // Pobierz nową ilość z formularza
        $newQuantity = (int) $request->request->get('quantity');

        if ($newQuantity < 1) {
            $this->addFlash('error', 'Ilość musi być co najmniej 1');
            return $this->redirectToRoute('cart_show');
        }

        // Zaktualizuj ilość i zapisz zmiany
        $cartItem->setQuantity($newQuantity);
        $entityManager->flush();

        // Zaktualizuj cenę całkowitą koszyka
        $cartId = 1; // Stały ID koszyka
        $cart = $entityManager->getRepository(Cart::class)->find($cartId);
        $this->updateCartTotalPrice($entityManager, $cart);

        return $this->redirectToRoute('cart_show');
    }

    private function updateCartTotalPrice(EntityManagerInterface $entityManager, Cart $cart): void
    {
        // Oblicz sumę cen wszystkich elementów w koszyku
        $totalPrice = 0;
        foreach ($entityManager->getRepository(CartItem::class)->findBy(['cart' => $cart]) as $item) {
            $totalPrice += $item->getCenaProduktu() * $item->getQuantity();
        }

        // Ustaw nową cenę całkowitą i zapisz zmiany
        $cart->setTotalPrice((string)$totalPrice);
        $entityManager->flush();
    }
}
