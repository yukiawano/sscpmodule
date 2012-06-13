# AudienceType

In this module, we personalize content based on AudienceType.
In this page, we will explain what an AudienceType is and why it has been introduced.

## What is an AudienceType

AudienceType is a collection of conditions.
In other words, an AudienceType represents a segment of visitors (customer segment).

An example for such an AudienceType is the below:

```
NewComerFromJapan
  newcomer: true
  location: Japan
```


## Why did we introduce the AudienceType

If there is no AudienceType, you would have to add conditions each time you want to personalize content.
In many use cases, you would personalize contents based on a segment of visitors.
Thus we have introduced the AudienceType for reusing collections of conditions.
 