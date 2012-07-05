# How we get the location of a visitor

In this document, we explain how our module get the location of visitors.

## Overview

We uses two sources to get the location of a visitor,
[HTML5 Geo-location APIs](http://dev.w3.org/geo/api/spec-source.html) and IP-Based location service.

We use HTML5 Geo-location APIs as the first and main source.
However, we sometimes cannot get location of a visitor by the HTML5 APIs.
(A visitor declined to provide location or a browser doesn't support the APIs, etc.)
In such case we uses IP-Based location service as a fallback,

In the first time access, we get the location of a visitor, and store it in cookie.
From the second access, we just read cookie for getting location of the visitor.

## HTML5 Geo-location APIs

HTML5 Geo-Location APIs return the location as longitude and latitude.
Then, we use [reverse geo-coding API of OpenStreetMap](https://wiki.openstreetmap.org/wiki/Nominatim#Reverse_Geocoding_.2F_Address_lookup) to get the address.

## IP-Based location service

IP-Based location services can tell us location of a visitor just by the IP-Address of the visitor.
We can use any IP-Based location service that converts IP-Address to real address.
As a default, we use [IPInfoDB](http://ipinfodb.com/).