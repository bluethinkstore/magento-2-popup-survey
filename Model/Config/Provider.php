<?php

declare(strict_types=1);

namespace Bluethinkinc\PopupSurvey\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Provider to fetch config value
 */
class Provider
{
    private const XML_PATH_ENABLE_DISABLE = 'popup_survey/general/enable';

    private const XML_PATH_BLUETHINKINC_SURVEY_PRODUCT_SKU = 'popup_survey/general/survey_product_sku';

    private const XML_PATH_BLUETHINKINC_SURVEY_URL = 'popup_survey/general/survey_url';

    private const XML_PATH_BLUETHINKINC_SURVEY_QUESTION = 'popup_survey/general/survey_text';

    private const XML_PATH_BLUETHINKINC_SURVEY_PARAGRAPH = 'popup_survey/general/survey_paragraph';

    private const XML_PATH_BLUETHINKINC_SURVEY_DATE_TO = 'popup_survey/general/survey_date_to';

    private const XML_PATH_BLUETHINKINC_SURVEY_DATE_FROM = 'popup_survey/general/survey_date_from';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Provider Constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Get Module Status from configuration
     *
     * @return bool
     */
    public function getModuleStatus(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_DISABLE,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * Get survey sku from configuration
     *
     * @return null|array
     */
    public function getSurveyProductSku(): ?array
    {
        $product = $this->getStoreValue(self::XML_PATH_BLUETHINKINC_SURVEY_PRODUCT_SKU);
        if ($product) {
            $productSku =  explode(",", $product);
            return $productSku;
        }
        return null;
    }

    /**
     * Get survey url from configuration
     *
     * @return null|string
     */
    public function getSurveyUrl(): ?string
    {
        return $this->getStoreValue(self::XML_PATH_BLUETHINKINC_SURVEY_URL);
    }

    /**
     * Get survey text from configuration
     *
     * @return null|string
     */
    public function getSurveyText(): ?string
    {
        return $this->getStoreValue(self::XML_PATH_BLUETHINKINC_SURVEY_QUESTION);
    }

    /**
     * Get survey paragraph from configuration
     *
     * @return null|string
     */
    public function getSurveyParagraph(): ?string
    {
        return $this->getStoreValue(self::XML_PATH_BLUETHINKINC_SURVEY_PARAGRAPH);
    }

    /**
     * Get survey date to from configuration
     *
     * @return null|string
     */
    public function getSurveyDateTo(): ?string
    {
        return $this->getStoreValue(self::XML_PATH_BLUETHINKINC_SURVEY_DATE_TO);
    }

    /**
     * Get survey date from configuration
     *
     * @return null|string
     */
    public function getSurveyDateFrom(): ?string
    {
        return $this->getStoreValue(self::XML_PATH_BLUETHINKINC_SURVEY_DATE_FROM);
    }

    /**
     * Get store value from configuration
     *
     * @param string $configPath
     * @return null|string
     */
    protected function getStoreValue(string $configPath): ?string
    {
        return $this->scopeConfig->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }
}
