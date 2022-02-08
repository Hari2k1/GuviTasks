# GuviTasks
profile_form.html - contains a form where in personal details such as username , email , password, date of birth , age , contact number can be filled
profile_form.js - contains jquery and ajax code required to send data to get_data.php
get_data.php - this file does the following functions
1) append the user data in UserDetails.json if file exists or create a file named UserDetails.json
2) using redis cache we store and append the user data in key : value pairs, if key exists , it append the new data in the key else , a key is created and new data is stored
3)insert records into the server.
this file inserts data everytime a record is created and it is cached. It is because we have to manually update the server once in a while and erase the cache.
print_data.php-this file does the following functions
1)using redis cache,we retrieve data from cache key and display in page.
2)if key doesn't exists , a key is created and data is cached
3)to avoid stale data , key is set to expire every 10 seconds.
