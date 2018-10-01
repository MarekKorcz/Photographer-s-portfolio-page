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
        
        $firstSession = $this->getDoctrine()->getManager()->getRepository('App\Entity\Session')->findOneBy(
            array(
                'isActive' => true
            )
        );
        
        if ($firstSession) {

            return $this->render('default/homepage.html.twig', array(
                'sessionPhotos' => $firstSession->getPhotographs()
            ));
        }
        
        return $this->render('default/homepage.html.twig');
    }
    
    /**
     * @Route("/contact", name="contact")
     */
    public function contact() {
        
        return $this->render('default/contact.html.twig');
    }
    
    /**
     * @Route("/about", name="about")
     */
    public function about() {
        
        return $this->render('default/about.html.twig');
    }
    
    /**
     * @Route("/get-sessions", name="get_sessions")
     * 
     * @return JsonResponse
     */
    public function getSessions() {

        $sessions = $this->getDoctrine()->getManager()->getRepository('App\Entity\Session')->findBy(array(
            'isActive' => true
        ));
        
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

        $sessionName = $requestData->sessionName;
        
        if ($sessionName) {
            
            $session = $this->getDoctrine()->getManager()->getRepository('App\Entity\Session')->findOneBy(array(
                "name" => $sessionName
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