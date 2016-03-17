<?php
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 3/17/16
 * Time: 12:01 PM
 */

namespace AppBundle\Entity\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacancyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('stardate', \DateTime::class)
            ->add('enddate', \Datetime::class)
            ->add('organisation', TextType::class)
            ->add('category', ChoiceType::class, array(
                'choices'  => array(
                    'eerste',
                    'tweede',
                    'derde')))
            ->add('skillproficiency', \Doctrine\Common\Collections\Collection::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Vacancy',
        ));
    }
}