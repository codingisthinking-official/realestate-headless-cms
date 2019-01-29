<?php

namespace AdminBundle\Controller;

use AdminBundle\Forms\UserForm;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/users", name="admin.users")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $paginator  = $this->get('knp_paginator');

        $this->get('admin.breadcrumb')->attach('List of users', $this->generateUrl('admin.users'));

        return [
            'users' => $paginator->paginate(
                $em->getRepository('AppBundle:User')->findAll(),
                $request->query->getInt('page', 1),
                30 
            ),
        ];
    }

    /**
     * @Route("/users/{id}/edit/", name="admin.users.edit")
     * @Template()
     */
    public function editAction(Request $request, User $user)
    {
        $userManager = $this->get('fos_user.user_manager');

        $this->get('admin.breadcrumb')->attach('List of users', $this->generateUrl('admin.users'));
        $this->get('admin.breadcrumb')->attach(
            $user->getUsername(), $this->generateUrl('admin.users.edit', ['id' => $user->getId()])
        );

        $form = $this->createForm(new UserForm(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $userManager->updateUser($user);

            $this->get('session')->getFlashBag()->add('success', 'Saved.');

            return $this->redirectToRoute('admin.users');
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/users/{id}/lock/", name="admin.users.lock")
     */
    public function lockAction(Request $request, User $user)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user->setLocked($user->isLocked() == false);
        $userManager->updateUser($user);

        if ($user->isLocked()) {
            $this->get('session')->getFlashBag()->add('success', 'Blocked.');
        } else {
            $this->get('session')->getFlashBag()->add('success', 'Unblocked.');
        }

        return $this->redirectToRoute('admin.users');
    }

    /**
     * @Route("/users/{id}/delete/", name="admin.users.delete")
     */
    public function deleteAction(Request $request, User $user)
    {
        $userManager = $this->get('fos_user.user_manager');
        $userManager->deleteUser($user);

        $this->get('session')->getFlashBag()->add('success', 'Removed user.');
        return $this->redirectToRoute('admin.users');
    }
}
