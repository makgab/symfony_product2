<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryType;
use App\Entity\Category;
use DateTime;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function indexCategory(  ManagerRegistry $doctrine  ): Response
    {
        // $category = $doctrine->getRepository( Category::class )->find(1);
        $category = $doctrine->getRepository( Category::class )->findAll();
        if ( !$category ) {
            // throw $this->createNotFoundException('No blog entry in database.');
            return $this->render('category/noindex.html.twig', [
                'category' => 'category',
            ]);
        } else {
            return $this->render( 'category/index.html.twig', [ 'category' => $category] );
        }
    }


    #[Route('/category/new', name: 'new_category')]
    public function newCategroy( ManagerRegistry $doctrine, Request $request ): Response
    {
        $entityManager = $doctrine->getManager();
        $cat = new Category();
        $cat->setCategory('');
        $cat->setDescription('');
        # FORM -------------------------------------------------------------------------
        $form = $this->createForm( CategoryType::class, $cat );
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            // get form data
            $category = $form->getData();
            // data to doctrine entitymanager
            $entityManager->persist( $category );
            // save data
            $entityManager->flush();
            return $this->redirectToRoute('new_category');
        }
        # -------------------------------------------------------------------------------
        return $this->render( 'category/new.html.twig', ['form'=>$form->createView()] );
    }


    #[Route('/category/del/{id}', name: 'del_category')]
    public function delCategory( ManagerRegistry $doctrine, Request $request, int $id ): Response
    {
        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository( Category::class )->find($id);

        if ( !$category ) {
            return $this->render('category/noindex.html.twig', [
                'category' => 'category',
            ]);
            exit();
        }
        # FORM -------------------------------------------------------------------------
        $form = $this->createForm( CategoryType::class, $category );
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            // data to doctrine entitymanager
            $entityManager->remove( $category );
            // save data
            $entityManager->flush();
            return $this->redirectToRoute('app_category');
        }
        # -------------------------------------------------------------------------------
        return $this->render( 'category/del.html.twig', ['form'=>$form->createView()] );
    }

    
    #[Route('/category/edit/{id}', name: 'edit_category')]
    public function updateCategory( ManagerRegistry $doctrine, Request $request, int $id ): Response
    {
        $entityManager = $doctrine->getManager();
        $category = $entityManager->getRepository( Category::class )->find($id);

        if ( !$category ) {
            return $this->render('category/noindex.html.twig', [
                'category' => 'category',
            ]);
            exit();
        }
        # FORM -------------------------------------------------------------------------
        $form = $this->createForm( CategoryType::class, $category );
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            // data to doctrine entitymanager
            $entityManager->persist( $category );
            // save data
            $entityManager->flush();
            return $this->redirectToRoute('app_category');
        }
        # -------------------------------------------------------------------------------
        return $this->render( 'category/edit.html.twig', ['form'=>$form->createView()] );
    }

    
 
}
