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
                                    │                                    │
                                    │                                    │
                                    │                                    │
                                    │                                    │
                                    │                                    │
                                    │                                    │
                                    │                                    │
                                    │                                    │
                                    │                                    │
                                    │                                    │
                                    │                                    │
                                    │                                    │
                                    └────────────────────────────────────┘
