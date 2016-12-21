<?php

namespace AppBundle\Entity\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;



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
                'search.choices.date' => 'startdate',
                'search.choices.endDate' => 'enddate',
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
        ->add('estimatedWorkInHours', IntegerType::class, array(
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

        $builder->get('intensity')
            ->addModelTransformer(new CallbackTransformer(
                function ($intensityAsString) {// transform the string to an array
                    $intensity = [
                        'long' => false,
                        '1time' => false,
                    ];

                    $intensity[$intensityAsString] = true;

                    return $intensity;
                },
                function ($intensityAsArray) { // transform the array back to a string
                    $output = '';
                    if(array_key_exists('long', $intensityAsArray) && $intensityAsArray['long']) { $output = 'long'; }
                    else if(array_key_exists('1time', $intensityAsArray) && $intensityAsArray['1time']) { $output = '1time'; }

                    return $output;
                }
        ));
        $builder->get('person')
            ->addModelTransformer(new CallbackTransformer(
                function ($notBoolean) { //what you get from the entity
                    return $notBoolean === 1 ? true : false;
                },
                function ($boolean) { // what you get from the form
                    return $boolean ? 1 : 0;
                }
        ));
                $builder->get('org')
            ->addModelTransformer(new CallbackTransformer(
                function ($notBoolean) { //what you get from the entity
                    return $notBoolean === 1 ? true : false;
                },
                function ($boolean) { // what you get from the form
                    return $boolean ? 1 : 0;
                }
        ));
                $builder->get('vacancy')
            ->addModelTransformer(new CallbackTransformer(
                function ($notBoolean) { //what you get from the entity
                    return $notBoolean === 1 ? true : false;
                },
                function ($boolean) { // what you get from the form
                    return $boolean ? 1 : 0;
                }
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
