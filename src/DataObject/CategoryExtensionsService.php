<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQL\Example\DataObject;
use OxidEsales\GraphQL\Base\Service\LegacyServiceInterface;
use OxidEsales\GraphQL\Example\Dao\CategoryDaoInterface;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Category::class)
 */
class CategoryExtensionsService
{
    /** @var CategoryDaoInterface  */
    private $categoryDao;

    /** @var LegacyServiceInterface  */
    private $legacyService;

    public function __construct(CategoryDaoInterface $categoryDao, LegacyServiceInterface $legacyService)
    {
        $this->categoryDao = $categoryDao;
        $this->legacyService = $legacyService;
    }

    /**
     * @Field()
     */
    public function getParent(Category $child): ?Category
    {
        return $this->categoryDao->getCategoryById(
            $child->getParentid(),
            $this->legacyService->getLanguageId(),
            $this->legacyService->getShopId()
        );
    }

    /**
     * @Field()
     * @return Category[]
     */
    public function getChildren(Category $parent): array
    {
        return $this->categoryDao->getCategoriesByParentId(
            $parent->getId(),
            $this->legacyService->getLanguageId(),
            $this->legacyService->getShopId()
        );
    }

}