<?php
<<<<<<< HEAD

namespace AppBundle\Entity\Form;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    ChoiceType,
    CollectionType,
    DateType,
    TextareaType,
    TextType,
    SubmitType
};
=======
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 3/17/16
 * Time: 12:01 PM
 */

namespace AppBundle\Entity\Form;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
>>>>>>> master

class VacancyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
<<<<<<< HEAD
            ->add("title", TextType::class, array(
                "label" => "vacancy.label.title",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "vacancy.label.title")
            ))
            ->add("description", TextareaType::class, array(
                "label" => "vacancy.label.description",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "vacancy.label.description")
            ))
            ->add("startdate", DateType::class, array(
                "label" => "vacancy.label.startdate",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "vacancy.placeholder.date"),
                "widget" => "single_text"
            ))
            ->add("enddate", DateType::class, array(
                "label" => "vacancy.label.enddate",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "vacancy.placeholder.date"),
                "widget" => "single_text"
            ))
            ->add("submit", SubmitType::class, array(
                "label" => "vacancy.label.submit",
                "translation_domain" => "validators",
            ));
=======
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('startdate', DateTimeType::class)
            ->add('enddate', DateTimeType::class)
            ->add('organisation', EntityType::class, array(
                'class' => 'AppBundle\Entity\Organisation',
                'choice_label' => 'name',
                'label' => 'Organisatie'
            ))
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle\Entity\Vacancycategory',
                'label' => 'categoriën'
            ))
            ->add('skillproficiency', CollectionType::class);
>>>>>>> master
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
<<<<<<< HEAD
            "data_class" => "AppBundle\Entity\Vacancy",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
=======
            'data_class' => 'AppBundle\Entity\Vacancy',
>>>>>>> master
        ));
    }
}
