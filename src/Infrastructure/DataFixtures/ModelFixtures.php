<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Brand;
use App\Domain\Entity\Model;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ModelFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $models = [
            ['name' => 'Camry', 'brand' => 'Toyota'],
            ['name' => 'Civic', 'brand' => 'Honda'],
            ['name' => 'Mustang', 'brand' => 'Ford'],
            ['name' => 'X5', 'brand' => 'BMW'],
        ];

        foreach ($models as $modelData) {
            $manager->persist(
                new Model(
                    name: $modelData['name'],
                    brand: $manager->getRepository(Brand::class)->findOneBy(['name' => $modelData['brand']]),
                ),
            );
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BrandFixtures::class,
        ];
    }
}
