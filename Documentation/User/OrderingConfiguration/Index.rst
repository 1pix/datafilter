.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _user-ordering:

Ordering configuration
^^^^^^^^^^^^^^^^^^^^^^

The sorting of records can also be defined inside the Data Filter.
There again the syntax for expressions can be used. The general
syntax, however, is a bit different, since both a field and a sort
order must be defined for each sorting criterion, plus an optional
:code:`engine`. So an actual ordering definition might look like:

.. code-block:: text

   field = tx_specialsearch_pi1|sort // items.name
   order = tx_specialsearch_pi1|order
   engine = source

In this case the name of the field to use for ordering will be fetched
from variable :code:`tx\_specialsearch\_pi1[sort]`. If not defined,
the default search will use the "name" field from the "items" table.
The sorting order will be fetched from variable
:code:`tx\_specialsearch\_pi1[order]`. There are no restrictions on
the values of the order property. It is up to the Data Provider to
implement whatever is meaningful for itself, and default to some
appropriate ordering if the values provided by the filter are not
valid within its context.

The :code:`engine` property is optional. It can be used to define where the
ordering should happen, that is either at the "source" (the data
source queried by the Data Provider) or in the "provider" (with some
PHP code inside the Data Provider, after the data is fetched). If the
value is other than "source" or "provider", it will be replaced by a
blank string, instructing the Data Provider to use its default
behavior. Refer to the Tesseract manual for more details.


.. _user-ordering-random:

Random ordering
"""""""""""""""

For random ordering, the simple keyword :code:`\rand` can be used. There's
no need to define any "field" or "order" (as per the above
configuration). Just a single line with the keyword:

.. code-block:: text

   \rand

Any appearance of the :code:`\rand` keyword will trigger random ordering.
Thus the following lines have the same effect:

.. code-block:: text

   field = \rand
   order = \rand

Note that all other ordering configurations will be ignored if the
keyword :code:`\rand` is found on any line.

