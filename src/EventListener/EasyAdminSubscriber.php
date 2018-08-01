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
    
    // TODO: change it to global paramter value
    private $targetDirectory = './../public/uploads/photographs/';

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
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
        
        // move file to target directory and return its name
        $photoName = $this->fileUploader->upload($photoFile);
        
        // asign name to Photograph's entity
        $entity->setPhotoName($photoName);

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
        
            // download Photograph's name and remove previous photo from directory
            $photoName = $entity->getPhotoName();
            unlink($this->targetDirectory . $photoName);

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
            
            // remove photo from directory
            unlink($this->targetDirectory . $photo->getPhotoName());
        }
        
        // return Photograph's entity to event
        $event['entity'] = $entity;
    }

    public function removePhotoFromUploadsDirectory(GenericEvent $event) {
        
        $entity = $event->getSubject();

        if (!($entity instanceof Photograph)) {
            return;
        }
        
        // download Photograph's name
        $photoName = $entity->getPhotoName();
        
        // remove photo from directory
        unlink($this->targetDirectory . $photoName);
        
        // return Photograph's entity to event
        $event['entity'] = $entity;
    }
}