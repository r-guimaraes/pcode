## Important Notes
This example app does not deal with user registration/authentication. For this reason, there's no need to register before
sending requests, neither deal with credentials/authenticate tokens.

But in a real world scenario, this would be primarily necessary.

There are complementary comments about specific details around the codebase.

Original order id, item's internal and external ids are saved as strings to be more flexible regarding
different types of different platforms ids.

### Running
App made with Laravel Sail, so make sure to have Docker and Docker-compose up and running.

There are some seed files that can be run during the first migration to make it easier to manually test.

### API SPEC
Developers integrating with this service are supposed to send a POST request to `${base_url}/api/v1/orders`, with the following spec:

### Available Endpoints
All endpoints are prefixed with `api/v1`. These are the available ones:
    
    * This is the "main" endpoint, to where developers will send orders
    - POST /orders - Sends an order to be ingested and relayed to external partner
    - GET /orders - Lists all orders
    - GET /orders/{order} - Lists a single order
    - GET /partners/ - Lists all partners
    - GET /partners/{partner} - Lists a single partner

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

* These variables are also available at the bottom of `.env.example` file.

The endpoint `https://morsumpartner.free.beeceptor.com/api/v1/orders` is a MOCK API listening to POST request like
Partner#1 spec.

### Summary
This service receives e-commerce orders through an endpoint, based on previously-known data spec,
saves it internally, and then prepares ingested data to be relayed to different kinds of integrations.

With this service, each partner may have its own type of integration, be it CSV, API, XML, or others. Besides, each partner can specify its own URI integration, proper to its integration.

For example, Partner#1 is registered as an API-integration, and may specify the main endpoint to have its orders relayed.
Partner#2, by the other hand, might use CSV-integration, and specify his SFTP server path as the URI to have its orders sent.

Right now, both CSV/SFTP and API integrations are implemented, and structured in a manner flexible enough to be extended
to other types of integrations.

The decision to where and how to send ingested order is made automatically based on partner's previously registered exchange type.

After relaying the order successfully, order's status is changed to `relayed`, so it can be considered OK, or `errored` in case of something go wrong.
The service also tracks when the order has been successfully relayed, so it can be used to detect any anomalies. 
Besides, these managed order statuses could be expanded in the future to have even more control about order lifecycle, 
and eventually notifies external services, logs, monitorings and emails if something goes wrong, or when everything is
considered done for that order.

In a real world scenario, orders would be relayed through a background job, but not the case for this example app.

Also, incoming data would have a proper validation, especially for addresses existence/format, valid partner id, and valid order items data. 
