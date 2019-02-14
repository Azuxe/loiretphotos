<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use App\Entity\IndexIconographiques;
use App\Form\IndexIconographiquesType;
class IndexIconographiquesController extends Controller
{

    /**
     * @Rest\Get("/indexIconographiques/columns")
     */
    public function getClichesColumnAction(Request $request)
    {
        $columnNames = $this->getDoctrine()->getEntityManager()
                 ->getClassMetadata(IndexIconographiques::class)->getColumnNames();
        return $columnNames;
    }

    /**
     * @Rest\View(serializerGroups={"indexicos"})
     * @Rest\Get("/indexIconographiques")
     */
    public function getIndexIconographiquesAction(Request $request)
    {
        $indexIconographiques = $this->getDoctrine()->getEntityManager()
                ->getRepository(IndexIconographiques::class)
                ->findAll();
        /* @var $indexIconographiques indexIconographique[] */

        return $indexIconographiques;
    }

    /**
     * @Rest\View(serializerGroups={"indexicos"})
     * @Rest\Get("/indexIconographiques/{id}")
     */
    public function getIndexIconographiqueAction($id,Request $request)
    {
        $indexIconographique = $this->getDoctrine()->getEntityManager()
                ->getRepository(IndexIconographiques::class)
                ->find($id);
        /* @var $indexIconographique indexIconographique */

        if (empty($indexIconographique)) {
            return \FOS\RestBundle\View\View::create(['message' => 'IndexIconographiques not found'], Response::HTTP_NOT_FOUND);
        }

        return $indexIconographique;
  
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"indexicos"})
     * @Rest\Post("/indexIconographiques")
     */
    public function postIndexIconographiqueAction(Request $request)
    {
        $indexIconographique = new IndexIconographiques();
        $form = $this->createForm(IndexIconographiquesType::class, $indexIconographique);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($indexIconographique);
            $em->flush();
            return $indexIconographique;
        } else {
            return $form;
        }
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"indexicos"})
     * @Rest\Delete("/indexIconographiques/{id}")
     */
    public function removeIndexIconographiqueAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $indexIconographique = $em->getRepository(IndexIconographiques::class)
                    ->find($request->get('id'));
        /* @var $indexIconographique indexIconographique */

        if ($indexIconographique){
            $em->remove($indexIconographique);
            $em->flush();
        }
        
    }

    /**
     * @Rest\View(serializerGroups={"indexicos"})
     * @Rest\Put("/indexIconographiques/{id}")
     */
    public function updateIndexIconographiqueAction(Request $request)
    {
        $indexIconographique = $this->getDoctrine()->getEntityManager()
                ->getRepository(IndexIconographiques::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $indexIconographique indexIconographique */

        if (empty($indexIconographique)) {
            return \FOS\RestBundle\View\View::create(['message' => 'IndexIconographiques not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(IndexIconographiquesType::class, $indexIconographique);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($indexIconographique);
            $em->flush();
            return $indexIconographique;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"indexicos"})
     * @Rest\Patch("/indexIconographiques/{id}")
     */
    public function patchIndexIconographiqueAction(Request $request)
    {
        $indexIconographique = $this->getDoctrine()->getEntityManager()
                ->getRepository(IndexIconographiques::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $indexIconographique IndexIconographiques */

        if (empty($indexIconographique)) {
            return \FOS\RestBundle\View\View::create(['message' => 'IndexIconographiques not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(IndexIconographiquesType::class, $indexIconographique);

         // Le paramètre false dit à Symfony de garder les valeurs dans notre 
         // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($indexIconographique);
            $em->flush();
            return $indexIconographique;
        } else {
            return $form;
        }
    }
}



