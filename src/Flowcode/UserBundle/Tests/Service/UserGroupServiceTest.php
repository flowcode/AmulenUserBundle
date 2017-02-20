<?php

namespace Flowcode\UserBundle\Tests\Service;

use Flowcode\UserBundle\Tests\BaseTestCase;

class UserGroupServiceTest extends BaseTestCase
{
    private $userGroupService;

    public function setUp()
    {
        parent::setUp();
        $this->userGroupService = $this->getContainer()->get('flowcode.user.group');
    }

    public function testFindByName_nameExistent_getUserGroup()
    {
        $nameGroup = "group_user";
        $userGroup = $this->userGroupService->findByName($nameGroup);
        $this->assertNotNull($userGroup);
        $this->assertEquals($nameGroup, $userGroup->getName());
    }

    public function testFindByName_nameInexistent_getUserGroup()
    {
        $nameGroup = "group_admin";
        $userGroup = $this->userGroupService->findByName($nameGroup);
        $this->assertNull($userGroup);
    }

    public function testCreate_userGroupInexistent_createUserGroup()
    {
        $nameGroup = "group_admin";
        $userGroup = $this->userGroupService->create($nameGroup);
        $this->assertNotNull($userGroup);
        $this->assertEquals($nameGroup, $userGroup->getName());
        $this->assertEmpty($userGroup->getRoles());
    }

    public function testCreate_userGroupExistent_notCreateUserGroup()
    {
        $nameGroup = "group_user";
        $userGroupFinded = $this->userGroupService->findByName($nameGroup);

        $userGroupCreated = $this->userGroupService->create($nameGroup);
        $this->assertNotNull($userGroupFinded);
        $this->assertNotNull($userGroupCreated);
        $this->assertEquals($userGroupFinded->getId(), $userGroupCreated->getId());
        $this->assertEquals(1, sizeof($userGroupFinded->getRoles()));
        $this->assertEquals("ROLE_USER", $userGroupFinded->getRoles()[0]->getName());
    }
}
