# What is CPEnvironment

We use CPEnvironment for handling the information of the current session (CP is an abbreviation for Content Personalization). It stores values of the current session and provides an API for retrieving information from it (e.g. location, user agent,…).
Every state related to the current session is stored in CPEnvironment.
Instances of conditions do not have any state.


## The reason for using CPEnvironment

CPEnvironment seems a little redundant. Here we explain the reasons of why we introduced CPEnvironment:

1. It can make conditions more testable and debuggable.
By separating the state from each condition, we can make conditions stateless.
If we want to test a condition in a specific environment, we just need to create a stub environment for that.
And when we provide a debug toolbar for users, we can swap the actual environment for another one.
2. It can abstract the datastore and API layer.  
  Currently we are using cookies for storing the information of the current session, but we may want to change to other datastore system later on. In this situation, we can do this just by changing CPEnvironment.  
  Another example is that we are currently using [http://ipinfodb.com](http://ipinfodb.com) for getting the location of the current user. We may want to change to another web API for getting the location in the future. Currently the condition that uses the location is only Location.php, but in the future there may be many conditions using it. If the situation is so, we would need to change it in every condition that uses the location. However, if the location is provided by CPEnvironment, we can switch the web API by only changing it in CPEnvironment.

This is why we have introduced CPEnvironment.