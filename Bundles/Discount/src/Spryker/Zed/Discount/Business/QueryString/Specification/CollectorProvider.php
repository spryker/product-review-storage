<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorAndSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorContext;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorOrSpecification;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class CollectorProvider extends BaseSpecificationProvider implements SpecificationProviderInterface
{

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface[]
     */
    protected $collectorPlugins = [];

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface[] $collectorPlugins
     */
    public function __construct(array $collectorPlugins)
    {
        $this->collectorPlugins = $collectorPlugins;
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface $left
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface $right
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorAndSpecification
     */
    public function createAnd($left, $right)
    {
        return new CollectorAndSpecification($left, $right);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface $left
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface $right
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorAndSpecification
     */
    public function createOr($left, $right)
    {
        return new CollectorOrSpecification($left, $right);
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     */
    public function getSpecificationContext(ClauseTransfer $clauseTransfer)
    {
        foreach ($this->collectorPlugins as $collectorPlugin) {

            $clauseFieldName = $this->getClauseFieldName($clauseTransfer);

            if (strcasecmp($collectorPlugin->getFieldName(), $clauseFieldName) === 0) {
                return new CollectorContext($collectorPlugin, $clauseTransfer);
            }
        }

        throw new QueryStringException(
            sprintf(
                'Could not find collector plugin for "%s" field. Have you registered it in "%s::getCollectorPlugins" plugins stack?',
                $clauseTransfer->getField(),
                DiscountDependencyProvider::class
            )
        );
    }

}
