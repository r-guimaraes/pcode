## Important Notes
This example app does not deal with user registration/authentication. For this reason, there's no need to register before
sending requests, neither deal with credentials/authenticate tokens.

But in a real world scenario, this would be primarily necessary.

### Running
App made with Laravel Sail, so make sure to have Docker and Docker-compose up and running.

There are some seed files that can be run during the first migration to make it easier to manually test.

### API SPEC
Developers integrating with this service are supposed to send a POST request to `${base_url}/api/v1/orders`, with the following spec:

### Postman
There's a Postman collection available to make it easier to interact with APIs endpoints,
along with examples of expected data.

### Env Variables
In order to test CSV files being sent to your SFTP server, set up the following env variables:
```
SFTP_HOST=
SFTP_PORT=
SFTP_USERNAME=
SFTP_PASSWORD=
SFTP_ROOT=
```

* These variables are also avaiable at the bottom of `.env.example` file.

### Summary
