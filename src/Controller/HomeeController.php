<?php
// src/Controller/HomeController.php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\TypeArticle;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\TypeArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeeController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, TypeArticleRepository $typeArticleRepo): Response
    {
        $type = $typeArticleRepo->findOneBy(['type'=>'Accueil']);
        $texteAccueil = $articleRepository->findBy(['typeArticle'=>$type]);
        return $this->render(
            'home/index.html.twig',
            ['texteAccueil' => $texteAccueil]
        );
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $articleFranchise): Response
    {
        $formArticleHome = $this->createForm(ArticleType::class, $articleFranchise);
        $formArticleHome->handleRequest($request);

        if ($formArticleHome->isSubmitted() && $formArticleHome->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $articleFranchise,
            'form' => $formArticleHome->createView(),
        ]);
    }
}
