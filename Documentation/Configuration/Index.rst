..  include:: /Includes.rst.txt

..  _configuration:

Configuration
=============

.. rst-class:: bignums-xxl

1. Add the routeEnhancers configuration

   Add the PageType for the solr suggest endpoint to all TYPO3 site configurations:

   .. code-block:: yaml

       imports:
         - { resource: "EXT:theme_base_addon_solr/Configuration/SiteConfiguration/SiteSettings/DefaultSettings.yaml" }

   Example:

   ..  image:: /Images/TYPO3SiteSettingsExampleImport.png
               :class: with-shadow

2. Include the TypoScript in your TYPO3 project

   TypoScript Constants (`EXT:theme_project/Configuration/TypoScript/Extensions/Solr/constants.typoscript`)

   .. code-block:: typoscript

       # first line
       @import 'EXT:theme_base_addon_solr/Configuration/TypoScript/constants.typoscript'

   TypoScript Setup (`EXT:theme_project/Configuration/TypoScript/Extensions/Solr/constants.typoscript`)

   .. code-block:: typoscript

       # first line
       @import 'EXT:theme_base_addon_solr/Configuration/TypoScript/setup.typoscript'

3. Include optional TypoScript in your TYPO3 project if `georgringer/news` needs to be indexed

   TypoScript Constants (`EXT:theme_project/Configuration/TypoScript/Extensions/Solr/constants.typoscript`)

   .. code-block:: typoscript

       # second line
       @import 'EXT:theme_base_addon_solr/Configuration/TypoScript/Extensions/GeorgRingerNews/constants.typoscript'

   TypoScript Setup (`EXT:theme_project/Configuration/TypoScript/Extensions/Solr/constants.typoscript`)

   .. code-block:: typoscript

       # second line
       @import 'EXT:theme_base_addon_solr/Configuration/TypoScript/Extensions/GeorgRingerNews/setup.typoscript'

4. Set the provided TYPO3 site settings

   #. The minimum configuration is to set the search page of the TYPO3 site:

      .. code-block:: yaml

          themeBase:
            addonSolr:
              search:
                targetPage: <theUidOfTheGeneralSearchSubpage>

      Please check <EXT:theme_base_addon_solr/Configuration/SiteConfiguration/SiteSettings/DefaultSettings.yaml> for
      all available settings used by this package.

   #. Optional step: Add TYPO3 site settings for `georgringer/news`

      .. code-block:: yaml

          themeBase:
            addonSolr:
              indexQueue:
                news:
                  additionalWhereClause:
                    storagePage: <theUidOfTheNewsRecordsStoragePage>
                  fields:
                    url:
                      default:
                        detailPage: <theUidOfTheDefaultDetailPageForNewsRecords>

      Please check <EXT:theme_base_addon_solr/Configuration/SiteConfiguration/SiteSettings/GeorgRingerNewsSettings.yaml>
      for all available settings used by this package.