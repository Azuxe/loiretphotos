<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle
use App\Entity\Cliches;
use App\Form\ClichesType;
class ClichesController extends Controller
{

    /**
     * @Rest\Get("/cliches/columns")
     */
    public function getClichesColumnAction(Request $request)
    {
        $columnNames = $this->getDoctrine()->getEntityManager()
                 ->getClassMetadata(Cliches::class)->getColumnNames();
        return $columnNames;
    }

    /**
     * @Rest\View(serializerGroups={"cliches"})
     * @Rest\Get("/cliches")
     * @QueryParam(name="villesin", requirements="[a-zA-Z:]+", default="", description="Index de début de la pagination") 
     * @QueryParam(name="villesout", requirements="[a-zA-Z,]+", default="", description="Index de début de la pagination")
     * @QueryParam(name="sujetsin", requirements="[a-zA-Z,]+", default="", description="Index de début de la pagination") 
     * @QueryParam(name="sujetsout", requirements="[a-zA-Z,]+", default="", description="Index de début de la pagination")
     * @QueryParam(name="hauteur", requirements="[a-zA-Z\d:]+", default="", description="Index de début de la pagination") 
     * @QueryParam(name="largeur", requirements="[a-zA-Z\d:]+", default="", description="Index de début de la pagination")
     * @QueryParam(name="seriesin", requirements="[a-zA-Z\d:]+", default="", description="Index de début de la pagination") 
     * @QueryParam(name="seriesout", requirements="[a-zA-Z\d:]+", default="", description="Index de début de la pagination")
     * @QueryParam(name="indexperin", requirements=".*", default="", description="Index de début de la pagination") 
     * @QueryParam(name="indexperout", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="indexicoin", requirements=".*", default="", description="Index de début de la pagination") 
     * @QueryParam(name="indexicoout", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="cindocin", requirements=".*", default="", description="Index de début de la pagination") 
     * @QueryParam(name="cindocout", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="remarquein", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="remarqueout", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="basdepagein", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="basdepageout", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="nbcliche", requirements="\d+", default="", description="Index de début de la pagination")
     * @QueryParam(name="discriminantin", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="discriminantout", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="chromain", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="chromaout", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="supportin", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="supportout", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="datedeprise", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="descriptionin", requirements=".*", default="", description="Index de début de la pagination")
     * @QueryParam(name="descriptionout", requirements=".*", default="", description="Index de début de la pagination")
     */
    public function getClichesAction(ParamFetcher $paramFetcher,Request $request)
    {
        //var_dump($paramFetcher->get('indexperin'));
        $cliches = $this->getDoctrine()->getEntityManager()
                 ->getRepository(Cliches::class)
                ->findAllByParam($paramFetcher);
        /* @var $cliches cliche[] */

        //$cliches = $qb->getQuery()->getResult();

        return $cliches;
    }

    /**
     * @Rest\View(serializerGroups={"cliches"})
     * @Rest\Get("/cliches/{id}")
     */
    public function getClicheAction($id,Request $request)
    {
        $cliche = $this->getDoctrine()->getEntityManager()
                ->getRepository(Cliches::class)
                ->find($id);
        /* @var $cliche cliche */

        if (empty($cliche)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Cliches not found'], Response::HTTP_NOT_FOUND);
        }

        return $cliche;
  
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"cliches"})
     * @Rest\Post("/cliches")
     */
    public function postClicheAction(Request $request)
    {
        $cliche = new Cliches();
        $form = $this->createForm(ClichesType::class, $cliche);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($cliche);
            $em->flush();
            return $cliche;
        } else {
            return $form;
        }
    }

     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"cliches"})
     * @Rest\Delete("/cliches/{id}")
     */
    public function removeClicheAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $cliche = $em->getRepository(Cliches::class)
                    ->find($request->get('id'));
        /* @var $cliche cliche */

        if ($cliche){
            $em->remove($cliche);
            $em->flush();
        }
        
    }

    /**
     * @Rest\View(serializerGroups={"cliches"})
     * @Rest\Put("/cliches/{id}")
     */
    public function updateClicheAction(Request $request)
    {
        $cliche = $this->getDoctrine()->getEntityManager()
                ->getRepository(Cliches::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $cliche cliche */

        if (empty($cliche)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Cliches not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(ClichesType::class, $cliche);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($cliche);
            $em->flush();
            return $cliche;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"cliches"})
     * @Rest\Patch("/cliches/{id}")
     */
    public function patchClicheAction(Request $request)
    {
        $cliche = $this->getDoctrine()->getEntityManager()
                ->getRepository(Cliches::class)
                ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $cliche Cliches */

        if (empty($cliche)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Cliches not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(ClichesType::class, $cliche);

         // Le paramètre false dit à Symfony de garder les valeurs dans notre 
         // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            // l'entité vient de la base, donc le merge n'est pas nécessaire.
            // il est utilisé juste par soucis de clarté
            $em->merge($cliche);
            $em->flush();
            return $cliche;
        } else {
            return $form;
        }
    }
}



