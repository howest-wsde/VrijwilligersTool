<?php

namespace AppBundle\Entity\Form;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // TODO: checken of dit niet beter gebruikt kan worden voor een aantal van de Number velden

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array(
                "label" => "contact.label.name",
                "attr" => array("placeholder" => "contact.placeholder.name"),
                "required" => false,
            ))
            ->add("email", EmailType::class, array(
                "label" => "contact.label.email",
                "attr" => array("placeholder" => "contact.placeholder.email"),
                "required" => false,
            ))
            ->add("telephone", TextType::class, array(
                "label" => "contact.label.telephone",
                "attr" => array("placeholder" => "contact.placeholder.telephone"),
                "required" => false,
            ))
            ->add("description", TextareaType::class, array(
                "label" => "contact.label.description",
                "attr" => array("placeholder" => "contact.placeholder.description"),
                "required" => false,
            ))
            ->add("submit", SubmitType::class, array(
                "label" => "contact.label.create",
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "translation_domain" => "validators",
            "data_class" => "AppBundle\Entity\Contact",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
