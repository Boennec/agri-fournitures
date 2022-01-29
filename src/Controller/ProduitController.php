<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/nos-produits", name="produits")
     */
    public function index(): Response
    {
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="produit")
     */
    public function show($slug): Response
    {
        $produit = $this->entityManager->getRepository(Produit::class)->findOneBySlug($slug);

        if(!$produit){

            return $this->redirectToRoute('produits');
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit
        ]);
    }
}
