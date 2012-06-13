# AudienceType

In this module, we personalize content based on AudienceType.
In this page, we will explain what an AudienceType is and why it has been introduced.

## What is an AudienceType

AudienceType is a collection of conditions.
In other words, an AudienceType represents a segment of visitors (customer segment).

An example for such an AudienceType is the below:

```
NewComerFromJapan: 
  newcomer: true
  location: Japan
```

To say in more generally, the syntax of audience type is the below.

```
Name of Audience Type:
  Condition1: Args for condition 1
  Condition2: Args for condition 2
  ...
```

Conditions which are listed in are considered as *AND* conditions.
So in the above example, NewComerFromJapan is who accesses from Japan AND new comer to our web site.

## Where and how we define AudienceTypes

We manage audience types in configuration file.
As written in [the getting started guide](https://github.com/yukiawano/sscpmodule/blob/master/docs/getting-started/getting-started.md), you can copy ``sscp/_config/audiencetype.yml.sample`` to your mysite directory, and you can define your own Audience Types.
In this section, we explain the details of the yaml file by using the sample file as an example.

```
AudienceType:
  MatchingRule: InclusiveOR
  AudienceTypes:
    NewComer:
      NewComer: true
    NewYorker:
      Location: NewYork
      Device: Linux
```

You see that there are Matching Rule and Audience Types part.
We will explain Matching Rule in the next.

In AudienceTypes part, Audience Types are listed with the syntax that we explained before.

### Matching Rule

Matching Rule is a strategy how multiple audience types are treated when a visitor fulfilled multiple audience types.

At current version, you can use two Matching Rule, InclusiveOR or ExclusiveOR.

 * IncusiveOR - EVERY rule that is matched to the visitor is applied.
 * ExclusiveOR - ONLY THE FIRST rule that is matched to the visitor is applied.

Let us think the difference between them.

When you define a snippet A for NewYoker, consider the case that a visitor accseesed to this site for the first time from NewYork using Linux.

In this case, if you are using InclusiveOR, snippet A is shown.
However, if you are using ExclusiveOR, snippet A is NOT shown, because the system only returns first matched rule, NewComer. 

## Why did we introduce the AudienceType

If there is no AudienceType, you would have to add conditions each time you want to personalize content.
In many use cases, you would personalize contents based on a segment of visitors.
Thus we have introduced the AudienceType for reusing collections of conditions.
 