SeoBundle
=========

SeoBundle is part of the framework BigFoot created by C2IS.


Installation
------------

To install this bundle, you have to add 'BigFoot/SeoBundle' into your composer.json file in the 'require' section
and do a composer update.


Usage
-----

This bundle is made up of two entries' menu in the back-office:

    - Seo Parameters:
    Here, you can associate X parameters to a route. It will be useful for adding dynamical contents to your metadata.

    - Seo:
    Here, you can enter a title, a description and some keywords for a route by using the parameters you entered
    previously.

Example
-------

I have a route called 'hotel_information' and i want to create a title like 'Hotel : My custom Hotel' with 'My custom
hotel' the name of the hotel.

First, i create a parameter named 'hotel_name' and i associate it to the route 'hotel_information' in the
'Seo Parameters' section.

Then, i create an entry in the 'Seo' section with the route 'hotel_information' and the title 'Hotel : :hotel_name:'.
Note that i surrounded the parameter with two '::'.



