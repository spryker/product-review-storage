<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewStorage\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductReviewStorageTransfer;
use Spryker\Client\ProductReviewStorage\Dependency\Client\ProductReviewStorageToStorageInterface;
use Spryker\Shared\ProductReviewStorage\ProductReviewStorageConfig;

class ProductAbstractReviewStorageReader implements ProductAbstractReviewStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductReviewStorage\Dependency\Client\ProductReviewStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductReviewStorage\Storage\ProductReviewStorageKeyGeneratorInterface
     */
    protected $productReviewStorageKeyGenerator;

    public function __construct(
        ProductReviewStorageToStorageInterface $storageClient,
        ProductReviewStorageKeyGeneratorInterface $productReviewStorageKeyGenerator
    ) {
        $this->storageClient = $storageClient;
        $this->productReviewStorageKeyGenerator = $productReviewStorageKeyGenerator;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer|null
     */
    public function findProductAbstractReview($idProductAbstract)
    {
        $key = $this->productReviewStorageKeyGenerator->generateKey(ProductReviewStorageConfig::PRODUCT_ABSTRACT_REVIEW_RESOURCE_NAME, $idProductAbstract);

        return $this->findProductReviewProductStorageTransfer($key);
    }

    /**
     * @param array<int> $idProductAbstracts
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReviewStorageTransfer>
     */
    public function findProductAbstractReviewBulk(array $idProductAbstracts): ArrayObject
    {
        $keys = [];
        foreach ($idProductAbstracts as $idProductAbstract) {
            $keys[] = $this->productReviewStorageKeyGenerator->generateKey(ProductReviewStorageConfig::PRODUCT_ABSTRACT_REVIEW_RESOURCE_NAME, $idProductAbstract);
        }

        return $this->findProductReviewProductStorageTransferBulk($keys);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer|null
     */
    protected function findProductReviewProductStorageTransfer($key)
    {
        $reviewData = $this->storageClient->get($key);

        if (!$reviewData) {
            return null;
        }

        $productReviewStorageTransfer = new ProductReviewStorageTransfer();
        $productReviewStorageTransfer->fromArray($reviewData, true);

        return $productReviewStorageTransfer;
    }

    /**
     * @param array<string> $keys
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReviewStorageTransfer>
     */
    public function findProductReviewProductStorageTransferBulk(array $keys): ArrayObject
    {
        $data = $this->storageClient->getMulti($keys);

        $productReviewStorageTransfers = new ArrayObject();
        foreach ($data as $value) {
            if (!$value) {
                continue;
            }

            $productReviewStorageTransfer = new ProductReviewStorageTransfer();
            $productReviewStorageTransfer->fromArray(json_decode($value, true), true);
            $productReviewStorageTransfers->append($productReviewStorageTransfer);
        }

        return $productReviewStorageTransfers;
    }
}
