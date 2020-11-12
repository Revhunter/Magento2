<?php
/**
 * @category    Fwc
 * @author      Fast White Cat <fastwhitecat.com>
 * @copyright   Copyright (c) 2020 Fast White Cat S. A.
 * @since       1.0.0
 */

declare(strict_types=1);

namespace Fwc\RevHunter\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 *
 * @package Fwc\RevHunter\Helper
 */
class Config
{
    private const XML_PATH_MODULE_STATUS         = 'rev_hunter/configuration/status';
    private const XML_PATH_REV_HUNTER_IDENTIFIER = 'rev_hunter/configuration/rev_hunter_id';

    /** @var ScopeConfigInterface $scopeConfig */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->getModuleStatus() && !empty($this->getRevHunterIdentifier());
    }

    /**
     * @return bool
     */
    private function getModuleStatus(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_MODULE_STATUS,
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string|null
     */
    public function getRevHunterIdentifier(): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_REV_HUNTER_IDENTIFIER,
            ScopeInterface::SCOPE_STORES
        );
    }
}
