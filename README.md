# Triton

#### The beta version is deployed at https://sqltry3.azurewebsites.net/uploads/loginsignup/login.php
##### cpanel link http://triton.byethost7.com

#### A social media network for space enthusiast.
#### The web app is written in PHP and currently uses Azure SQL Database.

#### Features available currently in the app are:
1. Profile/Cover image support
2. Follow and connect to a user
3. Real time messaging support
4. Uploading posts and giving likes
5. NASA Image APIs content in the feed page.

##### The web app will be available on Azure and cpanel with separate databases.

#### Different Sections of application and their names.
1. Feed/home page aka SpaceShuttel
2. All users page aka Astronauts
3. Messaging as Radio
4. Users profile


#### Followers/Connections/Requests (web APIs)
1. For maintaining the network such as liked posts, connections, requests A JSON file is used where each user is present as a object.
2. A separate JSON file is used for all users posts which provides content in feed page.

#### Feed page logic
1. First all the posts sorted by the likes is displayed then the user can keep scrolling through the NASA images APIs [->](https://github.com/Spectre-ak/Triton/blob/main/SpaceShuttel/SpaceShuttel.php)


#### Messaging
1. Messaging is only available for connections, so users must be connected to chat [->](https://github.com/Spectre-ak/Triton/blob/main/radio/Radio.php)
2. Chat history is loaded once and each current chat gets addded on the history database for both the user
3. Real-time messaging uses different databases and gets removed after each received message
4. Status(online/offline) -Each user keeps updating status(current time) on the firebase database using setinterval() for a particular chat

Note:- Messages are not encrypted yet.
