<?php
/**
 * Created by PhpStorm.
 * User: STARDIRECT
 * Date: 24/04/2018
 * Time: 15:36
 */

namespace AppBundle\Controller;

//use AppBundle\Entity\Emploi;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use Symfony\Component\HttpFoundation\Response; // N'oublons pas d'inclure Get

class ApiUsersController extends Controller
{

    /**
     * @Rest\View()
     * @Rest\Get("api/users")
     */
    public function getUsersAction(Request $request)
    {
        $users = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->findAll()
        ;

        $formatted = [];
        foreach ($users as $user) {
            $formatted[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'compteType' => $user->getTypeCompte(),
            ];
        }

        //return new JsonResponse($formatted);

        $view = View::create($formatted);
        $view->setFormat('json');

        return $view;
    }
    /**
     * @Rest\View()
     * @Rest\Get("api/users/{user_id}")
     */
    public function getUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('user_id'));

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $formatted = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'compteType' => $user->getTypeCompte(),
        ];


        $view = View::create($formatted);
        $view->setFormat('json');
        return $view;
    }

    /**
     * @Rest\View()
     * @Rest\Post("/api/users/checkuser")
     */
    public function postCheckUserIDAction(Request $request)
    {
        $userid = $request->request->get('userid');
        $password = $request->request->get('pass');

        $passwordEncoder = $this->container->get('security.password_encoder');
        //$pwd = $encoder->encodePassword($user, $pwd);
        $em = $this->get('doctrine')->getEntityManager();
        $query = $em->createQuery("SELECT u FROM \AppBundle\Entity\User u WHERE u.username = :username");
        $query->setParameter('username', $userid);
        $user = $query->getOneOrNullResult();

        if ($user) {
            // Get the encoder for the users password
            $encoder_service = $this->get('security.encoder_factory');
            //$encoder_service = $this->get('security.password_encoder');
            $encoder = $encoder_service->getEncoder($user);

            // Note the difference
            if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
                // Get profile list
                $vali=true;
                $msg='good user/password';
            } else {
                // Password bad
                $vali=false;
                $msg='wrong password';
            }
        } else {
            // Username bad
            $vali=false;
            $msg='no userid found';
        }
        $response = new JsonResponse();
        if($user){
            $schoolID=$user->getSchool()->getId();
            //GET ALL EMPLOIS
            $listemplois = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Emploi')
                ->findBy(array(
                    'school' => $schoolID,
                ))
            ;
            $emplois = [];
            foreach ($listemplois as $emploi) {
                $fileName=$emploi->getImageName();
                //$linkImage=$this->getParameter('images_directory').'/'.$fileName;


                $emplois[] = [
                    //'id' => $event->getId(),
                    'niveau' => $emploi->getNiveau(),
                    //'dateNews' => $event->getEventDate()->format('Y-m-d'),
                    'thumbnail_images' => [
                        'full' => [
                            'url'=> $fileName,
                            'width'=> 640,
                            'height'=> 480
                        ],
                        'thumbnail'=> [
                            'url'=> $fileName,
                            'width'=> 150,
                            'height'=> 150
                        ],
                        'medium'=> [
                            'url'=> 'http://dev.studio31.co/wp-content/uploads/2013/09/dsc20050604_133440_34211-300x225.jpg',
                            'width'=> 300,
                            'height'=> 225
                        ],

                    ]
                ];
            }
            //END GET ALL EMPLOIS
            //GET ALL CANTINES
            $listcantines = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Cantine')
                ->findBy(array(
                    'school' => $schoolID,
                ))
            ;
            $cantines = [];
            foreach ($listcantines as $cantine) {
                $fileName=$cantine->getImageName();
                //$linkImage=$this->getParameter('images_directory').'/'.$fileName;


                $cantines[] = [
                    //'id' => $event->getId(),
                    'titre' => $cantine->getTitre(),
                    //'dateNews' => $event->getEventDate()->format('Y-m-d'),
                    'thumbnail_images' => [
                        'full' => [
                            'url'=> $fileName,
                            'width'=> 640,
                            'height'=> 480
                        ],
                        'thumbnail'=> [
                            'url'=> $fileName,
                            'width'=> 150,
                            'height'=> 150
                        ],
                        'medium'=> [
                            'url'=> 'http://dev.studio31.co/wp-content/uploads/2013/09/dsc20050604_133440_34211-300x225.jpg',
                            'width'=> 300,
                            'height'=> 225
                        ],

                    ]
                ];
            }
            //END GET ALL CANTINES
            $childName=[];
            if($user->getIsParent()){
                //$user->getChildName() 4; <--IDs

                $topics=explode(";",$user->getChildrenTopics());
                foreach ($topics as $topic){
                    if($topic!="")
                    $childName[]=[
                        "child" => $topic
                    ];
                }

            }
            return $response->setData(array(
                'success' => $vali,
                'msg' => $msg,
                'extras' => array(
                    'msg' => 1,
                    'userProfileModel' => array(
                        'userid' => $user->getId(),
                        'username'=> $user->getUsername(),
                        'nom'=> $user->getNom(),
                        'prenom'=> $user->getPrenom(),
                        'email' => $user->getEmail(),
                        'compteType' => $user->getTypeCompte(),
                        'isParent' => $user->getIsParent(),
                        'isChild' => $user->getIsChild(),
                        'isChauffeur' => $user->getIsChauffeur(),
                        'isAssistante' => $user->getIsAssistante(),
                        'schoolName' => $user->getSchoolName(),
                        'childName' => $childName,
                        'parentID' => $user->getParentID(),
                        'numeroMatriculeVehicule' => $user->getNumeroMatriculeVehicule(),
                        'schoolID' => $schoolID,
                        'schoolDescription' => $user->getSchool()->getDescription(),
                        'schoolAdresse' => $user->getSchool()->getAdresse(),
                        'schoolTel' => $user->getSchool()->getTel(),
                        'emplois'=> $emplois,
                        'cantines' => $cantines
                    ),
                    'sessionId' => '1234',
                ),
            ));
        }else{
            return $response->setData(array(
                'success' => $vali,
                'msg' => $msg,
                'extras' => array(
                    'msg' => 2,
                    'userProfileModel' => array(
                        'username'=> null,
                        'email' => null,
                    ),
                    'sessionId' => 'null',
                ),
            ));
        }

    }

    /**
     * @Rest\View()
     * @Rest\Post("/api/users/liststudents")
     */
    public function postListStudentsAction(Request $request)
    {
        $schoolID = $request->request->get('schoolID');
        $matricule = $request->request->get('matricule');

        $em = $this->getDoctrine()->getManager();
        $students = $em->getRepository('AppBundle:User')
            //->findAll()
            ->findBy(array(
                'school' => $schoolID,
                'numeroMatriculeVehicule' => $matricule,
                'isChild' => true
            ))
        ;

        $formatted = [];
        foreach ($students as $user) {
            $formatted[] = [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'parentName' => 'empty',
                'schoolID' => $schoolID,
                'username' => $user->getUsername()
            ];
        }

        $view = View::create($formatted);
        $view->setFormat('json');

        return $view;

    }
    /**
     * @Rest\View()
     * @Rest\Post("/api/users/teachers")
     */
    public function postListTeachersAction(Request $request)
    {
        $schoolID = $request->request->get('schoolID');
        //$matricule = $request->request->get('matricule');

        $em = $this->getDoctrine()->getManager();
        $teachers = $em->getRepository('AppBundle:User')
            //->findAll()
            ->findBy(array(
                'school' => $schoolID,
                'isTeacher' => true
            ))
        ;

        $formatted = [];
        foreach ($teachers as $user) {
            $formatted[] = [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'parentName' => 'empty',
                'schoolID' => $schoolID,
                'username' => $user->getUsername(),
                'email' => $user->getEmail()
            ];
        }

        $view = View::create($formatted);
        $view->setFormat('json');

        return $view;

    }

    //Parent Report of their children
    /**
     * @Rest\View()
     * @Rest\Post("/api/users/parent/report")
     */
    public function postParentReportAction(Request $request)
    {
        $childID = $request->request->get('childID');
        //$matricule = $request->request->get('matricule');
        $em = $this->getDoctrine()->getManager();

        $etudiant = $em->getRepository('AppBundle:User')->find($childID);

        if (!empty($etudiant)) {


        $absences = $em->getRepository('AppBundle:Absence')
            ->findBy(array(
                'etudiantID' => $childID,
            ));

        $absencesTab = [];
        foreach ($absences as $absence) {
            $absencesTab[] = [
                'id' => $absence->getId(),
                'dateAbsence' => $absence->getDateAbsence()->format('Y-m-d'),
                //'heureAbsence' => $absence->getHeureAbsence()->format('h:i:s'),
                'matiereName' => $absence->getMatiereName(),
                'raison' => $absence->getRaison(),
                'etudiantName' => $absence->getEtudiantName(),
                'observation' => $absence->getObservation(),
            ];
        }

        $remarques = $em->getRepository('AppBundle:Remarque')
            ->findBy(array(
                'etudiantID' => $childID,
            ));

        $remarquesTab = [];
        foreach ($remarques as $remarque) {
            $remarquesTab[] = [
                'id' => $remarque->getId(),
                'niveau' => $remarque->getNiveau(),
                'niveauID' => $remarque->getNiveauID(),
                'dateRemarque' => $remarque->getDateRemarque()->format('Y-m-d'),
                'observation' => $remarque->getObservation()
            ];
        }

        $retards = $em->getRepository('AppBundle:Retard')
            ->findBy(array(
                'etudiantID' => $childID,
            ));

        $retardsTab = [];
        foreach ($retards as $retard) {
            $retardsTab[] = [
                'id' => $retard->getId(),
                'etudiantName' => $retard->getEtudiantName(),
                'dateRetard' => $retard->getDateRetard()->format('Y-m-d'),
                'heureRetard' => $retard->getHeureRetard()->format('h:i:s'),
                'duree' => $retard->getDuree(),
                'observation' => $retard->getObservation(),
                'author' => $retard->getAuthor(),
                'authorID' => $retard->getAuthorID(),
            ];
        }

        //Devoir
        //$user=new User();
        //$user->getNiveaux();

        $groupeID = "";
        foreach ($etudiant->getNiveaux() as $niveau) {
            $groupeID = $niveau->getId();

        }

        $devoirs = $em->getRepository('AppBundle:Devoir')
            ->findBy(array(
                'niveauID' => $groupeID,
            ));
        $emploi = $em->getRepository('AppBundle:Emploi')
            ->findBy(array(
                'niveauID' => $groupeID,
            ));
        $devoirsTab = [];
        foreach ($devoirs as $devoir) {
            $devoirsTab[] = [
                'id' => $devoir->getId(),
                'titre' => $devoir->getTitre(),
                'description' => $devoir->getDescription(),
                'matiereName' => $devoir->getMatiere(),
                'matiereID' => $devoir->getMatiereID(),
                'dateDelai' => $devoir->getDateDelai()->format('Y-m-d'),
                'dateCreation' => $devoir->getDateCreation()->format('Y-m-d'),
                'author' => $devoir->getAuthor(),
            ];
        }
        //infermeries
        $infermeries = $em->getRepository('AppBundle:Infermerie')
            ->findBy(array(
                'etudiantID' => $childID,
            ));

        $infermerieTab = [];
        foreach ($infermeries as $infermerie) {
            $infermerieTab[] = [
                'id' => $infermerie->getId(),
                'etudiantName' => $infermerie->getEtudiantName(),
                'niveau' => $infermerie->getNiveau(),
                'datePassage' => $infermerie->getDatePassage()->format('Y-m-d'),
                'description' => $infermerie->getDescription(),

            ];
        }
        //payement list
        $payments = $em->getRepository('AppBundle:Payment')
            ->findBy(array(
                'etudiantID' => $childID,
            ));
        $paymentsTab = [];
        foreach ($payments as $payment) {
            $paymentsTab[] = [
                'id' => $payment->getId(),
                'etudiantName' => $payment->getEtudiantName(),
                'niveau' => $payment->getNiveau(),
                'datePayment' => $payment->getDatePayment()->format('Y-m-d'),
                'mode' => $payment->getModePayment(),
                'anneeScolaire' => $payment->getAnneeScolaire(),
                'montant' => $payment->getMontant(),
                'reste' => $payment->getReste(),
                'status' => $payment->getStatusPayment(),
                'delai' => $payment->getDelaiPayment()->format('Y-m-d'),

            ];
        }
        //check emploi
        if (!empty($emploi)) {
            //$emp=new Emploi()
            $imgEmploi = $emploi[0]->getImageName();
        } else {
            $imgEmploi = "";
        }
        //report
        $report = array(
            "absences" => $absencesTab,
            "devoirs" => $devoirsTab,
            "remarques" => $remarquesTab,
            "retards" => $retardsTab,
            "infermeries" => $infermerieTab,
            "payments" => $paymentsTab,
            "emploi" => $imgEmploi
        );
        }//end if student exist
        else{
            $report = array(
                "msg" => "Eleve non existant",

            );
        }
        $view = View::create($report);
        $view->setFormat('json');

        return $view;

    }
}