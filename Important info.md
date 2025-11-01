\#  Tech-Hub Setup Notes



1\. For starters, I installed \*\*XAMPP\*\*, but I’m sure you already have it or something similar installed.  

2\. When I first ran the site, I got a message saying the \*\*database was not initialized\*\* or something similar.  

&nbsp;  - To fix that, open \*\*phpMyAdmin\*\* (the admin page) and create a new SQL database.  

&nbsp;  - Then, import your pre-existing database file into it.  

3\. That should fix the database error.  

4\. Next, go to \*\*Paystack\*\* and create an account  you’ll need to fill out a short form.  

5\. After creating your account, go to the \*\*Settings\*\* page and click on the \*\*API Keys \& Webhooks\*\* tab.  

6\. If you want the site to go live, use the \*\*Live Public Key\*\*  but if you’re just testing, use the \*\*Test Key\*\* instead.  

7\. I already made the \*\*.env\*\* file to make things simple, so just replace the existing API keys inside it with the ones you’ll get from Paystack.  

8\. I got an error saying \*\*“Dotenv not found”\*\* at first. If you get that too, install \*\*Composer\*\* directly inside the \*\*paystack\*\* folder.  

9\. That should be all  everything should run smoothly after that!



