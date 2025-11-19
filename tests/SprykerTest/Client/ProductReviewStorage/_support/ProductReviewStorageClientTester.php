<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductReviewStorage;

use Codeception\Actor;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Spryker\Client\Kernel\Locator locator()
 * @method void setDependency(string $key, callable $callable)
 *
 * @SuppressWarnings(\SprykerTest\Client\ProductReviewStorage\PHPMD)
 */
class ProductReviewStorageClientTester extends Actor
{
    use _generated\ProductReviewStorageClientTesterActions;
}
