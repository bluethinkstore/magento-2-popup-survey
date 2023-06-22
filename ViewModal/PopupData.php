<?php

namespace Bluethinkinc\PopupSurvey\ViewModal;

use Bluethinkinc\PopupSurvey\Model\Config\Provider;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class PopupData implements ArgumentInterface
{
    /**
     * @var Provider
     */
    private $configProvider;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var productSku
     */
    private $productSku;

    private const SMALL_IMAGE = 'small_image';

    private const CATALOG_PATH = 'catalog/product';

    /**
     * @param Provider $configProvider
     * @param Session $checkoutSession
     * @param StoreManagerInterface $storeManager
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Provider $configProvider,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository
    ) {
        $this->configProvider = $configProvider;
        $this->checkoutSession = $checkoutSession;
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
    }

    /**
     * Get module status enable or disable
     *
     * @return bool
     */
    public function getModuleStatus()
    {
        return $this->configProvider->getModuleStatus();
    }

    /**
     * Product SKU for survery modal
     *
     * @return array|null
     */
    public function getSurveyProductSku()
    {
        return $this->configProvider->getSurveyProductSku();
    }

    /**
     * Get product image for modal
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImage()
    {
        $product = $this->productRepository->get($this->getProductSku());
        return $this->getBaseUrl().$product->getData(self::SMALL_IMAGE);
    }

    /**
     * Popup survey url
     *
     * @return string|null
     */
    public function getSurveyUrl()
    {
        return $this->configProvider->getSurveyUrl();
    }

    /**
     * Popup modal survey text
     *
     * @return string|null
     */
    public function getSurveyText()
    {
        return $this->configProvider->getSurveyText();
    }

    /**
     * Popup modal survey paragraph
     *
     * @return string|null
     */
    public function getSurveyParagraph()
    {
        return $this->configProvider->getSurveyParagraph();
    }

    /**
     * Check product is purchased
     *
     * @return bool|void
     */
    public function isProductPurchased()
    {
        if ($this->configProvider->getModuleStatus()) {
            $order  = $this->checkoutSession->getLastRealOrder();
            $surveySku = $this->configProvider->getSurveyProductSku();
            $orderItems = $order->getAllVisibleItems();
            foreach ($orderItems as $item) {
                if (in_array($item->getSku(), $surveySku)) {
                    $this->setProductSku($item->getSku());
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Set product sku for get image
     *
     * @param string $sku
     * @return mixed
     */
    public function setProductSku($sku)
    {
        $this->productSku = $sku;
        return $this->productSku;
    }

    /**
     * Get product sku for image
     *
     * @return mixed
     */
    public function getProductSku()
    {
        return $this->productSku;
    }

    /**
     * Check date
     *
     * @return bool|void
     */
    public function isDateAvailable()
    {
        if ($this->configProvider->getModuleStatus()) {
            $startDate = strtotime($this->configProvider->getSurveyDateTo());
            $endDate = strtotime($this->configProvider->getSurveyDateFrom());
            $todayDate = strtotime(date("Y-m-d"));
            if ($startDate <= $endDate && $endDate>=$todayDate) {
                return true;
            }
            return false;
        }
    }

    /**
     * Get base url
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseUrl()
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        return $baseUrl . self::CATALOG_PATH;
    }
}
