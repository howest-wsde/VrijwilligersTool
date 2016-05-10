<?php

namespace AppBundle\Entity\Form;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    CheckboxType,
    CollectionType,
    DateType,
    TextareaType,
    TextType,
    SubmitType
};

class SearchFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("term", TextType::class, array(
                "label" => "term",
                "required" => false,
                "attr" => array("placeholder" => "(optioneel) zoekterm")
            ))
            ->add("person", CheckboxType::class, array(
                "label" => "personen",
                "attr" => array("checked" => "checked"),
                "required" => false,
            ))
            ->add("organisation", CheckboxType::class, array(
                "label" => "verenigingen",
                "attr" => array("checked" => "checked"),
                "required" => false,
            ))
            ->add("vacancy", CheckboxType::class, array(
                "label" => "vacatures",
                "attr" => array("checked" => "checked"),
                "required" => false,
            ))
            ->add("submit", SubmitType::class, array(
                "label" => "Zoeken",
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "translation_domain" => "validators",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
