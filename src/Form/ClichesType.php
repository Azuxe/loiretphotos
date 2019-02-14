<?php

namespace App\Form;

use App\Entity\Cliches;
use App\Form\TaillesType;
use App\Form\SeriesType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;



class ClichesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('date_de_prise')
            ->add('fichier')
            ->add('support')
            ->add('chroma')
            ->add('discriminant')
            ->add('nb_Cliche')
            ->add('note_de_bas_De_Page')
            ->add('remarque')
            ->add('villes', CollectionType::class, [
                'entry_type' => VillesType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
            ->add('taille',TaillesType::class)
            ->add('sujets', CollectionType::class, [
                'entry_type' => SujetsType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
            ->add('serie', SeriesType::class)
            ->add('indexPersonnes', CollectionType::class, [
                'entry_type' => IndexPersonnesType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
            ->add('indexIconographiques', CollectionType::class, [
                'entry_type' => IndexIconographiquesType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
            ->add('cindoc', CollectionType::class, [
                'entry_type' => CindocType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cliches::class,
            'csrf_protection' => false,
        ]);
    }
}
