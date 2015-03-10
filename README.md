SemverLib
=========

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/stuartherbert/php-myversion/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/stuartherbert/php-myversion/?branch=develop)

**MyVersionLib** is a simple-to-use PHP component for easily determine your app's installed version by reading Composer's `installed.json` file.

The main advantage of this is that you don't have to manually update a version string in your source code any more. Use this library to ask Composer which version it installed instead :)

System-Wide Installation
------------------------

MyVersionLib should be installed using composer:

    require: {
        "stuart/myversion": "~1"
    }

Usage
-----

This library is really easy to use:

{% highlight php startinline %}
$version = new Stuart\MyVersion("datasift/storyplayer");
echo (string)$version;
{% endhighlight %}

Just substitute "datasift/storyplayer" for the composer package you want the version for.

Contributing
------------

This library is developed using the Gitflow model.  To contribute:

1. Fork this project on GitHub
1. Create a feature branch off the develop branch
1. Commit your changes to your feature branch
1. Send me a Pull Request

License
-------

[New BSD license](LICENSE.txt)