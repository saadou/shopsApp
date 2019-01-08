<?php
/**
 * Created by PhpStorm.
 * User: STARDIRECT
 * Date: 12/02/2018
 * Time: 12:48
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Emploi;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use AppBundle\ImageUpload;

class ImageUploadListener
{
    private $uploader;

    public function __construct(ImageUpload $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        /*
        //added this to test
        if (!$entity instanceof User) {
            return;
        }
        $fileName = $entity->getImage();
        $entity->setImage($fileName);
        //end adding
        */
        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof Emploi) {
            return;
        }

        $file = $entity->getImageName();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setImageName($fileName);
        }
        /*
        // upload only works for Product entities
        if (!$entity instanceof User) {
            return;
        }

        $file = $entity->getImage();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file);
        $entity->setImage($fileName);
        */
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Emploi) {
            return;
        }

        if ($fileName = $entity->getImageName()) {
            //$entity->setBrochure(new File($this->getParameter('images_directory').'/'.$fileName));
            //$entity->setImage(new File($this->uploader->getTargetDir().'/'.$fileName));
            //$entity->setImage($fileName);

        }
    }

}