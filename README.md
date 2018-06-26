## About Hacker News Clone

Its a small website of Hacker news, you can find more details on https://news.ycombinator.com

### Features:
- List of top stories.
- List of new stories.
- Display a story with its nested comments.

### Tech Features:
- Using Silex 2.x.
- Using PHP 7.x.
- PSR-2 standard code style.
- Configurable.
- Testable.
- Bootstrap 4.x.

### System requirements:
- PHP 7.x.
- curl php extension.
- php composer.

### Installation:

Run the following commands in terminal.

~~~
$ git clone https://github.com/fghazaleh/hacker-news-clone.git
~~~
then..
~~~
$ composer install
~~~

### Manual testing:

Run the composer command in terminal. 
~~~
$ composer run
~~~
open the web browser on the following address:
~~~
http://localhost:8000
~~~

or for debug, on address:
~~~
http://localhost:8000/index_dev.php/
~~~

### PHP testing:

To run the **Unit** suites
~~~
$ ./test unit
~~~

To run the **Feature** suites
~~~
$ ./test feature
~~~

To run the **Integration** suites
~~~
$ ./test int
~~~

To run the all suites
~~~
$ ./test all
~~~

#### TODO List:
- Create a **Ask** feature.
- Create a **Job** feature.
- Develop a cache service for performance. 
- Enhance the website UI.
- Create more unit tests.