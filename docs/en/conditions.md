## Available Conditions

Currently these conditions are available.
However, please remember that they are also still under development and the specifications of them would be changed in the future.

* Location
* Device
* NewComer

## Location (code/condition/Location.php)

Location is a condition that where a visitor is accessing from.
For example, if you are running shops in multiple cities(Otsu, Kyoto), by using this condition, you can show the location of a shop at Otsu for visitors from Otsu city, 

Location can be used as the below examples.

e.g.
```
Location: Japan
```
```
Location: Otsu
```

By default, we get location of a visitor from IPInfoDB.
You can specify the location with the name which is used in IPInfoDB, such as Japan, Kyoto.
I don't know where the list of names which IPInfoDB uses, 
but I think you can get some about it from [the list in correcting form](http://ipinfodb.com/report.php).

### Tips : Location is Cached in Cookie

Once a visitor has accessed to a web site, the location is cached in Cookie.
In other words, if a visitor has accessed from NewZealand for the first time,
then he or she moved to Austria, and access again to the website, 
our module says that the visitor is accessing from NewZealand.

## Device (code/condition/Device.php)

Device is a condition that which browser and operating system a visitor is using.
We consider the case that you are distributing an application for Windows and Mac.
By using this condition, you can show download link of Windows version, 
when the visitor is using Windows.

e.g.
```
Device: Windows
```
```
Device: Mac OS X
```

For getting browser and operating system of visitors, we uses User_agent.php which is from CodeIgniter framework.
You can see the name list of OSs and browsers at ``thirdparty/useragent/user_agents.php``

## NewComer (code/condition/NewComer.php)

NewComer is a condition that whether a visitor comes to this site for the first time or not.
For example, you can show welcome message for new comers by using this condition.

e.g.
```
NewComer: true
```