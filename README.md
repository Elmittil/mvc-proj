[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Elmittil/mvc-proj/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Elmittil/mvc-proj/?branch=master)  [![Code Coverage](https://scrutinizer-ci.com/g/Elmittil/mvc-proj/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Elmittil/mvc-proj/?branch=master)  [![Build Status](https://scrutinizer-ci.com/g/Elmittil/mvc-proj/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Elmittil/mvc-proj/build-status/master)  [![Code Intelligence Status](https://scrutinizer-ci.com/g/Elmittil/mvc-proj/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)  [![Build Status](https://travis-ci.com/Elmittil/mvc-proj.svg?branch=master)](https://travis-ci.com/Elmittil/mvc-proj)

# Game 21

![site-header](https://github.com/Elmittil/mvc-proj/blob/master/public/img/game21_screen.png)

## Exam project for MVC course at BTH  

The goal of the project was to extend functionality of a website-based dice game (21). For this project I used Symfony framework and Doctrine ORM. 

#### Features

- user login
- user credit
- score list for orunds per game and successful highest bets
- rolled dice statistic
- anonymous practice rounds
- play with 1 or 2 dice

#### Rules 
In Game 21 the user plays against the computer. A user can choose to log in and use credits to play with bets or play anonymously without betting aka practice rounds. 
A choice to log in, register or play anonymously is available on the index page. 
A user can purchase extra credits or grow their pot with winnings from playing against the computer. 
When logged in the user may use the credits they have (or purchase credits) to play. A bet is at least 10 credits and maximum of the user's total credits, with increments of 5 credits in between. 
To play the user must choose the bet level and roll their dice. The user can roll dice until they get sum total of point that exceeds 21. Computer rolls after the user. A user may pass, but only once. Passing to roll the dice will allow the computer to roll untill it has either won or lost. 
The user can play unlimited amount of rounds per game, given that they have enough credit. Players can play unlimited amount of rounds per game and games anonymously. 
The user can finish the game by choosing 'new game' option. The rounds won will then be recorded. 

#### Scoring
The player who has the highest total points, that doesn't not exceed 21, wins. If both computer and user score 21 computer wins. 
Once the bet has been won the betting amount is saved along with the user name. The highest bets won can be seen on the scores page ```/top-scores```, along with the highest and lowest total rounds per game won. (Ie if a user wins no rounds per game there will be no record).

#### Installation
To run the application copy the repo to a directory on your device and run ```make install``` command in a text terminal from this directory. Point your browser at ```public/index.php``` and enjoy!
