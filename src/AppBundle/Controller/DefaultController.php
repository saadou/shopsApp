<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Shop;
use AppBundle\Entity\User;
use AppBundle\Entity\userShop;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {




        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/main", name="main")
     */
    public function mainAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $city=$this->getUser()->getCity();
        //var_dump($city);
        $user=$this->getUser();
        //var_dump($user->getUsername());
        $allstoreNearby=$em->getRepository('AppBundle:Shop')->findBy(array(
            'city' => $city
        ));

        return $this->render('default/main.html.twig', [
            'user' => $this->getUser(),
            'allNearStores' => $allstoreNearby
        ]);
    }

    /**
     * @Route("/favorite", name="favorite")
     */
    public function favoriteAction(Request $request)
    {

        return $this->render('default/favorite.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/favorite/like/{user_id}/{shop_id}", name="like_store")
     * @ParamConverter(name="user", class="AppBundle:User",options={"id" = "user_id"})
     * @ParamConverter(name="shop", class="AppBundle:Shop",options={"id" = "shop_id"})
     */
    public function likeShopAction(Request $request,Shop $shop,User $user)
    {

        $em = $this->getDoctrine()->getManager();

        $userShops=new userShop();

        if(!empty($shop)){
            $userShops
                //->setIsfavorite(true)
                //->setShop($shop)
                ->setUser($user)
            ;
            $userShops
                ->setIsfavorite(true)
                ->setShop($shop)
                ;

            $em->persist($userShops);
            $em->flush();
        }

        return $this->redirectToRoute('main');

    }
}
