<?php
/**
 * @category    Fwc
 * @author      Fast White Cat <fastwhitecat.com>
 * @copyright   Copyright (c) 2020 Fast White Cat S. A.
 * @since       1.0.0
 */

declare(strict_types=1);

namespace Fwc\RevHunter\ViewModel;

use Fwc\RevHunter\Helper\Config;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Checkout\Helper\Data;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class RevHunter
 *
 * @package Fwc\RevHunter\ViewModel
 */
class RevHunter implements ArgumentInterface
{
    public const TYPE_START = 'start';
    public const TYPE_CART  = 'cart';
    public const TYPE_STOP  = 'stop';

    private const REV_HUNTER_BASE_URL        = 'https://app.revhunter.tech/px/%s?type=%s';
    private const CATEGORY_PAGE              = '&category=%s';
    private const PRODUCT_VIEW_FROM_CATEGORY = '&product=%s&category=%s';
    private const DIRECT_PRODUCT_VIEW        = '&product=%s';
    private const SUCCESS_PAGE               = '&actionId=%s&actionValue=%s';

    /** @var Data $checkoutHelper */
    private $checkoutHelper;

    /** @var Config $configHelper */
    private $configHelper;

    /**
     * RevHunter constructor.
     *
     * @param Data   $checkoutHelper
     * @param Config $configHelper
     */
    public function __construct(
        Data $checkoutHelper,
        Config $configHelper
    ) {
        $this->checkoutHelper = $checkoutHelper;
        $this->configHelper   = $configHelper;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->configHelper->isModuleEnabled();
    }

    /**
     * @param string                                             $type
     * @param DataObject|CategoryInterface|ProductInterface|null $object
     *
     * @return string
     */
    public function getRevHunterUrl(string $type, ?DataObject $object): string
    {
        $url = $this->getRevHunterPageStart();
        switch ($type) {
            case self::TYPE_START && $object instanceof ProductInterface:
                return $this->getRevHunterProductViewUrl($object);
            case self::TYPE_START && $object instanceof CategoryInterface:
                return $this->getRevHunterCategoryUrl($object);
            case self::TYPE_CART:
                return $this->getRevHunterCheckoutUrl();
            case self::TYPE_STOP:
                return $this->getRevHunterSuccessPageUrl();
        }

        return $url;
    }

    /**
     * @return string
     */
    private function getRevHunterPageStart(): string
    {
        return $this->getRevHunterDefaultUrl(self::TYPE_START);
    }

    /**
     * @param CategoryInterface $category
     *
     * @return string
     */
    private function getRevHunterCategoryUrl(CategoryInterface $category): string
    {
        return sprintf(
            $this->getRevHunterDefaultUrl(self::TYPE_START) . self::CATEGORY_PAGE,
            $category->getId()
        );
    }

    /**
     * @param ProductInterface $product
     *
     * @return string
     */
    private function getRevHunterProductViewUrl(ProductInterface $product): string
    {
        if ($product->getCategory()) {
            return sprintf(
                $this->getRevHunterDefaultUrl(self::TYPE_START) . self::PRODUCT_VIEW_FROM_CATEGORY,
                $product->getSku(),
                $product->getCategory()->getId()
            );
        }

        return $this->getRevHunterDirectProductViewUrl($product);
    }

    /**
     * @return string
     */
    private function getRevHunterCheckoutUrl(): string
    {
        return $this->getRevHunterDefaultUrl(self::TYPE_CART);
    }

    /**
     * @return string
     */
    private function getRevHunterSuccessPageUrl(): string
    {
        $order      = $this->checkoutHelper->getCheckout()->getLastRealOrder();
        $defaultUrl = $this->getRevHunterDefaultUrl(self::TYPE_STOP);

        return sprintf(
            $defaultUrl . self::SUCCESS_PAGE,
            $order->getIncrementId(),
            $order->getGrandTotal()
        );
    }

    /**
     * @param ProductInterface $product
     *
     * @return string
     */
    private function getRevHunterDirectProductViewUrl(ProductInterface $product): string
    {
        return sprintf(
            $this->getRevHunterDefaultUrl(self::TYPE_START) . self::DIRECT_PRODUCT_VIEW,
            $product->getId()
        );
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function getRevHunterDefaultUrl(string $type): string
    {
        return sprintf(self::REV_HUNTER_BASE_URL, $this->configHelper->getRevHunterIdentifier(), $type);
    }
}
