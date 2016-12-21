<?php

namespace AppBundle\Entity\Form;

use Doctrine\ORM\EntityRepository;
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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EditPersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("firstname", TextType::class, array(
                "label" => "person.label.firstname",
                "attr" => array("placeholder" => "person.label.firstname")
            ))
            ->add("lastname", TextType::class, array(
                "label" => "person.label.lastname",
                "attr" => array("placeholder" => "person.label.lastname")
            ))
            ->add("avatarFile", FileType::class, array(
                "label" => "person.label.avatar",
                "required" => false,
                "attr" => array("accept" => "image/png, image/jpg, image/jpeg")
            ))
            ->add("email", EmailType::class, array(
                "label" => "person.label.email",
                "attr" => array("placeholder" => "person.label.email"),
                "required" => false
            ))
            ->add("telephone", TextType::class, array(
                "label" => "person.label.telephone",
                "attr" => array("placeholder" => "person.placeholder.telephone"),
                "required" => false
            ))
            ->add('contactOrganisation', EntityType::class, array(
                'label' => "person.label.organisation",
                "placeholder" => "person.placeholder.organisation",
                // query choices from this entity
                'class' => 'AppBundle:Organisation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->where('o.intermediary = true and o.deleted = false')
                        ->orderBy('o.name', 'ASC');
                },
                // use the name property as the visible option string
                'choice_label' => 'name',
                // render as select box
                'expanded' => false,
                'multiple' => false,
                'required' => false,
            ))
            ->add("plainPassword", PasswordType::class, array(
                "label" => "person.label.password",
                "attr" => array("placeholder" => "person.placeholder.password"),
                'required' => false
            ))
            ->add("username", TextType::class, array(
                "label" => "person.label.username",
                "attr" => array("placeholder" => "person.label.username",
                                "pattern" => "^[^ /]+$"),
                'required' => false,
            ))
            ->add("street", TextType::class, array(
                "label" => "person.label.street",
                "attr" => array("placeholder" => "person.label.street"),
                'required' => false,
            ))
            ->add("number", NumberType::class, array(
                "label" => "person.label.number",
                "attr" => array("placeholder" => "person.label.number"),
                'required' => false,
            ))
            ->add("bus", TextType::class, array(
                "label" => "person.label.bus",
                "attr" => array("placeholder" => "person.label.bus"),
                "required" => false
            ))
            ->add("postalcode", NumberType::class, array(
                "label" => "person.label.postalcode",
                "attr" => array("placeholder" => "person.label.postalcode"),
                'required' => false,
            ))
            ->add("city", TextType::class, array(
                "label" => "person.label.city",
                "attr" => array("placeholder" => "person.label.city"),
                'required' => false,
            ))
            ->add("linkedinUrl", TextType::class, array(
                "label" => "person.label.linkedin",
                "required" => false,
                "attr" => array("placeholder" => "person.placeholder.linkedin")
            ))
            ->add('skills', EntityType::class, array(
                "label" => "person.label.skills",
                "placeholder" => false,
                // query choices from this entity
                'class' => 'AppBundle:Skill',
                //only pick skills that are childs of the sector skill
                'query_builder' => function (EntityRepository $er){
                        return $er->createQueryBuilder('s')
                            ->where('s.parent != 36 or s.id != 36')
                            ->orderBy('s.parent, s.name', 'ASC');
                    },
                // use the name property as the visible option string
                'choice_label' => 'name',
                // render as select box
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ))
            ->add('access', ChoiceType::class, array(
                'label' => 'person.label.accessible',
                'placeholder' => false,
                'choices'  => array(
                    'person.choices.yes' => true,
                    'person.choices.no' => false,
                ),
                // render as radiobuttons
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add("renumerate", ChoiceType::class, array(
                "label" => "person.label.renumeration",
                "placeholder" => false,
                'choices'  => array(
                    'person.choices.yes' => true,
                    'person.choices.no' => false,
                ),
                // render as radiobuttons
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add("longterm", ChoiceType::class, array(
                "label" => "person.label.longterm",
                "placeholder" => false,
                'choices'  => array(
                    'person.choices.yes' => true,
                    'person.choices.no' => false,
                ),
                // render as radiobuttons
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add('estimatedWorkInHours', IntegerType::class, array(
                'label' => 'person.label.estimatedWorkInHours',
                'required' => false,
            ))
            ->add('socialInteraction', ChoiceType::class, array(
                'label' => 'person.label.socialInteraction',
                'placeholder' => false,
                'choices'  => array(
                    'person.choices.all' => 'all',
                    'person.choices.normal' => 'normal',
                    'person.choices.little' => 'little',
                ),
                // render as radiobuttons
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add("submit", SubmitType::class, array(
                "label" => "person.label.finish",
                "validation_groups" => array('edit'),
            ))
            ->add("submit2", SubmitType::class, array(
                "label" => "person.label.finish",
                "validation_groups" => array('edit'),
            ))
            ->add("digest", ChoiceType::class, array(
                'label' => 'person.label.mailPreference',
                'placeholder' => false,
                'choices'  => array(
                    'person.choices.immediately' => '1',
                    'person.choices.daily' => '2',
                    'person.choices.weekly' => '3',
                    'person.choices.monthly' => '4',
                    'person.choices.nomail' => '5',
                ),
                // render as radio buttons
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add("personalAlert", ChoiceType::class, array(
                'label' => 'person.label.alertPersonal',
                'placeholder' => false,
                'choices'  => array(
                    'person.alertsettings.no' => '0',
                    'person.alertsettings.yes' => '1',
                ),
                // render as radio buttons
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add("organisationAlert", ChoiceType::class, array(
                'label' => 'person.label.alertOrganisations',
                'placeholder' => false,
                'choices'  => array(
                    'person.alertsettings.no' => '0',
                    'person.alertsettings.yes' => '1',
                    'person.alertsettings.customorganisations' => '2',
                ),
                // render as radio buttons
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add("vacancyAlert", ChoiceType::class, array(
                'label' => 'person.label.alertVacancies',
                'placeholder' => false,
                'choices'  => array(
                    'person.alertsettings.no' => '0',
                    'person.alertsettings.yes' => '1',
                    'person.alertsettings.customvacancies' => '2',
                ),
                // render as radio buttons
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add("submit3", SubmitType::class, array(
                "label" => "person.label.finish",
                "validation_groups" => array('edit'),
            )); #http://symfony.com/doc/current/form/validation_groups.html
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "translation_domain" => "validators",
            "data_class" => "AppBundle\Entity\person",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
