<?php

// src/Form/Type/RegistrationType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;


class FormTypeFirma extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nume');
        $builder->add('adresa');
        $builder->add('categorie');
        $builder->add('logoFile', VichImageType::class, [
            'required' => false,
        ]);
    }
}