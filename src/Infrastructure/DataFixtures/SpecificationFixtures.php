<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Model;
use App\Domain\Entity\Specification;
use App\Domain\Enum\BodyType;
use App\Domain\Enum\DriveType;
use App\Domain\Enum\FuelType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class SpecificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $specifications = [
            [
                'engineVolume' => 2.5,
                'fuelType' => FuelType::HYBRID,
                'bodyType' => BodyType::SEDAN,
                'model' => 'Camry',
                'power' => 180,
                'fuelTankCapacity' => 60,
                'driveType' => DriveType::FWD,
            ],
            [
                'engineVolume' => 1.8,
                'fuelType' => FuelType::DIESEL,
                'bodyType' => BodyType::SEDAN,
                'model' => 'Civic',
                'power' => 140,
                'fuelTankCapacity' => 50,
                'driveType' => DriveType::AWD,
            ],
            [
                'engineVolume' => 5.0,
                'fuelType' => FuelType::GASOLINE,
                'bodyType' => BodyType::COUPE,
                'model' => 'Mustang',
                'power' => 450,
                'fuelTankCapacity' => 60,
                'driveType' => DriveType::FWD,
            ],
            [
                'engineVolume' => 3.0,
                'fuelType' => FuelType::DIESEL,
                'bodyType' => BodyType::SUV,
                'model' => 'X5',
                'power' => 250,
                'fuelTankCapacity' => 85,
                'driveType' => DriveType::AWD,
            ],
        ];

        foreach ($specifications as $specData) {
            $model = $manager->getRepository(Model::class)->findOneBy(['name' => $specData['model']]);

            $specification = new Specification(
                model: $model,
                fuelType: $specData['fuelType'],
                engineVolume: $specData['engineVolume'],
                power: $specData['power'],
                fuelTankCapacity: $specData['fuelTankCapacity'],
                driveType: $specData['driveType'],
                bodyType: $specData['bodyType'],
            );

            $manager->persist($specification);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ModelFixtures::class,
        ];
    }
}
