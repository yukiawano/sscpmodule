## Notice

This module is under development.
It means that there would be a lot of large changes and this module is not reliable.
For example, we may change the database structure largely.
We strongly recomment not use this module on production environment.
Please know this before you begin to use this module.

## Install

For installing this module, you just need to

    git clone https://github.com/yukiawano/sscpmodule.git

After you cloned this module, you need to rename the folder of this module to sscp.

Finally, access to /dev/build/?flush=all

## Configuration

You must make configuration before you start to use this module.
Copy two configuration files

    sscp/_config/audiencetype.yml.sample
    sscp/_config/apikey.yml.sample

to

    mysite/_config/audiencetype.yml
    mysite/_config/apikey.yml

### APIKeys

You need to set some api keys to use our module,
because our module uses some thirdparty apis(e.g. IP-Based Geo-Location).

Currently, you just need to set an apikey of IPInfoDB.
You can get an api key from the link below for free.

Register
http://ipinfodb.com/register.php

### AudienceTypes

On our personalization module, we personalize content based on AudienceTypes.
AudienceType is a collection of conditions.

You can define AudienceTypes with yaml as the example.

'''yaml
AudienceType:
  AudienceTypes:
    InclusiveOR:
      NewComer:
        NewComer: true
      JapanLinuxUser:
        Location: Japan
        Device: Linux
      Japanese:
        Location: Japan
'''

After you changed audience types you must access to /dev/build/?flush=all to reload configuration.

## Tutorial

In this tutorial we create a example of creating a customized block, which shows welcome message for new comers.
We use the audience types that is defined before.

### 1. Create BlockHolder

BlockHolder is a place holder for content personalization.
We can call block holder by using template key from template.

When we defined a block holder with template key "WelcomeBlock",
you can access to the block holder from template file as the below.

'''
$PersonalizedContent('WelcomeBlock')
'''

Now create a block holder with template name "WelcomeBlock", and input favorite title and description.

![Welcome BlockHolder](/docs/img/WelcomeBlockHolder.png)

### 2. Create Snippet

We add blocks to block holder with specifying an audience type.
However, before we go on to add blocks to a block holder, we must intorduce snippets.

We introduced snippet for reusing html snippet.

In this tutorial we create two snippet, WelcomeSnippet and WelcomeBackSnippet.

![Welcome Snippet](/docs/img/WelcomeSnippet.png)
![Welcome Back Snippet](/docs/img/WelcomeBackSnippet.png)

### 3. Create Blocks

Now we create a block for showing a welcome message for new comers.
Move to the block holder that we created before, click the right-top tab 'Blocks', and hit 'Add Block'.

![Welcome Block](/docs/img/WelcomeBlock.png)

### 4. Define default snippet

Now a new comer gets welcome message that we created before,
but we want to show welcome-back message for return users.

We set welcome-back message snippet as default snippet.
Default snippet is shown when there is no block that corresponds to the current session.

In editor of a block holder, check "Show default snippet" and set "WelcomeBackSnippet" as a default snippet.

![Default Snippet](/docs/img/DefaultSnippet.png)

### 5. Add Function in Template

This is a final step.

Add a function for showing the block holder in your template.
If you are using "simple" template that is bundled with SS3, includes/SideBar.ss may be a good place.

'''
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
'''

### 6. Congratulations

Now when you accessed to the site for the first time,
you will get welcome message.

And when you access the site again, you will get welcome-back message.

If you want to see welcome message again, delete cookie of this site or use private browsing mode.

![Welcome Message Is Shown](/docs/img/WelcomeMessageIsShown.png)