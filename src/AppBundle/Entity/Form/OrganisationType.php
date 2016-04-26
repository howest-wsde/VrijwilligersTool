<?php

namespace AppBundle\Entity\Form;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
<<<<<<< HEAD
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
=======
use Symfony\Component\Form\Extension\Core\Type\{
    ChoiceType,
    CollectionType,
    DateType,
    TextareaType,
    TextType,
    SubmitType,
    EmailType,
    NumberType
};
>>>>>>> 425577f92ba02987d807c89208595ae3766a9613

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array(
                "label" => "organisation.label.name",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "organisation.label.name")
            ))
            ->add("description", TextareaType::class, array(
                "label" => "organisation.label.description",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "organisation.label.description")
            ))
            ->add("email", EmailType::class, array(
                "label" => "organisation.label.email",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "organisation.placeholder.email")
            ))
            ->add("street", TextType::class, array(
                "label" => "organisation.label.street",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "organisation.label.street")
            ))
            ->add("number", NumberType::class, array(
                "label" => "organisation.label.number",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "organisation.label.number")
            ))
            ->add("bus", NumberType::class, array(
                "label" => "organisation.label.bus",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "organisation.label.bus"),
                "required" => false
            ))
            ->add("postalCode", NumberType::class, array(
                "label" => "organisation.label.postalcode",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "organisation.label.postalcode")
            ))
            ->add("city", TextType::class, array(
                "label" => "organisation.label.city",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "organisation.label.city")
            ))
            ->add("telephone", TextType::class, array(
                "label" => "organisation.label.telephone",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "organisation.placeholder.telephone")
            ))
            ->add("submit", SubmitType::class, array(
                "label" => "organisation.label.submit",
                "translation_domain" => "validators",
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AppBundle\Entity\Organisation",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
