<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Form\PersonType;
use AppBundle\Entity\Form\EditPersonType;
use AppBundle\Entity\Organisation;
use AppBundle\Entity\Vacancy;
use AppBundle\Entity\Form\VacancyType;

class PersonController extends UtilityController
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/persoon/{username}", name="person_username")
     */
    public function personViewAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')
            ->findOneByUsername($username);
        return $this->render('person/persoon.html.twig',
            ["person" => $person]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/persoon/{id}", name="person_id")
     */
    public function personViewByIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')
            ->findOneById($id);
        return $this->render('person/persoon.html.twig',
            ["person" => $person]);
    }

    /**
     * @Route("/persoon", name="self_profile")
     */
    public function selfAction()
    {
        // logged in
        if ($this->get('security.authorization_checker')
        ->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute("person_username",
            ["username" => $this->getUser()->getUsername()]);
        }
        else //not logged in
        {
            return $this->redirectToRoute("login");
        }
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/mijnprofiel", name="profile_edit")
     */
    public function editProfileAction(Request $request){
        $t = $this->get('translator');
        $person = $this->getUser();
        $form = $this->createForm(EditPersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            if(empty($person->getUsername())){
                $person->setUsername('');
            }
            $this->setCoordinates($person);
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            //recreate form so the changes are visible
            $form = $this->createForm(EditPersonType::class, $person);

            //set a success message
            $this->addFlash('approve_message', $t->trans('person.flash.editProfile'));
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            //set an error message
            $this->addFlash('error', $t->trans('general.flash.formError'));
        }

        return $this->render("person/edit_profile.html.twig", array("form" => $form->createView() ));
    }

    /**
     * Get volunteers matching a vacancy profile
     * @param  integer $id   id for the vacancy for which the volunteers have to be retrieved
     */
    public function vrijwilligersOpMaatAction($id)
    {
        $vacancy = $this->getDoctrine()->getManager()->getRepository("AppBundle:Vacancy")->findOneById($id);
        $es = $this->get("ElasticsearchQuery");

        $query = '{
            "query": {
                "function_score": {
                    "filter": {
                      "bool": {
                        "must": [';

        $must = '';
        if(!$vacancy->getAccess()){ //if vacancy does not provide access, filter out users that don't need it
            $must .= '{ "term": { "access": false }}';
        }

        if($vacancy->getLongterm()){ //if vacancy is longterm, filter out users that are willing to do longterm volunteering
            empty($must) ? false : $must .= ',';
            $must .= '{ "term": { "longterm": true }}';
        }

        if($vacancy->getRenumeration() == 0){ //if no pay is provided, filter out users that don't require payment
            empty($must) ? false : $must .= ',';
            $query .= '{ "term": { "renumerate": false }}';
        }

        $query .= $must . '     ]
                      }
                    },
                    "functions": [';

        if($vacancy->getLatitude() && $vacancy->getLongitude()){ //proximity boost
            $query .= '{
                "filter": {
                    "exists": {
                        "field": "location"
                    }
                },
                "gauss":{
                    "location":{
                        "origin": { "lat": ' . $vacancy->getLatitude() . ', "lon": ' . $vacancy->getLongitude() . ' },
                        "offset": "1km",
                        "scale": "1km"
                    }
                },
                "weight": 2
            }';
        }

        //boost (sliding scale) as per work load
        $estimatedWorkInHours = $vacancy->getEstimatedWorkInHours();
        if(!empty($estimatedWorkInHours)){ //boost on work time
            $query .= ',{
                "filter": {
                    "exists": {
                        "field": "estimatedWorkInHours"
                    }
                },
                "gauss":{
                    "estimatedWorkInHours":{
                        "origin": ' . ($estimatedWorkInHours / 2) . ',
                        "offset": ' . ($estimatedWorkInHours / 2) . ',
                        "scale": 1
                    }
                }
            }';
        }

        //boost as per social interaction preference
        $query .= ',{
            "filter": {
                "term": {
                   "socialInteraction": "' . $vacancy->getSocialInteraction() . '"
                }
            },
            "weight": 1
        }';

        $vacancySkills = $vacancy->getSkills(); //boost per overlappende skill
        if(!is_null($vacancySkills) && !$vacancySkills->isEmpty()){
            foreach ($vacancySkills as $key => $skill) {
                $query .= ',{
                    "filter": {
                        "term": {
                           "skills.name": "' . $skill->getName() . '"
                        }
                    },
                    "weight": 1
                }';
            }
        }

        //boost indien vrijwilliger organisatie bewaarde
        $query .= ',{
            "filter": {
                "term": {
                   "liked_organisations.name": "' . $vacancy->getOrganisation()->getName() . '"
                }
            },
            "weight": 1
        }';

        //boost indien vrijwilliger admin is van de organisatie die de vacature plaatste
        $query .= ',{
            "filter": {
                "term": {
                   "organisations.name": "' . $vacancy->getOrganisation()->getName() . '"
                }
            },
            "weight": 0.5
        }';

        $vacancyId = $vacancy->getId();

        //boost indien vrijwilliger de vacature bewaarde
        //groot want deze vrijwilliger is niet onmiddelijk zichtbaar bij kandidaten
        $query .= ',{
            "filter": {
                "term": {
                   "liked_vacancies.id": ' . $vacancyId . '
                }
            },
            "weight": 3
        }';

        //boost indien vrijwilliger zich kandidaat stelde voor de vacature
        //klein want deze vrijwilliger is onmiddelijk zichtbaar bij de kandidaten
        $query .= ',{
            "filter": {
                "nested": {
                    "path": "candidacies",
                    "query": {
                        "filtered": {
                           "query": {},
                           "filter": {
                               "bool": {
                                   "must": [
                                      { "term": { "candidacies.state": 0 }},
                                      { "term": { "candidacies.vacancy.id": ' . $vacancyId . ' }}
                                   ]
                               }
                           }
                        }
                    }
                }
            },
            "weight": 1
        }';

        $query .= '],
               "score_mode": "sum"
               }
           }
       }';

       // $result = $es->requestByType($query, 'person');
       // var_dump($result);
       // exit();

       return $this->render("person/vrijwilligersOpMaat.html.twig",
       [
           'persons' => $es->requestByType($query, 'person')
       ]);
    }
}
