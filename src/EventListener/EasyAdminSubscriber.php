<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Service\FileUploader;
use App\Entity\Photograph;
use App\Entity\Session;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $fileUploader;

    private $photographsDirectory;
    
    private $blankPhotoName = '_blank.jpg';

    public function __construct(FileUploader $fileUploader, $photographsDirectory)
    {
        $this->fileUploader = $fileUploader;
        $this->photographsDirectory = $photographsDirectory;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_persist' => array('setPhotographPhoto'),
            'easy_admin.pre_update'  => array('updatePhotographPhoto'),
            'easy_admin.pre_remove'  => array('removePhotosWhichBelongsToSession'),
            'easy_admin.post_remove' => array('removePhotoFromUploadsDirectory')
        );
    }

    public function setPhotographPhoto(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (!($entity instanceof Photograph)) {
            return;
        }
        
        // download Photograph's entity file from event
        $photoFile = $entity->getPhotoFile();
        
        // if photo haven't been chosen, skip the process of adding it
        if ($photoFile) {
        
            // move file to target directory and return its name
            $photoName = $this->fileUploader->upload($photoFile);

            // asign name to Photograph's entity
            $entity->setPhotoName($photoName);
            
        } else {
            
            // asign _blank to Photograph's entity name
            $entity->setPhotoName($this->blankPhotoName);
            
            // disable default active state
            $entity->setActive(false);
            
            // set date manually
            $entity->setUpdatedAt();
        }
        
        // return Photograph's entity to event
        $event['entity'] = $entity;
    }
    
    public function updatePhotographPhoto(GenericEvent $event) {
        
        $entity = $event->getSubject();

        if (!($entity instanceof Photograph)) {
            return;
        }
        
        // download Photograph's entity file from event
        $photoFile = $entity->getPhotoFile();
        
        // check if new photo is passed to edit
        if ($photoFile) {
        
            $this->removeThePhotoIfItIsNotBlank($entity);

            // move file to target directory and return its name
            $newPhotoName = $this->fileUploader->upload($photoFile);

            // asign name to Photograph's entity
            $entity->setPhotoName($newPhotoName);

            // return Photograph's entity to event
            $event['entity'] = $entity;
        }
    }
    
    public function removePhotosWhichBelongsToSession(GenericEvent $event) {
        
        $entity = $event->getSubject();

        if (!($entity instanceof Session)) {
            return;
        }
        
        // download photos
        $photos = $entity->getPhotographs();
        
        foreach ($photos as $photo) {
            
            $this->removeThePhotoIfItIsNotBlank($photo);
        }
        
        // return Photograph's entity to event
        $event['entity'] = $entity;
    }

    public function removePhotoFromUploadsDirectory(GenericEvent $event) {
        
        $entity = $event->getSubject();

        if (!($entity instanceof Photograph)) {
            return;
        }
        
        $this->removeThePhotoIfItIsNotBlank($entity);
        
        // return Photograph's entity to event
        $event['entity'] = $entity;
    }
    
    private function removeThePhotoIfItIsNotBlank($entity) {
        
        // download Photograph's name
        $photoName = $entity->getPhotoName();

        if ($photoName != $this->blankPhotoName) {

            // remove photo from directory
            unlink($this->photographsDirectory . $photoName);
        }
    }
}