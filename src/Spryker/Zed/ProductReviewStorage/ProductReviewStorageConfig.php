<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewStorage;

use Spryker\Shared\Synchronization\SynchronizationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductReviewStorageConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(SynchronizationConstants::STORAGE_SYNC_ENABLED, true);
    }

    /**
     * @return string|null
     */
    public function getProductAbstractReviewSynchronizationPoolName(): ?string
    {
        return null;
    }
}
