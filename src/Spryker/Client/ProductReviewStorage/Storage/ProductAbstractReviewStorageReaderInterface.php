<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReviewStorage\Storage;

use ArrayObject;

interface ProductAbstractReviewStorageReaderInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer|null
     */
    public function findProductAbstractReview($idProductAbstract);

    /**
     * @param array<int> $idProductAbstracts
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReviewStorageTransfer>
     */
    public function findProductAbstractReviewBulk(array $idProductAbstracts): ArrayObject;

    /**
     * @param array<string> $keys
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReviewStorageTransfer>
     */
    public function findProductReviewProductStorageTransferBulk(array $keys): ArrayObject;
}
