<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\ProductForm;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


final class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index')]
    public function index(ProductRepository $repository): Response
    {
        // Render the index template and pass the products to it
        return $this->render('product/index.html.twig', [
            'products' => $repository->findAll(),
        ]);
    }

    #[Route('/product/{id<\d+>}', name: 'product_show')]
    public function show(Product $product): Response
    {
        // Render the show template and pass the product
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    // Create a new product
    #[Route('/product/new', name: 'product_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductForm::class, $product);
        // Handle the form submission
        $form->handleRequest($request);
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {

            // Mark the product entity for saving in the database
            $manager->persist($product);

            // Save the product entity to the database
            $manager->flush();

            $this->addFlash('notice', 'Product created successfully!');

            return $this->redirectToRoute('product_show', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    // Edit an existing product
    #[Route('/product/{id<\d+>}/edit', name: 'product_edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(ProductForm::class, $product);

        // Handle the form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {

            // Save the product entity to the databaseafter editing
            $manager->flush();

            $this->addFlash('notice', 'Product updated successfully!');

            return $this->redirectToRoute('product_show', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form,
        ]);
    }

    // Delete a product
    #[Route('/product/{id<\d+>}/delete', name: 'product_delete')]
    public function delete(Request $request, Product $product, EntityManagerInterface $manager): Response
    {

        // Check if the request is a POST request
        if ($request->isMethod('POST')) {

            // Remove the product entity from the database
            $manager->remove($product);
            $manager->flush();

            // Add a flash message to notify the user
            $this->addFlash('notice', 'Product deleted successfully!');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/delete.html.twig', [
            'id' => $product->getId()
        ]);
    }
    
}
