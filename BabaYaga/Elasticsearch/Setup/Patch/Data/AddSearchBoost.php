<?php
declare(strict_types=1);

namespace BabaYaga\Elasticsearch\Setup\Patch\Data;

use BabaYaga\Elasticsearch\Model\Config\Source\Options\SearchBoost;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Zend_Validate_Exception;

/**
 * Class AddSearchBoost.
 *
 * Installs a search boost attribute on products.
 */
class AddSearchBoost implements DataPatchInterface
{
    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * AddCustomAttributes constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return $this|AddSearchBoost
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $attributeCode = "search_boost";
        $eavSetup->removeAttribute(Product::ENTITY, $attributeCode);

        $eavSetup->addAttribute(
            Product::ENTITY,
            $attributeCode,
            $this->getAttributeConfiguration()
        );

        $eavSetup->addAttributeToSet(
            Product::ENTITY,
            $eavSetup->getDefaultAttributeSetId(Product::ENTITY),
            $eavSetup->getDefaultAttributeGroupId(Product::ENTITY),
            $attributeCode
        );

        return $this;
    }

    /**
     * @return array
     */
    private function getAttributeConfiguration() {
        return [
            'input'                      => 'select',
            'type'                       => 'int',
            'label'                      => 'Search Boost',
            'required'                   => false,
            'global'                     => ScopedAttributeInterface::SCOPE_STORE,
            'unique'                     => false,
            'used_in_grid'               => false,
            'visible_in_grid'            => false,
            'filterable_in_grid'         => false,
            'searchable'                 => true,
            'search_weight'              => '1',
            'visible_in_advanced_search' => false,
            'comparable'                 => false,
            'filterable'                 => false,
            'filterable_in_search'       => false,
            'position'                   => 100,
            'used_for_promo_rules'       => false,
            'html_allowed_on_front'      => false,
            'visible_on_front'           => false,
            'used_in_product_listing'    => false,
            'used_for_sort_by'           => false,
            'user_defined'               => true,
            'source'                     => SearchBoost::class,
        ];
    }
}
