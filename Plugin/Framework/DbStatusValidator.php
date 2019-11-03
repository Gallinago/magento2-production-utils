<?php
declare(strict_types=1);

/**
 * File:DbStatusValidator.php
 * @author Maciej Sławik
 */

namespace Gallinago\ProductionUtils\Plugin\Framework;

use Magento\Framework\App\FrontController;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Cache\FrontendInterface as FrontendCacheInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Module\DbVersionInfo;
use Magento\Framework\Phrase;

/**
 * Class DbStatusValidator
 * @package Gallinago\ProductionUtils\Plugin\Framework
 */
class DbStatusValidator
{
    /**
     * @var FrontendCacheInterface
     */
    private $cache;

    /**
     * @var DbVersionInfo
     */
    private $dbVersionInfo;

    /**
     * DbStatusValidator constructor.
     * @param FrontendCacheInterface $cache
     * @param DbVersionInfo $dbVersionInfo
     */
    public function __construct(FrontendCacheInterface $cache, DbVersionInfo $dbVersionInfo)
    {
        $this->cache = $cache;
        $this->dbVersionInfo = $dbVersionInfo;
    }

    /**
     * @param FrontController $subject
     * @param RequestInterface $request
     * @return void
     * @throws LocalizedException
     */
    public function beforeDispatch(FrontController $subject, RequestInterface $request): void
    {
        if (!$this->cache->load('db_is_up_to_date')) {
            list($versionTooLowErrors, $versionTooHighErrors) = array_values($this->getGroupedDbVersionErrors());
            if ($versionTooLowErrors) {
                $message = 'Please upgrade your database: '
                    . "Run \"bin/magento setup:upgrade\" from the Magento root directory.\n"
                    . "The following modules are outdated:\n%1";

                throw new LocalizedException(
                    new Phrase($message, [implode("\n", $this->formatVersionTooLowErrors($versionTooLowErrors))])
                );
            } else {
                $this->cache->save('true', 'db_is_up_to_date');
            }
        }
    }

    /**
     * @param array $errorsData
     * @return array
     */
    private function formatVersionTooLowErrors(array $errorsData): array
    {
        $formattedErrors = [];

        foreach ($errorsData as $error) {
            $formattedErrors[] = $error[DbVersionInfo::KEY_MODULE] . ' ' . $error[DbVersionInfo::KEY_TYPE]
                . ': current version - ' . $error[DbVersionInfo::KEY_CURRENT]
                . ', required version - ' . $error[DbVersionInfo::KEY_REQUIRED];
        }

        return $formattedErrors;
    }

    /**
     * @return mixed
     */
    private function getGroupedDbVersionErrors(): array
    {
        $allDbVersionErrors = $this->dbVersionInfo->getDbVersionErrors();
        return array_reduce(
            (array)$allDbVersionErrors,
            function ($carry, $item) {
                if ($item[DbVersionInfo::KEY_CURRENT] === 'none'
                    || $item[DbVersionInfo::KEY_CURRENT] < $item[DbVersionInfo::KEY_REQUIRED]
                ) {
                    $carry['version_too_low'][] = $item;
                } else {
                    $carry['version_too_high'][] = $item;
                }
                return $carry;
            },
            [
                'version_too_low' => [],
                'version_too_high' => [],
            ]
        );
    }
}
