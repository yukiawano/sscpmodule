# BlockHolder, Block and Snippet

In this module, we introduce BlockHolder, Block and Snippet for personalizing a website.
In this document, we explain what they are, and why they are introduced.

## Overview

Relations between them are as the below image.

![Object Relations](https://github.com/yukiawano/sscpmodule/raw/master/docs/img/ObjectRelations.png)

## BlockHolder

BlockHolder is a placeholder for personalization.
If you want to personalize a part of web site, create a BlockHolder.

Each BlockHolder has a template key.
You use the template key for showing this from template file.

## Block

Block is a pair of AudienceType and Snippet.
If you want to show a specific content for newcomers to a website, create a Block with AudienceType newcomer.

A BlockHolder holds multiple Blocks.

We explain Block in technically, Blocks table is a join table of BlockHolder and Snippet many-many relationship.

## Snippet

Snippet is a fragment of html, the content that is actually shown for visitors.

A Snippet can be set to multiple Blocks.
In other words, you can reuse a fragment of html by using Snippet.