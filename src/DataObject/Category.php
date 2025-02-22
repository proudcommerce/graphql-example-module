<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\DataObject;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\GraphQL\Example\Dao\CategoryDaoInterface;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
class Category
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $parentid;

    public function __construct(
        string $id,
        string $name,
        string $parentid
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->parentid = $parentid;
    }

    /**
     * unique ID
     *
     * @Field(outputType="ID")
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @Field()
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getParentid(): string
    {
        return $this->parentid;
    }
}
