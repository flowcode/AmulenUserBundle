<?php

namespace Flowcode\UserBundle\Tests\Service;

use Flowcode\UserBundle\Tests\BaseTestCase;

class RoleServiceTest extends BaseTestCase
{
    private $roleService;

    public function setUp()
    {
        parent::setUp();
        $this->roleService = $this->getContainer()->get('flowcode.role');
    }

    public function testFindByName_nameExistent_getRole()
    {
        $nameRole = "ROLE_USER";
        $role = $this->roleService->findByName($nameRole);
        $this->assertNotNull($role);
        $this->assertEquals($nameRole, $role->getName());
    }

    public function testFindByName_nameInexistent_getUserGroup()
    {
        $nameRole = "ROLE_SARASA";
        $role = $this->roleService->findByName($nameRole);
        $this->assertNull($role);
    }

    public function testCreate_roleInexistent_createRole()
    {
        $nameRole = "ROLE_SARASA";
        $role = $this->roleService->create($nameRole);
        $this->assertNotNull($role);
        $this->assertEquals($nameRole, $role->getName());
    }

    public function testCreate_roleExistent_notCreateRole()
    {
        $nameRole = "ROLE_USER";
        $roleFinded = $this->roleService->findByName($nameRole);

        $roleCreated = $this->roleService->create($nameRole);
        $this->assertNotNull($roleFinded);
        $this->assertNotNull($roleCreated);

        $this->assertEquals($roleFinded->getId(), $roleCreated->getId());
    }
}
