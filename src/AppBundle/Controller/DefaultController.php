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
            //'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
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
        //Get ALL Stores Nearby
        $allstoreNearby=$em->getRepository('AppBundle:Shop')->findBy(array(
            'city' => $city
        ));
        //Get Favorite Store By User
        $favStores=$em->getRepository('AppBundle:userShop')->findBy(array(
            'user' => $user,
            'isfavorite' => true
        ));

        $favArr=[];
        foreach ($favStores as $key => $fav){
            $favArr[$key]=$fav->getShop();
        }
        $stores=[];
        if(!empty($favArr)){
            foreach ($allstoreNearby as $key => $storeNear){
                if(in_array($storeNear,$favArr)){
                    //remove the store which is already favorite
                    unset($allstoreNearby[$key]);
                    $stores = array_values($allstoreNearby);
                }
            }
        }else{
            $stores= $allstoreNearby;
        }
        //check if disliked time is Passed
        $filteredStores= [];
        foreach ($stores as $key => $store){
            $tmpUS= $em->getRepository('AppBundle:userShop')->findBy(array(
                'user' => $user,
                'shop' => $store,
                'isfavorite' => false
            ));
            if(!empty($tmpUS)){
                //var_dump($tmpUS[0]->getDislikedTime());
                $now= new \DateTime("now");
                //var_dump($now);
                if($now < $tmpUS[0]->getDislikedTime()){
                    //var_dump('dislike still');
                }else{
                    //var_dump('dislike removed');
                    $filteredStores[$key]=$store;

                }
            }else{
                //empty result
                $filteredStores[$key]=$store;
            }
        }
        //$filteredStores = array_values($filteredStores);

        return $this->render('default/main.html.twig', [
            'user' => $this->getUser(),
            'stores' => $filteredStores
        ]);
    }

    /**
     * @Route("/favorite", name="favorite")
     */
    public function favoriteAction(Request $request)
    {
        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $favoriteStores =$em->getRepository('AppBundle:userShop')->findBy(array(
            'user' => $user,
            'isfavorite' => true
        ));
        return $this->render('default/favorite.html.twig', [
            'user' => $user,
            'favoriteStores'=> $favoriteStores
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

        $us= $em->getRepository('AppBundle:userShop')->findBy(array(
            'user' => $user,
            'shop' => $shop
        ));
        $userShops=null;
        if(!empty($us)){
            $userShops=$us[0];
        }else{
            $userShops=new userShop();
        }


        if(!empty($shop)){
            $userShops
                ->setUser($user)
            ;
            $userShops
                ->setDislikedTime(new \DateTime("now"))
                ->setIsfavorite(true)
                ->setShop($shop)
                ;

            $em->persist($userShops);
            $em->flush();
        }

        return $this->redirectToRoute('main');

    }
    /**
     * @Route("/favorite/remove/{id}", name="remove_fav_store")
     */
    public function removeFavShopAction(Request $request,userShop $usershop)
    {
        $em = $this->getDoctrine()->getManager();
        if(!empty($usershop)){
            $em->remove($usershop);
            $em->flush();
        }

        return $this->redirectToRoute('favorite');

    }

    /**
     * @Route("/favorite/dislike/{user_id}/{shop_id}", name="dislike_store")
     * @ParamConverter(name="user", class="AppBundle:User",options={"id" = "user_id"})
     * @ParamConverter(name="shop", class="AppBundle:Shop",options={"id" = "shop_id"})
     */
    public function disLikeShopAction(Request $request,Shop $shop,User $user)
    {
        $em = $this->getDoctrine()->getManager();

        $userShops=new userShop();

        if(!empty($shop)){
            $userShops
                ->setIsfavorite(false)
                ->setUser($user)
            ;
            $userShops
                ->setDislikedTime(new \DateTime("+2 hour"))
                ->setShop($shop)
            ;

            $em->persist($userShops);
            $em->flush();
        }

        return $this->redirectToRoute('main');
    }

}
