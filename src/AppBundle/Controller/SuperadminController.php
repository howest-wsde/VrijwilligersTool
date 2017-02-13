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
     * @Route("/admin", name="superadmin_main")
     * @Route("/admin/users", name="superadmin_users")
     */
    public function adminUsersAction()
    {
        $user = $this->getUser();
        if(!$user || !$user->getSuperadmin()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $persons = $this->getDoctrine()
            ->getRepository("AppBundle:Person")
            ->findBy(array(), array('id' => 'DESC'));

        return $this->render('superadmin/persons.html.twig', [
            'persons' => $persons,
        ]);
    }

    /**
     * @Route("/admin/organisations", name="superadmin_organisations")
     */
    public function adminOrganisationsAction()
    {
        $user = $this->getUser();
        if(!$user || !$user->getSuperadmin()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $organisations = $this->getDoctrine()
            ->getRepository("AppBundle:Organisation")
            ->findBy(array("deleted"=>0), array('id' => 'DESC'));

        return $this->render('superadmin/organisations.html.twig', [
            'organisations' => $organisations,
        ]);
    }

    /**
     * @Route("/admin/{organisation}/vacancies", name="superadmin_organisation_vacancies")
     * @Route("/admin/vacancies", name="superadmin_vacancies", defaults={"organisation" = ""})
     */
    public function adminVacanciesAction($organisation)
    {
        $user = $this->getUser();
        if(!$user || !$user->getSuperadmin()) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        if ($organisation != "") {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Organisation')->findOneByUrlid($organisation);
            $vacancies = $this->getDoctrine()
                ->getRepository("AppBundle:Vacancy")
                ->findBy(array("organisation"=>$entity), array('id' => 'DESC'));
        } else {
            $vacancies = $this->getDoctrine()
                ->getRepository("AppBundle:Vacancy")
                ->findBy(array(), array('id' => 'DESC'));
        }


        return $this->render('superadmin/vacancies.html.twig', [
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

        $photoUrl = "";

        $em = $this->getDoctrine()->getManager();
        switch($type) {
            case "person":
                $entity = $em->getRepository('AppBundle:Person')->findOneByUsername($urlid);
                $form = $this->createForm(SuperadminPersonType::class, $entity);
                $redirectPath = $this->generateUrl("superadmin_users");
                $photoUrl = 'users/'.$entity->getAvatarName();
                break;
            case "organisation":
                $entity = $em->getRepository('AppBundle:Organisation')->findOneByUrlid($urlid);
                $form = $this->createForm(SuperadminOrganisationType::class, $entity);
                $redirectPath = $this->generateUrl("superadmin_organisations");
                $photoUrl = 'organisations/'.$entity->getLogoName();
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
            'form' => $form->createView(),
            'photoUrl' => $photoUrl

        ]);

    }


}
