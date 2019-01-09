<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Shop controller.
 *
 * @Route("admin/shop")
 */
class ShopController extends Controller
{
    /**
     * Lists all shop entities.
     *
     * @Route("/", name="admin_shop_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shops = $em->getRepository('AppBundle:Shop')->findAll();

        return $this->render('shop/index.html.twig', array(
            'shops' => $shops,
        ));
    }

    /**
     * Creates a new shop entity.
     *
     * @Route("/new", name="admin_shop_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $shop = new Shop();
        $form = $this->createForm('AppBundle\Form\ShopType', $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shop);
            $em->flush();

            return $this->redirectToRoute('admin_shop_show', array('id' => $shop->getId()));
        }

        return $this->render('shop/new.html.twig', array(
            'shop' => $shop,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a shop entity.
     *
     * @Route("/{id}", name="admin_shop_show")
     * @Method("GET")
     */
    public function showAction(Shop $shop)
    {
        $deleteForm = $this->createDeleteForm($shop);

        return $this->render('shop/show.html.twig', array(
            'shop' => $shop,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing shop entity.
     *
     * @Route("/{id}/edit", name="admin_shop_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Shop $shop)
    {
        $deleteForm = $this->createDeleteForm($shop);
        $editForm = $this->createForm('AppBundle\Form\ShopType', $shop);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_shop_edit', array('id' => $shop->getId()));
        }

        return $this->render('shop/edit.html.twig', array(
            'shop' => $shop,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a shop entity.
     *
     * @Route("/{id}", name="admin_shop_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Shop $shop)
    {
        $form = $this->createDeleteForm($shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shop);
            $em->flush();
        }

        return $this->redirectToRoute('admin_shop_index');
    }

    /**
     * Creates a form to delete a shop entity.
     *
     * @param Shop $shop The shop entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Shop $shop)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_shop_delete', array('id' => $shop->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
