# AudienceType

In this module, we personalized content based on AudienceType.
In this page, we will explain what is AudienceType and why AudienceType is introduced.

## What is AudienceType

AudienceType is a collection of conditions.
In other words, AudienceType represents a segment of visitors(customer segment).

An example of AudienceType is the below.

```
NewComerFromJapan
  newcomer: true
  location: Japan
```

## Why we introduce AudienceType

When there is no AudienceType, you should add conditions each time, when you want to personalize content.
In many use cases, you would personalize contents based on a segment of visitors.
Thus we introduced AudienceType for reusing collections of conditions.
 