<?php


namespace App\Controller;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Entity\Tarif;
use App\Form\DepotType;
use App\Form\CompteType;
use App\Entity\Entreprise;
use App\Entity\Expediteur;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Beneficiaire;
use App\Form\EntrepriseType;
use App\Form\ExpediteurType;
use App\Form\TransactionType;
use App\Form\UserType;
use App\Form\BeneficiaireType;
use JMS\Serializer\SerializerBuilder;
use App\Repository\EntrepriseRepository;
use App\Repository\TransactionRepository;
use App\Repository\TarifRepository;
use App\Repository\BeneficiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class TransactionController extends AbstractController
{
   /**
     * @Route("/envoi", name="tra", methods={"GET","POST"})
     */
    public function envoi(Request $request, EntityManagerInterface $entityManager,
    SerializerInterface $serializer, ValidatorInterface $validator):Response
    {

        $beneficiaire = new Beneficiaire();
        $form=$this->createForm(BeneficiaireType::class , $beneficiaire);
        $data=$request->request->all();
         $form->submit($data);

         $expediteur = new Expediteur();
         $form=$this->createForm(ExpediteurType::class , $expediteur);
         $data=$request->request->all();
          $form->submit($data);

        $envoi = new Transaction();
        $form = $this->createForm(TransactionType::class,$envoi);
        
        $user = $this->getUser();
        $data = $request->request->all();
        $form->submit($data); 

         
       

            $envoi->setDateenvoi(new \DateTime());
            $envoi->setType("envoi");
            while (true) {
                if (time() % 1 == 0) {
                    $alea = rand(100,1000000);
                    break;
                }else {
                    slep(1);
                }
            }
            $envoi->setCode($alea);
            $envoi->setExpediteur($expediteur);
            $envoi->setBeneficiaire($beneficiaire);
          
           
            $vo=$form->get('montant')->getData();
            $frais= $this->getDoctrine()->getRepository(Tarif::class)->findAll();
           
            foreach($frais as $values){
                $values->getBorninf();
                $values->getBornsup();
                $values->getValeur();
                if($vo>=$values->getBorninf() && $vo<=$values->getBornsup() ){
                $com=$values->getValeur();
                
            $envoi->setFrais($com);
            $envoi->setCometat(($com*30)/100);
            $envoi->setComsys(($com*40)/100);
            $envoi->setComenvoi(($com*10)/100);
            $envoi->setComretrait(($com*20)/100);
                }
            }
             

            $Compte=$user->getCompte();
            $envoi->setUser($user);         
            if($Compte->getSolde() > $envoi->getMontant() ){
                $Montant= $Compte->getSolde()-$envoi->getMontant()+$envoi->getComenvoi();
                $envoi->setUtilisateur($user);
                $Compte->setSolde($Montant);
            $entityManager->persist($Compte);
            $entityManager->persist($envoi);
            $entityManager->persist($expediteur);
            $entityManager->persist($beneficiaire);
            $entityManager->flush();
           
 return new Response('Le transfert a été effectué avec succés. Voici le code : '.$envoi->getCode());
            }
            else{
    
    return new Response('Le solde de votre compte ne vous permet d effectuer une transaction');
            }


        }
    
   /**
     * @Route("/retrait", name="add_retrait" ,methods={"POST", "GET"})
     */

    public function retrait(Request $request, EntityManagerInterface $entityManager, TransactionRepository $transaction)
    {

        $trans = new Beneficiaire();
        $form = $this->createForm(BeneficiaireType::class, $trans);
        $user = $this->getUser();
        $data = $request->request->all();
        $form->submit($data); 
        $code=$data['code'];

        $trouve=$transaction->findOneBy(['code' =>$code]);
        
        if (!$trouve) {
            return new Response('Le code saisi est incorecte .Veuillez ressayer un autre  ');
        } 
        
        $statut=$trouve->getType();

        if($trouve->getCode()== $code && $statut=="retrait"){
            return new Response('Le code saisi est déjà retiré  ');

        }

        $trans->setCni($data["cni"]);

        $trouve->setUserRetrait($data["userRetrait"]);
        $trouve->setDateRetrait(new \DateTime());

        $trouve->setType("retrait");
        $trouve->setUtilisateur($user);

        $entityManager->flush();
        
        return new Response('Vous venez de retirer  ' . $trouve->getMontant());
        
    }

    /**
     * @Route("/listEnvoi", name="detailEnv",methods={"POST"})
     */
    public function detailEnvoi(Request $request, EntityManagerInterface $entityentityManager, ValidatorInterface $validator, SerializerInterface $serializer)
    {

        $user = $this->getUser();
        $values = json_decode($request->getContent());
        if (!$values) {
            $values = $request->request->all();
        }
        $debut = new \DateTime($values->dateFrom);
        $fin = new \DateTime($values->dateTo);


        try {
            $repo1 = $this->getDoctrine()->getRepository(Transaction::class);
            $detail = $repo1->finByDateE($debut, $fin, $user);
            if ($detail == []) {
                return $this->json([
                    'message' => 'aucune transaction pour cette intervale!'
                ]);
            }
        } catch (ParseException $exception) {
            $exception = [
                'status' => 500,
                'message' => 'Vous devez renseigner tous les champs'
            ];
            return new JsonResponse($exception, 500);
        }
        $data      = $serializer->serialize($detail, 'json',  []);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
