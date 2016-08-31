<?php

namespace AppBundle\Entity\Form;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addAdmin', EntityType::class, array(
                'label' => "organisation.label.addAdmin",
                "placeholder" => "organisation.placeholder.addAdmin",
                // query choices from this entity
                'class' => 'AppBundle:Person',
                'query_builder' => function (EntityRepository $er)
                    use ($options) {
                        return $er->createQueryBuilder('p')
                            ->where('p.id not in (' . $options['administrators']
                                . ')')
                            ->andWhere('p.username != :empty')->setParameter('empty', serialize([]))
                            ->andWhere('p.username != :null')->setParameter('null', 'N;')
                            ->orderBy('p.username', 'ASC');
                    },
                    // use the name property as the visible option string
                    'choice_label' => 'username',
                    // render as select box
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'placeholder' => false,
            ))
            ->add("submit", SubmitType::class, array(
                "label" => "organisation.label.create",
                "validation_groups" => array("firstStep"),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "translation_domain" => "validators",
            "data_class" => "AppBundle\Entity\Person",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
