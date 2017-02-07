<?php

namespace Flowcode\UserBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Response;
use Flowcode\UserBundle\Entity\ResponseCode;
use Flowcode\UserBundle\Exception\ExistentUserException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends FOSRestController
{

    /**
     * Login user
     * 
     * #### Response ok #### 
     * {"token": "token"}
     * #### Response fail #### 
     * {"code": 401, "message": "Bad Credentials"}    
     * 
     * @ApiDoc(
     *  description="Login user",
     *  section="User Bundle",
     *  authentication = false,
     * parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="The user username"},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="The user password"},
     *  },
     *  statusCodes={
     *         200="Returned when successful",
     *         404="When not all required parameters was found"
     *         }
     * )
     */
    public function checkAction(Request $request)
    {
        
    }

    /**
     * Register user
     * 
     * #### Response ok ####
     * {
     *   "success": true,
     *   "message": "User registered",
     *   "code": 100
     * }
     * #### Response fail ####
     * 
     *
     * {
     *    "success": false,
     *    "message": "The username already exists",
     *    "code": 130
     * }
     * 
     * @ApiDoc(
     *  description="Register user",
     *  section="User Bundle",
     *  authentication = false,
     * parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="The user name"},
     *      {"name"="plainPassword", "dataType"="string", "required"=true, "description"="The user password"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="The user email"}
     * },
     *  statusCodes={
     *         200="Returned when successful",
     *         409="Returned in conflict",
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
            $notificationService = $this->get('flowcode.user.notification');
            $userService->generateRegisterToken($user);
            $activateAccountLink = $this->generateUrl('flowcode_user_activate_account', array('token' => $user->getRegisterToken()), UrlGeneratorInterface::ABSOLUTE_URL);
            $notificationService->notifyRegister($user, $activateAccountLink);
            $response = array("success" => true, "message" => $activateAccountLink, "code" => ResponseCode::USER_REGISTER_OK);
            return $this->handleView(FOSView::create($response, Response::HTTP_OK)->setFormat("json"));
        }

        $response = array('success' => false, 'errors' => $form->getErrors());
        return $this->handleView(FOSView::create($response, Response::HTTP_CONFLICT)->setFormat("json"));
    }


}
