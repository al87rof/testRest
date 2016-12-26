<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.12.2016
 * Time: 11:04
 */

namespace AppBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;




class UsersController extends FOSRestController
{
    private $serializer;

    public function  __construct()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function getUsersAction()
    {
        $response = new Response();
        $repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $users = $repo->findAll();

        if($users == null){
            $response->setContent('Users not found!');
            return $response;
        }

        $jsonContent = $this->serializer->serialize($users[0], 'json');

        $response->setContent($jsonContent);
        return $response;
    } // "get_users"            [GET] /users

    public function getUserAction($slug)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $users = $repo->find($slug);
        $jsonContent = $this->serializer->serialize($users, 'json');
        $response = new Response();
        $response->setContent($jsonContent);
        return $response;

    } // "get_user"             [GET] /users/{slug}


    public function postUsersAction()
    {
        
    } // "post_users"           [POST] /users

}