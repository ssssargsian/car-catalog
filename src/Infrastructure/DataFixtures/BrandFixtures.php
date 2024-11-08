<?php

declare(strict_types=1);

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class BrandFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $brandNames = ['Toyota', 'Honda', 'Ford', 'BMW'];

        foreach ($brandNames as $name) {
            $manager->persist(new Brand(name: $name));
        }

        $manager->flush();
    }
}
