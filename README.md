# Documentation

This is the documentation of our service. Here you will find information about the available endpoints,
how to use them, and what to expect in the responses.

## Postman endpoints

You wil find the postman endpoints at /postman/VendingMachine.postman_collection
On postman app click import and select or drag and drop the previous file.

## Docker configuration

Run on your bash after cloning the repository:

```
    cd vending-machine
    docker compose up -d --build
    docker compose exec app composer install
    docker compose exec app php bin/console doctrine:migrations:migrate
    docker compose exec app php ./vendor/bin/phpunit tests (to run all tests)
```

## Available Endpoints

### 1. Return inserted coins

GET /return_coins

#### Description

This endpoint returns all the money that the user has inserted in the machine.

#### Query parameters

- None

#### Successful response

```json
{
    "message": "The entered balance 0.10 has been returned successfully.",
    "result": "Coins returned: 0.10"
}

```

### 2. Select an item

GET item?item_name=PRODUCT_NAME

#### Description

This endpoint returns the selected item and the money change.
If the item is not available in the machine, it returns an informative message.
If the current balance is not enought to buy the item, it returns an informative message.

#### Query parameters

- item_name: string

#### Successful response

```json

{
    "message": "The item has been selected successfully.",
    "result": "Selected item: Water, Coins returned: 0.05"
}

```

### 3. Insert Coin

POST /insert_coin

#### Description

This endpoint adds a valid coin to the current user balance.
The valid coins are 0.05, 0.10, 0.25 and 1.00.
If the coin inserted is not valid, it returns an informative message.

#### Body parameters

Absolute score

- total: int

```json
{
  "coin": 0.05
}
```

#### Successful response

```json
{
    "message": "Coin has been inserted correctly",
    "result": "The current balance is 0.70"
}
```

### 4. Service call

POST /service

#### Description

This endpoint adds a set of valid coins and a set of valid items to the machine.
Valid coins are 0.05, 0.10, 0.25, and 1.00.
If the inserted coin is not valid, it returns an informational message.
Valid items are Water, Juice, and Soda.
If the inserted item is not valid, it returns an informational message.

#### Body parameters

Absolute score

- coins: object (coin_value: string, quantity: integer)
- items: object (item_name: string, quantity: integer)

```json
{
    "coins": {
        "0.05": 20,
        "0.10": 20,
        "0.25": 20,
        "1.00": 20
    },
    "items": {
        "Water": 20,
        "Juice": 20,
        "Soda": 20
    }
}
```

#### Successful response

```json
{
    "message": "Coin has been inserted correctly",
    "result": "The current balance is 0.70"
}
```
