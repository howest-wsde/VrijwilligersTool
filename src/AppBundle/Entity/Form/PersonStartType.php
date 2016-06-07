<?php

namespace AppBundle\Entity\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\IsTrue;

class PersonStartType extends AbstractType
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
            ->add("email", EmailType::class, array(
                "label" => "person.label.email",
                "attr" => array("placeholder" => "person.label.email"),
                "required" => false
            ))
            ->add("telephone", TextType::class, array(
                "label" => "person.label.telephone",
                "attr" => array("placeholder" => "person.placeholder.telephone"),
                "required" => false
            ))
            ->add('users', EntityType::class, array(
                // query choices from this entity
                'class' => 'AppBundle:Organisation',
                // use the name property as the visible option string
                'choice_label' => 'name',
                // render as select box
                'expanded' => false,
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
            ->add("submit", SubmitType::class, array(
                "label" => "person.label.submitStart",
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
