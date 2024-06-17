..  include:: /Includes.rst.txt

..  _installation:

Installation
============

..  attention::

    This package is intended to be used as an add-on package of
    `supseven/theme-base`. The package is therefore automatically required.

The extension needs to be installed as any other extension of TYPO3 CMS. Get the
extension with:

#. **Use composer**: Run the following command in your TYPO3 installation.

   .. code-block:: bash

      composer require supseven/theme-base-addon-solr


#. and :ref:`integrate <configuration>` it.


Compatibility
-------------

Ensure the compatibility of the extension with your TYPO3 installation by
considering this compatibility matrix:

=========== =========== =========== ======================================
  Package     TYPO3       PHP         Support / Development
=========== =========== =========== ======================================
  dev-main   12 - 12     8.2 - 8.3   unstable development branch
  12         12 - 12     8.2 - 8.3   features, bugfixes, security updates
=========== =========== =========== ======================================

(Branch and tags for version 12 are available once the support for TYPO3 13
gets added in future.)

Versioning
----------

This project uses `semantic versioning <https://semver.org/>`_, which means that

*  **bugfix updates** (e.g. 1.0.0 => 1.0.1) just include small bugfixes or
   security relevant stuff without breaking changes,
*  **minor updates** (e.g. 1.0.0 => 1.1.0) include new features and smaller
   tasks without breaking changes and
*  **major updates** (e.g. 1.0.0 => 2.0.0) contain breaking changes which can be
   refactorings, features or bugfixes.