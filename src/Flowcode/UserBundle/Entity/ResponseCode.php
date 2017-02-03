<?php
namespace Flowcode\UserBundle\Entity;

class ResponseCode
{
    const INCORRECT_PARAMETERS = 105;
    const USER_NOT_FOUND = 50;
    const USER_NOT_ACTIVE = 60;
    const USER_REGISTER_OK = 100;
    const USER_REGISTER_INVALID_USERNAME = 110;
    const USER_REGISTER_INVALID_USER_ACTIVE = 120;
    const USER_REGISTER_IN_SYSTEM = 130;
    const USER_GROUP_REGISTER_OK = 300;
}
