<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Collections;
use AppBundle\Entity\Person;
use AppBundle\Entity\DigestEntry;

class DigestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('rv:digest')
             ->setDescription('Work through all digest entries of the corresponding periodicity.')
             ->addArgument(
                 'periodicity',
                 InputArgument::OPTIONAL,
                 'For what periodicity do you want to send the digest?  Defaults to daily.'
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();
        $digestrepo = $em->getRepository('AppBundle:DigestEntry');
        $templating = $container->get('templating');

        ///////////////////////////////////////
        //set periodicity to a correct value //
        ///////////////////////////////////////

        //first check whether a value was given on the command line
        $periodicity = $input->getArgument('periodicity') ? $input->getArgument('periodicity') : Person::DAILY;
        //then check whether the value is valid
        if(!(is_numeric($periodicity) && $periodicity > 1 && $periodicity < 5)){
            $periodicity = Person::DAILY;
            $output->writeln("Value for periodicity argument was invalid.  Please make sure to use an integer between 1 and 5 (so 2,3,4 are valid choices).  Given the invalid choice periodicity has been set to daily (=2).");
        }

        ////////////
        //legwork //
        ////////////

        //1. get a list of all admins for that periodicity in the table
        $query = $em->createQuery(
                    'SELECT distinct identity(de.user)
                    FROM AppBundle:DigestEntry de
                    WHERE de.periodicity = :periodicity'
                 )->setParameter('periodicity', $periodicity);

        //get results and retrieve admin id's from the returned multi-dimensional array
        $admins = $query->getArrayResult();
        $values = [];
        foreach ($admins as $key => $value) {
            $values[] = $value[1];
        }

        //process DE's for every admin
        foreach ($values as $key => $admin) {
            //1. retrieve all DE's of the right periodicity for that admin, sort by event type
            $query = $em->createQuery(
                        'SELECT de
                        FROM AppBundle:DigestEntry de
                        WHERE de.user = :admin and de.periodicity = :periodicity
                        ORDER BY de.event, de.organisation, de.vacancy, de.admin, de.candidate'
                     )->setParameter('admin', $admin)
                      ->setParameter('periodicity', $periodicity);

            $entries = new Collections\ArrayCollection($query->getResult());

            //2. retrieve admin himself
            $admin = $em->getRepository('AppBundle:Person')->findOneById($admin);

            //3. send the digest-mail for that admin
            $data = [
                        'admin' => $admin,
                        'newCharge' => $entries->filter(
                                        function($entry) {
                                           return ($entry->getEvent() === 1);
                                    }),
                        'newVacancy' => $entries->filter(
                                        function($entry) {
                                           return ($entry->getEvent() === 2);
                                    }),
                        'newCandidate' => $entries->filter(
                                        function($entry) {
                                           return ($entry->getEvent() === 3);
                                    }),
                        'newAdmin' => $entries->filter(
                                        function($entry) {
                                           return ($entry->getEvent() === 4);
                                    }),
                        'approveCandidate' => $entries->filter(
                                        function($entry) {
                                           return ($entry->getEvent() === 5);
                                    }),
                        'removeCandidate' => $entries->filter(
                                        function($entry) {
                                           return ($entry->getEvent() === 6);
                                    }),
                    ];

           $message = \Swift_Message::newInstance()
            ->setSubject('Emaildigest Roeselare Vrijwilligt')
            ->setFrom('info@roeselarevrijwilligt.be')
            ->setTo($admin->getEmail())
            ->setBody(
                $templating->render(
                    // app/Resources/views/email/template-name
                    'email/digest.html.twig',
                    $data
                ),
                'text/html'
            )
            ->addPart(
                $templating->render(
                    'email/digest.txt.twig',
                    $data
                ),
                'text/plain'
            );

            $container->get('mailer')->send($message);

            //4. delete all DE's for that admin
            foreach ($entries as $key => $entry) {
                $em->remove($entry);
            }
            $em->flush();
        }

        $output->writeln('all is well that ends well');
    }
}
