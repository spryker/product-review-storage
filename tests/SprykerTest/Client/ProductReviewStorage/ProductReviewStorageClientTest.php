<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductReviewStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductReviewStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductReviewStorage\Dependency\Client\ProductReviewStorageToStorageInterface;
use Spryker\Client\ProductReviewStorage\Dependency\Service\ProductReviewStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductReviewStorage\ProductReviewStorageClient;
use Spryker\Client\ProductReviewStorage\ProductReviewStorageDependencyProvider;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Shared\ProductReviewStorage\ProductReviewStorageConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductReviewStorage
 * @group ProductReviewStorageClientTest
 * Add your own group annotations below this line
 */
class ProductReviewStorageClientTest extends Unit
{
    /**
     * @var array<string, mixed>
     */
    protected const PRODUCT_REVIEW_DATA_1 = [
        ProductReviewStorageTransfer::ID_PRODUCT_ABSTRACT => 1,
        ProductReviewStorageTransfer::AVERAGE_RATING => 4.5,
        ProductReviewStorageTransfer::REVIEW_COUNT => 10,
    ];

    /**
     * @var array<string, mixed>
     */
    protected const PRODUCT_REVIEW_DATA_2 = [
        ProductReviewStorageTransfer::ID_PRODUCT_ABSTRACT => 2,
        ProductReviewStorageTransfer::AVERAGE_RATING => 3,
        ProductReviewStorageTransfer::REVIEW_COUNT => 2,
    ];

    protected ProductReviewStorageClientTester $tester;

    public function testFindProductAbstractReviewBulkReturnsReviewTransfers(): void
    {
        $expectedProductReviewStorageData = [
            json_encode(static::PRODUCT_REVIEW_DATA_1),
            json_encode(static::PRODUCT_REVIEW_DATA_2),
        ];

        $this->mockStorageClientMock($expectedProductReviewStorageData);
        $this->mockSynchronizationServiceMock([
            static::PRODUCT_REVIEW_DATA_1[ProductReviewStorageTransfer::ID_PRODUCT_ABSTRACT],
            static::PRODUCT_REVIEW_DATA_2[ProductReviewStorageTransfer::ID_PRODUCT_ABSTRACT],
        ]);

        // Act
        $productReviewStorageTransfers = (new ProductReviewStorageClient())->findProductAbstractReviewBulk([
            static::PRODUCT_REVIEW_DATA_1[ProductReviewStorageTransfer::ID_PRODUCT_ABSTRACT],
            static::PRODUCT_REVIEW_DATA_2[ProductReviewStorageTransfer::ID_PRODUCT_ABSTRACT],
        ]);

        // Assert
        $this->assertCount(2, $productReviewStorageTransfers);

        $firstTransfer = $productReviewStorageTransfers[0];
        $secondTransfer = $productReviewStorageTransfers[1];

        $this->assertInstanceOf(ProductReviewStorageTransfer::class, $firstTransfer);
        $this->assertSame(static::PRODUCT_REVIEW_DATA_1[ProductReviewStorageTransfer::ID_PRODUCT_ABSTRACT], $firstTransfer->getIdProductAbstract());
        $this->assertSame(static::PRODUCT_REVIEW_DATA_1[ProductReviewStorageTransfer::AVERAGE_RATING], $firstTransfer->getAverageRating());
        $this->assertSame(static::PRODUCT_REVIEW_DATA_1[ProductReviewStorageTransfer::REVIEW_COUNT], $firstTransfer->getReviewCount());

        $this->assertInstanceOf(ProductReviewStorageTransfer::class, $secondTransfer);
        $this->assertSame(static::PRODUCT_REVIEW_DATA_2[ProductReviewStorageTransfer::ID_PRODUCT_ABSTRACT], $secondTransfer->getIdProductAbstract());
        $this->assertSame(static::PRODUCT_REVIEW_DATA_2[ProductReviewStorageTransfer::AVERAGE_RATING], $secondTransfer->getAverageRating());
        $this->assertSame(static::PRODUCT_REVIEW_DATA_2[ProductReviewStorageTransfer::REVIEW_COUNT], $secondTransfer->getReviewCount());
    }

    protected function mockSynchronizationServiceMock(array $idProductAbstracts): void
    {
        $synchronizationKeyBuilderPluginMock = $this->getMockBuilder(SynchronizationKeyGeneratorPluginInterface::class)->getMock();

        $synchronizationKeyBuilderPluginMock->expects($this->exactly(count($idProductAbstracts)))
            ->method('generateKey')
            ->willReturnCallback(function (SynchronizationDataTransfer $synchronizationDataTransfer): string {
                return sprintf('product_review:%s', $synchronizationDataTransfer->getReference());
            });

        $synchronizationServiceMock = $this->getMockBuilder(ProductReviewStorageToSynchronizationServiceInterface::class)->getMock();

        $synchronizationServiceMock->expects($this->once())
            ->method('getStorageKeyBuilder')
            ->with(ProductReviewStorageConfig::PRODUCT_ABSTRACT_REVIEW_RESOURCE_NAME)
            ->willReturn($synchronizationKeyBuilderPluginMock);

        $this->tester->setDependency(ProductReviewStorageDependencyProvider::SERVICE_SYNCHRONIZATION, $synchronizationServiceMock);
    }

    protected function mockStorageClientMock(array $expectedProductReviewStorageData): void
    {
        $storageClientMock = $this->getMockBuilder(ProductReviewStorageToStorageInterface::class)->getMock();

        $storageClientMock->expects($this->once())
            ->method('getMulti')
            ->with(['product_review:1', 'product_review:2'])
            ->willReturn($expectedProductReviewStorageData);

        $this->tester->setDependency(ProductReviewStorageDependencyProvider::CLIENT_STORAGE, $storageClientMock);
    }
}
