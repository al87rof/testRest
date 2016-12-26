<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.12.2016
 * Time: 11:04
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
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

    public function getUserAction($id)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:User');
        $users = $repo->find($id);
        $jsonContent = $this->serializer->serialize($users, 'json');
        $response = new Response();
        $response->setContent($jsonContent);
        return $response;

    } // "get_user"             [GET] /users/{slug}


    public function newUserAction(User $user)
    {
        $repo = $this->getDoctrine()->getRepository('User');
        $userInfo = $repo->findBy(['email'=>$user->getEmail()]);
        if($userInfo[0] instanceof User){
            return new Response('User already exist');
        }
        if($user instanceof User){
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush($user);
            return new Response('User add success');
        }


    } // "post_user"           [POST] /user

}