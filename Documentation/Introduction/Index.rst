..  include:: /Includes.rst.txt

..  _introduction:

Introduction
============

In order to fundamentally standardize recurring integrations of Apache Solr in TYPO3 projects, this package provides corresponding standards, which can be seen in the context of a TYPO3 major version.

In the day-to-day life of a multi-project agency, it is a great advantage to standardize the integration while maintaining flexibility.

Dependency graph
----------------

**The way this package must be used**

.. code-block::

   supseven/theme-base ───────┬─────► supseven/theme-base-addon-solr
                              │
                              │                         │
                              │                         │
   apache-solr-for-typo3/solr │                         │
                                                        │
                                                        │
                                                        │
                                    ┌───────────────────┼────────────────┐
                                    │ TYPO3 project     │                │
                                    │                   │                │
                                    │                   ▼                │
                                    │                                    │
                                    │   EXT:theme_project                │
                                    │                   │                │
                                    │                   │                │
                                    │                   ▼                │
                                    │           Final integration        │
                                    │                                    │
                                    │                                    │
                                    └────────────────────────────────────┘

..  _what-does-it-do:

What does it do? (technically)
==============================

.. rst-class:: bignums-xxl

1. Adds a reindex command for Development context

   This commands adds the possibility to easily put all items of a type in the index queue and index them.

   .. code-block:: bash

       `ddev typo3 theme-base:solr-reindex`

2. Adds a predefined site setting snippet to include for solr suggestion endpoint

3. Adds a lowlevel default TypoScript setup based on supseven's standard implementation of an Apache Solr server.

   - Extends `plugin.tx_solr.view.*` paths
   - Sets default `plugin.tx_solr.search.results.*` settings
   - Sets default `plugin.tx_solr.search.*` settings
   - Sets default `plugin.tx_solr.suggest.*` settings and enables the feature
   - Sets default `plugin.tx_solr.logging.*` settings
   - Sets default `plugin.tx_solr.index.queue.pages.*` settings like setting basic auth credentials via env vars
   - Cleans default JavaScript imports as we are using custom Typescript in our projects
