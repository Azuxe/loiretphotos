<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use App\Entity\Villes;
use App\Form\VillesType;
class VillesController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"villes"})
     * @Rest\Get("/villes")
     */
    public function getVillesAction(Request $request)
    {
        $villes = $this->getDoctrine()->getEntityManager()
                ->getRepository(Villes::class)
                ->findAll();
        /* @var $villes ville[] */

        return $villes;
    }

    /**
     * @Rest\View(serializerGroups={"villes"})
     * @Rest\Get("/villes/{id}")
     */
    public function getVilleAction($id,Request $request)
    {
        $ville = $this->getDoctrine()->getEntityManager()
                ->getRepository(Villes::class)
                ->find($id);
        /* @var $ville ville */

        if (empty($ville)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Villes not found'], Response::HTTP_NOT_FOUND);
        }

        return $ville;
  
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"villes"})
     * @Rest\Post("/villes")
     */
    public function postVilleAction(Request $request)
    {
        $ville = new Villes();
        $form = $this->createForm(VillesType::class, $ville);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($ville);
            $em->flush();
            return $ville;
        } else {
            return $form;
        }
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"villes"})
     * @Rest\Delete("/villes/{id}")
     */
    public function removeVilleAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $ville = $em->getRepository(Villes::class)
                    ->find($request->get('id'));
        /* @var $ville ville */

        if ($ville){
            $em->remove($ville);
            $em->flush();
        }
        
    }

    /**
     * @Rest\View(serializerGroups={"villes"})
     * @Rest\Put("/villes/{id}")
     */
    public function updateVilleAction(Request $request)
    {
        $ville = $this->getDoctrine()->getEntityManager()
                ->getRepository(Villes::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $ville ville */

        if (empty($ville)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Villes not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(VillesType::class, $ville);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($ville);
            $em->flush();
            return $ville;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"villes"})
     * @Rest\Patch("/villes/{id}")
     */
    public function patchVilleAction(Request $request)
    {
        $ville = $this->getDoctrine()->getEntityManager()
                ->getRepository(Villes::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $ville Villes */

        if (empty($ville)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Villes not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(VillesType::class, $ville);

         // Le paramètre false dit à Symfony de garder les valeurs dans notre 
         // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($ville);
            $em->flush();
            return $ville;
        } else {
            return $form;
        }
    }
}



