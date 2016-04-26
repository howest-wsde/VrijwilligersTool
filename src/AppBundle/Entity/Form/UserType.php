<?php
namespace AppBundle\Entity\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, array('label' => 'Familienaam'))
            ->add('firstname', TextType::class, array('label' => 'Voornaam'))
            ->add('username', TextType::class, array('label' => 'Username'))
            ->add('email', EmailType::class, array('label' => 'E-mailadres,'))
            ->add('street', TextType::class, array('label' => 'Straat'))
            ->add('number', NumberType::class, array('label' => 'Nummer'))
            ->add('postalcode', NumberType::class, array('label' => 'Postcode'))
            ->add('bus', NumberType::class, array('label' => 'bus (indien van toepassing)',
                                                  'required' => false))
            ->add('city', TextType::class, array('label' => 'Stad/gemeente'))
            ->add('telephone', TextType::class, array('label' => 'Telefoonnummer'))
            ->add('linkedin_url', TextType::class, array('label' => 'link naar uw LinkedIn profiel'))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Wachtzin'),
                'second_options' => array('label' => 'Herhaal wachtzin'),
            ))
            ->add('termsAccepted', CheckboxType::class, array(
                'mapped' => false,
                'constraints' => new IsTrue(),
                'label' => 'Ik ga akkoord met de overeenkomst'
            )
        );
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Person',
        ));
    }
}