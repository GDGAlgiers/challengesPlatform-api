[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]

<!-- PROJECT LOGO -->
<br />
<p align="center">
   <a href="https://github.com/GDGAlgiers/challengesPlatform-api"><img src="images/gdg.png" height="auto" width="200" style="border-radius:50%"></a>
  <h3 align="center">Challenges Platform API</h3>
  <p align="center">
This repository contains the API for GDG Algiers challenges platform Infrastructure API built with <a href="https://laravel.com/">Laravel</a> & <a href="https://www.mysql.com/">MySQL</a>.
    <br />
    <br />
    <a href="https://github.com/GDGAlgiers/challengesPlatform-api">View Demo</a>
    ·
    <a href="https://github.com/GDGAlgiers/challengesPlatform-api/issues">Report Bug</a>
    ·
    <a href="https://github.com/GDGAlgiers/challengesPlatform-api/issues">Request Feature</a>
  </p>

<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary><h2 style="display: inline-block">Table of Contents</h2></summary>
  <ol>
    <li><a href="#setup">How To Setup the API locally<a></li>
    <li><a href="#overview">Overview</a></li>
    <li><a href="#response-codes">Response Codes</a></li>
    <li>
      <a href="#collections">Collections</a>
      <ul>
        <li><a href="#collections">Authentication</a></li>
        <li><a href="#admin">Admin</a></li>
        <li><a href="#participant">Participant</a></li>
        <li><a href="#judge">Judge</a></li>
      </ul>
    </li>
    <li><a href="#join-our-community">Join our community</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>

<a name="setup"></a>

# How To Setup the API locally

- Clone the API repository
- Change the name of .`env.example` to `.env`. Fields that must be set:
    
    - `APP_URL`

    - `FRONT_URL`
        
    - `SESSION_DOMAIN`
        
    - `SANCTUM_STATEFUL_DOMAINS`
        
    - `DB_CONNECTION`
        
    - `DB_HOST`
        
    - `DB_PORT`
        
    - `DB_DATABASE`
        
    - `DB_USERNAME`
        
    - `DB_PASSWORD`
        
- Run the command: `composer install` (necessite downloading `composer`)
    
- Run the command: `php artisan key:generate`
    
- Run the command `php artisan migrate` to generate database tables
    
- If desired, run the command `php artisan db:seed` to seed database with fake data
    

<a name="overview"></a>

# Overview

The following things must be kept in mind:

- **For all requests**, a _Bearer token_ must be included on the request headers-`Authorization` property- ; _**except the Login request**_
    
    - The Bearer token is sent on the Login's response or the Register\`s response
        
    - `accept` _**MUST ALWAYS**_ be set to `application/json`
        
- Similar Routes have similar paths
    - Example: admin routes start with: `/api/admin/...`
        - Admin Track actions: `/api/admin/track/...`
- Two possible responses formats ALWAYS:
    
    - Success response:
        
        - **success**: Boolean (true)
        - **data**: Array of objects
        - **message**: Descriptive response message
    - Failure:
        - **success**: Boolean(false)
        - **message**: Descriptive error message
        - **data**: \[Not always, sent when error needs to display Data such as Validation errors\]

<a name="response-codes"></a>

# Response Codes

- **404**: Route can't be found
    
- **400**: Bad request
    
- **403**: Forbidden
    
- **401**: Unauthorized
    
- **200**: Success
    
- **500**: Internal Server error

<a name="collections"></a>

# Collections:

<a name="authentication"></a>
<details>

  <summary>📁 Authentication</summary>

  # 📁 Collection: Authentication 

## End-point: /api/login
**Accessibility**: Everyone non authenticated.

**Required Body infos:**

- full_name \[String\]
- password \[String, min:6 chars\]
    

**Response**: Authenticated user, with associated Token

> The Associated Token must be sent as Bearer token on the headers on each request requires the user to be authenticated
### Method: POST
>```
>/api/login
>```
### Headers

|Content-Type|Value|
|---|---|
|accept|application/json|


### Body (**raw**)

```json
{
    "email": "admin1@admin.com",
    "password": "123456"
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|AJv5hlt1o4Uvlt7k6aH2NBjqbKJAMgTCTvnJQd4M|string|


### Response: 200
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "full_name": "admin1",
            "email": "admin1@admin.com",
            "role": "administrator"
        },
        "token": "1|lYmuqy5Rwj9FhGjkRA2mnUrI6Zlh9pseRl9JPBhs"
    },
    "message": "Login succesfull"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/logout
**Accessibility**: Everyone authenticated.

**Required Body infos: Nothing**

**Response**: Success message
### Method: POST
>```
>/api/logout
>```
### Body (**raw**)

```json

```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|7|ehGLzss2EoFkpVBx8K3bzkxR29ITBroDGqlW4KQi|string|


### Response: undefined
```json

```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃
</details>

<a name="admin"></a>
<details>
  <summary>📁 Admin</summary>

  # 📁 Collection: Admin 


## End-point: /api/admin/user/create-participant
**Accessibility**: Admin

**Required Body infos:**

- full_name \[String\] \[this record is unique\]
    
- email \[String\] \[this record is unique\]
    
- password \[String, min:6 chars\]
- track \[String, track type\]
    

**Response**: Created user instance
### Method: POST
>```
>/api/admin/user/create-participant
>```
### Headers

|Content-Type|Value|
|---|---|
|Accept|application/json|


### Body (**raw**)

```json
{
    "full_name": "new participant",
    "password" : "123456",
    "track": "Placeat."
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|tqI2q0SblbvlSTIyGwRG53jYYIFrigJzvfotXrLRd049ddf5|string|


### Response: 200
```json
{
    "success": true,
    "data": {
        "id": 2,
        "full_name": "new participant",
        "points": 0,
        "role": "participant",
        "email_verified": false,
        "track": "Placeat.",
        "submissions": []
    },
    "message": "Participant was succefully created!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/user/create-judge
**Accessibility**: Admin

**Required Body infos:**

- full_name \[String\] \[this record is unique\]
    
- email \[String\] \[this record is unique\]
    
- password \[String, min:6 chars\]
- track \[String, track type\]
    

**Response**: Created user instance
### Method: POST
>```
>/api/admin/user/create-judge
>```
### Headers

|Content-Type|Value|
|---|---|
|Accept|Application/json|


### Body (**raw**)

```json
{
    "full_name" : "new judge",
    "password" : "123456",
    "track": "Placeat."
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|tqI2q0SblbvlSTIyGwRG53jYYIFrigJzvfotXrLRd049ddf5|string|


### Response: 200
```json
{
    "success": true,
    "data": {
        "id": 3,
        "full_name": "new judge",
        "role": "judge",
        "track": "Placeat."
    },
    "message": "Succefully registred the judge!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/user/2/delete
**Accessibility**: Admin

**Required Body infos: user ID on the route (/admin/user/{ID}/delete)**

**Response**: Successfull message
### Method: DELETE
>```
>/api/admin/user/2/delete
>```
### Headers

|Content-Type|Value|
|---|---|
|Accept|application/json|


### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|tqI2q0SblbvlSTIyGwRG53jYYIFrigJzvfotXrLRd049ddf5|string|


### Response: 200
```json
{
    "success": true,
    "data": [],
    "message": "The user was succefully deleted!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/user/
**Accessibility**: Admin

**Required Body infos: /**

**Response**: Users instances Array

> The response is sent as paginated format(10 records per request)
> 
> the link to call the next instances is found on the response object, property: `next_page_url`
> 
> The link to call the previous instances is found on the response object, property: `prev_page_url`
### Method: GET
>```
>/api/admin/user/
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|tqI2q0SblbvlSTIyGwRG53jYYIFrigJzvfotXrLRd049ddf5|string|


### Response: 200
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "full_name": "admin",
            "role": "admin",
            "track": null,
            "points": null
        },
        {
            "id": 3,
            "full_name": "new judge",
            "role": "judge",
            "track": {
                "id": 1,
                "type": "Placeat.",
                "description": "Odit recusandae et.",
                "is_locked": 1,
                "created_at": "2023-10-17T19:53:00.000000Z",
                "updated_at": "2023-10-17T19:53:00.000000Z"
            },
            "points": null
        }
    ],
    "message": "Successfully retrieved all the users!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/track
**Accessibility**: Admin

**Required Body infos: NONE**

**Response**: Array of Track objects
### Method: GET
>```
>/api/admin/track
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|7|ehGLzss2EoFkpVBx8K3bzkxR29ITBroDGqlW4KQi|string|


### Response: 200
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "type": "web",
            "description": "trackWEB",
            "number_of_challenges": 11
        },
        {
            "id": 2,
            "type": "ai",
            "description": "trackAI",
            "number_of_challenges": 4
        },
        {
            "id": 3,
            "type": "mobile",
            "description": "trackMOBILE",
            "number_of_challenges": 7
        },
        {
            "id": 4,
            "type": "cyberSecurity",
            "description": "trackSEC",
            "number_of_challenges": 3
        },
        {
            "id": 5,
            "type": "others",
            "description": "trackOTHERS",
            "number_of_challenges": 6
        }
    ],
    "message": "Tracks were succefully restored"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/track/create
**Accessibility**: Admin

**Required Body infos:**

- type \[String\] \[this record is unique\]
- description \[String\]
    

**Response**: Created Track instance
### Method: POST
>```
>/api/admin/track/create
>```
### Body (**raw**)

```json
{
    "type" : "Blockchain",
    "description": "Description about Blockchain Track"
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|7|ehGLzss2EoFkpVBx8K3bzkxR29ITBroDGqlW4KQi|string|


### Response: 200
```json
{
    "success": false,
    "message": {
        "id": 7,
        "type": "Blockchain",
        "description": "Description about Blockchain Track",
        "number_of_challenges": 0,
        "is_locked": true
    },
    "data": "Track was succefully created!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/track/lock-all
**Accessibility**: Admin

**Required Body infos: NONE**

**Response**: Successfull message
### Method: POST
>```
>/api/admin/track/lock-all
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|7|ehGLzss2EoFkpVBx8K3bzkxR29ITBroDGqlW4KQi|string|


### Response: 200
```json
{
    "success": true,
    "data": [],
    "message": "Tracks were succefully locked"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/track/unlock-all
**Accessibility**: Admin

**Required Body infos: NONE**

**Response**: Successfull message
### Method: POST
>```
>/api/admin/track/unlock-all
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|7|ehGLzss2EoFkpVBx8K3bzkxR29ITBroDGqlW4KQi|string|


### Response: 200
```json
{
    "success": true,
    "data": [],
    "message": "Tracks were succefully unlocked"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/track/1/lock
**Accessibility**: Admin

**Required Body infos: Track id(on the route)**

**Response**: Successfull message
### Method: POST
>```
>/api/admin/track/1/lock
>```
### Headers

|Content-Type|Value|
|---|---|
|Accept|application/json|


### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|tqI2q0SblbvlSTIyGwRG53jYYIFrigJzvfotXrLRd049ddf5|string|


### Response: 200
```json
{
    "success": true,
    "data": [],
    "message": "Track was succefully locked"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/track/1/unlock
**Accessibility**: Admin

**Required Body infos: ID(on the route)**

**Response**: Successfull message
### Method: POST
>```
>/api/admin/track/1/unlock
>```
### Headers

|Content-Type|Value|
|---|---|
|Accept|application/json|


### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|tqI2q0SblbvlSTIyGwRG53jYYIFrigJzvfotXrLRd049ddf5|string|


### Response: 200
```json
{
    "success": true,
    "data": [],
    "message": "Track was succefully unlocked"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/track/1/delete
**Accessibility**: Admin

**Required Body infos: ID(on the route)**

**Response**: Successfull message
### Method: DELETE
>```
>/api/admin/track/1/delete
>```
### Headers

|Content-Type|Value|
|---|---|
|Accept|application/json|


### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|tqI2q0SblbvlSTIyGwRG53jYYIFrigJzvfotXrLRd049ddf5|string|


### Response: 200
```json
{
    "success": true,
    "data": [],
    "message": "Track was succefully deleted!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/track/1/update
**Accessibility**: Admin

**Required Body infos: ID(on the route)**

**Response**: Updated Track instance

> If there's a validation error, a response contains necessary information will be returned
### Method: PUT
>```
>/api/admin/track/1/update
>```
### Body (**raw**)

```json
{
    "description": "Updated web description"
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|11|py83j4mRyqKoFO4rjZcd2QbVwRDOuGGhBlUbWLWy|string|


### Response: 200
```json
{
    "success": true,
    "data": {
        "id": 1,
        "type": "web",
        "description": "Updated web description",
        "number_of_challenges": 9,
        "is_locked": 1
    },
    "message": "Successfully updated the track!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/challenge
**Accessibility**: Admin

**Required Body infos: NONE**

**Response**: Challenges array instances
### Method: GET
>```
>/api/admin/challenge
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|LyrmV0uSviQ7dJwRR7hvBUVifkMlvyAJLDmQdfXb|string|


### Response: 200
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "track": "web",
            "name": "challenge1",
            "difficulty": "easy",
            "description": "description1",
            "points": 20,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 2,
            "track": "mobile",
            "name": "challenge2",
            "difficulty": "easy",
            "description": "description2",
            "points": 40,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 3,
            "track": "web",
            "name": "challenge3",
            "difficulty": "easy",
            "description": "description3",
            "points": 60,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 4,
            "track": "others",
            "name": "challenge4",
            "difficulty": "easy",
            "description": "description4",
            "points": 80,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 5,
            "track": "web",
            "name": "challenge5",
            "difficulty": "easy",
            "description": "description5",
            "points": 100,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 6,
            "track": "cyberSecurity",
            "name": "challenge6",
            "difficulty": "easy",
            "description": "description6",
            "points": 120,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 7,
            "track": "web",
            "name": "challenge7",
            "difficulty": "easy",
            "description": "description7",
            "points": 140,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 8,
            "track": "ai",
            "name": "challenge8",
            "difficulty": "easy",
            "description": "description8",
            "points": 160,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 9,
            "track": "cyberSecurity",
            "name": "challenge9",
            "difficulty": "easy",
            "description": "description9",
            "points": 180,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 10,
            "track": "mobile",
            "name": "challenge10",
            "difficulty": "easy",
            "description": "description10",
            "points": 200,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 11,
            "track": "others",
            "name": "challenge11",
            "difficulty": "easy",
            "description": "description11",
            "points": 220,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 12,
            "track": "ai",
            "name": "challenge12",
            "difficulty": "easy",
            "description": "description12",
            "points": 240,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 13,
            "track": "others",
            "name": "challenge13",
            "difficulty": "easy",
            "description": "description13",
            "points": 260,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 14,
            "track": "cyberSecurity",
            "name": "challenge14",
            "difficulty": "easy",
            "description": "description14",
            "points": 280,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 15,
            "track": "others",
            "name": "challenge15",
            "difficulty": "easy",
            "description": "description15",
            "points": 300,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 16,
            "track": "cyberSecurity",
            "name": "challenge16",
            "difficulty": "easy",
            "description": "description16",
            "points": 320,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 17,
            "track": "ai",
            "name": "challenge17",
            "difficulty": "easy",
            "description": "description17",
            "points": 340,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 18,
            "track": "web",
            "name": "challenge18",
            "difficulty": "easy",
            "description": "description18",
            "points": 360,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 19,
            "track": "mobile",
            "name": "challenge19",
            "difficulty": "easy",
            "description": "description19",
            "points": 380,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 20,
            "track": "cyberSecurity",
            "name": "challenge20",
            "difficulty": "easy",
            "description": "description20",
            "points": 400,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 21,
            "track": "ai",
            "name": "challenge21",
            "difficulty": "easy",
            "description": "description21",
            "points": 420,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 22,
            "track": "ai",
            "name": "challenge22",
            "difficulty": "easy",
            "description": "description22",
            "points": 440,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 23,
            "track": "web",
            "name": "challenge23",
            "difficulty": "easy",
            "description": "description23",
            "points": 460,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 24,
            "track": "cyberSecurity",
            "name": "challenge24",
            "difficulty": "easy",
            "description": "description24",
            "points": 480,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 25,
            "track": "web",
            "name": "challenge25",
            "difficulty": "easy",
            "description": "description25",
            "points": 500,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 26,
            "track": "others",
            "name": "challenge26",
            "difficulty": "easy",
            "description": "description26",
            "points": 520,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 27,
            "track": "ai",
            "name": "challenge27",
            "difficulty": "easy",
            "description": "description27",
            "points": 540,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 28,
            "track": "web",
            "name": "challenge28",
            "difficulty": "easy",
            "description": "description28",
            "points": 560,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 29,
            "track": "mobile",
            "name": "challenge29",
            "difficulty": "easy",
            "description": "description29",
            "points": 580,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 30,
            "track": "others",
            "name": "challenge30",
            "difficulty": "easy",
            "description": "description30",
            "points": 600,
            "attachment": null,
            "external_resource": null,
            "max_tries": 2,
            "requires_judge": 0,
            "is_locked": 0
        },
        {
            "id": 31,
            "track": "mobile",
            "name": "challenge31",
            "difficulty": "easy",
            "description": "description31",
            "points": 250,
            "attachment": null,
            "external_resource": "https://devfest22.gdgalgiers.com",
            "max_tries": 2,
            "requires_judge": 1,
            "is_locked": 0
        }
    ],
    "message": "Succefully retrieved all the challenges!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/challenge/create
**Accessibility**: Admin

**Required Body infos:**

- track: \[String\]
- name: \[String\]
- difficulty: \[String\] \[easy | medium | hard\]
- description: \[String\]
- max_tries: \[Integer\]
- requires_judge: \[Bool\]
- points: \[Float\]
- solution: \[String\]
- external_resource \[String\] // not required
- attachment: \[File\] // not required
    

> solution is required if requires_judge is sent as false

**Response**: new created challenge instance
### Method: POST
>```
>/api/admin/challenge/create
>```
### Body (**raw**)

```json
{
  "track": "web",
  "name": "challenge name",
  "difficulty": "medium",
  "description": "challenge description",
  "max_tries": 3,
  "requires_judge": true,
  "points": 100
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|12|RgBjGmpt49lEK9ruPFzOvvEH1BWc7Z33DoMRkJP8|string|


### Response: 200
```json
{
    "success": true,
    "data": {
        "id": 34,
        "track": "web",
        "name": "challenge name",
        "difficulty": "medium",
        "description": "challenge description",
        "points": 100,
        "attachment": null,
        "external_resource": null,
        "max_tries": 3,
        "requires_judge": true,
        "is_locked": false
    },
    "message": "The challenge was succefully added!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/challenge/32/update
**Accessibility**: Admin

**Required Body infos:**

- name: \[String\]
- difficulty: \[String\] \[easy | medium | hard\]
- description: \[String\]
- max_tries: \[Integer\]
- points: \[Float\]
- attachment: \[File\] // not required
- solution // not required
- external_resource \[String\] // not required
    

**Response: updated challenge instance**
### Method: PUT
>```
>/api/admin/challenge/update/32?name=updated name&difficulty=hard&description=Updated descriptions&max_tries=5&points=320.2&attachment=
>```
### Query Params

|Param|value|
|---|---|
|name|updated name|
|difficulty|hard|
|description|Updated descriptions|
|max_tries|5|
|points|320.2|
|attachment||


### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|7|ehGLzss2EoFkpVBx8K3bzkxR29ITBroDGqlW4KQi|string|


### Response: 200
```json
{
    "success": true,
    "data": {
        "id": 32,
        "track": "others",
        "name": "updated name",
        "difficulty": "hard",
        "description": "Updated descriptions",
        "points": "320.2",
        "attachment": null,
        "max_tries": "5",
        "requires_judge": 1,
        "solution": null
    },
    "message": "The challenge was succefully updated!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/challenge/32/delete
**Accessibility**: Admin

**Required Body infos: ID(one the route)**

**Response**: Successfull message
### Method: DELETE
>```
>/api/admin/challenge/delete/32
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|7|ehGLzss2EoFkpVBx8K3bzkxR29ITBroDGqlW4KQi|string|


### Response: 200
```json
{
    "success": true,
    "data": [],
    "message": "The challenge was succefully deleted!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/challenge/1/lock
**Accessibility**: Admin

**Required Body infos: ID(one the route)**

**Response**: Successfull message
### Method: POST
>```
>/api/admin/challenge/lock/1
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|GTh3cJ9tBnaAdnrQnfYqTqEmFbNy7GAmz7QYdXym|string|


### Response: 200
```json
{
    "success": false,
    "message": "Challenge Succefully locked!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/challenge/1/unlock
**Accessibility**: Admin

**Required Body infos: ID(one the route)**

**Response**: Successfull message
### Method: POST
>```
>/api/admin/challenge/unlock/1
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|GTh3cJ9tBnaAdnrQnfYqTqEmFbNy7GAmz7QYdXym|string|


### Response: 200
```json
{
    "success": true,
    "data": [],
    "message": "Challenge Succefully unlocked!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/admin/stats
**Accessibility**: Admin

**Required Body infos: /**

**Response**: statistics instance

> Concerning tracks stats, for each track created, the number of related participants will be returned
### Method: GET
>```
>/api/admin/stats
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|11|py83j4mRyqKoFO4rjZcd2QbVwRDOuGGhBlUbWLWy|string|


### Response: 200
```json
{
    "success": true,
    "data": {
        "total_challenges": 33,
        "tracks_stats": {
            "total_tracks": 5,
            "web": 6,
            "ai": 8,
            "mobile": 5,
            "cyberSecurity": 1,
            "others": 7
        },
        "total_submissions": 4
    },
    "message": "Successfully retrieved statistics"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃
</details>

<a name="participant"></a>
<details>
  <summary>📁 Participant</summary>

  # 📁 Collection: Participant 


## End-point: /api/participant/track
**Accessibility**: Participant

**Required Body infos: NONE**

**Response**: Tracks array instance
### Method: GET
>```
>/api/participant/track
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|8|AaKciDQ1jRSAVEMgqYIBnAqr5S3OvKqDYDhXsasj|string|


### Response: 200
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "type": "web",
            "description": "trackWEB",
            "number_of_challenges": 11,
            "is_locked": 0
        },
        {
            "id": 2,
            "type": "ai",
            "description": "trackAI",
            "number_of_challenges": 4,
            "is_locked": 0
        },
        {
            "id": 3,
            "type": "mobile",
            "description": "trackMOBILE",
            "number_of_challenges": 7,
            "is_locked": 0
        },
        {
            "id": 4,
            "type": "cyberSecurity",
            "description": "trackSEC",
            "number_of_challenges": 3,
            "is_locked": 0
        }
    ],
    "message": "Tracks were succefully restored"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/participant/track/1/challenges
**Accessibility**: Participant

**Required Body infos: Track ID(one the route)**

**Response**: Track challenges array instance
### Method: GET
>```
>/api/participant/track/1/challenges
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|8|AaKciDQ1jRSAVEMgqYIBnAqr5S3OvKqDYDhXsasj|string|


### Response: 200
```json
{
    "success": true,
    "data": [
        {
            "id": 2,
            "track": "web",
            "name": "challenge2",
            "difficulty": "easy",
            "description": "description2",
            "points": 40,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 0,
            "solution": null
        },
        {
            "id": 3,
            "track": "web",
            "name": "challenge3",
            "difficulty": "easy",
            "description": "description3",
            "points": 60,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 0,
            "solution": null
        },
        {
            "id": 7,
            "track": "web",
            "name": "challenge7",
            "difficulty": "easy",
            "description": "description7",
            "points": 140,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 0,
            "solution": null
        },
        {
            "id": 9,
            "track": "web",
            "name": "challenge9",
            "difficulty": "easy",
            "description": "description9",
            "points": 180,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 0,
            "solution": null
        },
        {
            "id": 13,
            "track": "web",
            "name": "challenge13",
            "difficulty": "easy",
            "description": "description13",
            "points": 260,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 0,
            "solution": null
        },
        {
            "id": 16,
            "track": "web",
            "name": "challenge16",
            "difficulty": "easy",
            "description": "description16",
            "points": 320,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 0,
            "solution": null
        },
        {
            "id": 20,
            "track": "web",
            "name": "challenge20",
            "difficulty": "easy",
            "description": "description20",
            "points": 400,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 0,
            "solution": null
        },
        {
            "id": 23,
            "track": "web",
            "name": "challenge23",
            "difficulty": "easy",
            "description": "description23",
            "points": 460,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 0,
            "solution": null
        },
        {
            "id": 25,
            "track": "web",
            "name": "challenge25",
            "difficulty": "easy",
            "description": "description25",
            "points": 500,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 0,
            "solution": null
        },
        {
            "id": 28,
            "track": "web",
            "name": "challenge28",
            "difficulty": "easy",
            "description": "description28",
            "points": 560,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 0,
            "solution": null
        },
        {
            "id": 31,
            "track": "web",
            "name": "challenge31",
            "difficulty": "easy",
            "description": "description31",
            "points": 250,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 1,
            "solution": null
        }
    ],
    "message": "Challenges were succefully retrieved!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/participant/challenge/14/submit
**Accessibility**: Participant

**NOTES**: the submission will be dropped in one of the following scenarios:

- The track's challenge is Locked.
- The challenge is already submitted and it's under judgment
- The participant exceeded possible number of submissions for the challenge
- The challenge doesn't belong to the track assigned to the participant
    

**Required Body infos:**

- If the challenge doesn't require judgment: **answer** \[String\]
- if the challenge requires judgment: **attachment** \[String\]
    

**Response**: Successfull message
### Method: POST
>```
>/api/participant/challenge/14/submit
>```
### Body (**raw**)

```json
{
    "answer": "YES"
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|8|AaKciDQ1jRSAVEMgqYIBnAqr5S3OvKqDYDhXsasj|string|


### Response: 200
```json
{
    "success": true,
    "data": [],
    "message": "That's right! you've succefully solved this challenge"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/participant/challenge/14/submissions
**Accessibility**: Participant

**Required Body infos: ID (on the route)**

**Response**: Submissions array instance
### Method: GET
>```
>/api/participant/challenge/14/submissions
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|8|AaKciDQ1jRSAVEMgqYIBnAqr5S3OvKqDYDhXsasj|string|


### Response: 200
```json
{
    "success": true,
    "data": [
        {
            "id": 4,
            "track": "cyberSecurity",
            "challenge": {
                "id": 14,
                "track": "cyberSecurity",
                "name": "challenge14",
                "difficulty": "easy",
                "description": "description14",
                "points": 280,
                "attachment": null,
                "max_tries": 3,
                "requires_judge": 0,
                "solution": "YES"
            },
            "attachment": null,
            "status": "rejected"
        },
        {
            "id": 5,
            "track": "cyberSecurity",
            "challenge": {
                "id": 14,
                "track": "cyberSecurity",
                "name": "challenge14",
                "difficulty": "easy",
                "description": "description14",
                "points": 280,
                "attachment": null,
                "max_tries": 3,
                "requires_judge": 0,
                "solution": "YES"
            },
            "attachment": null,
            "status": "approved"
        }
    ],
    "message": "Succefully retrieved all submissions"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/participant/submission/1/cancel
**Accessibility**: Participant

**Required Body infos: ID (on the route)**

**Response**: Submissions array instance
### Method: POST
>```
>/api/participant/submission/1/cancel
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|2|XMVPrpmEenrogsoYUPtpP4uaHSBCByWgLgKVCOlN|string|


### Response: 200
```json
{
    "success": true,
    "data": {
        "id": 1,
        "track": "mobile",
        "challenge": {
            "id": 31,
            "track": "mobile",
            "name": "challenge31",
            "difficulty": "easy",
            "description": "description31",
            "points": 250,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 1,
            "solution": null,
            "is_locked": 0
        },
        "attachment": "random attachment",
        "status": "canceled"
    },
    "message": "Submission was successfully canceled!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/participant/challenge/33/download
**Accessibility**: Participant

**Required Body infos: challenge ID (on the route)**

**Response**: Attachment downloaded(browser forced to download).

> In case of an error, an error message as JSON format will be sent
### Method: GET
>```
>/api/participant/challenge/33/download
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|5|P1EwlA4pYypSDpEmoyyDcEqBAS5hWVaKCWepiGMp|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/participant/challenge/8
**Accessibility**: Participant

**Required Body infos: challenge ID (on the route)**

**Response**: if success: Challenge instance

else: Error message

> Error message may occur when participant tries to access a challenge that doesn't belong to his track
### Method: GET
>```
>/api/participant/challenge/8
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|8|npHBGRgrIMPHsPyeoMSsuwVWRJyuCUgRVPQA0JzD|string|


### Response: 200
```json
{
    "success": true,
    "data": {
        "id": 8,
        "track": "ai",
        "name": "challenge8",
        "difficulty": "easy",
        "description": "description8",
        "points": 160,
        "attachment": null,
        "external_resource": null,
        "max_tries": 2,
        "requires_judge": 0,
        "is_locked": 0
    },
    "message": "Succefully retrieved the challenge"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/participant/submission
**Accessibility**: Participant

**Required Body infos: /**

**Response**: Submissions instances array
### Method: GET
>```
>/api/participant/submission
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|10|7gd9Q0nPcdwzVBSvgFVgf4HBTEEBBxD56rV0K5a1|string|


### Response: 200
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "track": "mobile",
            "challenge": {
                "id": 31,
                "track": "mobile",
                "name": "challenge31",
                "difficulty": "easy",
                "description": "description31",
                "points": 250,
                "attachment": null,
                "external_resource": "https://devfest22.gdgalgiers.com",
                "max_tries": 2,
                "requires_judge": 1,
                "is_locked": 0
            },
            "attachment": "random attachment",
            "status": "pending",
            "submitted_at": "1 week ago"
        }
    ],
    "message": "Succefully retrieved all previous submissions!"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃
</details>

<a name="judge"></a>
<details>
  <summary>📁 Judge</summary>

  # 📁 Collection: Judge 


## End-point: /api/judge/submissions
**Accessibility**: Judge

**Required Body infos: NONE**

**Response**: Submissions array instance (only the ones require judgement)
### Method: GET
>```
>/api/judge/submissions
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|11|Fam9YJcwGGP2mrcw4mBE7Es52OhStaL6QvE4rAz8|string|


### Response: 200
```json
{
    "success": true,
    "data": [
        {
            "id": 6,
            "track": "cyberSecurity",
            "challenge": {
                "id": 21,
                "track": "cyberSecurity",
                "name": "challenge21",
                "difficulty": "easy",
                "description": "description21",
                "points": 420,
                "attachment": null,
                "max_tries": 2,
                "requires_judge": 1,
                "solution": null
            },
            "attachment": "YES",
            "status": "pending"
        }
    ],
    "message": "Succefully retrieved all the submissions"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/judge/submission/6/judge
**Accessibility**: Judge

**Required Body infos:**

- judgment \[String | approved, rejected\]
- if judgment = approved
    - points \[float\]

**Response**:Successfull message
### Method: POST
>```
>/api/judge/submission/6/judge
>```
### Body (**raw**)

```json
{
    "judgment": "approved",
    "points": 1
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|11|Fam9YJcwGGP2mrcw4mBE7Es52OhStaL6QvE4rAz8|string|


### Response: 200
```json
{
    "success": true,
    "data": [],
    "message": "Succefully Approved the submission"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/judge/submission/1/assign-judge
**Accessibility**: Judge

**Required Body infos: Submission ID(one the route)**

**Response**: Updated Submission instance
### Method: POST
>```
>/api/judge/submission/1/assign-judge
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|1|AJv5hlt1o4Uvlt7k6aH2NBjqbKJAMgTCTvnJQd4M|string|


### Response: 200
```json
{
    "success": true,
    "data": {
        "id": 1,
        "track": "mobile",
        "challenge": {
            "id": 31,
            "track": "mobile",
            "name": "challenge31",
            "difficulty": "easy",
            "description": "description31",
            "points": 250,
            "attachment": null,
            "max_tries": 2,
            "requires_judge": 1,
            "solution": null,
            "is_locked": 0
        },
        "attachment": "random attachment",
        "status": "judging"
    },
    "message": "Succefully assigning submission"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: /api/track/web/leaderboard
**Accessibility**: Anyone Authenticated

**Required Body infos: Track name (on the URL)**

**Response**: Leaderboard instance
### Method: GET
>```
>/api/track/web/leaderboard
>```
### Headers

|Content-Type|Value|
|---|---|
|Accept|application/json|


### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|5|WVKgqThNyAIaF7xwQER0LgfrMKyN9GKsR35DrAnQ|string|


### Response: 200
```json
{
    "success": true,
    "data": [
        {
            "id": 6,
            "full_name": "participant5",
            "email": "participant5@gmail.com",
            "points": 0,
            "role": "participant",
            "track": "web"
        },
        {
            "id": 17,
            "full_name": "participant16",
            "email": "participant16@gmail.com",
            "points": 0,
            "role": "participant",
            "track": "web"
        }
    ],
    "message": "Succefully retrieved the leaderboard"
}
```

⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃
</details>

<a name="join-our-community"></a>

<!-- JOIN OUR COMMUNITY -->

# Join our community

Join us in the [GDG Algiers' Community Discord](https://discord.com/invite/7EvsP7eemQ) and post your question there.

<a name="contributing"></a>

# Contributing

Thank you for considering contributing to the challenges platform project. We welcome contributions from the community to make this project even better. Please take a moment to review our `CONTRIBUTION.md` file where the Contribution Guidelines are listed there.

<a name="contact"></a>

# Contact

GDG Algiers - [@gdg_algiers](https://twitter.com/gdg_algiers) - gdg.algiers@esi.dz

Project Link: [https://github.com/GDGAlgiers/challengesPlatform-api](https://github.com/GDGAlgiers/challengesPlatform-api)

_________________________________________________

[contributors-shield]: https://img.shields.io/github/contributors/GDGAlgiers/challengesPlatform-api.svg?style=for-the-badge
[contributors-url]: https://github.com/GDGAlgiers/challengesPlatform-api/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/GDGAlgiers/challengesPlatform-api.svg?style=for-the-badge
[forks-url]: https://github.com/GDGAlgiers/challengesPlatform-api/network/members
[stars-shield]: https://img.shields.io/github/stars/GDGAlgiers/challengesPlatform-api.svg?style=for-the-badge
[stars-url]: https://github.com/GDGAlgiers/challengesPlatform-api/stargazers
[issues-shield]: https://img.shields.io/github/issues2.0/GDGAlgiers/challengesPlatform-api.svg?style=for-the-badge
[issues-url]: https://github.com/GDGAlgiers/challengesPlatform-api/issues
[license-shield]: https://img.shields.io/github/license/GDGAlgiers/challengesPlatform-api.svg?style=for-the-badge