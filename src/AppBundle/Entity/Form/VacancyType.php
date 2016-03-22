<?php
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 3/17/16
 * Time: 12:01 PM
 */

namespace AppBundle\Entity\Form;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('startdate', DateTimeType::class)
            ->add('enddate', DateTimeType::class)
            ->add('organisation', EntityType::class, array(
                'class' => 'AppBundle\Entity\Organisation',
                'choice_label' => 'name'
            ))
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle\Entity\Vacancycategory',
                'label' => 'categoriën'
            ))
            ->add('skillproficiency', CollectionType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Vacancy',
        ));
    }
}
