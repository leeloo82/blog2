<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
//use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
//use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
//use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /*assignation du type de chaque champs du formulaire*/
        $builder
            ->add('titre',TextType::class)
            ->add('description',TextType::class)
            ->add('DateCreation',DateType::class)
            ->add('categorie',EntityType::class,[
                /*va rechercher dans la class categorie tout les nom des categories*/
                'class'=>Categorie::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
