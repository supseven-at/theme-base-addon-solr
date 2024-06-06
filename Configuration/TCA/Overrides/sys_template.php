<?php

declare(strict_types=1);

defined('TYPO3') or die;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('theme_base_addon_solr', 'Configuration/TypoScript', 'ThemeBase: AddonSolr Default');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('theme_base_addon_solr', 'Configuration/TypoScript/Extensions/GeorgRingerNews', 'ThemeBase: AddonSolr ext:news');
