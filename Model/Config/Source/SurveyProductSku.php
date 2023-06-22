<?php

declare(strict_types=1);

namespace Bluethinkinc\PopupSurvey\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class SurveyProductSku implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $productCollection;

    /**
     * @param CollectionFactory $productCollection
     */
    public function __construct(
        CollectionFactory $productCollection
    ) {
        $this->productCollection = $productCollection;
    }

    /**
     * Return Option for Region
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $collection = $this->productCollection->create();
            $collection->addAttributeToSelect('name', 'sku', 'type_id')
            ->addAttributeToFilter('type_id', ['eq' => 'simple']);
        foreach ($collection as $product) {
            $products[] = [
                'value' => $product->getSku(),
                'label' => $product->getName()
            ];
        }
        return $products;
    }
}
