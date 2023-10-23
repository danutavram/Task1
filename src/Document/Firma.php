<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


/**
 * @MongoDB\Document
 * @Vich\Uploadable
 */

class Firma
{
    /**
     * @MongoDB\Id(strategy="UUID")
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     */
    private $nume;

    /**
     * @MongoDB\Field(type="string")
     */
    private $adresa;

    /**
     * @MongoDB\Field(type="string")
     */
    private $categorie;
    
    /**
     * @MongoDB\Field(type="integer")
     */
    private $views;

/**
     * @Vich\UploadableField(mapping="firma_logo", fileNameProperty="logo")
     */
    private $logoFile;

    /**
     * @ODM\Field(type="string")
     */
    private $logo;

    // ...

    public function setLogoFile(File $image = null)
    {
        $this->logoFile = $image;
    }

    public function getLogoFile()
    {
        return $this->logoFile;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    public function getLogo()
    {
        return $this->logo;
    }


    // Getter și Setter pentru nume

    // Getter pentru id
    public function getId()
    {
        return $this->id;
    }
    public function getNume()
    {
        return $this->nume;
    }

    public function setNume($nume)
    {
        $this->nume = $nume;
    }

    // Getter și Setter pentru adresa
    public function getAdresa()
    {
        return $this->adresa;
    }

    public function setAdresa($adresa)
    {
        $this->adresa = $adresa;
    }

    // Getter și Setter pentru categorie
    public function getCategorie()
    {
        return $this->categorie;
    }

    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    public function getViews()
    {
        return $this->views;
    }

    public function setViews($views)
    {
        $this->views = $views;
    }
}
