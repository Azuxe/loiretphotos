<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use App\Entity\Sujets;
use App\Form\SujetsType;
class SujetsController extends Controller
{

    /**
     * @Rest\Get("/sujets/columns")
     */
    public function getSujetsColumnAction(Request $request)
    {
        $columnNames = $this->getDoctrine()->getEntityManager()
                 ->getClassMetadata(Sujets::class)->getColumnNames();
        return $columnNames;
    }

    /**
     * @Rest\View(serializerGroups={"sujets"})
     * @Rest\Get("/sujets")
     */
    public function getSujetsAction(Request $request)
    {
        $sujets = $this->getDoctrine()->getEntityManager()
                ->getRepository(Sujets::class)
                ->findAll();
        /* @var $sujets sujet[] */

        return $sujets;
    }

    /**
     * @Rest\View(serializerGroups={"sujets"})
     * @Rest\Get("/sujets/{id}")
     */
    public function getSujetAction($id,Request $request)
    {
        $sujet = $this->getDoctrine()->getEntityManager()
                ->getRepository(Sujets::class)
                ->find($id);
        /* @var $sujet sujet */

        if (empty($sujet)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Sujets not found'], Response::HTTP_NOT_FOUND);
        }

        return $sujet;
  
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"sujets"})
     * @Rest\Post("/sujets")
     */
    public function postSujetAction(Request $request)
    {
        $sujet = new Sujets();
        $form = $this->createForm(SujetsType::class, $sujet);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($sujet);
            $em->flush();
            return $sujet;
        } else {
            return $form;
        }
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"sujets"})
     * @Rest\Delete("/sujets/{id}")
     */
    public function removeSujetAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $sujet = $em->getRepository(Sujets::class)
                    ->find($request->get('id'));
        /* @var $sujet sujet */

        if ($sujet){
            $em->remove($sujet);
            $em->flush();
        }
        
    }

    /**
     * @Rest\View(serializerGroups={"sujets"})
     * @Rest\Put("/sujets/{id}")
     */
    public function updateSujetAction(Request $request)
    {
        $sujet = $this->getDoctrine()->getEntityManager()
                ->getRepository(Sujets::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $sujet sujet */

        if (empty($sujet)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Sujets not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(SujetsType::class, $sujet);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($sujet);
            $em->flush();
            return $sujet;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"sujets"})
     * @Rest\Patch("/sujets/{id}")
     */
    public function patchSujetAction(Request $request)
    {
        $sujet = $this->getDoctrine()->getEntityManager()
                ->getRepository(Sujets::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $sujet Sujets */

        if (empty($sujet)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Sujets not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(SujetsType::class, $sujet);

         // Le paramètre false dit à Symfony de garder les valeurs dans notre 
         // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($sujet);
            $em->flush();
            return $sujet;
        } else {
            return $form;
        }
    }
}



