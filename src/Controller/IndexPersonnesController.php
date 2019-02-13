<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use App\Entity\IndexPersonnes;
use App\Form\IndexPersonnesType;
class IndexPersonnesController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"indexpers"})
     * @Rest\Get("/indexPersonnes")
     */
    public function getIndexPersonnesAction(Request $request)
    {
        $indexPersonnes = $this->getDoctrine()->getEntityManager()
                ->getRepository(IndexPersonnes::class)
                ->findAll();
        /* @var $indexPersonnes indexPersonne[] */

        return $indexPersonnes;
    }

    /**
     * @Rest\View(serializerGroups={"indexpers"})
     * @Rest\Get("/indexPersonnes/{id}")
     */
    public function getIndexPersonneAction($id,Request $request)
    {
        $indexPersonne = $this->getDoctrine()->getEntityManager()
                ->getRepository(IndexPersonnes::class)
                ->find($id);
        /* @var $indexPersonne indexPersonne */

        if (empty($indexPersonne)) {
            return \FOS\RestBundle\View\View::create(['message' => 'IndexPersonnes not found'], Response::HTTP_NOT_FOUND);
        }

        return $indexPersonne;
  
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"indexpers"})
     * @Rest\Post("/indexPersonnes")
     */
    public function postIndexPersonneAction(Request $request)
    {
        $indexPersonne = new IndexPersonnes();
        $form = $this->createForm(IndexPersonnesType::class, $indexPersonne);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($indexPersonne);
            $em->flush();
            return $indexPersonne;
        } else {
            return $form;
        }
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"indexpers"})
     * @Rest\Delete("/indexPersonnes/{id}")
     */
    public function removeIndexPersonneAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $indexPersonne = $em->getRepository(IndexPersonnes::class)
                    ->find($request->get('id'));
        /* @var $indexPersonne indexPersonne */

        if ($indexPersonne){
            $em->remove($indexPersonne);
            $em->flush();
        }
        
    }

    /**
     * @Rest\View(serializerGroups={"indexpers"})
     * @Rest\Put("/indexPersonnes/{id}")
     */
    public function updateIndexPersonneAction(Request $request)
    {
        $indexPersonne = $this->getDoctrine()->getEntityManager()
                ->getRepository(IndexPersonnes::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $indexPersonne indexPersonne */

        if (empty($indexPersonne)) {
            return \FOS\RestBundle\View\View::create(['message' => 'IndexPersonnes not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(IndexPersonnesType::class, $indexPersonne);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($indexPersonne);
            $em->flush();
            return $indexPersonne;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"indexpers"})
     * @Rest\Patch("/indexPersonnes/{id}")
     */
    public function patchIndexPersonneAction(Request $request)
    {
        $indexPersonne = $this->getDoctrine()->getEntityManager()
                ->getRepository(IndexPersonnes::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $indexPersonne IndexPersonnes */

        if (empty($indexPersonne)) {
            return \FOS\RestBundle\View\View::create(['message' => 'IndexPersonnes not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(IndexPersonnesType::class, $indexPersonne);

         // Le paramètre false dit à Symfony de garder les valeurs dans notre 
         // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($indexPersonne);
            $em->flush();
            return $indexPersonne;
        } else {
            return $form;
        }
    }
}



