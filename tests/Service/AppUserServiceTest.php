<?php

declare(strict_types=1);

namespace App\Tests\Service;

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

    protected function setUp(): void
    {
        parent::setUp();
        $this->appUserRepositoryProphecy = $this->prophesize(AppUserRepository::class);
        $this->service = new AppUserService($this->appUserRepositoryProphecy->reveal());
        $this->paginationProphecy = $this->prophesize(Pagination::class);
    }
}
