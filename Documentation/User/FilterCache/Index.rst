.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _user-cache:

Filter cache
^^^^^^^^^^^^

Once parsed a filter is stored into session. This is not related to
the session storage mechanism mentioned above. It is used
automatically so that values from a previous parsing are preserved.
This is useful if you want to change just one value in a filter and
preserve the others.

The drawback – as with any caching mechanism – is that this cache
sometimes needs to be cleared. For example, if the filter relates to a
search form, the filter cache must be cleared when a new search is
performed. This can be achieved by sending "clear\_cache = 1" either
as a GET or POST parameter. In the example of a search form, this
could be a hidden field in the search form.

There's also a possibility to clear the cache more selectively, for a
single value, using a configuration like:

.. code-block:: text

   tt_news_cat.uid = vars:showUid // \clear_cache

In this example, if there's no showUid to be found in the vars, the
value returned by the parser will be :code:`\clear_cache`. The Data Filter
will act upon this and remove that value from the session cache.

