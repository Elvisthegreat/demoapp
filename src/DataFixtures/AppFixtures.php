<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName('Sample Product');
        $product->setDescription('This is a sample product description.');
        $product->setSize(100);

        $manager->persist($product);


        $product = new Product();
        $product->setName("Product 2");
        $product->setDescription("This is the second product.");
        $product->setSize(200);

        $manager->persist($product);

        $manager->flush();
    }
}
