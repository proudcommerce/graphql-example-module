<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Service;

use OxidEsales\GraphQL\Base\Framework\PermissionProviderInterface;

class PermissionProvider implements PermissionProviderInterface
{
    public function getPermissions(): array
    {
        return [
            'admin' => [
                'CATEGORY_CREATE'
            ]
        ];
    }
}
