<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use DateTime;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    //creation d'une fonction statique d'affichage
    static function print_q($val){
        echo "<pre style='background-color:#000;color:#3FBBD5;font-size:11px;z-index:99999;position:relative;'>";
        print_r($val);
        echo "</pre>";
    }


    /**
     * @Route("/home", name="app_home")
     */
    public function displayAnnee(EntityManagerInterface $entityManager): response
    {
        //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Article::class);

        $article = $repository->findDistinct();
       // self::print_q($article);
        return $this->render('home/index.html.twig', [
            'List_annee' => $article
        ]);
    }
   /**
     * @Route("/annee/{val}", name="app_annee")
     */

   public function displayArticleAnne($val,EntityManagerInterface $entityManager): response
    {

      //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Article::class);
        //appel de la fonction pour effectuer un select all dans la class repository find est par defaut
        $article = $repository->findValue($val);
       // self::print_q($article);
        return $this->render('article/AnneeArticle.html.twig', [
            'list_article_annee' => $article,
        ]);

    }
    /**
     * @Route("/titre/{val}", name="app_titre")
     */
    public function displayArticle($val,EntityManagerInterface $entityManager): response
    {

        //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Article::class);
        //appel de la fonction pour effectuer un select all dans la class repository find est par defaut
        $article = $repository->findByTitle($val);
        /**/
        $article=$article[0];
       // self::print_q($article);
        return $this->render('article/ArticleDescription.html.twig', [
            'article' => $article,
        ]);

    }
}