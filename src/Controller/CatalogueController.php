<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use SebastianBergmann\Environment\Console;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{
    private $session;
    private $em = null;

    #[Route('/', name: 'route_catalogue')]
    public function indexRoute(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->em = $doctrine->getManager();
        $session = $request->getSession();

        $category = $request->query->get('category');
        $searchField = $request->request->get('search_field');

        $categories = $this->retreiveAllCategories();
        $products = $this->retreiveProduct($category, $searchField);

        return $this->render('catalogue/catalogue.html.twig', [
            'products' => $products, 
            'categories' => $categories, 
            'cart'=> $session->get('cart')
        ]);
    }

    #[Route('/products/{idProduct}', name: 'product-modal')]
    public function infoProduct($idProduct, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->em = $doctrine->getManager();

        $product = $this->em->getRepository(Product::class)->find($idProduct);

        return $this->render('catalogue/product.modal.twig', ['product' => $product]);
    }

    private function retreiveAllCategories()
    {
        return $this->em->getRepository(Category::class)->findAll();
    }

    private function retreiveProduct($category, $searchField)
    {
        return $this->em->getRepository(Product::class)->findWithCriteria($category, $searchField);
    }
}
