<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\Specification;

use App\Application\Command\Specification\CreateCommand;
use App\Application\Command\Specification\CreateCommandHandler;
use App\Domain\Entity\Brand;
use App\Domain\Entity\Model;
use App\Domain\Entity\Specification;
use App\Domain\Enum\BodyType;
use App\Domain\Enum\DriveType;
use App\Domain\Enum\FuelType;
use App\Domain\Repository\ModelRepositoryInterface;
use App\Domain\Repository\SpecificationRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

final class CreateSpecificationCommandHandlerTest extends TestCase
{
    private ModelRepositoryInterface $modelRepository;
    private SpecificationRepositoryInterface $specificationRepository;
    private CreateCommandHandler $handler;

    protected function setUp(): void
    {
        $this->modelRepository = $this->createMock(ModelRepositoryInterface::class);
        $this->specificationRepository = $this->createMock(SpecificationRepositoryInterface::class);

        $this->handler = new CreateCommandHandler(
            $this->modelRepository,
            $this->specificationRepository
        );
    }

    public function testCreateSpecificationSuccess(): void
    {
        $model = new Model('Test Model', new Brand('Test Brand'));

        $this->modelRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($model);

        $command = new CreateCommand(
            modelId: $model->getId()->toString(),
            fuelType: FuelType::GASOLINE,
            engineVolume: 2.0,
            power: 250,
            fuelTankCapacity: 60,
            driveType: DriveType::AWD,
            bodyType: BodyType::SUV
        );

        $specification = $this->handler->__invoke($command);

        $this->assertInstanceOf(Specification::class, $specification);
        $this->assertEquals(250, $specification->getPower());
        $this->assertEquals(FuelType::GASOLINE, $specification->getFuelType());
    }

    public function testCreateSpecificationWithInvalidModelThrowsException(): void
    {
        $this->modelRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $this->expectException(BadRequestException::class);

        $command = new CreateCommand(
            modelId: 'invalid-id',
            fuelType: FuelType::GASOLINE,
            engineVolume: 2.0,
            power: 250,
            fuelTankCapacity: 60,
            driveType: DriveType::AWD,
            bodyType: BodyType::SUV
        );

        $this->handler->__invoke($command);
    }
}
