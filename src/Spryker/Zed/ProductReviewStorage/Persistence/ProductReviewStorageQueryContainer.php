<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewStorage\Persistence;

use Generated\Shared\Transfer\ProductReviewStorageTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStoragePersistenceFactory getFactory()
 */
class ProductReviewStorageQueryContainer extends AbstractQueryContainer implements ProductReviewStorageQueryContainerInterface
{
    public const FIELD_FK_PRODUCT_ABSTRACT = ProductReviewStorageTransfer::ID_PRODUCT_ABSTRACT;

    public const FIELD_AVERAGE_RATING = ProductReviewStorageTransfer::AVERAGE_RATING;

    public const FIELD_COUNT = ProductReviewStorageTransfer::REVIEW_COUNT;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return \Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorageQuery
     */
    public function queryProductAbstractReviewStorageByIds(array $productAbstractIds)
    {
        return $this
            ->getFactory()
            ->createSpyProductReviewStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReviewsByIdProductAbstracts(array $productAbstractIds)
    {
        return $this->getFactory()
            ->getProductReviewQuery()
            ->queryProductReview()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->filterByStatus(SpyProductReviewTableMap::COL_STATUS_APPROVED)
            ->withColumn(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT, static::FIELD_FK_PRODUCT_ABSTRACT)
            ->withColumn(sprintf('AVG(%s)', SpyProductReviewTableMap::COL_RATING), static::FIELD_AVERAGE_RATING)
            ->withColumn(sprintf('COUNT(%s)', SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT), static::FIELD_COUNT)
            ->select([static::FIELD_FK_PRODUCT_ABSTRACT, static::FIELD_AVERAGE_RATING, static::FIELD_COUNT])
            ->groupBy(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productReviewsIds
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReviewsByIds(array $productReviewsIds)
    {
        return $this->getFactory()
            ->getProductReviewQuery()
            ->queryProductReview()
            ->filterByIdProductReview_In($productReviewsIds)
            ->filterByStatus(SpyProductReviewTableMap::COL_STATUS_APPROVED)
            ->withColumn(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT, static::FIELD_FK_PRODUCT_ABSTRACT)
            ->withColumn(sprintf('AVG(%s)', SpyProductReviewTableMap::COL_RATING), static::FIELD_AVERAGE_RATING)
            ->withColumn(sprintf('COUNT(%s)', SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT), static::FIELD_COUNT)
            ->select([static::FIELD_FK_PRODUCT_ABSTRACT, static::FIELD_AVERAGE_RATING, static::FIELD_COUNT])
            ->groupBy(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT);
    }
}
