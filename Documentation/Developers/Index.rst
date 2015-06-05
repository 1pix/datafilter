.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _developers:

Developers manual
-----------------


.. _developers-hooks:

Hooks
^^^^^

This extension provides two hooks that can be used by developers:

postprocessReturnValue
  This hook comes at the end of the
  filter's evaluation. It receives a reference to the
  :code:`\Tesseract\Datafilter\Component\DataFilter` object. This makes it possible to access (using
  :code:`getFilter()`) and modify (using :code:`setFilter()`) the
  current values of the filter.

  Any class meaning to use this hook must
  implement the :code:`\Tesseract\Datafilter\PostprocessFilterInterface` interface.

postprocessEmptyFilterCheck
  This hook comes at the end of the
  empty filter check. It can be used to modify the result of the
  :code:`isFilterEmpty()` method. It receives as parameters the current
  value of the empty filter check (a boolean) and a reference to the
  calling :code:`\Tesseract\Datafilter\Component\DataFilter` object. It is expected to return a
  boolean value (true if the filter can be considered to be empty, false
  otherwise).

  Any class meaning to use this hook must implement the
  :code:`\Tesseract\Datafilter\PostprocessEmptyFilterCheckInterface` interface.

