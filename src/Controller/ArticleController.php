<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    //creation d'une fonction statique d'affichage
    static function print_q($val)
    {
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
     * fonction de creation d'un objet article
     */
    public function articleObject(EntityManagerInterface $entityManager)
    {
        //reception de l'objet de la la class repository categorie
        $id = rand(3,6);
        $categorie = $entityManager->getRepository(Categorie::class)->find($id);

        $categorie->getNom();

       // dd($categorie);

        $article = new Article();
        $article->setTitre('article '.rand(1,20));
        $article->setDescription('the content is for you');
        $article->setDateCreation(new DateTime('18-05-2018'));
        $article->setCategorie($categorie);


        $entityManager->persist($article);
        $entityManager->flush();

        //redirige vers la page
        return $this->render('article/index.html.twig', [
            'controller_article' => 'Article',
        ]);

    }

    /**
     * @Route("/article", name="app_article")
     * fonction affichage de l'ensemble des articles
     */
    public function displayArticle(EntityManagerInterface $entityManager): response
    {

        //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Article::class);
        //appel de la fonction pour effectuer un select all  dans la class repository pour rendre compatible avec utilisation
        // de la meme vue que l'affichage par categorie retourne le meme type
        $article = $repository->findArticle();
       // dd($article);

        return $this->render('article/article.html.twig', [
            'list_article' => $article,
        ]);

    }

    /**
     * @Route("/article/{id}", name="app_description_article")
     * fonction d'affiche de la description d'un article via id
     */
    public function displayDetailArticle($id, EntityManagerInterface $entityManager): response
    {

        //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Article::class);
        //appel de la fonction pour effectuer un select all dans la class repository find est par defaut
        $article = $repository->find($id);
        //self::print_q($article);
        return $this->render('article/ArticleDescription.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/magique/{value}", name="app_magique_article")
     * fonction d'affichage d'un ensemble d'article contenant un mot cible dans le contenu ici magique
     */
    public function displayMagiqueArticle($value, EntityManagerInterface $entityManager): response
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

    /**
     * @Route("/article/afficher/{id}", name="afficherById")
     * fonction automatique pour afficher un article avec description precis via à id en passant pas la class en direct
     */
    public function afficherById(Article $article): Response
    {
       // self::print_q($article);
        return $this->render('article/ArticleDescription.html.twig', [
            'article' => $article,
        ]);
    }
    /**
     * @Route("/vote/{id}/voter", name="article_vote",methods="POST")
     */
    public function vote($id,Article $article,Request $request,EntityManagerInterface $entityManager )
    {
        // afficher l'article et le contenu de a requête
        //dd($article, $request->request->all());

        // récupérer la valeur de l'action'via l'objet request
        $action = $request->request->get('action');

        if($action === 'add'){
            $article->addVote($article->setVotes($article->getVotes()+1));
        }
        elseif ($action === 'remove'){
            $article->downVote($article->setVotes($article->getVotes()-1));
        }
        $entityManager->flush();
        // redirige vers la route d’affichage d’un article
        return $this->redirectToRoute('afficherById', ['id' => $article->getId()]);
    }
    /**
     * @Route("/articleCategorie", name="app_article_categorie")
     * fonction de creation de catégorie
     */
    public function addCategorie(EntityManagerInterface $entityManager){
        $categorie = new Categorie();
        $categorie->setNom('categorie '.rand(6,100));

       // self::print_q($categorie);
        $entityManager->persist($categorie);
        $entityManager->flush();

        //redirige vers la page
        return $this->render('article/categorie.html.twig', [
            'categorie' => 'Categorie',
        ]);
    }
    /**
     * @Route("/Categorie", name="list_categorie")
     * fonction d'affichage de l'ensemble de la categorie passe par la class categorie execute une requete findAll()
     * renvoie un tableau de données reçus de la bd via la requete
     */

    public function displayCategorie(EntityManagerInterface $entityManager): response
    {

        //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Categorie::class);
        //appel de la fonction pour effectuer un select all dans la class repository findall est par defaut
        $categorie = $repository->findAll();


        return $this->render('article/listCategorie.html.twig', [
            'list_categorie' => $categorie,
        ]);

    }
    /**
     * @Route("/Categorie/{id}", name="list_categorie_article")
     *
     */
    public function showListCategArt(EntityManagerInterface $entityManager, int $id): Response
    {
        //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Article::class);
        //appel de la fonction pour effectuer un select all dans la class repository find est par defaut
        $article = $repository->findOneByIdJoinedToCategory($id);
       // dd($article);
        return $this->render('article/article.html.twig', [
            'list_article' => $article,
        ]);
    }
    /**
     * @Route("/formAddArticle", name="app_form_art")
     *
     */
    public function add(Request $request):response
    {
        //creation d'un nouvel objet article vide
        $article = new Article();
        //creation d'une nouvelle date dans l'objet article
        $article->setDateCreation(new DateTime());
        //creation du formulaire
        /*definition dy type formulaire provenant de la class ArticleType et dans quel il doit etre creer*/
        $formArticle = $this->createForm(ArticleType::class,$article);


        $formArticle->handleRequest($request);
        /*si le formulaire est remplis il envoie les datas a la bd et affiche le nouvel article  sinon il afffiche le formulaire*/
        if ($formArticle->isSubmitted() && $formArticle->isValid()) {

            $article = $formArticle->getData();
            // ... perform some action, such as saving the task to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            //on regirige vers la page de vie article par son id afin de visualiser l'article crée
            return $this->redirectToRoute('afficherById', [
                'id' => $article->getId()
            ]);
        }
        return $this->render('article/add.html.twig', [
            'formArticle' => $formArticle->createView()
        ]);
    }
    /**
     * @Route("/formAddCategorie", name="app_form_categ")
     *
     */
    public function addFormCateg(Request $request):response
    {

        //creation d'un new objet categorie
        $categorie = new Categorie();

        //creation du formulaire
        $formCategorie =$this->createForm(CategorieType::class,$categorie);

        // On récupère la requête du formulaire
        $formCategorie->handleRequest($request);

        /*si le formulaire est remplis il envoie les datas a la bd et affiche le nouvel article  sinon il afffiche le formulaire*/
        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {

            $categorie = $formCategorie->getData();
            // ... perform some action, such as saving the task to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();
            //on regirige vers la page de vie article par son id afin de visualiser l'article crée
            return $this->redirectToRoute('list_categorie', [
                'id' => $categorie->getId()
            ]);
        }
        return $this->render('article/addCateg.html.twig', [
            'formCategorie' => $formCategorie->createView()
        ]);

    }

}
