# redirect0r 
*htaccess performance measuring*

If you ever wondered, how long your Apache will take serving a folder from your server with a huge htaccess f	ile, than this will give you an answer.

The redirect0r creates an .htaccess-file in a given folder, fill this file with an increasing number of redirect-rules and simply calles this ressource via HTTP. 

The result is a simple table that you can use in Excel or Google-Drive to see, how the response time will decrease. 

### chunks & repeat
The script will create as many rows as you define in "limit" inside a loop. The amount of chunks defines, how many times you want the script to call the "dummy ressource".

The repeat parameter defines, how many times you are going to repeat the request in a chunk.

**Example**

Lets say your limit is 100. So the script will create an htaccess file with 100 lines. If you set chunks to 5 and repeat to 1, the script will create 5 http-requests:

* 1st request with 20 lines in the htaccess file, cool down
* 2nd request with 40 lines in the htacces file, cool down
* 3rd request with 60 lines in the htacces file, cool down
* 4th request with 80 lines in the htacces file, cool down
* 5th request with 100 lines in the htacces file, cool down

You also can increase the number of repeats for each chunk. For the given example you define repeat: 2.

This his how the amount of request will increase:

* 1st request with 20 lines in the htaccess file, cool down
* 2nd request with 20 lines in the htacces file, cool down
* 3rd request with 40 lines in the htacces file, cool down
* 4th request with 40 lines in the htacces file, cool down
* and so on...

Remember that the cool down will take place after single request.

###Parameters

You need to define a couple of parameters in the file config.json:

* **limit** - how many rows shall be created

* **coolDown** - delay in seconds to wait before next HTTP-request is send

* **repeat** - repeat a HTTP-request how many times to calculate an average

* **chunks** - how many HTTP-requests you want to send for a specific numbers of rows

* **dummyPath** - sub folder that should be tested (relative to the current script path)

* **scheme** - you can define a scheme (http / https) otherwise script will try to detect it

* **host** - when you run this script on CLI it cannot not identify the host, apparantly, so you have to set the host to query manually

* **dummyFile** - a file inside the sub folder, that is the destination of the redirection rule

* **dummyContent** - HTML-content of the dummy file

* **htAccess.firstLine** - first line of the test-htaccess-file, you should not change this, unless you know what to do

* **htAccess.lines** - if you want to add some additional stuff before the set of redirection rules, do it here

### Example result

The first column shows the number of rows in the htaccess-file. The second column shows how long it took to get an response and the third column is showing the cool down delay.

    Start. 
    
    Test-URL: http://www.nickyreinert.de/redirect0r/foobar/1/ 
    
    rows;delay [ms];sleep [s] 
    100;3;2
    100;5;2
    100;6;2
    100;6;2

