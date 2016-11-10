<?php

namespace AppBundle\Entity\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class TestimonialType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $receiverType = $options["attr"]["receiverType"];
        $vacancy = $options["attr"]["vacancy"];

        $builder
            ->add("submit", SubmitType::class, array(
                "label" => "testimonial.label.submit",
                "validation_groups" => array("firstStep")
            ))
            ->add("value", TextareaType::class, array(
                "label" => "testimonial.label.content",
                "required" => true
            ));

        if ($receiverType === "person") {
            $builder
                ->add('receiver', ChoiceType::class, array(
                    "label" => "testimonial.label.to",
                    'placeholder' => "testimonial.placeholder.to",
                    'choices' => $this->changeKeysOfPersonArrayToFullName($vacancy->getVolunteers()),
                    "required" => true
                ));
        } else if ($receiverType === "vacancy") {
            $builder
                ->add("receiver", TextType::class, array(
                    "label" => "testimonial.label.to",
                    "required" => true,
                    "data" => $vacancy->getOrganisation()->getName(),
                    "disabled" => true
                ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "translation_domain" => "validators",
            "data_class" => "AppBundle\Entity\Testimonial",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }

    private function changeKeysOfPersonArrayToFullName($arrayOfPersons){
        for ($i = 0, $l = count($arrayOfPersons); $i < $l; $i++) {
            $person = $arrayOfPersons[$i];
            $arrayOfPersons[$person->getFullName()] = $person;
            unset($arrayOfPersons[$i]);
        }

        return $arrayOfPersons;
    }
}
