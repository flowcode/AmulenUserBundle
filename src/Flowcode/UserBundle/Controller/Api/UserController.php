<?php

namespace Flowcode\UserBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Response;
use Flowcode\UserBundle\Entity\ResponseCode;
use Flowcode\UserBundle\Exception\ExistentUserException;

class UserController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  description="Login user",
     *  section="User Bundle",
     * parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="The user username or email"},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="The user password"},
     *  },
     *  statusCodes={
     *         201="Returned when successful",
     *         404={
     *           "Returned when the user exceed the number of devices registred."
     *         }
     *     }
     * )
     */
    public function checkAction(Request $request)
    {
        
    }

    /**
     * @ApiDoc(
     *  description="Register user",
     *  section="User Bundle",
     * parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="The user name"},
     *      {"name"="plainPassword", "dataType"="string", "required"=true, "description"="The user password"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="The user email"}
     * },
     *  statusCodes={
     *         200="Returned when successful",
     *         404="When not all required parameters was found",
     *     }
     * )
     */
    public function registerAction(Request $request)
    {
        $userService = $this->get('flowcode.user');

        $user = $userService->createNewUser();

        $form = $this->createForm($this->getParameter('form.type.user_register.api.class'), $user);
        $form->submit($request->request->all(), true);

        if ($form->isValid()) {
            try {
                $user = $userService->create($user);
            } catch (ExistentUserException $ex) {
                $response = array("success" => false, "message" => $ex->getMessage(), "code" => ResponseCode::USER_REGISTER_IN_SYSTEM);
                return $this->handleView(FOSView::create($response, Response::HTTP_OK)->setFormat("json"));
            }
            $response = array("success" => true, "message" => "User registered", "code" => ResponseCode::USER_REGISTER_OK);
            return $this->handleView(FOSView::create($response, Response::HTTP_OK)->setFormat("json"));
        }

        $response = array('success' => false, 'errors' => $form->getErrors());
        return $this->handleView(FOSView::create($response, Response::HTTP_CONFLICT)->setFormat("json"));
    }
}
