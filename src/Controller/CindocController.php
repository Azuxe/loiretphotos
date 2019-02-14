<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use App\Entity\Cindoc;
use App\Form\CindocType;
class CindocController extends Controller
{

    /**
     * @Rest\Get("/cindocs/columns")
     */
    public function getCindocsColumnAction(Request $request)
    {
        $columnNames = $this->getDoctrine()->getEntityManager()
                 ->getClassMetadata(Cindoc::class)->getColumnNames();
        return $columnNames;
    }

    /**
     * @Rest\View(serializerGroups={"cindoc"})
     * @Rest\Get("/cindocs")
     */
    public function getCindocsAction(Request $request)
    {
        $cindocs = $this->getDoctrine()->getEntityManager()
                ->getRepository(Cindoc::class)
                ->findAll();
        /* @var $cindocs cindoc[] */

        return $cindocs;
    }

    /**
     * @Rest\View(serializerGroups={"cindoc"})
     * @Rest\Get("/cindocs/{id}")
     */
    public function getCindocAction($id,Request $request)
    {
        $cindoc = $this->getDoctrine()->getEntityManager()
                ->getRepository(Cindoc::class)
                ->find($id);
        /* @var $cindoc cindoc */

        if (empty($cindoc)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Cindoc not found'], Response::HTTP_NOT_FOUND);
        }

        return $cindoc;
  
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"cindoc"})
     * @Rest\Post("/cindocs")
     */
    public function postCindocAction(Request $request)
    {
        $cindoc = new Cindoc();
        $form = $this->createForm(CindocType::class, $cindoc);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($cindoc);
            $em->flush();
            return $cindoc;
        } else {
            return $form;
        }
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"cindoc"})
     * @Rest\Delete("/cindocs/{id}")
     */
    public function removeCindocAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $cindoc = $em->getRepository(Cindoc::class)
                    ->find($request->get('id'));
        /* @var $cindoc cindoc */

        if ($cindoc){
            $em->remove($cindoc);
            $em->flush();
        }
        
    }

    /**
     * @Rest\View(serializerGroups={"cindoc"})
     * @Rest\Put("/cindocs/{id}")
     */
    public function updateCindocAction(Request $request)
    {
        $cindoc = $this->getDoctrine()->getEntityManager()
                ->getRepository(Cindoc::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $cindoc cindoc */

        if (empty($cindoc)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Cindoc not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(CindocType::class, $cindoc);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($cindoc);
            $em->flush();
            return $cindoc;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"cindoc"})
     * @Rest\Patch("/cindocs/{id}")
     */
    public function patchCindocAction(Request $request)
    {
        $cindoc = $this->getDoctrine()->getEntityManager()
                ->getRepository(Cindoc::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $cindoc Cindoc */

        if (empty($cindoc)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Cindoc not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(CindocType::class, $cindoc);

         // Le paramètre false dit à Symfony de garder les valeurs dans notre 
         // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($cindoc);
            $em->flush();
            return $cindoc;
        } else {
            return $form;
        }
    }
}



