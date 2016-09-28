<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Testresult;

class TestController extends Controller
{
    /**
     * @Route("/tests", name="tests")
     */
    public function testsAction()
    {
        $t = $this->get('translator');
        $user = $this->getUser();
        $es = $this->get("ElasticsearchQuery");

        $access = $user->getAccess();
        $renumerate = $user->getRenumerate();

        $query = '{
            "query": {
                "function_score": {
                    "filter": {
                      "bool": {
                        "must": [
                           { "term": { "published": 1 }},';

        if($user->getAccess()){
            $query .= '{ "term": { "access": true }},';
        }

        $query .= '{ "term": { "longterm": ' . $user->getLongterm() . ' }},';

        if($user->getRenumerate()){
            $query .= '{ "exists": { "field": "renumerate" }}';
        }

        $query .= '     ]
                      }
                    },
                    "functions": [
                        {
                            "gauss":{
                                "startdate":{
                                    "origin": "now",
                                    "offset": "4w",
                                    "scale": "4w"
                                }
                            }
                        },';

        if($user->getLatitude() && $user->getLongitude()){
            $query .= '{
                            "filter": {
                                "exists": {
                                    "field": "location"
                                }
                            },
                            "gauss":{
                                "location":{
                                    "origin": { "lat": 50.9436034, "lon": 3.1242917 },
                                    "offset": "1km",
                                    "scale": "1km"
                                }
                            },
                            "weight": 2
                        },';
        }

        $estimatedWorkInHours = $user->getEstimatedWorkInHours();
        if($estimatedWorkInHours > 0){
            $query .= '{
                            "filter": {
                                "exists": {
                                    "field": "estimatedWorkInHours"
                                }
                            },
                            "gauss":{
                                "estimatedWorkInHours":{
                                    "origin": 4,
                                    "offset": 4,
                                    "scale": 1
                                }
                            }
                        },';
        }

        $query .= '{
                      "gauss": {
                        "likers": {
                            "origin": 50,
                            "scale": 5
                        }
                      }
                    },';

        $userSkills = $user->getSkills();
        if(!$userSkills->isEmpty()){
            foreach ($userSkills as $key => $skill) {
                $query .= '{
                                "filter": {
                                    "term": {
                                       "skills.name": "' . $skill->getName() . '"
                                    }
                                },
                                "weight": 1
                            },';
            }
        }

        $query .= '{
                        "filter": {
                            "term": {
                               "socialInteraction": "normal"
                            }
                        },
                        "weight": 2
                    },';

        $orgIds = $user->getLikedOrganisationIds();
        if(!empty($orgIds)){
            foreach ($orgIds as $key => $id) {
                $query .= '{
                            "filter": {
                                "term": {
                                   "organisation.id": ' . $id . '
                                }
                            },
                            "weight": 1
                           }';
            }
        }

        $query .= '],
                       "score_mode": "sum"
                       }
                   }
               }';

        return $this->render("vacancy/vacature_tab.html.twig",
            [
                'vacancies' => $es->requestByType($query, 'vacancy'),
                'title' => $t->trans('vacancy.template.vacancyFit')
            ]);
    }

    /**
     * @Route("/test-type-vrijwilliger", name="test_type")
     */
    public function testTypeOfVolunteerAction()
    {
        return $this->render("tests/type-vrijwilliger.html.twig");
    }


    /**
     * @Route("/doe-de-test/", defaults={"next" = 1}, name="test_test")
     * @Route("/doe-de-test/{next}", name="test_answer")
     */
    public function doeDeTestAction($next, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository('AppBundle:Testquestion')->findBy(
             array(),
             array('weight' => 'ASC')
        );

        $nr = 0;
        foreach ($questions as $question) {
            $question->setNr(++$nr);
            if ($nr == $next) $newquestion = $question;
        }

        $session = new Session();

        if ($request->isMethod('POST')) {
            if ($request->request->get("answer")) {

                $user = $this->getUser();
                $answer = $em->getRepository('AppBundle:Testanswer')->findOneById($request->request->get("answer"));

                $session->set('q' . $answer->getQuestion()->getId(), $answer);

                $testresult = new Testresult();
                $testresult->setPerson( $user);
                $testresult->setanswer( $answer );
                $em = $this->getDoctrine()->getManager();
                $em->persist($testresult);
                $em->flush();
            }
        }

        if (isset($newquestion)) {
            return $this->render('tests/doedetest.html.twig', ["question" => $newquestion]);
        } else {
            $session = $request->getSession();
            return $this->render('tests/testresult.html.twig', ["session" => $session->all()]);
        }


    }

    /**
     * @Route("/test-taalvaardigheden", name="test_taal")
     */
    public function testLanguageSkillsAction()
    {
        return $this->render("tests/taalvaardigheden.html.twig");
    }

    /**
     * @Route("/test-computervaardigheden", name="test_comp")
     */
    public function testCompSkillsAction()
    {
        return $this->render("tests/computervaardigheden.html.twig");
    }

    /**
     * @Route("/esperson", name="esperson")
     */
    public function testESPerson()
    {
        $request = Request::createFromGlobals();
        $searchTerm = $request->query->get("q");
        $query = $this->get("ElasticsearchQuery");
        $result = $query->searchForPerson($searchTerm);

        var_dump($result);
        exit();
    }

    /**
     * @Route("/espersonraw", name="espersonraw")
     */
    public function testESPersonRaw()
    {
        $request = Request::createFromGlobals();
        $searchTerm = $request->query->get("q");
        $query = $this->get("ElasticsearchQuery");
        $result = $query->searchForPerson($searchTerm);

        return new JSonResponse($query->searchForPersonRaw($searchTerm));
    }

    /**
     * @Route("/esfiltered", name="esfiltered")
     */
    public function testESFiltered()
    {
        $request = Request::createFromGlobals();
        $searchTerm = $request->query->get("q");
        $ESquery = $this->get("ElasticsearchQuery");


        $result = $ESquery->searchByType($types, $query, $term);

        var_dump();
        exit();
    }
}
