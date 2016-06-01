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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\IsTrue;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("firstname", TextType::class, array(
                "label" => "person.label.firstname",
                "attr" => array("placeholder" => "person.label.firstname")
            ))
            ->add("lastname", TextType::class, array(
                "label" => "person.label.lastname",
                "attr" => array("placeholder" => "person.label.lastname")
            ))
            ->add("username", TextType::class, array(
                "label" => "person.label.username",
                "attr" => array("placeholder" => "person.label.username",
                                "pattern" => "^[^ /]+$")
            ))
            ->add("email", EmailType::class, array(
                "label" => "person.label.email",
                "attr" => array("placeholder" => "person.label.email"),
                "required" => false
            ))
            ->add("street", TextType::class, array(
                "label" => "person.label.street",
                "attr" => array("placeholder" => "person.label.street")
            ))
            ->add("number", NumberType::class, array(
                "label" => "person.label.number",
                "attr" => array("placeholder" => "person.label.number")
            ))
            ->add("bus", NumberType::class, array(
                "label" => "person.label.bus",
                "attr" => array("placeholder" => "person.label.bus"),
                "required" => false
            ))
            ->add("postalcode", NumberType::class, array(
                "label" => "person.label.postalcode",
                "attr" => array("placeholder" => "person.label.postalcode")
            ))
            ->add("city", TextType::class, array(
                "label" => "person.label.city",
                "attr" => array("placeholder" => "person.label.city")
            ))
            ->add("telephone", TextType::class, array(
                "label" => "person.label.telephone",
                "attr" => array("placeholder" => "person.placeholder.telephone"),
                "required" => false
            ))
            ->add("language", ChoiceType::class, array(
                "label" => "person.label.language",
                "attr" => array("placeholder" => "person.label.language"),
                "choices" => array(
                    "Nederlands" => "nl",
                    "English" => "en",
                )
            ))
            ->add("linkedinUrl", TextType::class, array(
                "label" => "person.label.linkedin",
                "required" => false,
                "attr" => array("placeholder" => "person.placeholder.linkedin")
            ))
            ->add("plainPassword", RepeatedType::class, array(
                "type" => PasswordType::class,
                "first_options"  => array(
                    "label" => "person.label.password",
                    "attr" => array("placeholder" => "person.placeholder.password")
                ),
                "second_options" => array(
                    "label" => "person.label.repeat_password",
                    "attr" => array("placeholder" => "person.placeholder.password")
                ),
            ))
            // ->add("termsAccepted", CheckboxType::class, array(
            //     "mapped" => false,
            //     "constraints" => new IsTrue(),
            //     "label" => "person.label.eula"
            // ))
            ->add("submit", SubmitType::class, array(
                "label" => "person.label.submit",
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "translation_domain" => "validators",
            "data_class" => "AppBundle\Entity\person",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
