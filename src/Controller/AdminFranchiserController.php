<?php

namespace App\Controller;

use App\Repository\TypeArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 *
 * @Route("/adminFranchise", name="adminFranchise_")
 */
class AdminFranchiserController extends AbstractController
{
    /**
     * Require ROLE_ADMIN for only this controller method.
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @Route("/index", name="index")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, TypeArticleRepository $typeArticleRepo): Response
    {
        $type = $typeArticleRepo->findOneBy(['type'=>'Franchisé']);
        $texteFranchise = $articleRepository->findBy(['typeArticle'=>$type]);
        return $this->render('franchise/index.html.twig', [
                'articles' =>$texteFranchise,
            ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $articleFranchise = new Article();
        $formArticleFranchise = $this->createForm(ArticleType::class, $articleFranchise);
        $formArticleFranchise->handleRequest($request);

        if ($formArticleFranchise->isSubmitted() && $formArticleFranchise->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($articleFranchise);
            $entityManager->flush();

            return $this->redirectToRoute('adminReservation_index');
        }

        return $this->render('franchise/new.html.twig', [
            'article' => $articleFranchise,
            'ourFormFranchise' => $formArticleFranchise->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Article $articleFranchise): Response
    {
        return $this->render('franchise/show.html.twig', [
            'article' => $articleFranchise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $articleFranchise): Response
    {
        $formArticleFranchise = $this->createForm(ArticleType::class, $articleFranchise);
        $formArticleFranchise->handleRequest($request);

        if ($formArticleFranchise->isSubmitted() && $formArticleFranchise->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adminReservation_index');
        }

        return $this->render('franchise/edit.html.twig', [
            'article' => $articleFranchise,
            'adminFormFranchise' => $formArticleFranchise->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $articleFranchise): Response
    {
        if ($this->isCsrfTokenValid('delete' . $articleFranchise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($articleFranchise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adminReservation_index');
    }
}
