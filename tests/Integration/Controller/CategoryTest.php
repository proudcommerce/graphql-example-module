<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Tests\Integration\Controller;

use OxidEsales\EshopCommunity\Tests\Integration\Internal\TestContainerFactory;
use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;
use OxidEsales\GraphQL\Example\Dao\CategoryDaoInterface;

class CategoryTest extends TestCase
{
    /** @var CategoryDaoInterface */
    private $categoryDao;

    public function testGetSingleCategoryWithoutParam()
    {
        $this->execQuery('query { category }');
        $this->assertEquals(
            400,
            static::$queryResult['status']
        );
    }

    public function testGetSingleCategoryWithNonExistantCategoryId()
    {
        $this->execQuery('query { category (id: "does-not-exist"){id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertNull(
            static::$queryResult['body']['data']['category']
        );
    }

    public function testGetCategorieListWithoutParams()
    {
        $this->execQuery('query { categories {id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
    }

    public function testCreateSimpleCategory()
    {
        $this->execQuery('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken(static::$queryResult['body']['data']['token']);
        $this->execQuery('mutation { categoryCreate(category: {id: "10", name: "foobar"}) {id, name} }');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['categoryCreate']['name']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreatedById()
    {
        $this->execQuery('query { category (id: "10") {id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['category']['name']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreated()
    {
        $this->execQuery('query { categories {id, name}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['categories'][0]['name']
        );
    }

    /**
     * @depends testCreateSimpleCategory
     */
    public function testGetSimpleCategoryJustCreatedWithExtras()
    {
        $this->execQuery('query { categories {id, name, children { id }, parent { id }}}');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['categories'][0]['name']
        );
        $this->assertEquals(
            [],
            static::$queryResult['body']['data']['categories'][0]['children']
        );
        $this->assertNull(
            static::$queryResult['body']['data']['categories'][0]['parent']
        );
    }

    public function testCreateSimpleCategoryWithAutoId()
    {
        $this->execQuery('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken(static::$queryResult['body']['data']['token']);
        $this->execQuery('mutation { categoryCreate(category: {name: "foobar"}) {id, name} }');
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals(
            'foobar',
            static::$queryResult['body']['data']['categoryCreate']['name']
        );
        $this->assertInternalType(
            'string',
            static::$queryResult['body']['data']['categoryCreate']['id']
        );
    }

    public function testCreateSubCategory()
    {
        $this->markTestSkipped("Does not work although the query works on console.");

        $this->execQuery('query { token (username: "admin", password: "admin") }');
        $this->setAuthToken(static::$queryResult['body']['data']['token']);

        $this->execQuery('mutation { categoryCreate(category: {name: "foobar1"}) {id, name} }');
        $parentid = static::$queryResult['body']['data']['categoryCreate']['id'];
        $this->assertNotNull($parentid);

        $this->execQuery(
            "mutation { categoryCreate(category: {name: \"foobar2\"}, parent: {id: \"$parentid\"}) {id, name, parent} }"
        );
        $this->assertEquals(
            200,
            static::$queryResult['status']
        );
        $this->assertEquals($parentid, static::$queryResult['body']['data']['categoryCreate']['parent']['id']);
    }
}
