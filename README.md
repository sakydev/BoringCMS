# BoringCMS
Simple headless CMS for modern world

I would start with [src](https://github.com/sakydev/BoringCMS/tree/master/src)

The slightly more developed features are entries and collections [here](https://github.com/sakydev/BoringCMS/tree/master/src/Http/Controllers/Api/Collection)

Tests are [here](https://github.com/sakydev/BoringCMS/tree/master/tests/Feature/Api/Collection)

# ALL THE BELOW INFO IS ACTUALLY OUTDATED (Sorry :)

# API Endpoints
### General information
- All items inside {} are `slug` for respective endpoint/feature
- Folders are children of containers
- Blueprints are underlying foundation of `/collections`, `/taxonomies`, `forms` and `containers`
- FieldSets are collection of fields

### Authentication
- POST:       /auth/register: create account
- POST:       /auth/login: login

### Settings
- POST:       /settings: create new setting
- GET:        /settings: list settings
- GET:        /settings/{setting}: get single setting
- PATCH:      /settings: update setting
- DELETE:     /settings/{setting}: delete setting

### Collections
- POST:       /collections: create collection
- GET:        /collections: list collections

- GET:        /collections/{collection}: get single collection
- PATCH:      /collections/{collection}: update single collection
- DELETE:     /collections/{collection}: delete single collection

- POST:       /collections/{collection}/entries: create collection entry
- GET:        /collections/{collection}/entries: list collection entries

- GET:        /collections/{collection}/entries/{entry}: get single collection entry
- PATCH:      /collections/{collection}/entries/{entry}: update single collection entry
- DELETE:     /collections/{collection}/entries/{entry}: delete single collection entry

### Containers
- POST:       /containers: create container
- GET:        /containers: list containers

- GET:        /containers/{container}: get single container
- PATCH:      /containers/{container}: update single container
- DELETE:     /containers/{container}: delete single container

- POST:       /containers/{container}/items: create container item (folder or file)
- GET:        /containers/{container}/items: list container items

- DELETE:     /containers/{container}/items/{item}: delete single container item

### Folders
- POST:       /folders: create folder
- GET:        /folders: list folders

- GET:        /folders/{folder}: get single folder
- PATCH:      /folders/{folder}: update single folder
- DELETE:     /folders/{folder}: delete single folder

- POST:       /folders/{folder}/items: create folder item (folder or file)
- GET:        /folders/{folder}/items: list folder items

- DELETE:     /folders/{folder}/items/{item}: delete single folder item

### Taxonomies
- POST:       /taxonomies: create taxonomy
- GET:        /taxonomies: list taxonomies

- GET:        /taxonomies/{taxonomy}: get single taxonomy
- PATCH:      /taxonomies/{taxonomy}: update single taxonomy
- DELETE:     /taxonomies/{taxonomy}: delete single taxonomy

- POST:       /taxonomies/{taxonomy}/terms: create taxonomy term
- GET:        /taxonomies/{taxonomy}/terms: list taxonomy terms

- GET:        /taxonomies/{taxonomy}/terms/{term}: get single taxonomy term
- PATCH:      /taxonomies/{taxonomy}/terms/{term}: update single taxonomy term
- DELETE:     /taxonomies/{taxonomy}/terms/{term}: delete single taxonomy entry

### Blueprints
- POST:       /blueprints: create a blueprint
- GET:        /blueprints: list all blueprints

- GET:        /blueprints/{blueprint}: get single blueprint
- PATCH:      /blueprints/{blueprint}: update single blueprint
- DELETE:     /blueprints/{blueprint}: delete a blueprint

### FieldSets
- POST:       /field-sets: create fieldset
- GET:        /field-sets: list field sets

- GET:        /field-sets/{set}: get single fieldset
- PATCH:      /field-sets/{set}: update single fieldset
- DELETE:     /field-sets/{set}: delete single fieldset

- POST:       /field-sets/{set}/items: create fieldset item
- GET:        /field-sets/{set}/items: list fieldset items

- GET:        /field-sets/{set}/items/{item}: get fieldset item
- PATCH:      /field-sets/{set}/items/{item}: update fieldset item
- DELETE:     /field-sets/{set}/items/{item}: delete fieldset item

### Forms
- POST:       /forms: create form
- GET:        /forms: list forms

- GET:        /forms/{form}: get single form
- PATCH:      /forms/{form}: update single form
- DELETE:     /forms/{form}: delete single form

- POST:       /forms/{form}/responses: create response 
- GET:        /forms/{form}/responses: list responses 

- GET:        /forms/{form}/responses/{responseId}: get single response
- DELETE:     /forms/{form}/responses/{responseId}: delete single response

