# BlockHolder, Block and Snippet

In this module, we introduce BlockHolder, Block and Snippet for personalizing a website.
In this document, we explain what they are, and why they are introduced.

## Overview

Relations between them are as the below image.

![Object Relations](https://github.com/yukiawano/sscpmodule/raw/master/docs/img/ObjectRelations.png)

## BlockHolder and it's extensibility

BlockHolder is a placeholder for personalization.

You call BlockHolder from the template file by using templateKey.

BlockHolder is designed as extensible.
You can customize BlockHolder by extending BlockHolderBase.

DefaultBlockHolder is a built-in BlockHolder.
DefaultBlockHolder holds some blocks.
It shows proper blocks to visitor depending on AudienceType that is defined before.

## DefaultBlockHolder

In this section, we explain about DefaultBlockHolder.

### Block

Block is a pair of AudienceType and Snippet.
If you want to show a snippet for newcomers, create a Block with AudienceType newcomer.
You can also specify multiple audience types to a block by listing AudienceTypes with comma separated style.

Here, we explain Block in technically, Blocks table is a join table of BlockHolder and Snippet many-many relationship.

### Snippet

Snippet is a fragment of html, the content that is actually shown for visitors.
Snippet is also designed as extensible.
You can create customized snippet by extending SnippetBase.

There are sevral snippet types, HTMLSnippet, Image Snippet.

A Snippet can be set to multiple Blocks.
In other words, you can reuse a fragment of html by using Snippet.