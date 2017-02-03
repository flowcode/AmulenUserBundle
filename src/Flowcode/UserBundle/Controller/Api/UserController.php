<?php

namespace Flowcode\UserBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Flowcode\UserBundle\Entity\User;

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
     *      {"name"="email", "dataType"="string", "required"=true, "description"="The user email"},
     *      {"name"="accessToken", "dataType"="string", "required"=false, "description"="The social network user token"},
     *      {"name"="externalId", "dataType"="string", "required"=false, "description"="The social network user id"},
     *      {"name"="socialNetwork", "dataType"="string", "required"=false, "description"="The social network name"}
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

        $user = new User();
        $form = $this->createForm($this->getParameter('form.type.user_register'), $user);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $user = $userService->create($user);

            /* $notificationService = $this->get('flou.user.notification');
              $notificationService->notifyRegister($user);

              if ($request->get('socialNetwork') && $request->get('accessToken') && $request->get('externalId')) {

              //Asocia el usuario a la red social
              $socialNetwork = $this->get('flou.social.network')->getSocialNetwork($request->get('socialNetwork'));

              $userSocialAccount = new UserSocialAccount();
              $userSocialAccount->setUser($user);
              $userSocialAccount->setAccessToken($request->get('accessToken'));
              $userSocialAccount->setSocialNetwork($socialNetwork);
              $userSocialAccount->setExternalId($request->get('externalId'));

              $userSocialAccountService = $this->get('flou.user.social.account');
              $userSocialAccountService->create($userSocialAccount);
              }
             */
            $response = array("success" => true, "message" => "User registered", "code" => ResponseCode::USER_REGISTER_OK);
            return $this->handleView(FOSView::create($response, Response::HTTP_OK)->setFormat("json"));
        }

        $response = array('success' => false, 'errors' => $form->getErrors());
        return $this->handleView(FOSView::create($response, Response::HTTP_CONFLICT)->setFormat("json"));
    }
}
