<?php

namespace Flowcode\UserBundle\Controller;

use Flowcode\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Login controller.
 *
 * @Route("/profile")
 *
 */
class ProfileController extends Controller
{
    /**
     * Profile.
     *
     * @Route("/", name="amulen_user_profile")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        return array(
            'entity' => $user,
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/edit", name="amulen_profile_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction()
    {
        $entity = $this->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm($this->get("form.type.user_profile"), $entity, array(
            'action' => $this->generateUrl('amulen_profile_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="amulen_profile_update")
     * @Method("PUT")
     * @Template("FlowcodeUserBundle:Profile:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AmulenUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            /* get user manager */
            $userManager = $this->container->get('flowcode.user');
            $userManager->update($entity);

            return $this->redirect($this->generateUrl('amulen_user_profile'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }


}
