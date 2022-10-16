<?php

declare(strict_types=1);

namespace Owl\Bundle\CategoryBundle\DependencyInjection\Compiler;

use Owl\Component\Category\Factory\CategoryFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class RegisterFileFactoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getParameter('owl.category.subjects') as $subject => $configuration) {
            $factory = $container->findDefinition('owl.factory.' . $subject . '_category');

            $categoryFactoryDefinition = new Definition(CategoryFactory::class, [$factory]);
            $categoryFactoryDefinition->setPublic(true);

            $container->setDefinition(sprintf('owl.factory.' . $subject . '_category'), $categoryFactoryDefinition);
        }
    }
}
