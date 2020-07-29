<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\AppUser;
use App\Exception\NotFoundException;
use App\Repository\AppUserRepository;
use App\Representation\Pagination;
use App\Service\AppUserService;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class AppUserServiceTest extends TestCase
{
    use ProphecyTrait;

    /** @var AppUserRepository|ObjectProphecy */
    private $appUserRepositoryProphecy;
    /** @var Pagination|ObjectProphecy */
    private $paginationProphecy;
    /** @var AppUser|ObjectProphecy */
    private $appUserProphecy;
    /** @var AppUserService */
    private $service;

    public function test_get_all(): void
    {
        $this->appUserRepositoryProphecy
            ->findAllAppUser(['limit' => 25, 'page' => 1])
            ->shouldBeCalledOnce()
            ->willReturn($this->paginationProphecy->reveal());

        $this->service->getAll();
    }

    public function test_get_one_app_user_not_found_exception(): void
    {
        $this->appUserRepositoryProphecy
            ->find(Argument::type('int'))
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $this->expectException(NotFoundException::class);

        $this->service->getUser(0);
    }

    /**
     * @throws NotFoundException
     */
    public function test_get_one_app_user(): void
    {
        $this->appUserRepositoryProphecy
            ->find(Argument::type('int'))
            ->shouldBeCalledOnce()
            ->willReturn($this->appUserProphecy->reveal());

        $this->service->getUser(0);
    }

    public function test_update_not_found_exception(): void
    {
        $this->appUserRepositoryProphecy
            ->find(Argument::type('int'))
            ->shouldBeCalledOnce()
            ->willReturn(null);

        $this->expectException(NotFoundException::class);

        $this->service->update([], 0);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->appUserRepositoryProphecy = $this->prophesize(AppUserRepository::class);
        $this->service = new AppUserService($this->appUserRepositoryProphecy->reveal());
        $this->paginationProphecy = $this->prophesize(Pagination::class);
        $this->appUserProphecy = $this->prophesize(AppUser::class);
    }
}
