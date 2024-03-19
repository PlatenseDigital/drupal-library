# Why Drupal Library?

I have created this functionality because on my team we work with very large sites, with many components and therefore many libraries and, on some servers, their versions have to be updated manually.
This task takes time and there is a possibility of accidents, such as two people editing at the same time. Or someone has a typo. And doing it with a command solves all these problems, speeding up our development and, with any luck, yours too.


## Installing
1. Download the files and simply merge the vendor folder with the vendor folder in your Drupal installation
2. In your composer.json file add the next code:

    "autoload": {
	    "psr-4": {
		    "Console\\": "vendor/drupallibrary"
	    },
	    "classmap": ["vendor/drupallibrary"]
    },

3. run the next command in your console: composer dump-autoload -o

And done!

## Using it
You can run

**php vendor/bin/drupal-library add -f "folderName" --has "css"/"js"/"_both_" -l "base"/"layout"/"_component_"/"state"/"theme" Theme-name library-name**

to create a new library. 
With the parameter has (-has) you can select if your library has CSS or JS. If you do not specify a "has" value, the default behavior is to have both.
With the parameter folder (-f) you can select the folder where your files will be created, the default value is components. You can also add subdirectories and these will be created if they do not exist. Such as "components/buttons"
With the parameter level (-l) you can select the weight of your library, the default value is component.

The default version is "VERSION" if you are not on a server with reverse proxy this will probably keep your libraries up to date.
But in the other case you can use

**php vendor/bin/drupal-library update Theme-name library-name**

to update the version of your library.
  
 
