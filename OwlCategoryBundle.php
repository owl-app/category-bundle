<?php

declare(strict_types=1);

namespace Owl\Bundle\CategoryBundle;

use Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

final class OwlCategoryBundle extends AbstractResourceBundle
{
    /**
     * @return string[]
     *
     * @psalm-return list{'doctrine/orm'}
     */
    public function getSupportedDrivers(): array
    {
        return [
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    /**
     * @psalm-suppress MismatchingDocblockReturnType https://github.com/vimeo/psalm/issues/2345
     *
     * @return string
     *
     * @psalm-return 'Owl\Component\Category\Model'
     */
    protected function getModelNamespace(): string
    {
        return 'Owl\Component\Category\Model';
    }
}
