.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _user-filter-configuration:

Filter Configuration
^^^^^^^^^^^^^^^^^^^^

You may define as many filters as you want, each on a line. All
filters will be linked to each other using the selected logical
operator (AND or OR). The general syntax of a filter is the following:

.. code-block:: text

    [extra keyword.][[table name].][field name] [operator] [value or expression]

**Example:**

.. code-block:: text

    uid = 12
    pages.uid = page:uid

In the two lines above, we are defining that the "uid" field of the
"pages" table must be strictly equal to some value. In the first case,
it is a simple number (12), that will be used as is. In the second
case, the test value will be retrieved as the uid of the current page.

As you can see, it is possible to omit the table name. It is up to the Data
Provider to know what to do in such a case. In the case of Data Query,
the condition will be considered to apply to the main table (the one
in the FROM clause).


.. _user-filter-configuration-extra:

Extra keywords
""""""""""""""

There are two extra keywords that can be used before the table name:
:code:`main` and :code:`void`. Usage of a special keyword (and its dot) is
optional.

**Example:**

.. code-block:: text

    main.tt_content.header like foo


The :code:`main` keyword means that the condition should be applied to the
"main condition" by the Data Provider. What this exactly means depends
on the Data Provider. Let's consider Data Query as an example. This
Provider will transform the conditions into SQL statements. The query
in the provider will have a main table (the one in the FROM clause).
It may also have secondary tables (linked using JOINs). By default
filter conditions that relate to the main table are applied in the
WHERE clause and those that relate to secondary tables apply to the ON
clause of the relevant JOIN statement. Using the :code:`main` keyword will
force a condition that would normally be applied inside an ON clause
to be moved to the WHERE clause.

The :code:`void` keyword means that the condition should be evaluated but
not applied. The Data Provider receiving this condition should simply
ignore. Such "void" filters are useful to pass some filter
configuration down the component chain. Indeed since Data Providers
include the filter information inside the data structure, it will be
passed on to the Data Consumers. This makes it possible to get filter
information in the Data Consumer, in order to display it or to react
to it.


.. _user-filter-configuration-void:

Using a void filter
~~~~~~~~~~~~~~~~~~~

The usefulness of "void" filters may not be obvious at first. An
example may help. Imagine a list of blog entries, which can be
filtered on month and year. Both the list of entries and the list of
possible years and months to select from are displayed using
Tesseract. When a month/year combination is selected, the list of blog
entries is filtered accordingly.

We would like the list of months and years to know about the selection
too, so that we can highlight the current selection. But we don't want
the month/year selection to apply to the months/years selector itself.
By defining conditions on the month and year with the "void" flag, the
month/year selector can know about the current selection without being
affected by it.


.. _user-filter-configuration-operators:

Operators
"""""""""

All possible operators are described below.

.. note::

   Actually the name of the operator does not matter for Data Filter
   (as long as it does not contain a blank). What is presented below
   is a list of usual operators which are expected to be supported
   by most Data Providers. Special operators may be used at any point
   if some custom Data Provider requires it.

.. important::

   All filter can be negated by prepending them with :code:`!`.

   **Example:**

   .. code-block:: text

        tt_content.title !like foo


.. _user-filter-configuration-operators-mathematical:

Mathematical operators
~~~~~~~~~~~~~~~~~~~~~~

=, <, >, <=, =>
  These operators correspond to the standard mathematical
  operators.


.. _user-filter-configuration-operators-sets:

Set operators
~~~~~~~~~~~~~

in
  In a group of values (think of the SQL IN operator)

andgroup, orgroup
  In a group of comma-separated values (AND or OR for each value
  depending on choice of operator)


.. _user-filter-configuration-operators-strings:

String operators
~~~~~~~~~~~~~~~~

like
  Similar to the SQL LIKE operator. The Data Provider is expected to
  wrap the value in wildcards.


start
  Should be similar to the SQL LIKE operator, but testing the start of a
  word. This means the Data Provider is expected to append a wildcard to
  the value.

end
  Should be similar to the SQL LIKE operator, but testing the end of a
  word. This means the Data Provider is expected to prepend a wildcard
  to the value.


.. _user-filter-configuration-values:

Values and expressions
""""""""""""""""""""""

Values are simple types (integers or strings) that are used as is.

Expressions are managed by the "expressions" extension. Any expression
described in that extension's manual can be used here.

On top of that some special values can be used in Data Filter. Special
values all start with a backslash (\).

\\empty
  This special value is equivalent to the empty string (''). The
  following will fail:

  .. code-block:: text

        pages.title !=

  because an empty value will cause the entire line to be ignored. Use
  the following instead:

  .. code-block:: text

        pages.title != \empty

\\null
  This special value is meant to be matched to an undefined or unset
  variable.

  It only makes sense with the "=" and "!=" operators. Any other
  operator than "=" should be interpreted as "!=" in this case.

\\all
  This special value indicates that the condition should actually not
  apply, because we want all values. This is sometimes necessary when
  all values should be explicitly retrieved, ignoring any default value
  for the filter.

  **Example:**

  .. code-block:: text

     tt_content.CType = gp:content_type // text

  In the above example, all content elements from a given type will be
  selected, according to the value found in the GET/POST variable called
  "content\_type". If this variable is empty, the type defaults to
  "text". In such a case it is not possible to see all content elements,
  except by setting the value of the GET/POST variable to "\all".

\\clear\_cache
  This special value can be used to clear the filter cache.
  See :ref:`Filter cache <user-cache>` for explanations.


.. _user-filter-configuration-values-types:

Values types
~~~~~~~~~~~~

The values or the results of expressions may be more complex types:

interval
  When an interval is defined, the original operator will be ignored and
  replaced with operators based on the type of square brackets used in
  the interval.

  "\*" can be used for not setting a limit.

  **Examples**

  [10,20]
    *A value between 10 and 20, inclusive*

  ]0,1000]
    *A value strictly bigger than 0 and smaller or equals to 1000*

  [5,\*]
    *A value greater or equals to 5, with no upper boundary*

comma-separated strings of values
  Such a value is used to defined a group of values among which at least
  one must be matched. It is up to the Data Provider to set up proper
  tests for this. This kind of value will normally be used in
  conjunction with the "in", "andgroup" and "orgroup" operators.

  **Examples**

  1,5,6,12
    *The value should be either 1, 5, 6 or 12*


array
  Data Providers are expected to know how to handle array values. The
  expected behavior is to loop on each value and handle them with the
  same operator, with all resulting conditions "added" together.


.. _user-filter-configuration-values-alternate:

Alternate values
~~~~~~~~~~~~~~~~

An expression can also contain multiple values. This is useful for
selecting alternate values if some are not defined, and in particular
for setting default values.

**Examples**

.. code-block:: text

    pages.title = page:nav_title // page:title

This expression will return the nav\_title of the current page or its
title, if no nav\_title is defined

.. code-block:: text

    sys_language_uid = tsfe:sys_language_content // 0

This will return the id of the current language or 0 if we are using
the default language

The order is always left to right. If the first value is not defined,
we pass on to the next one to the right, etc.


.. _user-filter-configuration-interpolated:

Interpolated configurations
"""""""""""""""""""""""""""

There can be expressions within expressions. These are called
"subexpressions". An example application is to select a table or field
name dynamically.

**Example**

.. code-block:: text

    pages.{vars:field_name} = [5,10]

This expression will match the field (from the "pages" table) defined
in the variable "field\_name" to a value between 5 and 10.


.. _user-filter-configuration-comments:

Comments
""""""""

It is possible to use comment markers inside the filter configuration.
Any line starting with :code:`#` or :code:`//` will be ignored.


.. _user-filter-configuration-named:

Named configuration lines
"""""""""""""""""""""""""

Inside a filter, configuration lines are numbered according to their
position in the text field, i.e. the first line is numbered 0, the
second line 1, etc. This internal numbering is useful when a filter
needs to be updated, that is when it is called again without the cache
being cleared.

However it is also possible to explicitly give a name to a
configuration line so that it is easier to target. This is
particularly useful in a filter which tries to retrieve information
from another filter stored into the session.

Naming a configuration line uses the following syntax:

.. code-block:: text

    interval5-10 :: pages.{vars:field_name} = [5,10]

In this case the line will be named "interval5-10".
