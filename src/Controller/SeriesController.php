<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use App\Entity\Series;
use App\Form\SeriesType;
class SeriesController extends Controller
{

    /**
     * @Rest\Get("/series/columns")
     */
    public function getSeriesColumnAction(Request $request)
    {
        $columnNames = $this->getDoctrine()->getEntityManager()
                 ->getClassMetadata(Series::class)->getColumnNames();
        return $columnNames;
    }

    /**
     * @Rest\View(serializerGroups={"series"})
     * @Rest\Get("/series")
     */
    public function getSeriesAction(Request $request)
    {
        $series = $this->getDoctrine()->getEntityManager()
                ->getRepository(Series::class)
                ->findAll();
        /* @var $series serie[] */

        return $series;
    }

    /**
     * @Rest\View(serializerGroups={"series"})
     * @Rest\Get("/series/{id}")
     */
    public function getSerieAction($id,Request $request)
    {
        $serie = $this->getDoctrine()->getEntityManager()
                ->getRepository(Series::class)
                ->find($id);
        /* @var $serie serie */

        if (empty($serie)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Series not found'], Response::HTTP_NOT_FOUND);
        }

        return $serie;
  
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"series"})
     * @Rest\Post("/series")
     */
    public function postSerieAction(Request $request)
    {
        $serie = new Series();
        $form = $this->createForm(SeriesType::class, $serie);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($serie);
            $em->flush();
            return $serie;
        } else {
            return $form;
        }
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"series"})
     * @Rest\Delete("/series/{id}")
     */
    public function removeSerieAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $serie = $em->getRepository(Series::class)
                    ->find($request->get('id'));
        /* @var $serie serie */

        if ($serie){
            $em->remove($serie);
            $em->flush();
        }
        
    }

    /**
     * @Rest\View(serializerGroups={"series"})
     * @Rest\Put("/series/{id}")
     */
    public function updateSerieAction(Request $request)
    {
        $serie = $this->getDoctrine()->getEntityManager()
                ->getRepository(Series::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $serie serie */

        if (empty($serie)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Series not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(SeriesType::class, $serie);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($serie);
            $em->flush();
            return $serie;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"series"})
     * @Rest\Patch("/series/{id}")
     */
    public function patchSerieAction(Request $request)
    {
        $serie = $this->getDoctrine()->getEntityManager()
                ->getRepository(Series::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $serie Series */

        if (empty($serie)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Series not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(SeriesType::class, $serie);

         // Le paramètre false dit à Symfony de garder les valeurs dans notre 
         // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($serie);
            $em->flush();
            return $serie;
        } else {
            return $form;
        }
    }
}



