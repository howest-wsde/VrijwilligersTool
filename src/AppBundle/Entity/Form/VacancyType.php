<?php

namespace AppBundle\Entity\Form;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Component\Form\Extension\Core\Type\CollectionType; 
use Symfony\Component\Form\Extension\Core\Type\DateType; 
use Symfony\Component\Form\Extension\Core\Type\TextareaType; 
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VacancyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, array(
                "label" => "vacancy.label.title",
                "attr" => array("placeholder" => "vacancy.label.title")
            ))
            ->add("description", TextareaType::class, array(
                "label" => "vacancy.label.description",
                "attr" => array("placeholder" => "vacancy.label.description")
            ))
            ->add("enddate", DateType::class, array(
                "label" => "vacancy.label.enddate",
                "attr" => array("placeholder" => "vacancy.placeholder.date"),
                "widget" => "single_text"
            ))
            ->add("submit", SubmitType::class, array(
                "label" => "vacancy.label.submit",
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "translation_domain" => "validators",
            "data_class" => "AppBundle\Entity\Vacancy",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
