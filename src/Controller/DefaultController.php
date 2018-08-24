<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends AbstractController {
   
    /**
     * @Route("/", name="homepage")
     */
    public function homepage() {
        
        $session = $this->getDoctrine()->getManager()->getRepository('App\Entity\Session')->findOneBy(array(
            // change in a future
            "name" => 'Cats'
        ));
        
        $photosNames = array();
            
        foreach($session->getPhotographs() as $photo) {

            array_push($photosNames, $photo->getPhotoName());
        }
        
        return $this->render('default/homepage.html.twig', array(
            'photos' => $photosNames
        ));
    }
    
    /**
     * @Route("/get-sessions", name="get_sessions")
     * 
     * @return JsonResponse
     */
    public function getSessions() {

        $sessions = $this->getDoctrine()->getManager()->getRepository('App\Entity\Session')->findAll();
        
        if ($sessions) {

            $encoders = array(
                new JsonEncoder()
            );
            $normalizers = array(
                
                (new ObjectNormalizer())
                    ->setCircularReferenceHandler(function($object)
                    {
                        return $object->__toString();
                    })
            );

            $serializer = new Serializer($normalizers, $encoders);

            $data = $serializer->serialize($sessions, 'json');

            return new JsonResponse($data, 200, array(), true);
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'No data'            
        ));
    }
    
    /**
     * @Route("/get-photos", name="get_photos")
     * @Method({"POST"})
     * 
     * @return JsonResponse
     */
    public function getPhotos(Request $request) {
        
        $requestData = json_decode($request->getContent());

        $session = $requestData->sessionName;
        
        if ($session) {
            
            $session = $this->getDoctrine()->getManager()->getRepository('App\Entity\Session')->findOneBy(array(
                "name" => $session
            ));
            
            $photosNames = array();
            
            foreach($session->getPhotographs() as $photo) {
                
                array_push($photosNames, $photo->getPhotoName());
            }
            
            if (is_array($photosNames)) {

                $encoders = array(
                    new JsonEncoder()
                );
                $normalizers = array(

                    (new ObjectNormalizer())
                        ->setCircularReferenceHandler(function($object)
                        {
                            return $object->__toString();
                        })
                );

                $serializer = new Serializer($normalizers, $encoders);

                $data = $serializer->serialize($photosNames, 'json');

                return new JsonResponse($data, 200, array(), true);
            }
        }
        
        return new JsonResponse(array(
            'type'    => 'error',
            'message' => 'No data'
        ));
    }
}