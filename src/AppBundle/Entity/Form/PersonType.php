<?php

namespace AppBundle\Entity\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\IsTrue;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("firstname", TextType::class, array(
                "label" => "person.label.firstname",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "person.label.firstname")
            ))
            ->add("lastname", TextType::class, array(
                "label" => "person.label.lastname",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "person.label.lastname")
            ))
            ->add("username", TextType::class, array(
                "label" => "person.label.username",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "person.label.username")
            ))
            ->add("email", EmailType::class, array(
                "label" => "person.label.email",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "person.placeholder.email")
            ))
            ->add("street", TextType::class, array(
                "label" => "person.label.street",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "person.label.street")
            ))
            ->add("number", NumberType::class, array(
                "label" => "person.label.number",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "person.label.number")
            ))
            ->add("bus", NumberType::class, array(
                "label" => "person.label.bus",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "person.label.bus"),
                "required" => false
            ))
            ->add("postalcode", NumberType::class, array(
                "label" => "person.label.postalcode",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "person.label.postalcode")
            ))
            ->add("city", TextType::class, array(
                "label" => "person.label.city",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "person.label.city")
            ))
            ->add("telephone", TextType::class, array(
                "label" => "person.label.telephone",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "person.placeholder.telephone")
            ))
            ->add("linkedinUrl", TextType::class, array(
                "label" => "person.label.linkedin",
                "translation_domain" => "validators",
                "required" => false,
                "attr" => array("placeholder" => "person.placeholder.linkedin")
            ))
            ->add("plainPassword", RepeatedType::class, array(
                "translation_domain" => "validators",
                "type" => PasswordType::class,
                "first_options"  => array("label" => "person.label.password"),
                "second_options" => array("label" => "person.label.repeat_password"),
            ))
            ->add("termsAccepted", CheckboxType::class, array(
                "mapped" => false,
                "constraints" => new IsTrue(),
                "label" => "Ik ga akkoord met de overeenkomst"
            ))
            ->add("submit", SubmitType::class, array(
                "label" => "person.label.submit",
                "translation_domain" => "validators",
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AppBundle\Entity\person",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
