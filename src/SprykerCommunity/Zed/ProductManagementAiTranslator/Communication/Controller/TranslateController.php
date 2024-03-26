<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerCommunity\Zed\ProductManagementAiTranslator\Communication\Controller;

use Generated\Shared\Transfer\AiTranslatorRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerCommunity\Zed\ProductManagementAiTranslator\Communication\ProductManagementAiTranslatorCommunicationFactory getFactory()
 */
class TranslateController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_TEXT = 'text';

    /**
     * @var string
     */
    protected const PARAM_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const PARAM_INVALIDATE_CACHE = 'invalidate_cache';

    /**
     * @var int
     */
    protected const STATUS_CODE_BAD_REQUEST = 400;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $text = $request->get(static::PARAM_TEXT);
        $targetLocale = $request->get(static::PARAM_LOCALE);
        $response = new JsonResponse();

        if (!$text || !$targetLocale) {
            return $response->setData([
                'error' => 'Bad request',
                'message' => 'Text and/or target locale are missing from request.',
            ])->setStatusCode(static::STATUS_CODE_BAD_REQUEST);
        }

        $translatorRequestTransfer = (new AiTranslatorRequestTransfer())
            ->setTargetLocale($targetLocale)
            ->setText($text)
            ->setInvalidateCache((bool)$request->get(static::PARAM_INVALIDATE_CACHE, false));
        $translationResponseTransfer = $this->getFactory()->getAiTranslatorClient()->translate($translatorRequestTransfer);

        return $response->setData([
            'locale' => $translationResponseTransfer->getTargetLocaleOrFail(),
            'translation' => $translationResponseTransfer->getTranslationOrFail(),
        ]);
    }
}
