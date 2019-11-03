<?php
declare(strict_types=1);

/**
 * File:Permissions.php
 * @author Maciej Sławik
 */

namespace Gallinago\ProductionUtils\Plugin\Backend\Block\Cache;

use Magento\Backend\Block\Cache\Permissions as Subject;

/**
 * Class Permissions
 * @package Gallinago\ProductionUtils\Plugin\Backend\Block\Cache
 */
class Permissions
{
    /**
     * @param Subject $subject
     * @param bool $result
     * @return bool
     */
    public function afterHasAccessToAdditionalActions(Subject $subject, bool $result): bool
    {
        return false;
    }
}
