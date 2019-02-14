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
use App\Entity\Villes;
use App\Entity\Tailles;
use App\Entity\Sujets;
use App\Entity\Series;
use App\Entity\IndexPersonnes;
use App\Entity\IndexIconographiques;
use App\Entity\Cindoc;  



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
         * @QueryParam(name="villesin", requirements=".*", default="", description="Index de début de la pagination") 
         * @QueryParam(name="villesout", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="sujetsin", requirements=".*", default="", description="Index de début de la pagination") 
         * @QueryParam(name="sujetsout", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="hauteur", requirements=".*", default="", description="Index de début de la pagination") 
         * @QueryParam(name="largeur", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="seriesin", requirements=".*", default="", description="Index de début de la pagination") 
         * @QueryParam(name="seriesout", requirements=".*", default="", description="Index de début de la pagination")
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
         * @QueryParam(name="nbcliche", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="discriminantin", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="discriminantout", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="chromain", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="chromaout", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="supportin", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="supportout", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="datedeprise", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="descriptionin", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="descriptionout", requirements=".*", default="", description="Index de début de la pagination")
         * @QueryParam(name="limit", requirements="\d+", default="", description="Index de fin de la pagination")
     */
    public function getClichesAction(ParamFetcher $paramFetcher,Request $request)
    {
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

            // Insertion des villes
            for ($i=0; $i < count($cliche->getVilles()); $i++) {
                $ville = $cliche->getVilles()[$i]; 
                $v = $em->getRepository(Villes::class)->findOneBy(array('nom' => $ville->getNom()));
                if (empty($v)) { // Si elle n'existe pas dans la db, on l'insert
                    $ville->addClich($cliche);
                }else{ // sinon on modifie celle présente dans $cliche par celle de la base
                    $cliche->getVilles()[$i] = $v;
                    $v->addClich($cliche);
                }
            }
                    $taille = $cliche->getTaille();
                    $t = $em->getRepository(Tailles::class)->findOneBy(array('hauteur_cm' => $taille->getHauteurCm(),'largeur_cm' => $taille->getLargeurCm()));
                    if (empty($t)) {
                        $taille->addClich($cliche);
                        $cliche->setTaille($taille);
                    }else{
                        $t->addClich($cliche);
                        $cliche->setTaille($t);
                    }

                for ($i=0; $i < count($cliche->getSujets()); $i++) {
                    $sujet = $cliche->getSujets()[$i]; 
                    $s = $em->getRepository(Sujets::class)->findOneBy(array('sujet' => $sujet->getSujet()));
                    if (empty($s)) { // Si elle n'existe pas dans la db, on l'insert
                        $sujet->addClich($cliche);
                    }else{ // sinon on modifie celle présente dans $cliche par celle de la base
                        $cliche->getSujets()[$i] = $s;
                        $s->addClich($cliche);
                    }
                }

                    $serie = $cliche->getSerie();
                    $s = $em->getRepository(Series::class)->findOneBy(array('serie' => $serie->getSerie()));
                    if (empty($s)) {
                        $serie->addClich($cliche);
                        $cliche->setSerie($serie);
                    }else{
                        $s->addClich($cliche);
                        $cliche->setSerie($s);
                    }

                for ($i=0; $i < count($cliche->getIndexIconographiques()); $i++) {
                    $newindex = $cliche->getIndexIconographiques()[$i]; 
                    $index = $em->getRepository(IndexIconographiques::class)->findOneBy(array('indexIco' => $newindex->getIndexIco()));
                    if (empty($index)) { // Si elle n'existe pas dans la db, on l'insert
                        $newindex->addClich($cliche);
                    }else{ // sinon on modifie celle présente dans $cliche par celle de la base
                        $cliche->getIndexIconographiques()[$i] = $index;
                        $index->addClich($cliche);
                    }
                }

                for ($i=0; $i < count($cliche->getIndexPersonnes()); $i++) {
                    $newindex = $cliche->getIndexPersonnes()[$i]; 
                    $index = $em->getRepository(IndexPersonnes::class)->findOneBy(array('indexPersonne' => $newindex->getIndexPersonne()));
                    if (empty($index)) { // Si elle n'existe pas dans la db, on l'insert
                        $newindex->addClich($cliche);
                    }else{ // sinon on modifie celle présente dans $cliche par celle de la base
                        $cliche->getIndexPersonnes()[$i] = $index;
                        $index->addClich($cliche);
                    }
                }

                for ($i=0; $i < count($cliche->getCindoc()); $i++) {
                    $newcindoc = $cliche->getCindoc()[$i]; 
                    $cindoc = $em->getRepository(Cindoc::class)->findOneBy(array('cindoc' => $newcindoc->getCindoc()));
                    if (empty($cindoc)) { // Si elle n'existe pas dans la db, on l'insert
                        $newcindoc->addClich($cliche);
                    }else{ // sinon on modifie celle présente dans $cliche par celle de la base
                        $cliche->getCindoc()[$i] = $cindoc;
                        $cindoc->addClich($cliche);
                    }
                }
                
                
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



