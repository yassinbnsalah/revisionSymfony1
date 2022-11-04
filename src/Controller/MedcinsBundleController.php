<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\AddPatientType;
use App\Form\RechercheNamePrenameType;
use App\Repository\PatientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedcinsBundleController extends AbstractController
{
    #[Route('/medcins/bundle', name: 'app_medcins_bundle')]
    public function index(): Response
    {
        return $this->render('medcins_bundle/index.html.twig', [
            'controller_name' => 'MedcinsBundleController',
        ]);
    }

    #[Route('/afficherpatient' , name:'afficherpatient')]
    public function afficherpatient(PatientRepository $repo):Response
    {
      
        $patients = $repo->findAll() ; 
        
        return $this->render('medcins_bundle/listPatient.html.twig' ,[
         'patients' => $patients
        ]);

    }

    #[Route('/recherchepatient' , name:'recherchepatient')]
    public function recherchepatient(PatientRepository $repo , Request $req):Response
    {
        $defaultData = [] ;
        $formRecherche = $this->createForm(RechercheNamePrenameType :: class , $defaultData); 
        $result =[] ; 
        $formRecherche->handleRequest($req) ; 
        if($formRecherche->isSubmitted()){
            $data = $formRecherche->getData(); 
        
            $result = $repo->findPatientwithNamePrename($data['name'] , $data['prename']) ; 
           
        }
        
        return $this->render('medcins_bundle/recherche.html.twig' ,[
            'f' => $formRecherche->createView(),
            'patients' => $result 
        ]);

    }

    #[Route('/patient/add' , name:'AddPatient')]
    public function AddPatient(PatientRepository $repo , Request $req):Response
    {
        $patient = new Patient() ; 
        $form = $this->createForm(AddPatientType::class , $patient);
        $form->handleRequest($req); 
        if( $form->isSubmitted()){
            $repo->save($patient , true) ; 
            return $this->redirectToRoute('app_medcins_bundle');
        }
        return $this->render('medcins_bundle/addPatient.html.twig' ,[
            'f' => $form->createView()
        ]);

    }
}
