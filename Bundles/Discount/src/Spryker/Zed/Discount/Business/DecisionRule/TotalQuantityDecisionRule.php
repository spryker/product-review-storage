<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

class TotalQuantityDecisionRule implements DecisionRuleInterface
{

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected $comparators;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators $comparators
     */
    public function __construct(ComparatorOperators $comparators)
    {
        $this->comparators = $comparators;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $currentItemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

        $totalQuantity = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $totalQuantity += $itemTransfer->getQuantity();
        }

        return $this->comparators->compare($clauseTransfer, $totalQuantity);
    }

}
