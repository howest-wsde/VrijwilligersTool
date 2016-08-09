<?php

namespace AppBundle\Entity\Form;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class SearchFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add("search", SearchType::class, array(
            "label" => false,
            "required" => false,
            "attr" => array("placeholder" => 'search.placeholder.searchTerm')
        ))
        ->add("submit", SubmitType::class, array(
            "label" => 'search.label.search',
        ))
        ->add('sort', ChoiceType::class, array(
            "label" => 'search.label.sort',
            'choices'  => array(
                'search.choices.distance' => 'distance',
                'search.choices.date' => 'date',
                'search.choices.endDate' => 'endDate',
                'search.choices.reward' => 'reward',
            ),
            // render as select box
            'expanded' => false,
            'multiple' => false,
            'required' => false,
        ))
        ->add("person", CheckboxType::class, array(
            "label" => 'search.label.person',
            "required" => false,
        ))
        ->add("org", CheckboxType::class, array(
            "label" => 'search.label.organisation',
            "required" => false,
        ))
        ->add("vacancy", CheckboxType::class, array(
            "label" => 'search.label.vacancy',
            "required" => false,
        ))
        ->add("sectors", EntityType::class, array(
            "label" => false,
            "placeholder" => false,
            // query choices from this entity
            'class' => 'AppBundle:Skill',
            //only pick skills that are childs of the sector skill
            'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('s')
                        ->where('s.parent = 36')
                        ->orderBy('s.name', 'ASC');
                },
            // use the name property as the visible option string
            'choice_label' => 'name',
            // render as select box
            'expanded' => true,
            'multiple' => true,
            'required' => false,
        ))
        ->add('categories', EntityType::class, array(
            'label' => false,
            // query choices from this entity
            'class' => 'AppBundle:Skill',
            // use the name property as the visible option string
            'choice_label' => 'name',
            // render as checkbox
            'expanded' => true,
            'multiple' => true,
            'required' => false,
            ))
        ->add('intensity', ChoiceType::class, array(
            'label' => false,
            'choices'  => array(
                'search.choices.long' => 'long',
                'search.choices.1time' => '1time',
            ),
            // render as checkbox
            'expanded' => true,
            'multiple' => true,
            'required' => false,
        ))
        ->add('hoursAWeek', IntegerType::class, array(
            'label' => 'search.label.hoursAWeek',
            'required' => false,
        ))
        ->add('distance', IntegerType::class, array(
            'label' => 'search.label.distance',
            'required' => false,
        ))
        ->add('characteristic', ChoiceType::class, array(
            'label' => false,
            'choices'  => array(
                'search.choices.weelchair' => 'weelchair',
                'search.choices.lotsContact' => 'lotsContact',
                'search.choices.littleContact' => 'littleContact',
            ),
            // render as checkbox
            'expanded' => true,
            'multiple' => true,
            'required' => false,
        ))
        ->add('advantages', ChoiceType::class, array(
            'label' => false,
            'choices'  => array(
                'search.choices.renumeration' => 'reward',
                'search.choices.other' => 'other',
            ),
            // render as checkbox
            'expanded' => true,
            'multiple' => true,
            'required' => false,
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "translation_domain" => "validators",
            "data_class" => "AppBundle\Entity\SearchFilter",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
