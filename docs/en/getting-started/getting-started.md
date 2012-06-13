# Getting started with SilverStripe's Content Personalization Module (SSCP)


## Notice

Please note that this module is **under development** and **not suitable for use in production**!

* There will be a lot of changes.
* We may change the database structure.
* It is not feature complete.


## Installation

To install this module:

1. Either clone the code from the GitHub repository with ``git clone https://github.com/yukiawano/sscpmodule.git`` or download it at [https://github.com/yukiawano/sscpmodule/downloads](https://github.com/yukiawano/sscpmodule/downloads).
2. Add the folder to your SilverStripe installation and rename it to ``sscp``.
3. Access ``/dev/build/?flush=all`` to install the module.


## Configuration

You must configure the module before you can start using it.
Copy the two configuration files

    sscp/_config/audiencetype.yml.sample
    sscp/_config/apikey.yml.sample

to

    mysite/_config/audiencetype.yml
    mysite/_config/apikey.yml


### APIKeys (mysite/_config/apikey.yml)

You need to some set the following API keys to use the respective thirdparty services:

* **IP-base geo-location:** We are using IPInfoDB for which you can get a free key at
[http://ipinfodb.com/register.php](http://ipinfodb.com/register.php).


### AudienceTypes (mysite/_config/audiencetype.yml)

The module personalizes content based on [AudienceTypes](https://github.com/yukiawano/sscpmodule/blob/master/docs/en/topics/audience-type.md).
An AudienceType is a collection of [conditions](https://github.com/yukiawano/sscpmodule/blob/master/docs/en/conditions.md).

You can define AudienceTypes in YAML like this:

```
AudienceType:
  MatchingRule: InclusiveOR
  AudienceTypes:
    NewComer:
      NewComer: true
    JapanLinuxUser:
      Location: Japan
      Device: Linux
    Japanese:
      Location: Japan
```

After you have changed audience types you must access ``/dev/build/?flush=all`` to reload the configuration.


## Tutorial

In this tutorial we will create a customized block, which shows a welcome message for first time visitors.
We will use the audience types from the sample configuration, which already covers this.


### 1. Add Personalized Content

A BlockHolder is a place holder for content personalization and looks like this in the template:

```
$PersonalizedContent('WelcomeBlock')
```

* ``$PersonalizedContent()`` is a method provided by the module.
* ``WelcomeBlock`` is a template key, which we will use to load the desired content. You can select any (unique) name you want.

Now add the BlockHolder to your template.
If you are using the Simple template that is bundled with SS3, ``includes/SideBar.ss`` may be a good place - the second line is the relevant change:

```
<aside>
	<div>$PersonalizedContent('WelcomeBlock')</div>
	<% if Menu(2) %>
		<nav class="secondary">
			<h3>
				<% loop Level(1) %>
				$Title
				<% end_loop %>
			</h3>
			<ul>
				<% loop Menu(2) %>
				<li class="$LinkingMode"><a href="$Link" title="Go to the $Title.XML page"><span class="arrow">&rarr;</span><span class="text">$MenuTitle.XML</span></a></li>
				<% end_loop %>
			</ul>
		</nav>
	<% end_if %>  	
</aside>
```


### 2. Create a Snippet

Snippets are reusable pieces of HTML, which are the different personalizations your page can display.

For this tutorial we need to create two Snippets, a ``WelcomeSnippet`` and a ``WelcomeBackSnippet``.
Log into the CMS and go to the *Personalization* page (``/admin/personalization``) and click on the *Snippet* tab where you can *Add Snippet* add the information shown in the following two screenshots:

![Welcome Snippet](https://github.com/yukiawano/sscpmodule/raw/master/docs/img/WelcomeSnippet.png)  
![Welcome Back Snippet](https://github.com/yukiawano/sscpmodule/raw/master/docs/img/WelcomeBackSnippet.png)


### 3. Create a BlockHolder

BlockHolders are the containers of personalized content. They provide the mapping to the template key from the first step.

Go to the *Block Holder* tab and click on the *Add Block Holder*. On the following page set the *Template Key* to ``WelcomeBlock`` and add a title and description to give it some context.

![Welcome BlockHolder](https://github.com/yukiawano/sscpmodule/raw/master/docs/img/WelcomeBlockHolder.png)


### 4. Create a Block

Once we have defined a BlockHolder, we need to add Blocks to it. These Blocks specify the audience type and map to the correct Snippet.

On the current page, where we have just defined the *Template Key*, go the *Blocks* tab and hit *Add Block*. There, add the information as shown in the following screenshot:

![Welcome Block](https://github.com/yukiawano/sscpmodule/raw/master/docs/img/WelcomeBlock.png)


### 5. Define a DefaultSnippet

Now a new comer gets the ``WelcomeSnippet`` that we have created, but we also want to show the ``WelcomeBackSnippet`` to returning users.
To accomplish that, we set the ``WelcomeBackSnippet`` as the DefaultSnippet, which is shown when there is no block that corresponds to the current session.

Go back to the details page of the current BlockHolder, check *Show default snippet* and set the ``WelcomeBackSnippet`` as the *Default Snippet*.

![Default Snippet](https://github.com/yukiawano/sscpmodule/raw/master/docs/img/DefaultSnippet.png)


### 6. Congratulations

Now when you access the site for the first time, you will get a welcome message.

And when you access the site again, you will get welcome-back message.

If you want to see welcome message again, delete the cookie of this site or use private browsing mode.

![Welcome Message Is Shown](https://github.com/yukiawano/sscpmodule/raw/master/docs/img/WelcomeMessageIsShown.png)