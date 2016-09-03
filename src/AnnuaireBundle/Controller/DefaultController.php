<?php

namespace AnnuaireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AnnuaireBundle\Entity\Annuaire;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AnnuaireBundle:Default:index.html.twig');
    }

    public function annuaireAction(){
    	if($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')){

    		if(isset($_POST)&&sizeof($_POST)!=0){
    			if(!isset($_POST['ajout'])){
			   		$em = $this->getDoctrine()->getManager();
				    $user_to_update = $em->getRepository('AnnuaireBundle:Annuaire')->find($_POST['id']);
				    $user_to_update->setNom($_POST['nom']);
				    $user_to_update->setPrenom($_POST['prenom']);
				    $user_to_update->setEmail($_POST['email']);
				    $user_to_update->setTelephone($_POST['telephone']);
				    $user_to_update->setAdresse($_POST['adresse']);
				    $user_to_update->setSiteweb($_POST['site']);
				    $em->flush();
				    $rep=$this->getDoctrine()->getManager();
					$liste=$rep->getRepository('AnnuaireBundle:Annuaire')->recuperer_liste();
		    		return $this->render('AnnuaireBundle:Default:annuaire.html.twig',array('liste' => $liste ,'id' => $this->getUser()->getId(), 'post' =>true, 'nom'=>$_POST['nom'], 'prenom'=>$_POST['prenom']));
		    	}
		    	else{
		    		if($_POST['nom']==''||$_POST['prenom']==''||$_POST['email']==''||$_POST['telephone']==''||$_POST['adresse']==''||$_POST['site']==''){
		    			$rep=$this->getDoctrine()->getManager();
						$liste=$rep->getRepository('AnnuaireBundle:Annuaire')->recuperer_liste();
		    			return $this->render('AnnuaireBundle:Default:annuaire.html.twig',array('liste' => $liste ,'id' => $this->getUser()->getId(), 'post' =>'champ_vide'));
		    		}
		    		$user_to_create=new Annuaire();
				    $user_to_create->setNom($_POST['nom']);
				    $user_to_create->setPrenom($_POST['prenom']);
				    $user_to_create->setEmail($_POST['email']);
				    $user_to_create->setTelephone($_POST['telephone']);
				    $user_to_create->setAdresse($_POST['adresse']);
				    $user_to_create->setSiteweb($_POST['site']);

				    $validator = $this->get('validator');
				    $em = $this->getDoctrine()->getManager();
    				$em->persist($user_to_create);
				    $em->flush();
				    $liste=$em->getRepository('AnnuaireBundle:Annuaire')->recuperer_liste();
		    		return $this->render('AnnuaireBundle:Default:annuaire.html.twig',array('liste' => $liste ,'id' => $this->getUser()->getId(), 'post' =>true, 'nom'=>$_POST['nom'], 'prenom'=>$_POST['prenom']));
		    	}
    		}

			$rep=$this->getDoctrine()->getManager();
			$liste=$rep->getRepository('AnnuaireBundle:Annuaire')->recuperer_liste();
	    	return $this->render('AnnuaireBundle:Default:annuaire.html.twig',array('liste' => $liste ,'id' => $this->getUser()->getId()));
		}
	    else{
	        return $this->render('AnnuaireBundle:Default:index.html.twig');
	    }

    }

	public function supprimerAction($id)
    {
    	$rep=$this->getDoctrine()->getManager();
	    $adv=$rep->getRepository('AnnuaireBundle:Annuaire')->find($id);
	    $rep->remove($adv);
	    $rep->flush();

		$liste=$rep->getRepository('AnnuaireBundle:Annuaire')->recuperer_liste();
		return $this->render('AnnuaireBundle:Default:annuaire.html.twig',array('liste' => $liste ,'id' => $this->getUser()->getId(), 'post' =>'suppression'));
    }

}
