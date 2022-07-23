### Introduction 
This php application sends emails to members who are set to yes

#### Setup
Requires php and composer.
Run `composer install` to download php libraries for this project.

#### Requirement 
1.
Create a file name client.json on root directory with format 
```
{
    "smtp": {
        "host" : "smtp.gmail.com",
        "user" : "your-gmail-account@gmail.com",
        "pass" : "your-gmail-app-password"
    },
    "sheet_id": "Sheet id of Google sheets",
    "tab_name": "Sheet name of Google Sheets"
}
```

2.
Download API Key credentials from Google Console Cloud.
Enable Google Sheets api from Google Console Cloud.

3. 
Run application using LAMP or php using `php -S localhost:8000`

5. Open `localhost:8000` on your browser to return the valid table and output.

#### Notes

The Google sheets should have following headers 
* First Name
* Last Name
* Email
* Phone
* Send Mail?