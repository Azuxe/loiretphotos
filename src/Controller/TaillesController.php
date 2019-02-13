<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use App\Entity\Tailles;
use App\Form\TaillesType;
class TaillesController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"tailles"})
     * @Rest\Get("/tailles")
     */
    public function getTaillesAction(Request $request)
    {
        $tailles = $this->getDoctrine()->getEntityManager()
                ->getRepository(Tailles::class)
                ->findAll();
        /* @var $tailles taille[] */

        return $tailles;
    }

    /**
     * @Rest\View(serializerGroups={"tailles"})
     * @Rest\Get("/tailles/{id}")
     */
    public function getTailleAction($id,Request $request)
    {
        $taille = $this->getDoctrine()->getEntityManager()
                ->getRepository(Tailles::class)
                ->find($id);
        /* @var $taille taille */

        if (empty($taille)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Tailles not found'], Response::HTTP_NOT_FOUND);
        }

        return $taille;
  
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"tailles"})
     * @Rest\Post("/tailles")
     */
    public function postTailleAction(Request $request)
    {
        $taille = new Tailles();
        $form = $this->createForm(TaillesType::class, $taille);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($taille);
            $em->flush();
            return $taille;
        } else {
            return $form;
        }
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"tailles"})
     * @Rest\Delete("/tailles/{id}")
     */
    public function removeTailleAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $taille = $em->getRepository(Tailles::class)
                    ->find($request->get('id'));
        /* @var $taille taille */

        if ($taille){
            $em->remove($taille);
            $em->flush();
        }
        
    }

    /**
     * @Rest\View(serializerGroups={"tailles"})
     * @Rest\Put("/tailles/{id}")
     */
    public function updateTailleAction(Request $request)
    {
        $taille = $this->getDoctrine()->getEntityManager()
                ->getRepository(Tailles::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $taille taille */

        if (empty($taille)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Tailles not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(TaillesType::class, $taille);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($taille);
            $em->flush();
            return $taille;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"tailles"})
     * @Rest\Patch("/tailles/{id}")
     */
    public function patchTailleAction(Request $request)
    {
        $taille = $this->getDoctrine()->getEntityManager()
                ->getRepository(Tailles::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $taille Tailles */

        if (empty($taille)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Tailles not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(TaillesType::class, $taille);

         // Le paramètre false dit à Symfony de garder les valeurs dans notre 
         // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($taille);
            $em->flush();
            return $taille;
        } else {
            return $form;
        }
    }
}



