<?php

namespace App\Controller;

use App\Core\Icons;
use App\Core\Notification;
use App\Core\NotificationColor;
use App\Entity\Cart;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class CartController extends AbstractController
{
    private $cart;
    private $em = null;

    #[Route('/cart', name: 'route_cart')]
    public function index(Request $request): Response
    {
        $this->initSession($request);

        if ($this->cart->isEmpty()) {
            $this->addFlash("cart", new Notification("Warning", "Your cart is currently empty", NotificationColor::WARNING, Icons::WARNING));
        }

        return $this->render('cart/cart.html.twig', [
            'cart' => $this->cart
        ]);
    }

    #[Route('/cart/add/{idProduct}', name: 'add_purchase')]
    public function addPurchase($idProduct, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->initSession($request);
        $this->em = $doctrine->getManager();

        $product = $this->em->getRepository(Product::class)->find($idProduct);

        $this->cart->add($product);
        $this->addFlash("cart", new Notification("Success", "Product have been successfully added to your cart", NotificationColor::SUCCESS));

        return $this->redirectToRoute('route_cart');
    }

    #[Route('/cart/delete/{index}', name: 'delete_purchase')]
    public function deletePurchase($index, Request $request): Response
    {
        $this->initSession($request);

        $this->cart->delete($index);

        $this->addFlash("cart", new Notification("Success", "Product have been successfully deleted from your cart", NotificationColor::SUCCESS));

        return $this->redirectToRoute('route_cart');
    }

    #[Route('/cart/update', name: 'update_cart')]
    public function updateCart(Request $request): Response
    {
        $this->initSession($request);

        if (!$this->cart->isEmpty()) {
            $post = $request->request->all();

            $action = $request->request->get('action');
    
            if ($action == 'update') {
                $this->cart->update($post);
    
                $this->addFlash("cart", new Notification("Success", "Your cart have been successfully updated", NotificationColor::SUCCESS));
            } else if ($action == 'empty') {
                $session = $request->getSession();
                $session->remove('cart');
    
                $this->addFlash("cart", new Notification("Success", "Cart is now empty", NotificationColor::SUCCESS));
            }
        }


        return $this->redirectToRoute('route_cart');
    }

    private function initSession(Request $request)
    {
        $session = $request->getSession();
        $this->cart = $session->get('cart', new Cart());
        $session->set('cart', $this->cart);
    }
}
