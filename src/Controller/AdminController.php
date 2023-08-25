<?php

namespace App\Controller;

use App\Core\Notification;
use App\Core\NotificationColor;
use App\Entity\Category;
use App\Entity\Constants;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\CategoryCollection;
use App\Form\CategoryCollectionType;
use App\Form\OrderStatusType;
use App\Form\ProductType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted('ROLE_ADMIN', statusCode: 423)]
class AdminController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/admin/categories', name: 'route_adminCategories')]
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('route_profil');
        }

        $categories = $this->em->getRepository(Category::class)->findAll();

        $categoriesCollection = new CategoryCollection($categories);

        $formCategories = $this->createForm(CategoryCollectionType::class, $categoriesCollection);

        $formCategories->handleRequest($request);

        if ($formCategories->isSubmitted() && $formCategories->isValid()) {
            $newCategoriesCollection = $formCategories->getData()->getCategories();

            foreach ($newCategoriesCollection as $newCategory) {

                if ($newCategory->getCategory() != null) {
                    $this->em->persist($newCategory);
                }
            }

            $this->em->flush();
            $this->addFlash("categoryUpdate", new Notification("Success", "Update succesfull", NotificationColor::SUCCESS));
        }

        return $this->render('admin/adminCategories.html.twig', [
            'formCategories' => $formCategories,
        ]);
    }

    #[Route('/admin/products', name: 'route_adminProducts')]
    public function products(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('route_profil');
        }

        $products = $this->em->getRepository(Product::class)->findAll();
        return $this->render('admin/adminProducts.html.twig', 
            ['products' => $products]);
    }

    #[Route('/admin/products/add', name: 'route_adminAddProduct')]
    public function addProduct(Request $request, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('route_profil');
        }

        $newProduct = new Product();
        $formProduct = $this->createForm(ProductType::class, $newProduct);
        $formProduct->handleRequest($request);

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            
            $image = $formProduct->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFileName = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {

                    $image->move($this->getParameter('products_images_directory'), $newFileName);
                    $newProduct->setimagePath(Constants::PRODUCTS_IMAGES_PATH . $newFileName);

                } catch(FileException $e) {

                }
            }
            else {
                $newProduct->setimagePath(Constants::IMAGE_DEFAULT);
            }
            $this->em->persist($newProduct);
            $this->em->flush();

            return $this->redirectToRoute('route_adminModifyProduct', ['idProduct' => $newProduct->getIdProduct()]);
        }

        return $this->render('admin/adminAddProduct.html.twig', [
            'formProduct' => $formProduct,
        ]);
    }

    #[Route('/admin/products/{idProduct}', name: 'route_adminModifyProduct')]
    public function modifyProduct($idProduct, Request $request, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('route_profil');
        }

        $product = $this->em->getRepository(Product::class)->find($idProduct);
        $product->setNegativeQuantityToZero();

        $formProduct = $this->createForm(ProductType::class, $product);
        $formProduct->handleRequest($request);

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {

            $image = $formProduct->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFileName = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {

                    $image->move($this->getParameter('products_images_directory'), $newFileName);
                    $product->setimagePath(Constants::PRODUCTS_IMAGES_PATH . $newFileName);
                    
                } catch(FileException $e) {

                }
            }
            
            $this->em->persist($product);
            $this->em->flush();
            $this->addFlash("productUpdated", new Notification("Success", "Product updated succesfully", NotificationColor::SUCCESS));
        }

        return $this->render('admin/adminModifyProduct.html.twig', [
            'formProduct' => $formProduct,
        ]);
    }

    #[Route('/admin/orders', name: 'route_adminOrders')]
    public function orders(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('route_profil');
        }

        $orders = $this->em->getRepository(Order::class)->findBy([], ['orderDate'=>'DESC']);

        return $this->render('admin/adminOrders.html.twig', ['orders' => $orders]);
    }

    #[Route('/admin/orders/{idOrder}', name: 'route_adminUpdateOrders')]
    public function updateOrderStatus($idOrder, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('route_profil');
        }

        $order = $this->em->getRepository(Order::class)->find($idOrder);

        $formOrderStatus = $this->createForm(OrderStatusType::class, $order);

        $formOrderStatus->handleRequest($request);

        if ($formOrderStatus->isSubmitted() && $formOrderStatus->isValid()) {

            if ($order->isDelivered()) {
                $order->setDeliveryDate(new DateTime());
            } else {
                $order->setDeliveryDate();
            }

            $this->em->persist($order);
            $this->em->flush();

            $this->addFlash("stateUpdated", new Notification("Success", "State updated succesfully", NotificationColor::SUCCESS));
        }

        return $this->render('checkout/orderDetails.html.twig', ['order' => $order, 'formStatus' => $formOrderStatus]);
    }
}
