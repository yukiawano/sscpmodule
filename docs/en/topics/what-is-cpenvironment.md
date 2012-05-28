We use CPEnvironment for handling the information of current session(CP is abbreviation of Content Personalization). It stores values of current session, and provides some APIs for getting information of current session(e.g location, user agent).
Every state related to current sessions are stored in CPEnvironment.
Instances of conditions don't have any state.

## The reasons of why we use CPEnvironment

CPEnvironment seems a little redundant. Here we explain the reasons of why we introduced CPEnvironment.

First reason is that it can make conditions more testable and debuggable.
By making the state apart from each condition, we can make condition stateless.
If we want to test the condition in specific environment, we just need to create a stub environment for that.
And when we provided a debug toolbar for users, we can doing it by swapping an actual environment for another environment.

And the second reason is that it can abstract the datastore and api layer.
Currently we are using cookie for storing the information of current session, but we may want to change to other datastore system. In this situation, we can doing this just by changing CPEnvironment.
And another example is that we are using http://ipinfodb.com for getting location of current user, but we may want to change to another web api for getting location. Currently the condition that uses location is only Location.php, but in the future there may be many conditions that uses location. If the situation is so, we should change in every conditions that uses location. However if apis are provided from CPEnvironment, we can change the api just by changing only CPEnvironment.

This is why we introduced CPEnvironment for this.