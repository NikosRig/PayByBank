PayByBank
=================================================



Introduction
------------
Paybybank is a framework for accepting SEPA payments and can be used by PISPs. It can be extended and supports PSD2 compliant payment methods that follow Berlin group's specifications.

&nbsp;&nbsp;&nbsp;

<p>
    <img src="https://github.com/NikosRig/PayByBank/blob/master/var/paybybank.jpg" alt="flow"/>
    <img src="https://github.com/NikosRig/PayByBank/blob/master/var/Screenshot.png" alt="screenshot" width="350"/>
</p>


Prerequisites
------------
* [docker-compose](https://docs.docker.com/compose)

&nbsp;&nbsp;

Installation
------------

Create the .env file

```
cp .env.example .env
```

Build & run containers

```
docker-compose up --build
```

Install dependencies

```
docker-compose exec paybybank_fpm composer install
```

&nbsp;&nbsp;&nbsp;&nbsp;

API Overview
------------


#### Merchant endpoints

##### Create a new merchant
`POST /merchants/create`

| **Request parameters** | **Description** |  **Type** |            
| --- | --- |  --- |
| `firstName` | Merchant's first name | string | 
| `lastName` | Merchant's last name | string | 

| **Response parameters** | **Description** |  **Type** |
| --- | --- |  --- |
| `mid` | Merchant's unique id | string | 

| **Response status codes** | **Description** | 
| --- | --- |
| `201` | Merchant created | 
| `400` | Merchant creation failed | 

Request example 
```
curl --location --request POST 'http://localhost/merchants/create' \
--header 'Content-Type: application/json' \
--data-raw '{
    "firstName": "Nikos",
    "lastName": "Rigas"
}'
```

Response example 
```
201 {"mid":"fd6d98b078ab5a582077a1ed51bad9ac33c0b581744e58a8"}
```

&nbsp;

##### Create a new bank account for a merchant

`PUT /merchants/accounts`

| **Request parameters** | **Description** |  **Type** |
| --- | --- |  --- |
| `mid` | Merchant's unique id | string | 
| `iban` | Merchant's iban | string | 
 `accountHolderName` | Bank account beneficial owner | string |
 
 | **Response status codes** | **Description** | 
| --- | --- |
| `201` | Bank account created | 
| `400` | Bank account creation failed | 

Request example 
```
curl --location --request PUT 'http://localhost/merchants/accounts' \
--header 'Content-Type: application/json' \
--data-raw '{
    "mid": "95eb159ae3f679157492b13d634b43ce8264296b087d8b3f",
    "iban": "NL04ABNA7639905176",
    "accountHolderName": "Nikos Rigas"
}'
```

#### OAuth2 endpoints

##### Create access token

`POST /oauth2/token`

| **Request parameters** | **Description** |  **Type** |
| --- | --- |  --- |
| `mid` | Merchant's unique id | string | 

 | **Response status codes** | **Description** | 
| --- | --- |
| `201` | Access token created | 
| `400` | Access token creation failed | 

Request example 
```
curl --location --request POST 'http://localhost/oauth2/token' \
--header 'Content-Type: application/json' \
--data-raw '{
    "mid": "2b28ed8ba385029cac8f480213f23d598b687bb8ad5c9299"
}'
```

Response example 
```
201 {"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJwYXlieWJhbmsiLCJhdWQ"}
```

&nbsp;

#### Payment order endpoints

##### Create payment order

`POST /payment/orders/create`

 ``` Authorization: Bearer ```

| **Request parameters** | **Description** |  **Type** |
| --- | --- |  --- |
| `amount` | Payment order's amount | integer | 
| `description` | Payment order's description | string | 

 | **Response status codes** | **Description** | 
| --- | --- |
| `200` | Payment order created | 
| `400` | Payment order creation failed | 

Request example 
```
curl --location --request POST 'http://localhost/payment/orders/create' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJwYX' \
--data-raw '{
    "amount": 10050,
    "description": "Order#20e304"
}'
```

Response example 
```
201 {"token":"088f443b11caeb2ba988f2e39f6cd54712d8229ecf656ea7"}
```

##### Checkout payment order

`GET /payment/orders/checkout/:token`

| **Request parameters** | **Description** |  **Type** |
| --- | --- |  --- |
| `:token` | Payment order's token | string | 



Useful info
------------
 [Postman collection](https://github.com/NikosRig/PayByBank/blob/master/var/PayByBank.postman_collection.json)


##### To-Do
* Create a use-case that informs the merchant about payment order results
