<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
class ArticleController extends AbstractController
{
    //creation d'une fonction statique d'affichage
    static function print_q($val){
        echo "<pre style='background-color:#000;color:#3FBBD5;font-size:11px;z-index:99999;position:relative;'>";
        print_r($val);
        echo "</pre>";
    }
    /**
     * @Route("/home_article", name="app_home_home")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("/articleObject", name="app_article_object")
     */
  public function articleObject(EntityManagerInterface $entityManager)
    {

        $article = new Article();
        $article->setTitre('article 5');
        $article->setDescription('the content is for you');
        $article->setDateCreation(new DateTime('18-05-2015'));

        $entityManager->persist($article);
        $entityManager->flush();

        //redirige vers la page
        return $this->render('article/index.html.twig', [
            'controller_article' => 'Article',
        ]);

    }
    /**
     * @Route("/article", name="app_article")
     */
    public function displayArticle(EntityManagerInterface $entityManager): response
    {

        //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Article::class);
        //appel de la fonction pour effectuer un select all dans la class repository findall est par defaut
        $article = $repository->findAll();
        //self::print_q($article);

       return $this->render('article/article.html.twig', [
            'list_article' => $article,
        ]);

    }
    /**
     * @Route("/article/{id}", name="app_description_article")
     */
    public function displayDetailArticle($id,EntityManagerInterface $entityManager): response
    {

        //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Article::class);
        //appel de la fonction pour effectuer un select all dans la class repository find est par defaut
        $article = $repository->find($id);
        self::print_q($article);
        return $this->render('article/ArticleDescription.html.twig', [
            'detail_article' => $article,
        ]);
    }

    /**
     * @Route("/magique/{value}", name="app_magique_article")
     */
  public function displayMagiqueArticle($value,EntityManagerInterface $entityManager): response
    {

        //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Article::class);
        //appel de la fonction pour effectuer un select all dans la class repository find est par defaut
        $article = $repository->findOneByLike($value);
        //self::print_q($article);
        return $this->render('article/magique.html.twig', [
            'magique_article' => $article,
        ]);
    }
}
