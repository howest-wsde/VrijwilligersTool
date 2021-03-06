<?php

namespace AppBundle\Entity\Form;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // TODO: checken of dit niet beter gebruikt kan worden voor een aantal van de Number velden

class SuperadminOrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array(
                "label" => "organisation.label.name",
                "attr" => array("placeholder" => "organisation.label.name"),
                "required" => false,
            ))
            ->add("slogan", TextType::class, array(
                "label" => "organisation.label.slogan",
                "attr" => array("placeholder" => "organisation.label.slogan"),
                "required" => false,
            ))
            ->add("logoFile", FileType::class, array(
                "label" => "organisation.label.logo",
                "required" => false,
            ))
            ->add("type", ChoiceType::class, array(
                'label' => 'organisation.label.type',
                'placeholder' => false,
                'choices'  => array(
                    'organisation.choices.ngo' => 'vzw',
                    'organisation.choices.fv' => 'fv',
                ),
                // render as select box
                'expanded' => true,
                'multiple' => false,
                'required' => false,
            ))
            ->add("intermediary", CheckboxType::class, array(
                "label" => 'organisation.label.intermediary',
                'required' => false,
                "attr" => array("info" => "Bent u een bemiddelingsorganisatie die het profiel van andere gebruikers beheert? Selecteer dan deze optie. "),
            ))
            ->add("description", TextareaType::class, array(
                "label" => "organisation.label.description",
                "attr" => array("placeholder" => "organisation.label.description"),
                "required" => false,
            ))
            ->add("email", EmailType::class, array(
                "label" => "organisation.label.email",
                "attr" => array("placeholder" => "organisation.placeholder.email"),
                "required" => false,
            ))
            ->add("street", TextType::class, array(
                "label" => "organisation.label.street",
                "attr" => array("placeholder" => "organisation.label.street"),
                "required" => false,
            ))
            ->add("number", NumberType::class, array(
                "label" => "organisation.label.number",
                "attr" => array("placeholder" => "organisation.label.number"),
                "required" => false,
            ))
            ->add("bus", NumberType::class, array(
                "label" => "organisation.label.bus",
                "attr" => array("placeholder" => "organisation.label.bus"),
                "required" => false,
            ))
            ->add("postalCode", NumberType::class, array(
                "label" => "organisation.label.postalcode",
                "attr" => array("placeholder" => "organisation.label.postalcode"),
                "required" => false,
            ))
            ->add("city", TextType::class, array(
                "label" => "organisation.label.city",
                "attr" => array("placeholder" => "organisation.label.city"),
                "required" => false,
            ))
            ->add("telephone", TextType::class, array(
                "label" => "organisation.label.telephone",
                "attr" => array("placeholder" => "organisation.placeholder.telephone"),
                "required" => false,
            ))
            ->add("website", UrlType::class, array(
                "label" => "organisation.label.website",
                "attr" => array("placeholder" => "organisation.placeholder.website"),
                "required" => false,
            ))
            ->add("deleted", CheckboxType::class, array(
                "label" => 'verwijderen',
                'required' => false,
                "attr" => array("info" => "Check deze box om de vereniging te verwijderen "),
            ))
            ->add("submitEnd", SubmitType::class, array(
                "label" => "general.label.save",
                "validation_groups" => array("thirdStep"),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "translation_domain" => "validators",
            "data_class" => "AppBundle\Entity\Organisation",
            "csrf_protection" => true,
            "csrf_field_name" => "_token",
            // a unique key to help generate the secret token
            "csrf_token_id"   => "id",
        ));
    }
}
