<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewStorage\Business\Storage;

use Generated\Shared\Transfer\ProductReviewStorageTransfer;
use Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorage;
use Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainerInterface;

class ProductReviewStorageWriter implements ProductReviewStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductReviewStorageQueryContainerInterface $queryContainer,
        $isSendingToQueue
    ) {
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productReviewCollection */
        $productReviewCollection = $this->queryContainer->queryProductReviewsByIdProductAbstracts($productAbstractIds)->find();
        $productReviewStorageEntities = $this->findProductReviewStorageEntitiesByProductAbstractIds($productAbstractIds);

        if (!$productReviewCollection->toArray()) {
            $this->deleteStorageData($productReviewStorageEntities);
        }

        $this->storeData($productReviewCollection->toArray(), $productReviewStorageEntities);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $productReviewStorageEntities = $this->findProductReviewStorageEntitiesByProductAbstractIds($productAbstractIds);
        foreach ($productReviewStorageEntities as $productReviewStorageEntity) {
            $productReviewStorageEntity->delete();
        }
    }

    /**
     * @param array $productReviewStorageEntities
     *
     * @return void
     */
    protected function deleteStorageData(array $productReviewStorageEntities)
    {
        foreach ($productReviewStorageEntities as $productReviewStorageEntity) {
            $productReviewStorageEntity->delete();
        }
    }

    /**
     * @param array $productReviewEntities
     * @param array $spyProductAbstractReviewStorageEntities
     *
     * @return void
     */
    protected function storeData(array $productReviewEntities, array $spyProductAbstractReviewStorageEntities)
    {
        foreach ($productReviewEntities as $productReviewEntity) {
            $idProduct = $productReviewEntity['idProductAbstract'];
            if (isset($spyProductAbstractReviewStorageEntities[$idProduct])) {
                $this->storeDataSet($productReviewEntity, $spyProductAbstractReviewStorageEntities[$idProduct]);

                continue;
            }

            $this->storeDataSet($productReviewEntity);
        }
    }

    /**
     * @param array $productReview
     * @param \Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorage|null $spyProductAbstractReviewStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $productReview, ?SpyProductAbstractReviewStorage $spyProductAbstractReviewStorageEntity = null)
    {
        if ($spyProductAbstractReviewStorageEntity === null) {
            $spyProductAbstractReviewStorageEntity = new SpyProductAbstractReviewStorage();
        }

        $productReviewStorageTransfer = (new ProductReviewStorageTransfer())->fromArray($productReview);
        $productReviewStorageTransfer->setAverageRating(round($productReviewStorageTransfer->getAverageRating(), 1));
        $spyProductAbstractReviewStorageEntity->setFkProductAbstract($productReview['idProductAbstract']);
        $spyProductAbstractReviewStorageEntity->setData($productReviewStorageTransfer->modifiedToArray());
        $spyProductAbstractReviewStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductAbstractReviewStorageEntity->save();
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array
     */
    protected function findProductReviewStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractReviewStorageEntities = $this->queryContainer->queryProductAbstractReviewStorageByIds($productAbstractIds)->find();
        $productAbstractStorageReviewEntitiesById = [];
        foreach ($productAbstractReviewStorageEntities as $productAbstractReviewStorageEntity) {
            $productAbstractStorageReviewEntitiesById[$productAbstractReviewStorageEntity->getFkProductAbstract()] = $productAbstractReviewStorageEntity;
        }

        return $productAbstractStorageReviewEntitiesById;
    }
}
