# MongoDBHelper Class

The MongoDBHelper class is a PHP helper class for working with MongoDB databases using the MongoDB PHP driver. It provides various methods to perform common operations such as CRUD operations (create, read, update, delete), querying, aggregating, and more.
Prerequisites

To use the MongoDBHelper class, make sure you have the following prerequisites installed:

- PHP: The PHP programming language runtime environment.
- MongoDB PHP Driver: The MongoDB PHP driver extension, which allows PHP applications to communicate with MongoDB databases.

## Installation

- Clone the repository or copy the MongoDBHelper class code into your project directory.
- Include or require the MongoDBHelper.php file in your PHP script.

```
require_once 'MongoDBHelper.php';
```

## Usage

### Creating an Instance

To create an instance of the MongoDBHelper class, use the following code:

```
$mdh = new MongoDBHelper($mongodbHost, $mongodbPort, $databaseName, $collectionName);
```

- `$mongodbHost` (optional): The MongoDB server host. Defaults to "localhost".
- `$mongodbPort` (optional): The MongoDB server port. Defaults to 27017.
- `$databaseName` (optional): The name of the MongoDB database. Defaults to "test".
- `$collectionName` (optional): The name of the MongoDB collection. Defaults to "Tzwei".

### Debugging

The MongoDBHelper class supports debugging mode. To enable debugging, use the setDebug method:

```
$mdh->setDebug(1);
```

Debugging mode will print additional information during the execution of various methods.

## CRUD Operations

The MongoDBHelper class provides methods for performing CRUD operations on MongoDB collections.
Inserting a Document

To insert a document into the collection, use the insertDocument method:

```
$document = [...]; // The document to be inserted
$result = $mdh->insertDocument($document);
```

The method returns the result of the insertion operation as a JSON-encoded string.
Finding Documents

To find documents in the collection based on a query, use the find method:

```
$query = [...]; // The query to filter the documents
$projection = [...]; // Optional: The fields to include or exclude from the result
$offset = null; // Optional: The number of documents to skip from the result
$limit = null; // Optional: The maximum number of documents to return
$documents = $mdh->find($query, $projection, $offset, $limit);
```

The method returns an array of documents that match the query.

### Updating a Document

To replace a document in the collection with a new document, use the replaceDocument method:

```
$documentId = '...'; // The ID of the document to replace
$newDocument = [...]; // The new document to replace with
$result = $mdh->replaceDocument($documentId, $newDocument);
```
The method returns the result of the update operation as a JSON-encoded string.

### Deleting a Document

To delete a document from the collection, use the deleteEntry method:

```
$documentId = '...'; // The ID of the document to delete
$result = $mdh->deleteEntry($documentId);
```

The method returns the result of the deletion operation as a JSON-encoded string.
