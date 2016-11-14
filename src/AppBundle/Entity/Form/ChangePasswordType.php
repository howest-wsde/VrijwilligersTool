<?php

namespace AppBundle\Entity\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldPassword', PasswordType::class, array('label' =>'Oud wachtwoord'));
        $builder->add('newPassword', RepeatedType::class,
            array(
            'type' => PasswordType::class,
            'invalid_message' => 'De paswoorden komen niet overeen',
            'required' => true,
            'first_options'  => array('label' => 'Nieuw wachtwoord'),
            'second_options' => array('label' => 'Herhaal nieuw wachtwoord'),
        ))
        ->add('submit', SubmitType::class, array('label' => 'Versturen'));
    }

}