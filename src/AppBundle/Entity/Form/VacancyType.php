<?php

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
    SubmitType};

use Symfony\Component\Validator\Constraints\{
    Length,
    NotBlank,
    Date,
    GreaterThan,
    GreaterThanOrEqual};

class VacancyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, array(
                "label" => "vacancy.label.title",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "vacancy.label.title"),
                "constraints" => array(
                    new Length(array(
                        "min" => 4,
                        "max" => 100,
                        "minMessage" => "vacancy.title.min_message",
                        "maxMessage" => "vacancy.title.max_message"),
                    new NotBlank(array("message" => "vacancy.not_blank"))
            ))))
            ->add("description", TextareaType::class, array(
                "label" => "vacancy.label.description",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "vacancy.label.description"),
                "constraints" => array(
                    new Length(array(
                        "min" => 20,
                        "max" => 2000,
                        "minMessage" => "vacancy.description.min_message",
                        "maxMessage" => "vacancy.description.max_message"),
                    new NotBlank(array("message" => "vacancy.not_blank"))
            ))))
            ->add("startdate", DateType::class, array(
                "label" => "vacancy.label.startdate",
                "translation_domain" => "validators",
                "attr" => array("placeholder" => "vacancy.placeholder.date"),
                "widget" => "single_text",
                "constraints" => array(
                    new Date(array(
                        "message" => "vacancy.date.message"
                    ),
                    new GreaterThan("today")
            ))))
            ->add("enddate", DateType::class, array(
                "label" => "vacancy.label.enddate",
                "translation_domain" => "validators",
                "widget" => "single_text",
                "attr" => array("placeholder" => "vacancy.placeholder.date"),
                "constraints" => array(
                    new Date(array(
                        "message" => "vacancy.date.message"
                    ),
                    new GreaterThan("today"),
                    new GreaterThanOrEqual("startdate")
            ))))
            ->add("submit", SubmitType::class, array(
                "label" => "vacancy.label.submit",
                "translation_domain" => "validators",
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AppBundle\Entity\Vacancy",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
