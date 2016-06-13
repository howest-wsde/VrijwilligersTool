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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class VacancyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, array(
                "label" => "vacancy.label.title",
                "attr" => array("placeholder" => "vacancy.label.title"),
            ))
            ->add("summary", TextareaType::class, array(
                "label" => "vacancy.label.summary",
                "attr" => array("placeholder" => "vacancy.label.summary"),
            ))
            ->add("description", TextareaType::class, array(
                "label" => "vacancy.label.description",
                "attr" => array("placeholder" => "vacancy.label.description"),
            ))
            ->add("enddate", DateType::class, array(
                "label" => "vacancy.label.enddate",
                "attr" => array("placeholder" => "vacancy.placeholder.date"),
                "widget" => "single_text",
            ))
             ->add("wanted", IntegerType::class, array(
                "label" => 'vacancy.label.wanted',
                'required' => false,
            ))
            ->add("longterm", CheckboxType::class, array(
                "label" => 'vacancy.label.longterm',
                'required' => false,
            ))
            ->add('estimatedWorkInHours', IntegerType::class, array(
                'label' => 'vacancy.label.estimatedWorkInHours',
                'required' => false,
            ))
            ->add("street", TextType::class, array(
                "label" => "vacancy.label.street",
                "attr" => array("placeholder" => "vacancy.label.street"),
                "required" => false,
            ))
            ->add("number", NumberType::class, array(
                "label" => "vacancy.label.number",
                "attr" => array("placeholder" => "vacancy.label.number"),
                "required" => false,
            ))
            ->add("bus", NumberType::class, array(
                "label" => "vacancy.label.bus",
                "attr" => array("placeholder" => "vacancy.label.bus"),
                "required" => false,
            ))
            ->add("postalCode", NumberType::class, array(
                "label" => "vacancy.label.postalcode",
                "attr" => array("placeholder" => "vacancy.label.postalcode"),
                "required" => false,
            ))
            ->add("city", TextType::class, array(
                "label" => "vacancy.label.city",
                "attr" => array("placeholder" => "vacancy.label.city"),
                "required" => false,
            ))
            ->add('socialInteraction', ChoiceType::class, array(
                'label' => 'vacancy.label.socialInteraction',
                'placeholder' => false,
                'choices'  => array(
                    'vacancy.choices.all' => 'all',
                    'vacancy.choices.normal' => 'normal',
                    'vacancy.choices.little' => 'little',
                ),
                // render as radiobuttons
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add('independent', ChoiceType::class, array(
                'label' => 'vacancy.label.independent',
                'placeholder' => false,
                'choices'  => array(
                    'vacancy.choices.independent' => 'independent',
                    'vacancy.choices.dependent' => 'dependent',
                ),
                // render as radiobuttons
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add('skills', EntityType::class, array(
                'label' => "vacancy.label.skills",
                // query choices from this entity
                'class' => 'AppBundle:Skill',
                // use the name property as the visible option string
                'choice_label' => 'name',
                // render as select box
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ))
            ->add("renumeration", MoneyType::class, array(
                "label" => "vacancy.label.renumeration",
                'required' => false,
            ))
            ->add("otherReward", TextType::class, array(
                "label" => "vacancy.label.otherReward",
                'required' => false,
            ))
            ->add("submit", SubmitType::class, array(
                "label" => "vacancy.label.submit",
            ))
            ->add("save", SubmitType::class, array(
                "label" => "vacancy.label.save",
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
