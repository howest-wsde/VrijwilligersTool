<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Form\SuperadminPersonType;
use AppBundle\Entity\Form\SuperadminOrganisationType;
use AppBundle\Entity\Form\SuperadminVacancyType;
use AppBundle\Entity\Person;
use AppBundle\Entity\Organisation;
use AppBundle\Entity\Vacancy;

class SuperadminController extends Controller
{
    /**
     * @Route("/admin", name="superadmin_main", defaults={"personcount" = 1000} )
     * @Route("/admin/users", name="superadmin_users", defaults={"personcount" = 1000})
     * @Route("/admin/organisations", name="superadmin_organisations", defaults={"organisationcount" = 1000})
     * @Route("/admin/vacancies", name="superadmin_vacancies", defaults={"vacancycount" = 1000})
     */
    public function adminAction($personcount=0, $organisationcount=0, $vacancycount=0)
    {
        $user = $this->getUser();
        if(!$user || !$user->getSuperadmin()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $persons = ($personcount > 0)?$this->getDoctrine()
            ->getRepository("AppBundle:Person")
            ->findBy(array(), array('id' => 'DESC'), $personcount):Array();

        $organisations = ($organisationcount > 0)?$this->getDoctrine()
            ->getRepository("AppBundle:Organisation")
            ->findBy(array(), array('id' => 'DESC'), $organisationcount):Array();

        $vacancies = ($vacancycount > 0)?$this->getDoctrine()
            ->getRepository("AppBundle:Vacancy")
            ->findBy(array(), array('id' => 'DESC'), $vacancycount):Array();

        return $this->render('superadmin/index.html.twig', [
            'persons' => $persons,
            'organisations' => $organisations,
            'vacancies' => $vacancies
        ]);
    }


    /**
     * @Route("/admin/user/{urlid}", name="superadmin_user", defaults={"type" = "person"})
     * @Route("/admin/organisation/{urlid}", name="superadmin_organisation", defaults={"type" = "organisation"})
     * @Route("/admin/vacancy/{urlid}", name="superadmin_vacancy", defaults={"type" = "vacancy"})
     */
    public function adminChangeAction(Request $request, $urlid, $type)
    {
        $t = $this->get('translator');

        $user = $this->getUser();
        if(!$user || !$user->getSuperadmin()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $em = $this->getDoctrine()->getManager();
        switch($type) {
            case "person":
                $entity = $em->getRepository('AppBundle:Person')->findOneByUsername($urlid);
                $form = $this->createForm(SuperadminPersonType::class, $entity);
                $redirectPath = $this->generateUrl("superadmin_users");
                break;
            case "organisation":
                $entity = $em->getRepository('AppBundle:Organisation')->findOneByUrlid($urlid);
                $form = $this->createForm(SuperadminOrganisationType::class, $entity);
                $redirectPath = $this->generateUrl("superadmin_organisations");
                break;
            case "vacancy":
                $entity = $em->getRepository('AppBundle:Vacancy')->findOneByUrlid($urlid);
                $form = $this->createForm(SuperadminVacancyType::class, $entity);
                $redirectPath = $this->generateUrl("superadmin_vacancies");
                break;
            default:
                return $this->redirect($this->generateUrl('superadmin_main'));
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entity = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //set a success message
            $this->addFlash('approve_message', $t->trans('general.flash.formOK'));

            return $this->redirect($redirectPath);
        }
        else if ($form->isSubmitted() && !$form->isValid())
        {
            //set an error message
            $this->addFlash('error', $t->trans('general.flash.formError'));

           // $this->addFlash('error', (string) $form->getErrors() );

        }

        return $this->render('superadmin/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }


}
