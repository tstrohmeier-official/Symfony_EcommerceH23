<?php

namespace App\Controller;

use App\Core\Icons;
use App\Core\Notification;
use App\Core\NotificationColor;
use App\Entity\Constants;
use App\Entity\Order;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CheckOutController extends AbstractController
{
    private $em = null;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }

    #[Route('/orders/review', name: 'route_orderReview')]
    public function orderReview(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $cart = $request->getSession()->get('cart');

        //Reglé dans une issue précédente
        if ($cart != null && !$cart->isEmpty()) {

            return $this->render('checkout/orderReview.html.twig', [
                'cart' => $cart,
                'user' => $user
            ]);
        }

        return $this->redirectToRoute('route_cart');
    }

    #[Route('/stripe-chechout', name: 'stripe_chechout')]
    public function stripeCheckout(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $cart = $request->getSession()->get('cart');

        \Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);

        $successURL = $this->generateUrl('stripe_success', [], UrlGeneratorInterface::ABSOLUTE_URL) . "?stripe_id={CHECKOUT_SESSION_ID}";

        $sessionData = [
            'line_items' => [[
                'quantity' => 1,
                'price_data' => ['unit_amount' => $cart->getStripePriceInCents(), 'currency' => 'CAD', 'product_data' => ['name' => 'Dark Roasted Coffee checkout']]
            ]],
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'success_url' => $successURL,
            'cancel_url' => $this->generateUrl('stripe_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL)
        ];

        $checkoutSession = \Stripe\Checkout\Session::create($sessionData);
        return $this->redirect($checkoutSession->url, 303);
    }

    #[Route('/stripe-success', name: 'stripe_success')]
    public function stripeSuccess(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $cart = $request->getSession()->get('cart');

        try {
            $stripe = new \Stripe\StripeClient($_ENV["STRIPE_SECRET"]);

            $stripeSessionId = $request->query->get('stripe_id');
            $sessionStripe = $stripe->checkout->sessions->retrieve($stripeSessionId);
            $payementIntent = $sessionStripe->payment_intent;

            $order = new Order($user, $payementIntent);
            
            $itemsOutOfStock = [];

            foreach ($cart->getItems() as $item) {

                $entity = $this->em->merge($item);
                $order->addProduct($entity);

                $entity->decrementQuantityInStock();

                if ($entity->getQuantityInStock() <= 0) {
                    array_push($itemsOutOfStock, $entity);
                }
            }

            $this->em->persist($order);
            $this->em->flush();

            $notification = Notification::createOutOfStockNotification($order->getIdOrder(), $itemsOutOfStock);
            $this->addFlash("order", $notification);

            $request->getSession()->remove('cart');

        } catch (\Exception $e) {
            return $this->redirectToRoute('route_catalogue');
        }

        return $this->redirectToRoute('route_userOrders');
    }

    #[Route('/stripe-cancel', name: 'stripe_cancel')]
    public function stripeCancel(): Response
    {
        $this->addFlash("order", new Notification("WARNING", "Transaction failed, please retry.", NotificationColor::DANGER, Icons::WARNING));

        return $this->redirectToRoute('route_cart');
    }

    #[Route('/user/orders', name: 'route_userOrders')]
    public function userOrders(Request $request): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $orders = $this->em->getRepository(Order::class)->findBy(['user'=>$user->getIdProfil()], ['orderDate'=>'DESC']);

        return $this->render('checkout/userOrders.html.twig', ['orders' => $orders]);
    }

    #[Route('/orders/{idOrder}', name: 'route_orderDetails')]
    public function infoProduct($idOrder, Request $request, ManagerRegistry $doctrine): Response 
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $order = $this->em->getRepository(Order::class)->find($idOrder);

        if ($user->isThisMyId($order->getUserId())) {
            return $this->render('checkout/orderDetails.html.twig', ['order' => $order]);
        }
        
        return $this->redirectToRoute('route_userOrders');
    }
}
