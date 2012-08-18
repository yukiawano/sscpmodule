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

## Strategy of getting visitor's location

We uses sevral source for getting visitor's location.

When a visitor comes to a website for the first time, we use the default location as the visitor's location.
Default location is defined with yaml in configuration.
This is because getting visitor's location by using HTML5 API or IP-Based location service needs Web API calls, which are time consuming tasks.
Rendering a page faster is really important especially in commercial website.

When the page is loaded, a javascript code will run.
The code try to get visitor's location by using HTML5 Geo-location API in background. If it success, it stores the location in cookie.
On the other hand, when it fails, the code try to get the location by using IP-Based location service.

In short, the strategy of getting visitor's location is the below.

1. Use Default location
2. Try to get the location by Using HTML5 Geo-location API
3. When it failed, try to get the location by using IP-Based location service.

## HTML5 Geo-location APIs

HTML5 Geo-Location APIs return the location as longitude and latitude.
Then, we use [reverse geo-coding API of OpenStreetMap](https://wiki.openstreetmap.org/wiki/Nominatim#Reverse_Geocoding_.2F_Address_lookup) to get the address.

## IP-Based location service

IP-Based location services can tell us location of a visitor just by the IP-Address of the visitor.
We can use any IP-Based location service that converts IP-Address to real address.
As a default, we use [IPInfoDB](http://ipinfodb.com/).