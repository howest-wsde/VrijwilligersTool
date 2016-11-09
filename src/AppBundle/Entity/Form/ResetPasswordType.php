<?php
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 10/7/16
 * Time: 9:54 PM
 */

namespace AppBundle\Entity\Form;


use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('newPassword', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password fields must match.',
            'required' => true,
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ));
    }
}