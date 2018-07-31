# This manual describes the API commands for recipes

### Authentication

    User should signup and signin with credentials (email, password). User will recieve a token for next API calls.
    
    Likewise, you can use the 401 - Unauthorized status code to indicate that a user could not authenticate.
    
### HTTP Status Code Meaning

**Successful**

    - `200 OK` - Everything worked as expected.
    - `201 Created` - The request was successful and a resource was created. This is typically a response from a `POST` request to create a resource, such as the recipe or upload image.
    - `204 No Content` - The request was successful but the response body is empty. This is typically a response from a `DELETE` request to delete a resource.

**Error**

    - `400 Bad Request` - A required parameter or the request is invalid.
    - `401 Unauthorized` - The authentication credentials are invalid.
    - `404 Not Found` - The requested resource doesn’t exist.
    
### API methods

**Sign Up**

    POST /api/signup
    
    body params:
    - name
    - email
    - password
    
    responses:
        201: created user
        400: validation failed
    
**Sign In**

    POST /api/signin
    
    body params
    - email
    - password
    
    responses:
        200 - contains token and timestamp of token expiration
        400 - validation failed
        401 - authentication failed
        
**Upload Image**

    POST /api/images
    
    body params
    - image
    
    responses:
        200 - uploaded image
        400 - validation failed
        401 - unauthorized
        
**Add a recipe**

    POST /api/recipes
    
    body params
    - title
    - body
    - image_id
    
    responses:
        201 - created recipe
        400 - validation failed
        401 - unauthorized
        
**Update a recipe**

    PUT /api/recipes/{id}
    
    body params
    - title
    - body
    - image_id
    
    responses:
        200 - updated recipe
        400 - validation failed
        401 - unauthorized
        403 - forbidden
        
**Delete a recipe**

    DELETE /api/recipes/{id}
    
    responses:
        204 - recipe has been deleted
        400 - object does not exist
        401 - unauthorized
        403 - forbidden