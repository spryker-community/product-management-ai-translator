<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerCommunity\Zed\ProductManagementAiTranslator\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerCommunity\Client\AiTranslator\AiTranslatorClientInterface;
use SprykerCommunity\Zed\ProductManagementAiTranslator\ProductManagementAiTranslatorDependencyProvider;

class ProductManagementAiTranslatorCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerCommunity\Client\AiTranslator\AiTranslatorClientInterface
     */
    public function getAiTranslatorClient(): AiTranslatorClientInterface
    {
        return $this->getProvidedDependency(ProductManagementAiTranslatorDependencyProvider::CLIENT_AI_TRANSLATOR);
    }
}
